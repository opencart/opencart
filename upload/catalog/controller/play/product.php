<?php
class ControllerPlayProduct extends Controller{
    public function getProductChanges(){
        $this->play->log('getProductChanges()');

        if(!empty($this->request->post)){
            $auth = $this->validateAuth();
            if($auth == true){
                
                $this->load->model('play/product');
                
                //get the formatted array of products
                $products = $this->play->getLinkedProducts(true);
                $response = array(
                    'error' => false,
                    'msg'   => ''
                );

                if($products != 0){
                    foreach($products as $product){
                        if($product['product_id'] != 0){
                            $response['products'][] = array(
                                'product-id'            => html_entity_decode($product['play_product_id']),
                                'product-id-type'       => (int)$product['play_product_id_type'],
                                'sku'                   => (int)$product['product_id'],
                                'dispatch-to'           => (int)$product['dispatch_to'],
                                'delivered-price-gbp'   => (double)$product['price_gb'],
                                'delivered-price-euro'  => (double)$product['price_eu'],
                                'quantity'              => (int)$product['quantity'],
                                'item-condition'        => (int)$product['condition'],
                                'comment'               => html_entity_decode($product['comment']),
                                'dispatch-from'         => (int)$product['dispatch_from'],
                                'add-delete'            => html_entity_decode($product['action']),
                            );

                            $this->db->query("UPDATE `" . DB_PREFIX . "play_product_insert` SET `status` = '2', `submitted` = now() WHERE `product_id` = '".(int)$product['product_id']."'");
                        }
                    }
                }
                
                $remove = $this->play->getRemovedProducts();

                if($remove != 0){
                    foreach($remove as $product){
                        if($product['product_id'] != 0){
                            $response['products'][] = array(
                                'product-id'            => html_entity_decode($product['play_product_id']),
                                'product-id-type'       => (int)$product['play_product_id_type'],
                                'sku'                   => (int)$product['product_id'],
                                'dispatch-to'           => (int)$product['dispatch_to'],
                                'delivered-price-gbp'   => (double)$product['price_gb'],
                                'delivered-price-euro'  => (double)$product['price_eu'],
                                'quantity'              => 0,
                                'item-condition'        => (int)$product['condition'],
                                'comment'               => html_entity_decode($product['comment']),
                                'dispatch-from'         => (int)$product['dispatch_from'],
                                'add-delete'            => html_entity_decode($product['action']),
                            );

                            $this->play->delete($product['product_id']);
                        }
                    }
                }

                $this->play->log('getProductChanges() - '.json_encode($response));
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

    public function updateProductChanges(){
        $this->play->log('updateProductChanges()');
        if(!empty($this->request->post)){

            if($this->validateAuth() == true){
                
                $this->load->model('play/product');
                
                $data = $this->play->decrypt($this->request->post['data'], true);

                $pWarnings = array();
                
                if(isset($data['warnings']) && is_array($data['warnings'])){
                    foreach($data['warnings'] as $warning){
                        $this->model_play_product->removeAllWarnings($warning['product_id']);
                        $this->model_play_product->updateProduct($warning['product_id'], 6);
                        $this->model_play_product->addWarning($warning['msg'], $warning['product_id']);
                        $pWarnings[] = $warning['product_id'];
                    }
                }

                if(isset($data['errors']) && is_array($data['errors'])){
                    foreach($data['errors'] as $error){
                        $this->model_play_product->removeAllWarnings($error['product_id']);
                        $this->model_play_product->updateProduct($error['product_id'], 4);
                        $this->model_play_product->addWarning($error['msg'], $error['product_id']);
                        $pWarnings[] = $warning['product_id'];
                    }
                }

                if(isset($data['active']['products']) && is_array($data['active']['products'])){
                    foreach($data['active']['products'] as $active){
                        if(!in_array($active['sku'], $pWarnings)){
                            $this->model_play_product->removeAllWarnings($active['sku']);
                            $this->model_play_product->updateProduct($active['sku'], 3);
                        }
                    }
                }
            }

            $this->response->addHeader('Content-type: application/json');
            $this->response->setOutput(json_encode(array('ok')));
        }else{
            $this->response->addHeader('Content-type: application/json');
            $this->response->setOutput(json_encode(array('fail')));
        }
    }

    private function validateAuth(){
        $this->play->log('validateAuth()');

        if($this->request->post['token'] == $this->config->get('obp_play_token') &&
           $this->request->post['secret'] == $this->config->get('obp_play_secret'))
        {
            return true;
        }else{
            return false;
        }
    }
}