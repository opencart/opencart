<?php
class ControllerPlayOrder extends Controller{
    public function putOrders(){
        if(!empty($this->request->post)){
            $auth = $this->validateAuth();
            if($auth == true){
                $this->load->model('play/product');
                $this->load->model('play/order');
                $this->load->model('play/customer');
                $this->load->model('checkout/order');

                $data = $this->request->post;
                
                //decrypt the order data
                $data['data'] = $this->play->decrypt($data['data'], true);
                
                $import_id      = $this->config->get('obp_play_import_id');
                $paid_id        = $this->config->get('obp_play_paid_id');
                $shipped_id     = $this->config->get('obp_play_shipped_id');
                $cancelled_id   = $this->config->get('obp_play_cancelled_id');              
                $refunded_id    = $this->config->get('obp_play_refunded_id');

                if(!empty($data['data']) && is_array($data['data'])){
                    foreach($data['data']['orders'] as $order){
                        if($order['payments-status'] != 'Sale Pending'){

                            $order_id = $this->play->getOrderId($order['order-id']);

                            if(!$order_id){
                                //new order
                                //handle customer account
                                $customer_id = $this->model_play_customer->getCustomerId($order['buyer-email']);
                                if(!$customer_id){
                                    $customer_id = $this->model_play_customer->createCustomer($order['buyer-email'], $order['recipient-name']);
                                }

                                //create order
                                $order_id = $this->model_play_order->createOrder($customer_id, $order);

                                //get product if it exists
                                $product = $this->model_play_product->getProduct($order['sku']);
                                if($product){
                                    $product_id         = $product['product_id'];
                                    $product_name       = $product['name'];
                                    $model              = $product['model'];
                                    $taxRate            = $this->openbay->getTaxRate($product['tax_class_id']);
                                }else{
                                    $product_id         = 0;
                                    $product_name       = $order['item-name'];
                                    $model              = '';
                                    $taxRate            = $this->config->get('obp_play_default_tax');
                                }

                                //currency data
                                if($order['sale-value-gbp'] != ''){
                                    $currency   = $this->model_play_order->getCurrency('GBP');
                                    $total      = (double)$order['sale-value-gbp'];
                                    $tax        = number_format(($total / 100) * $taxRate, 3);
                                    $price      = $total - $tax;
                                    $totalNet   = $price;
                                    $gbp        = $total;
                                    $eur        = '';
                                }

                                if($order['sale-value-euro'] != ''){
                                    $currency   = $this->model_play_order->getCurrency('EUR');
                                    $total      = (double)$order['sale-value-euro'];
                                    $tax        = number_format(($total / 100) * $taxRate, 3);
                                    $price      = $total - $tax;
                                    $totalNet   = $price;
                                    $gbp        = '';
                                    $eur        = $total;
                                }

                                $quantity   = 1;

                                //order totals
                                $data['totals'][0] = array(
                                    'code' => 'sub_total',
                                    'title' => 'Sub-Total:',
                                    'text' => $this->db->escape($currency['symbol_left']).number_format($totalNet, $currency['decimal_place']).$this->db->escape($currency['symbol_right']),
                                    'value' => number_format($totalNet, 2),
                                    'sort_order' => '1'
                                );
                                $data['totals'][1] = array(
                                    'code' => 'tax',
                                    'title' => 'Tax:',
                                    'text' => $this->db->escape($currency['symbol_left']).number_format($tax, $currency['decimal_place']).$this->db->escape($currency['symbol_right']),
                                    'value' => number_format($tax, 3),
                                    'sort_order' => '5'
                                );

                                $data['totals'][2] = array(
                                    'code' => 'total',
                                    'title' => 'Total:',
                                    'text' => $this->db->escape($currency['symbol_left']).number_format($total, $currency['decimal_place']).$this->db->escape($currency['symbol_right']),
                                    'value' => number_format($total, 2),
                                    'sort_order' => '6'
                                );


                                foreach ($data['totals'] as $totals) {
                                        $this->db->query("INSERT INTO `" . DB_PREFIX . "order_total` SET `order_id` = '" . (int)$order_id . "', `code` = '" . $this->db->escape($totals['code']) . "', `title` = '" . $this->db->escape($totals['title']) . "', `text` = '" . $this->db->escape($totals['text']) . "', `value` = '" . (double)$totals['value'] . "', `sort_order` = '" . (int)$totals['sort_order'] . "'");
                                }

                                //add product to order
                                $this->model_play_order->addProduct($order_id, $product_id, $product_name, $model, $quantity, $price, $total, $tax);

                                //add order to play order table
                                $this->model_play_order->addPlayOrder($order_id, $order['order-id'], $gbp, $eur);

                                //confirm order
                                $this->model_play_order->confirm($order_id, $import_id);

                                //if paid, mark as paid
                                if($order['payments-status'] == 'Sold'){
                                    $this->model_play_order->update($order_id, $paid_id);
                                }

                                //if shipped, mark as paid then shipped
                                if($order['payments-status'] == 'Posted'){
                                    $this->model_play_order->update($order_id, $paid_id);
                                    $this->model_play_order->update($order_id, $shipped_id);
                                }

                                if($order['payments-status'] == 'Refunded' || $order['payments-status'] == 'Cancelled'){
                                    //if cancelled, mark as cancelled
                                    if($order['payments-status'] == 'Cancelled'){
                                        $this->model_play_order->update($order_id, $cancelled_id);
                                        $this->model_play_order->restockProducts($order_id);
                                    }

                                    //if cancelled, mark as refunded
                                    if($order['payments-status'] == 'Refunded'){
                                        $this->model_play_order->update($order_id, $refunded_id);
                                        $this->model_play_order->restockProducts($order_id);
                                    }
                                }else{
                                    //notify openbay about new order
                                    $this->openbay->orderNew($order_id);
                                }
                            }else{
                                //get order
                                $order_info = $this->model_checkout_order->getOrder($order_id);

                                //compare status
                                $status = $order_info['order_status_id'];

                                //if paid, mark as paid
                                if($order['payments-status'] == 'Sold' && $status != $paid_id){
                                    $this->model_play_order->update($order_id, $paid_id);
                                }

                                //if shipped, mark as shipped
                                if($order['payments-status'] == 'Posted' && $status != $shipped_id){
                                    $this->model_play_order->update($order_id, $shipped_id);
                                }

                                if($order['payments-status'] == 'Refunded' || $order['payments-status'] == 'Cancelled'){
                                    //if cancelled, mark as cancelled
                                    if($order['payments-status'] == 'Cancelled' && $status != $cancelled_id){
                                        $this->model_play_order->update($order_id, $cancelled_id);
                                        $this->model_play_order->restockProducts($order_id);
                                    }

                                    //if cancelled, mark as refunded
                                    if($order['payments-status'] == 'Refunded' && $status != $refunded_id){
                                        $this->model_play_order->update($order_id, $refunded_id);
                                        $this->model_play_order->restockProducts($order_id);
                                    }

                                    //notify openbay about change
                                    /* @todo */

                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public function getModifiedOrders(){
        if(!empty($this->request->post)){

            $auth = $this->validateAuth();
            if($auth == true){
                $this->load->model('play/order');
                $this->load->model('checkout/order');
                
                $response = array(
                    'error' => false,
                    'msg'   => ''
                );

                //get all orders where the status has been changed
                $orders = $this->model_play_order->getModifiedOrders();

                $shipped = array();
                $refunded = array();

                if(!empty($orders) && is_array($orders)){
                    foreach($orders as $play_order){
                        $order_info = $this->model_checkout_order->getOrder($play_order['order_id']);

                        //now shipped?
                        if($order_info['order_status_id'] == $this->config->get('obp_play_shipped_id')){
                            
                            $tmp = array(
                                'order-id'          => $play_order['play_order_id'],
                                'dispatched'        => 'Y',
                                'tracking-no'       => $play_order['tracking_no'],
                                'carrier-id'        => $play_order['carrier_id'],
                                'carrier-name'      => '',
                                'carrier-contact'   => '',
                            );
                            
                            $shipped[] = $tmp;
                        }

                        //now refunded?
                        if($order_info['order_status_id'] == $this->config->get('obp_play_refunded_id')){
                            
                            if($play_order['paid_gbp'] != ''){
                                $gbp = $play_order['paid_gbp'];
                                $eur = '';
                                
                            }elseif($play_order['paid_euro'] != ''){
                                $gbp = '';
                                $eur = $play_order['paid_euro'];
                            }
                            
                            
                            $tmp = array(
                                'order-id'                      => $play_order['play_order_id'],
                                'refund-amount-gbp'             => $gbp,
                                'postage-refund-amount-gbp'     => '',
                                'refund-amount-euro'            => $eur,
                                'postage-refund-amount-euro'    => '',
                                'reason'                        => $play_order['refund_reason'],
                                'message'                       => $play_order['refund_message'],
                            );
                            
                            $refunded[] = $tmp;
                        }
                    }
                    
                    //update the play order to not be modified
                    $this->db->query("UPDATE `" . DB_PREFIX . "play_order` SET `modified` = '0' WHERE `order_id` = '".(int)$play_order['order_id']."' LIMIT 1");
                }
                
                $response['data']['shipped'] = $shipped;
                $response['data']['refunded'] = $refunded;
                
            }else{
                $response = array(
                    'error' => true,
                    'msg'   => 'Invalid details'
                );
            }
        }else{
            $response = array(
                'error' => true,
                'msg'   => 'Data error'
            );
        }
        
        $this->response->addHeader('Content-type: application/json');
        $this->response->setOutput(json_encode($response));
    }

    private function validateAuth(){
        if($this->request->post['token'] == $this->config->get('obp_play_token') &&
           $this->request->post['secret'] == $this->config->get('obp_play_secret')){
            return true;
        }else{
            return false;
        }
    }
}