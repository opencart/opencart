<?php
class ControllerPaymentPPExpress extends Controller {
    protected function index() {
        $this->language->load('payment/pp_express');

        $this->data['button_continue'] = $this->language->get('button_continue');
        $this->data['button_continue_action'] = $this->url->link('payment/pp_express/checkout', '', 'SSL');

        /**
         * if there is any other paypal session data, clear it
         */
        unset($this->session->data['paypal']);


        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/pp_express.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/payment/pp_express.tpl';
        } else {
            $this->template = 'default/template/payment/pp_express.tpl';
        }

        $this->render();
    }

    public function express() {
        if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
            $this->log->write('No product redirect');
            $this->redirect($this->url->link('checkout/cart'));
        }

        if($this->customer->isLogged()) {
            /**
             * If the customer is already logged in
             */
            $this->session->data['paypal']['guest'] = false;
            unset($this->session->data['guest']);
        } else {
            if($this->config->get('config_guest_checkout') && !$this->config->get('config_customer_price') && !$this->cart->hasDownload() && !$this->cart->hasRecurringProducts()) {
                /**
                 * If the guest checkout is allowed (config ok, no login for price and doesn't have downloads)
                 */
                $this->session->data['paypal']['guest'] = true;
            } else {
                /**
                 * If guest checkout disabled or login is required before price or order has downloads
                 *
                 * Send them to the normal checkout flow.
                 */
                unset($this->session->data['guest']);
                $this->redirect($this->url->link('checkout/checkout', '', 'SSL'));
            }
        }

        unset($this->session->data['shipping_method']);
        unset($this->session->data['shipping_methods']);
        unset($this->session->data['payment_method']);
        unset($this->session->data['payment_methods']);

        $this->load->model('payment/pp_express');
        $this->load->model('tool/image');

        if($this->cart->hasShipping()) {
            $shipping = 2;
        } else {
            $shipping = 1;
        }
        
        $max_amount = $this->currency->convert($this->cart->getTotal(), $this->config->get('config_currency'), 'USD');
        $max_amount = min($max_amount * 1.5, 10000);
        $max_amount = $this->currency->format($max_amount, $this->currency->getCode(), '', false);

        $data = array(
            'METHOD' => 'SetExpressCheckout',
            'MAXAMT' => $max_amount,
            'RETURNURL' => $this->url->link('payment/pp_express/expressReturn', '', 'SSL'),
            'CANCELURL' => $this->url->link('checkout/cart'),
            'REQCONFIRMSHIPPING' => 0,
            'NOSHIPPING' => $shipping,
            'ALLOWNOTE' => $this->config->get('pp_express_allow_note'),
            'LOCALECODE' => 'EN',
            'LANDINGPAGE' => 'Login',
            'HDRIMG' => $this->model_tool_image->resize($this->config->get('pp_express_logo'), 790, 90),
            'HDRBORDERCOLOR' => $this->config->get('pp_express_border_colour'),
            'HDRBACKCOLOR' => $this->config->get('pp_express_header_colour'),
            'PAYFLOWCOLOR' => $this->config->get('pp_express_page_colour'),
            'CHANNELTYPE' => 'Merchant',
        );

        $data = array_merge($data, $this->model_payment_pp_express->paymentRequestInfo());
        
        $result = $this->model_payment_pp_express->call($data);

        
        
        /**
         * If a failed PayPal setup happens, handle it.
         */
        if(!isset($result['TOKEN'])) {
            $this->session->data['error'] = $result['L_LONGMESSAGE0'];
            /**
             * Unable to add error message to user as the session errors/success are not
             * used on the cart or checkout pages - need to be added?
             * If PayPal debug log is off then still log error to normal error log.
             */
            if($this->config->get('pp_express_debug')) {
                $this->log->write(serialize($result));
            }

            $this->redirect($this->url->link('checkout/checkout', '', 'SSL'));
        }

        $this->session->data['paypal']['token'] = $result['TOKEN'];

        if ($this->config->get('pp_express_test') == 1) {
            header('Location: https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=' . $result['TOKEN']);
        } else {
            header('Location: https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=' . $result['TOKEN']);
        }
    }

    public function expressReturn() {
        /**
         * This is the url when PayPal has completed the auth.
         *
         * It has no output, instead it sets the data and locates to checkout
         */
        $this->load->model('payment/pp_express');
        $data = array(
            'METHOD' => 'GetExpressCheckoutDetails',
            'TOKEN' => $this->session->data['paypal']['token'],
        );

        $result = $this->model_payment_pp_express->call($data);
        $this->session->data['paypal']['payerid']   = $result['PAYERID'];
        $this->session->data['paypal']['result']    = $result;

        $this->session->data['comment'] = '';
        if(isset($result['PAYMENTREQUEST_0_NOTETEXT'])) {
            $this->session->data['comment'] = $result['PAYMENTREQUEST_0_NOTETEXT'];
        }

        if($this->session->data['paypal']['guest'] == true) {

            $this->session->data['guest']['customer_group_id'] = $this->config->get('config_customer_group_id');
            $this->session->data['guest']['firstname'] = trim($result['FIRSTNAME']);
            $this->session->data['guest']['lastname'] = trim($result['LASTNAME']);
            $this->session->data['guest']['email'] = trim($result['EMAIL']);
            if(isset($result['PHONENUM'])) {
                $this->session->data['guest']['telephone'] = $result['PHONENUM'];
            } else {
                $this->session->data['guest']['telephone'] = '';
            }
            $this->session->data['guest']['fax'] = '';

            $this->session->data['guest']['payment']['firstname'] = trim($result['FIRSTNAME']);
            $this->session->data['guest']['payment']['lastname'] = trim($result['LASTNAME']);

            if(isset($result['BUSINESS'])) {
                $this->session->data['guest']['payment']['company'] = $result['BUSINESS'];
            } else {
                $this->session->data['guest']['payment']['company'] = '';
            }

            $this->session->data['guest']['payment']['company_id'] = '';
            $this->session->data['guest']['payment']['tax_id'] = '';

            if($this->cart->hasShipping()) {
                $shipping_name = explode(' ', trim($result['PAYMENTREQUEST_0_SHIPTONAME']));
                $shipping_first_name = $shipping_name[0];
                unset($shipping_name[0]);
                $shipping_last_name = implode(' ', $shipping_name);


                $this->session->data['guest']['payment']['address_1'] = $result['PAYMENTREQUEST_0_SHIPTOSTREET'];
                if(isset($result['PAYMENTREQUEST_0_SHIPTOSTREET2'])) {
                    $this->session->data['guest']['payment']['address_2'] = $result['PAYMENTREQUEST_0_SHIPTOSTREET2'];
                } else {
                    $this->session->data['guest']['payment']['address_2'] = '';
                }
                $this->session->data['guest']['payment']['postcode'] = $result['PAYMENTREQUEST_0_SHIPTOZIP'];
                $this->session->data['guest']['payment']['city'] = $result['PAYMENTREQUEST_0_SHIPTOCITY'];

                $this->session->data['guest']['shipping']['firstname'] = $shipping_first_name;
                $this->session->data['guest']['shipping']['lastname'] = $shipping_last_name;
                $this->session->data['guest']['shipping']['company'] = '';
                $this->session->data['guest']['shipping']['address_1'] = $result['PAYMENTREQUEST_0_SHIPTOSTREET'];
                if(isset($result['PAYMENTREQUEST_0_SHIPTOSTREET2'])) {
                    $this->session->data['guest']['shipping']['address_2'] = $result['PAYMENTREQUEST_0_SHIPTOSTREET2'];
                } else {
                    $this->session->data['guest']['shipping']['address_2'] = '';
                }
                $this->session->data['guest']['shipping']['postcode'] = $result['PAYMENTREQUEST_0_SHIPTOZIP'];
                $this->session->data['guest']['shipping']['city'] = $result['PAYMENTREQUEST_0_SHIPTOCITY'];

                $this->session->data['shipping_postcode'] = $result['PAYMENTREQUEST_0_SHIPTOZIP'];

                $country_info = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE `iso_code_2` = '" . $this->db->escape($result['PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE']) . "' AND `status` = '1' LIMIT 1")->row;

                if ($country_info) {
                    $this->session->data['guest']['shipping']['country_id'] = $country_info['country_id'];
                    $this->session->data['guest']['shipping']['country'] = $country_info['name'];
                    $this->session->data['guest']['shipping']['iso_code_2'] = $country_info['iso_code_2'];
                    $this->session->data['guest']['shipping']['iso_code_3'] = $country_info['iso_code_3'];
                    $this->session->data['guest']['shipping']['address_format'] = $country_info['address_format'];
                    $this->session->data['guest']['payment']['country_id'] = $country_info['country_id'];
                    $this->session->data['guest']['payment']['country'] = $country_info['name'];
                    $this->session->data['guest']['payment']['iso_code_2'] = $country_info['iso_code_2'];
                    $this->session->data['guest']['payment']['iso_code_3'] = $country_info['iso_code_3'];
                    $this->session->data['guest']['payment']['address_format'] = $country_info['address_format'];
                    $this->session->data['shipping_country_id'] = $country_info['country_id'];
                } else {
                    $this->session->data['guest']['shipping']['country_id'] = '';
                    $this->session->data['guest']['shipping']['country'] = '';
                    $this->session->data['guest']['shipping']['iso_code_2'] = '';
                    $this->session->data['guest']['shipping']['iso_code_3'] = '';
                    $this->session->data['guest']['shipping']['address_format'] = '';
                    $this->session->data['guest']['payment']['country_id'] = '';
                    $this->session->data['guest']['payment']['country'] = '';
                    $this->session->data['guest']['payment']['iso_code_2'] = '';
                    $this->session->data['guest']['payment']['iso_code_3'] = '';
                    $this->session->data['guest']['payment']['address_format'] = '';
                    $this->session->data['shipping_country_id'] = '';
                }

                if (isset($result['PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE'])) {
                    $returned_shipping_zone = $result['PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE'];
                } else {
                    $returned_shipping_zone = '';
                }
                
                $zone_info = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE `name` = '" . $this->db->escape($returned_shipping_zone) . "' AND `status` = '1' AND `country_id` = '" . (int)$country_info['country_id'] . "'")->row;

                if ($zone_info) {
                    $this->session->data['guest']['shipping']['zone'] = $zone_info['name'];
                    $this->session->data['guest']['shipping']['zone_code'] = $zone_info['code'];
                    $this->session->data['guest']['shipping']['zone_id'] = $zone_info['zone_id'];
                    $this->session->data['guest']['payment']['zone'] = $zone_info['name'];
                    $this->session->data['guest']['payment']['zone_code'] = $zone_info['code'];
                    $this->session->data['guest']['payment']['zone_id'] = $zone_info['zone_id'];
                    $this->session->data['shipping_zone_id'] = $zone_info['zone_id'];
                } else {
                    $this->session->data['guest']['shipping']['zone'] = '';
                    $this->session->data['guest']['shipping']['zone_code'] = '';
                    $this->session->data['guest']['shipping']['zone_id'] = '';
                    $this->session->data['guest']['payment']['zone'] = '';
                    $this->session->data['guest']['payment']['zone_code'] = '';
                    $this->session->data['guest']['payment']['zone_id'] = '';
                    $this->session->data['shipping_zone_id'] = '';
                }

                $this->session->data['guest']['shipping_address'] = true;
            } else {
                $this->session->data['guest']['payment']['address_1'] = '';
                $this->session->data['guest']['payment']['address_2'] = '';
                $this->session->data['guest']['payment']['postcode'] = '';
                $this->session->data['guest']['payment']['city'] = '';
                $this->session->data['guest']['payment']['country_id'] = '';
                $this->session->data['guest']['payment']['country'] = '';
                $this->session->data['guest']['payment']['iso_code_2'] = '';
                $this->session->data['guest']['payment']['iso_code_3'] = '';
                $this->session->data['guest']['payment']['address_format'] = '';
                $this->session->data['guest']['payment']['zone'] = '';
                $this->session->data['guest']['payment']['zone_code'] = '';
                $this->session->data['guest']['payment']['zone_id'] = '';
                $this->session->data['guest']['shipping_address'] = false;
            }

            $this->session->data['account'] = 'guest';

            unset($this->session->data['shipping_method']);
            unset($this->session->data['shipping_methods']);
            unset($this->session->data['payment_method']);
            unset($this->session->data['payment_methods']);
        } else {
            unset($this->session->data['guest']);
            /**
             * if the user is logged in, add the address to the account and set the ID.
             */

            if($this->cart->hasShipping()) {
                $this->load->model('account/address');

                $addresses = $this->model_account_address->getAddresses();

                /**
                 * Compare all of the user addresses and see if there is a match
                 */
                $match = false;
                foreach($addresses as $address) {
                    if(trim(strtolower($address['address_1'])) == trim(strtolower($result['PAYMENTREQUEST_0_SHIPTOSTREET'])) && trim(strtolower($address['postcode'])) == trim(strtolower($result['PAYMENTREQUEST_0_SHIPTOZIP']))) {
                        $match = true;

                        $this->session->data['payment_address_id'] = $address['address_id'];
                        $this->session->data['payment_country_id'] = $address['country_id'];
                        $this->session->data['payment_zone_id'] = $address['zone_id'];

                        $this->session->data['shipping_address_id'] = $address['address_id'];
                        $this->session->data['shipping_country_id'] = $address['country_id'];
                        $this->session->data['shipping_zone_id'] = $address['zone_id'];
                        $this->session->data['shipping_postcode'] = $address['postcode'];

                        break;
                    }
                }

                /**
                 * If there is no address match add the address and set the info.
                 */
                if($match == false) {

                    $shipping_name = explode(' ', trim($result['PAYMENTREQUEST_0_SHIPTONAME']));
                    $shipping_first_name = $shipping_name[0];
                    unset($shipping_name[0]);
                    $shipping_last_name = implode(' ', $shipping_name);

                    $country_info = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE `iso_code_2` = '" . $this->db->escape($result['PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE']) . "' AND `status` = '1' LIMIT 1")->row;
                    $zone_info = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE `name` = '" . $this->db->escape($result['PAYMENTREQUEST_0_SHIPTOSTATE']) . "' AND `status` = '1' AND `country_id` = '" . (int)$country_info['country_id'] . "'")->row;

                    $address_data= array(
                        'firstname' => $shipping_first_name,
                        'lastname' => $shipping_last_name,
                        'company' => '',
                        'company_id' => '',
                        'tax_id' => '',
                        'address_1' => $result['PAYMENTREQUEST_0_SHIPTOSTREET'],
                        'address_2' => (isset($result['PAYMENTREQUEST_0_SHIPTOSTREET2']) ? $result['PAYMENTREQUEST_0_SHIPTOSTREET2'] : ''),
                        'postcode' => $result['PAYMENTREQUEST_0_SHIPTOZIP'],
                        'city' => $result['PAYMENTREQUEST_0_SHIPTOCITY'],
                        'zone_id' => (isset($zone_info['zone_id']) ? $zone_info['zone_id'] : 0),
                        'country_id' => (isset($country_info['country_id']) ? $country_info['country_id'] : 0),
                    );

                    $address_id = $this->model_account_address->addAddress($address_data);

                    $this->session->data['payment_address_id'] = $address_id;
                    $this->session->data['payment_country_id'] = $address_data['country_id'];
                    $this->session->data['payment_zone_id'] = $address_data['zone_id'];

                    $this->session->data['shipping_address_id'] = $address_id;
                    $this->session->data['shipping_country_id'] = $address_data['country_id'];
                    $this->session->data['shipping_zone_id'] = $address_data['zone_id'];
                    $this->session->data['shipping_postcode'] = $address_data['postcode'];
                }
            }
        }


        $this->redirect($this->url->link('payment/pp_express/expressConfirm', '', 'SSL'));
    }

    public function expressConfirm() {
        $this->language->load('payment/pp_express');
        $this->language->load('checkout/cart');

        $this->load->model('tool/image');
                
        // Coupon    
		if (isset($this->request->post['coupon']) && $this->validateCoupon()) { 
			$this->session->data['coupon'] = $this->request->post['coupon'];
			            
			$this->session->data['success'] = $this->language->get('text_coupon');
			
			$this->redirect($this->url->link('payment/pp_express/expressConfirm', '', 'SSL'));
		}

        // Voucher
        if (isset($this->request->post['voucher']) && $this->validateVoucher()) {
            $this->session->data['voucher'] = $this->request->post['voucher'];

            $this->session->data['success'] = $this->language->get('text_voucher');

            $this->redirect($this->url->link('payment/pp_express/expressConfirm', '', 'SSL'));
        }
        
		// Reward
        if (isset($this->request->post['reward']) && $this->validateReward()) {
            $this->session->data['reward'] = abs($this->request->post['reward']);

            $this->session->data['success'] = $this->language->get('text_reward');

            $this->redirect($this->url->link('payment/pp_express/expressConfirm', '', 'SSL'));
        }
        
        $this->document->setTitle($this->language->get('express_text_title'));

        $points = $this->customer->getRewardPoints();

        $points_total = 0;

        foreach ($this->cart->getProducts() as $product) {
            if ($product['points']) {
                $points_total += $product['points'];
            }
        }
        
        
        $this->data['text_title'] = $this->language->get('express_text_title');
        $this->data['text_next'] = $this->language->get('text_next');
        $this->data['text_next_choice'] = $this->language->get('text_next_choice');
        $this->data['text_use_voucher'] = $this->language->get('text_use_voucher');
        $this->data['text_use_coupon'] = $this->language->get('text_use_coupon');
        $this->data['text_use_reward'] = sprintf($this->language->get('text_use_reward'), $points);
        
        $this->data['button_coupon'] = $this->language->get('button_coupon');
        $this->data['button_voucher'] = $this->language->get('button_voucher');
        $this->data['button_reward'] = $this->language->get('button_reward');
        $this->data['button_shipping'] = $this->language->get('express_button_shipping');
        $this->data['entry_coupon'] = $this->language->get('express_entry_coupon');
        $this->data['entry_voucher'] = $this->language->get('entry_voucher');
        $this->data['entry_reward'] = sprintf($this->language->get('entry_reward'), $points_total);
        $this->data['button_confirm'] = $this->language->get('express_button_confirm');

        $this->data['column_name'] = $this->language->get('column_name');
        $this->data['column_model'] = $this->language->get('column_model');
        $this->data['column_quantity'] = $this->language->get('column_quantity');
        $this->data['column_price'] = $this->language->get('column_price');
        $this->data['column_total'] = $this->language->get('column_total');
        $this->data['text_until_cancelled'] = $this->language->get('text_until_cancelled');

        $this->data['text_trial'] = $this->language->get('text_trial');
        $this->data['text_recurring'] = $this->language->get('text_recurring');
        $this->data['text_length'] = $this->language->get('text_length');
        $this->data['text_recurring_item'] = $this->language->get('text_recurring_item');
        $this->data['text_payment_profile'] = $this->language->get('text_payment_profile');

        if (isset($this->request->post['next'])) {
            $this->data['next'] = $this->request->post['next'];
        } else {
            $this->data['next'] = '';
        }
        
        $this->data['coupon_status'] = $this->config->get('coupon_status');

        if (isset($this->request->post['coupon'])) {
            $this->data['coupon'] = $this->request->post['coupon'];
        } elseif (isset($this->session->data['coupon'])) {
            $this->data['coupon'] = $this->session->data['coupon'];
        } else {
            $this->data['coupon'] = '';
        }
                
        $this->data['voucher_status'] = $this->config->get('voucher_status');
        
        if (isset($this->request->post['voucher'])) {
            $this->data['voucher'] = $this->request->post['voucher'];
        } elseif (isset($this->session->data['voucher'])) {
            $this->data['voucher'] = $this->session->data['voucher'];
        } else {
            $this->data['voucher'] = '';
        }
        
        $this->data['reward_status'] = ($points && $points_total && $this->config->get('reward_status'));
        
        if (isset($this->request->post['reward'])) {
            $this->data['reward'] = $this->request->post['reward'];
        } elseif (isset($this->session->data['reward'])) {
            $this->data['reward'] = $this->session->data['reward'];
        } else {
            $this->data['reward'] = '';
        }
        
        $this->data['action'] = $this->url->link('payment/pp_express/expressConfirm', '', 'SSL');
        
        $products = $this->cart->getProducts();

        foreach ($products as $product) {
            $product_total = 0;

            foreach ($products as $product_2) {
                if ($product_2['product_id'] == $product['product_id']) {
                    $product_total += $product_2['quantity'];
                }
            }

            if ($product['minimum'] > $product_total) {
                $this->data['error_warning'] = sprintf($this->language->get('error_minimum'), $product['name'], $product['minimum']);
            }

            if ($product['image']) {
                $image = $this->model_tool_image->resize($product['image'], $this->config->get('config_image_cart_width'), $this->config->get('config_image_cart_height'));
            } else {
                $image = '';
            }

            $option_data = array();

            foreach ($product['option'] as $option) {
                if ($option['type'] != 'file') {
                    $value = $option['option_value'];
                } else {
                    $filename = $this->encryption->decrypt($option['option_value']);

                    $value = utf8_substr($filename, 0, utf8_strrpos($filename, '.'));
                }

                $option_data[] = array(
                    'name' => $option['name'],
                    'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
                );
            }

            // Display prices
            if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                $price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')));
            } else {
                $price = false;
            }

            // Display prices
            if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                $total = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity']);
            } else {
                $total = false;
            }
            
            $profile_description = '';

            if ($product['recurring']) {
                $frequencies = array(
                    'day' => $this->language->get('text_day'),
                    'week' => $this->language->get('text_week'),
                    'semi_month' => $this->language->get('text_semi_month'),
                    'month' => $this->language->get('text_month'),
                    'year' => $this->language->get('text_year'),
                );

                if ($product['recurring_trial']) {
                        $recurring_price = $this->currency->format($this->tax->calculate($product['recurring_trial_price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')));
                        $profile_description = sprintf($this->language->get('text_trial_description'), $recurring_price, $product['recurring_trial_cycle'], $frequencies[$product['recurring_trial_frequency']], $product['recurring_trial_duration']) . ' ';
                    }

                    $recurring_price = $this->currency->format($this->tax->calculate($product['recurring_price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')));

                    if ($product['recurring_duration']) {
                        $profile_description .= sprintf($this->language->get('text_payment_description'), $recurring_price, $product['recurring_cycle'], $frequencies[$product['recurring_frequency']], $product['recurring_duration']);
                    } else {
                        $profile_description .= sprintf($this->language->get('text_payment_until_canceled_description'), $recurring_price, $product['recurring_cycle'], $frequencies[$product['recurring_frequency']], $product['recurring_duration']);
                    }
            }

            $this->data['products'][] = array(
                'key' => $product['key'],
                'thumb' => $image,
                'name' => $product['name'],
                'model' => $product['model'],
                'option' => $option_data,
                'quantity' => $product['quantity'],
                'stock' => $product['stock'] ? true : !(!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning')),
                'reward' => ($product['reward'] ? sprintf($this->language->get('text_points'), $product['reward']) : ''),
                'price' => $price,
                'total' => $total,
                'href' => $this->url->link('product/product', 'product_id=' . $product['product_id']),
                'remove' => $this->url->link('checkout/cart', 'remove=' . $product['key']),
                'recurring' => $product['recurring'],
                'profile_name' => $product['profile_name'],
                'profile_description' => $profile_description,
            );
        }

        /**
         * Vouchers
         *
         * @todo
         */
        $this->data['vouchers'] = array();


        if($this->cart->hasShipping()) {

            $this->data['has_shipping'] = true;
            /**
             * Shipping services
             */
            if ($this->customer->isLogged()) {
                $this->load->model('account/address');
                $shipping_address = $this->model_account_address->getAddress($this->session->data['shipping_address_id']);
            } elseif (isset($this->session->data['guest'])) {
                $shipping_address = $this->session->data['guest']['shipping'];
            }

            if (!empty($shipping_address)) {
                // Shipping Methods
                $quote_data = array();

                $this->load->model('setting/extension');

                $results = $this->model_setting_extension->getExtensions('shipping');

                if(!empty($results)) {
                    foreach ($results as $result) {
                        if ($this->config->get($result['code'] . '_status')) {
                            $this->load->model('shipping/' . $result['code']);

                            $quote = $this->{'model_shipping_' . $result['code']}->getQuote($shipping_address);

                            if ($quote) {
                                $quote_data[$result['code']] = array(
                                    'title' => $quote['title'],
                                    'quote' => $quote['quote'],
                                    'sort_order' => $quote['sort_order'],
                                    'error' => $quote['error']
                                );
                            }
                        }
                    }

                    $sort_order = array();

                    foreach ($quote_data as $key => $value) {
                        $sort_order[$key] = $value['sort_order'];
                    }

                    array_multisort($sort_order, SORT_ASC, $quote_data);

                    $this->session->data['shipping_methods'] = $quote_data;
                    $this->data['shipping_methods'] = $quote_data;

                    if(!isset($this->session->data['shipping_method'])) {
                        //default the shipping to the very first option.
                        $key1 = key($quote_data);
                        $key2 = key($quote_data[$key1]['quote']);
                        $this->session->data['shipping_method'] = $quote_data[$key1]['quote'][$key2];
                    }

                    $this->data['code'] = $this->session->data['shipping_method']['code'];
                    $this->data['action_shipping'] = $this->url->link('payment/pp_express/shipping', '', 'SSL');
                } else {
                    unset($this->session->data['shipping_methods']);
                    unset($this->session->data['shipping_method']);
                    $this->data['error_no_shipping'] = $this->language->get('error_no_shipping');
                }
            }
        } else {
            $this->data['has_shipping'] = false;
        }

        /**
         * Product totals
         */
        $this->load->model('setting/extension');

        $total_data = array();
        $total = 0;
        $taxes = $this->cart->getTaxes();

        // Display prices
        if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
            $sort_order = array();

            $results = $this->model_setting_extension->getExtensions('total');

            foreach ($results as $key => $value) {
                $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
            }

            array_multisort($sort_order, SORT_ASC, $results);

            foreach ($results as $result) {
                if ($this->config->get($result['code'] . '_status')) {
                    $this->load->model('total/' . $result['code']);

                    $this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
                }

                $sort_order = array();

                foreach ($total_data as $key => $value) {
                    $sort_order[$key] = $value['sort_order'];
                }

                array_multisort($sort_order, SORT_ASC, $total_data);
            }
        }

        $this->data['totals'] = $total_data;

        /**
         * Payment methods
         */
        if ($this->customer->isLogged()) {
            $this->load->model('account/address');
            $payment_address = $this->model_account_address->getAddress($this->session->data['payment_address_id']);
        } elseif (isset($this->session->data['guest'])) {
            $payment_address = $this->session->data['guest']['payment'];
        }

        $method_data = array();

        $this->load->model('setting/extension');

        $results = $this->model_setting_extension->getExtensions('payment');

        foreach ($results as $result) {
            if ($this->config->get($result['code'] . '_status')) {
                $this->load->model('payment/' . $result['code']);

                $method = $this->{'model_payment_' . $result['code']}->getMethod($payment_address, $total);

                if ($method) {
                    $method_data[$result['code']] = $method;
                }
            }
        }

        $sort_order = array();

        foreach ($method_data as $key => $value) {
            $sort_order[$key] = $value['sort_order'];
        }

        array_multisort($sort_order, SORT_ASC, $method_data);

        $this->session->data['payment_methods'] = $method_data;
        $this->session->data['payment_method'] = $this->session->data['payment_methods']['pp_express'];

        $this->data['action_confirm'] = $this->url->link('payment/pp_express/expressComplete', '', 'SSL');

        $this->data['error_warning'] = '';
        $this->data['attention'] = '';
        $this->data['success'] = '';

        if(isset($this->session->data['error_warning'])) {
            $this->data['error_warning'] = $this->session->data['error_warning'];
            unset($this->session->data['error_warning']);
        }

        if(isset($this->session->data['success'])) {
            $this->data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        }

        if(isset($this->session->data['attention'])) {
            $this->data['attention'] = $this->session->data['attention'];
            unset($this->session->data['attention']);
        }

        $this->children = array(
            'common/column_left',
            'common/column_right',
            'common/content_top',
            'common/content_bottom',
            'common/footer',
            'common/header'
        );

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/pp_express_confirm.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/payment/pp_express_confirm.tpl';
        } else {
            $this->template = 'default/template/payment/pp_express_confirm.tpl';
        }

        $this->response->setOutput($this->render());
    }

    public function expressComplete() {
        $this->language->load('payment/pp_express');
        $redirect = '';

        if ($this->cart->hasShipping()) {
            // Validate if shipping address has been set.
            $this->load->model('account/address');

            if ($this->customer->isLogged() && isset($this->session->data['shipping_address_id'])) {
                $shipping_address = $this->model_account_address->getAddress($this->session->data['shipping_address_id']);
            } elseif (isset($this->session->data['guest'])) {
                $shipping_address = $this->session->data['guest']['shipping'];
            }

            if (empty($shipping_address)) {
                $redirect = $this->url->link('checkout/checkout', '', 'SSL');
            }

            // Validate if shipping method has been set.
            if (!isset($this->session->data['shipping_method'])) {
                $redirect = $this->url->link('checkout/checkout', '', 'SSL');
            }
        } else {
            unset($this->session->data['shipping_method']);
            unset($this->session->data['shipping_methods']);
        }

        // Validate if payment address has been set.
        $this->load->model('account/address');

        if ($this->customer->isLogged() && isset($this->session->data['payment_address_id'])) {
            $payment_address = $this->model_account_address->getAddress($this->session->data['payment_address_id']);
        } elseif (isset($this->session->data['guest'])) {
            $payment_address = $this->session->data['guest']['payment'];
        }

        if (empty($payment_address)) {
            $redirect = $this->url->link('checkout/checkout', '', 'SSL');
        }

        // Validate if payment method has been set.
        if (!isset($this->session->data['payment_method'])) {
            $redirect = $this->url->link('checkout/checkout', '', 'SSL');
        }

        // Validate cart has products and has stock.
        if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
            $redirect = $this->url->link('checkout/cart');
        }

        // Validate minimum quantity requirments.
        $products = $this->cart->getProducts();

        foreach ($products as $product) {
            $product_total = 0;

            foreach ($products as $product_2) {
                if ($product_2['product_id'] == $product['product_id']) {
                    $product_total += $product_2['quantity'];
                }
            }

            if ($product['minimum'] > $product_total) {
                $redirect = $this->url->link('checkout/cart');

                break;
            }
        }

        if ($redirect == '') {
            $total_data = array();
            $total = 0;
            $taxes = $this->cart->getTaxes();

            $this->load->model('setting/extension');

            $sort_order = array();

            $results = $this->model_setting_extension->getExtensions('total');

            foreach ($results as $key => $value) {
                $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
            }

            array_multisort($sort_order, SORT_ASC, $results);

            foreach ($results as $result) {
                if ($this->config->get($result['code'] . '_status')) {
                    $this->load->model('total/' . $result['code']);

                    $this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
                }
            }

            $sort_order = array();

            foreach ($total_data as $key => $value) {
                $sort_order[$key] = $value['sort_order'];
            }

            array_multisort($sort_order, SORT_ASC, $total_data);

            $this->language->load('checkout/checkout');

            $data = array();

            $data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
            $data['store_id'] = $this->config->get('config_store_id');
            $data['store_name'] = $this->config->get('config_name');

            if ($data['store_id']) {
                $data['store_url'] = $this->config->get('config_url');
            } else {
                $data['store_url'] = HTTP_SERVER;
            }

            if ($this->customer->isLogged()) {
                $data['customer_id'] = $this->customer->getId();
                $data['customer_group_id'] = $this->customer->getCustomerGroupId();
                $data['firstname'] = $this->customer->getFirstName();
                $data['lastname'] = $this->customer->getLastName();
                $data['email'] = $this->customer->getEmail();
                $data['telephone'] = $this->customer->getTelephone();
                $data['fax'] = $this->customer->getFax();

                $this->load->model('account/address');

                $payment_address = $this->model_account_address->getAddress($this->session->data['payment_address_id']);
            } elseif (isset($this->session->data['guest'])) {
                $data['customer_id'] = 0;
                $data['customer_group_id'] = $this->session->data['guest']['customer_group_id'];
                $data['firstname'] = $this->session->data['guest']['firstname'];
                $data['lastname'] = $this->session->data['guest']['lastname'];
                $data['email'] = $this->session->data['guest']['email'];
                $data['telephone'] = $this->session->data['guest']['telephone'];
                $data['fax'] = $this->session->data['guest']['fax'];

                $payment_address = $this->session->data['guest']['payment'];
            }

            $data['payment_firstname'] = $payment_address['firstname'];
            $data['payment_lastname'] = $payment_address['lastname'];
            $data['payment_company'] = $payment_address['company'];
            $data['payment_company_id'] = $payment_address['company_id'];
            $data['payment_tax_id'] = $payment_address['tax_id'];
            $data['payment_address_1'] = $payment_address['address_1'];
            $data['payment_address_2'] = $payment_address['address_2'];
            $data['payment_city'] = $payment_address['city'];
            $data['payment_postcode'] = $payment_address['postcode'];
            $data['payment_zone'] = $payment_address['zone'];
            $data['payment_zone_id'] = $payment_address['zone_id'];
            $data['payment_country'] = $payment_address['country'];
            $data['payment_country_id'] = $payment_address['country_id'];
            $data['payment_address_format'] = $payment_address['address_format'];

            $data['payment_method'] = '';
            if (isset($this->session->data['payment_method']['title'])) {
                $data['payment_method'] = $this->session->data['payment_method']['title'];
            }

            $data['payment_code'] = '';
            if (isset($this->session->data['payment_method']['code'])) {
                $data['payment_code'] = $this->session->data['payment_method']['code'];
            }

            if ($this->cart->hasShipping()) {
                if ($this->customer->isLogged()) {
                    $this->load->model('account/address');

                    $shipping_address = $this->model_account_address->getAddress($this->session->data['shipping_address_id']);
                } elseif (isset($this->session->data['guest'])) {
                    $shipping_address = $this->session->data['guest']['shipping'];
                }

                $data['shipping_firstname'] = $shipping_address['firstname'];
                $data['shipping_lastname'] = $shipping_address['lastname'];
                $data['shipping_company'] = $shipping_address['company'];
                $data['shipping_address_1'] = $shipping_address['address_1'];
                $data['shipping_address_2'] = $shipping_address['address_2'];
                $data['shipping_city'] = $shipping_address['city'];
                $data['shipping_postcode'] = $shipping_address['postcode'];
                $data['shipping_zone'] = $shipping_address['zone'];
                $data['shipping_zone_id'] = $shipping_address['zone_id'];
                $data['shipping_country'] = $shipping_address['country'];
                $data['shipping_country_id'] = $shipping_address['country_id'];
                $data['shipping_address_format'] = $shipping_address['address_format'];

                $data['shipping_method'] = '';
                if (isset($this->session->data['shipping_method']['title'])) {
                    $data['shipping_method'] = $this->session->data['shipping_method']['title'];
                }

                $data['shipping_code'] = '';
                if (isset($this->session->data['shipping_method']['code'])) {
                    $data['shipping_code'] = $this->session->data['shipping_method']['code'];
                }
            } else {
                $data['shipping_firstname'] = '';
                $data['shipping_lastname'] = '';
                $data['shipping_company'] = '';
                $data['shipping_address_1'] = '';
                $data['shipping_address_2'] = '';
                $data['shipping_city'] = '';
                $data['shipping_postcode'] = '';
                $data['shipping_zone'] = '';
                $data['shipping_zone_id'] = '';
                $data['shipping_country'] = '';
                $data['shipping_country_id'] = '';
                $data['shipping_address_format'] = '';
                $data['shipping_method'] = '';
                $data['shipping_code'] = '';
            }

            $product_data = array();

            foreach ($this->cart->getProducts() as $product) {
                $option_data = array();

                foreach ($product['option'] as $option) {
                    if ($option['type'] != 'file') {
                        $value = $option['option_value'];
                    } else {
                        $value = $this->encryption->decrypt($option['option_value']);
                    }

                    $option_data[] = array(
                        'product_option_id' => $option['product_option_id'],
                        'product_option_value_id' => $option['product_option_value_id'],
                        'option_id' => $option['option_id'],
                        'option_value_id' => $option['option_value_id'],
                        'name' => $option['name'],
                        'value' => $value,
                        'type' => $option['type']
                    );
                }

                $product_data[] = array(
                    'product_id' => $product['product_id'],
                    'name' => $product['name'],
                    'model' => $product['model'],
                    'option' => $option_data,
                    'download' => $product['download'],
                    'quantity' => $product['quantity'],
                    'subtract' => $product['subtract'],
                    'price' => $product['price'],
                    'total' => $product['total'],
                    'tax' => $this->tax->getTax($product['price'], $product['tax_class_id']),
                    'reward' => $product['reward']
                );
            }

            // Gift Voucher
            $voucher_data = array();

            if (!empty($this->session->data['vouchers'])) {
                foreach ($this->session->data['vouchers'] as $voucher) {
                    $voucher_data[] = array(
                        'description' => $voucher['description'],
                        'code' => substr(md5(mt_rand()), 0, 10),
                        'to_name' => $voucher['to_name'],
                        'to_email' => $voucher['to_email'],
                        'from_name' => $voucher['from_name'],
                        'from_email' => $voucher['from_email'],
                        'voucher_theme_id' => $voucher['voucher_theme_id'],
                        'message' => $voucher['message'],
                        'amount' => $voucher['amount']
                    );
                }
            }

            $data['products'] = $product_data;
            $data['vouchers'] = $voucher_data;
            $data['totals'] = $total_data;
            $data['comment'] = $this->session->data['comment'];
            $data['total'] = $total;

            if (isset($this->request->cookie['tracking'])) {
                $this->load->model('affiliate/affiliate');

                $affiliate_info = $this->model_affiliate_affiliate->getAffiliateByCode($this->request->cookie['tracking']);
                $subtotal = $this->cart->getSubTotal();

                if ($affiliate_info) {
                    $data['affiliate_id'] = $affiliate_info['affiliate_id'];
                    $data['commission'] = ($subtotal / 100) * $affiliate_info['commission'];
                } else {
                    $data['affiliate_id'] = 0;
                    $data['commission'] = 0;
                }
            } else {
                $data['affiliate_id'] = 0;
                $data['commission'] = 0;
            }

            $data['language_id'] = $this->config->get('config_language_id');
            $data['currency_id'] = $this->currency->getId();
            $data['currency_code'] = $this->currency->getCode();
            $data['currency_value'] = $this->currency->getValue($this->currency->getCode());
            $data['ip'] = $this->request->server['REMOTE_ADDR'];

            if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
                $data['forwarded_ip'] = $this->request->server['HTTP_X_FORWARDED_FOR'];
            } elseif(!empty($this->request->server['HTTP_CLIENT_IP'])) {
                $data['forwarded_ip'] = $this->request->server['HTTP_CLIENT_IP'];
            } else {
                $data['forwarded_ip'] = '';
            }

            if (isset($this->request->server['HTTP_USER_AGENT'])) {
                $data['user_agent'] = $this->request->server['HTTP_USER_AGENT'];
            } else {
                $data['user_agent'] = '';
            }

            if (isset($this->request->server['HTTP_ACCEPT_LANGUAGE'])) {
                $data['accept_language'] = $this->request->server['HTTP_ACCEPT_LANGUAGE'];
            } else {
                $data['accept_language'] = '';
            }

            $this->load->model('checkout/order');

            $order_id = $this->model_checkout_order->addOrder($data);
            $this->session->data['order_id'] = $order_id;

            $this->load->model('payment/pp_express');

            $paypal_data = array(
                'TOKEN' => $this->session->data['paypal']['token'],
                'PAYERID' => $this->session->data['paypal']['payerid'],
                'METHOD' => 'DoExpressCheckoutPayment',
                'PAYMENTREQUEST_0_NOTIFYURL' => $this->url->link('payment/pp_express/ipn', '', 'SSL'),
                'RETURNFMFDETAILS' => 1,
            );

            $paypal_data = array_merge($paypal_data, $this->model_payment_pp_express->paymentRequestInfo());

            $result = $this->model_payment_pp_express->call($paypal_data);

            if($result['ACK'] == 'Success') {
                //handle order status
                switch($result['PAYMENTINFO_0_PAYMENTSTATUS']) {
                    case 'Canceled_Reversal':
                        $order_status_id = $this->config->get('pp_express_canceled_reversal_status_id');
                        break;
                    case 'Completed':
                         $order_status_id = $this->config->get('pp_express_completed_status_id');
                        break;
                    case 'Denied':
                        $order_status_id = $this->config->get('pp_express_denied_status_id');
                        break;
                    case 'Expired':
                        $order_status_id = $this->config->get('pp_express_expired_status_id');
                        break;
                    case 'Failed':
                        $order_status_id = $this->config->get('pp_express_failed_status_id');
                        break;
                    case 'Pending':
                        $order_status_id = $this->config->get('pp_express_pending_status_id');
                        break;
                    case 'Processed':
                        $order_status_id = $this->config->get('pp_express_processed_status_id');
                        break;
                    case 'Refunded':
                        $order_status_id = $this->config->get('pp_express_refunded_status_id');
                        break;
                    case 'Reversed':
                        $order_status_id = $this->config->get('pp_express_reversed_status_id');
                        break;
                    case 'Voided':
                        $order_status_id = $this->config->get('pp_express_voided_status_id');
                        break;
                }

                $this->model_checkout_order->confirm($order_id, $order_status_id);

                //add order to paypal table
                $paypal_order_data = array(
                    'order_id' => $order_id,
                    'capture_status' => ($this->config->get('pp_express_method') == 'Sale' ? 'Complete' : 'NotComplete'),
                    'currency_code' => $result['PAYMENTINFO_0_CURRENCYCODE'],
                    'authorization_id' => $result['PAYMENTINFO_0_TRANSACTIONID'],
                    'total' => $result['PAYMENTINFO_0_AMT'],
                );
                $paypal_order_id = $this->model_payment_pp_express->addOrder($paypal_order_data);

                //add transaction to paypal transaction table
                $paypal_transaction_data = array(
                    'paypal_order_id' => $paypal_order_id,
                    'transaction_id' => $result['PAYMENTINFO_0_TRANSACTIONID'],
                    'parent_transaction_id' => '',
                    'note' => '',
                    'msgsubid' => '',
                    'receipt_id' => (isset($result['PAYMENTINFO_0_RECEIPTID']) ? $result['PAYMENTINFO_0_RECEIPTID'] : ''),
                    'payment_type' => $result['PAYMENTINFO_0_PAYMENTTYPE'],
                    'payment_status' => $result['PAYMENTINFO_0_PAYMENTSTATUS'],
                    'pending_reason' => $result['PAYMENTINFO_0_PENDINGREASON'],
                    'transaction_entity' => ($this->config->get('pp_express_method') == 'Sale' ? 'payment' : 'auth'),
                    'amount' => $result['PAYMENTINFO_0_AMT'],
                    'debug_data' => json_encode($result),
                );                
                $this->model_payment_pp_express->addTransaction($paypal_transaction_data);

                $recurring_products = $this->cart->getRecurringProducts();
                
                //loop through any products that are recurring items
                if(!empty($recurring_products)) {

                    $this->language->load('payment/pp_express');

                    $this->load->model('checkout/recurring');

                    $billing_period = array(
                        'day' => 'Day',
                        'week' => 'Week',
                        'semi_month' => 'SemiMonth',
                        'month' => 'Month',
                        'year' => 'Year'
                    );
                    
                    foreach($recurring_products as $item) {
                        $data = array(
                            'METHOD' => 'CreateRecurringPaymentsProfile',
                            'TOKEN' => $this->session->data['paypal']['token'],
                            'PROFILESTARTDATE' => gmdate("Y-m-d\TH:i:s\Z", mktime(gmdate("H"), gmdate("i")+5, gmdate("s"), gmdate("m"), gmdate("d"), gmdate("y"))),
                            'BILLINGPERIOD' => $billing_period[$item['recurring_frequency']],
                            'BILLINGFREQUENCY' => $item['recurring_cycle'],
                            'TOTALBILLINGCYCLES' => $item['recurring_duration'],
                            'AMT' => $this->currency->format($this->tax->calculate($item['recurring_price'], $item['tax_class_id'], $this->config->get('config_tax')), false, false, false) * $item['quantity'],
                            'CURRENCYCODE' => $this->currency->getCode(),
                        );

                        //trial information
                        if($item['recurring_trial'] == 1) {
                            $data_trial = array(
                                'TRIALBILLINGPERIOD' => $billing_period[$item['recurring_trial_frequency']],
                                'TRIALBILLINGFREQUENCY' => $item['recurring_trial_cycle'],
                                'TRIALTOTALBILLINGCYCLES' => $item['recurring_trial_duration'],
                                'TRIALAMT' => $this->currency->format($this->tax->calculate($item['recurring_trial_price'], $item['tax_class_id'], $this->config->get('config_tax')), false, false, false) * $item['quantity'],
                            );

                            $trial_amt = $this->currency->format($this->tax->calculate($item['recurring_trial_price'], $item['tax_class_id'], $this->config->get('config_tax')), false, false, false) * $item['quantity'].' '.$this->currency->getCode();
                            $trial_text =  sprintf($this->language->get('text_trial'), $trial_amt, $item['recurring_trial_cycle'], $item['recurring_trial_frequency'], $item['recurring_trial_duration']);

                            $data = array_merge($data, $data_trial);
                        } else {
                            $trial_text = '';
                        }

                        $recurring_amt = $this->currency->format($this->tax->calculate($item['recurring_price'], $item['tax_class_id'], $this->config->get('config_tax')), false, false, false)  * $item['quantity'].' '.$this->currency->getCode();
                        $recurring_description = $trial_text . sprintf($this->language->get('text_recurring'), $recurring_amt, $item['recurring_cycle'], $item['recurring_frequency']);

                        if($item['recurring_duration'] > 0) {
                            $recurring_description .= sprintf($this->language->get('text_length'), $item['recurring_duration']);
                        }

                        //create new profile and set to pending status as no payment has been made yet.
                        $recurring_id = $this->model_checkout_recurring->create($item, $order_id, $recurring_description);

                        $data['PROFILEREFERENCE'] = $recurring_id;
                        $data['DESC'] = $recurring_description;

                        $result = $this->model_payment_pp_express->call($data);

                        if(isset($result['PROFILEID'])) {
                            $this->model_checkout_recurring->addReference($recurring_id, $result['PROFILEID']);
                        } else {
                            // there was an error creating the profile, need to log and also alert admin / user


                        }
                    }
                }

                $this->redirect($this->url->link('checkout/success'));

                if(isset($result['REDIRECTREQUIRED']) && $result['REDIRECTREQUIRED'] == true) { //- handle german redirect here
                    $this->redirect('https://www.paypal.com/cgi-bin/webscr?cmd=_complete-express-checkout&token='.$this->session->data['paypal']['token']);
                }
            } else {
                if ($result['L_ERRORCODE0'] == '10486') {
                    if (isset($this->session->data['paypal_redirect_count'])) {
                        
                        if ($this->session->data['paypal_redirect_count'] == 2) {
                            $this->session->data['paypal_redirect_count'] = 0;
                            $this->session->data['error'] = $this->language->get('error_too_many_failures');
                            $this->redirect($this->url->link('checkout/checkout', '', 'SSL'));                            
                        } else {
                            $this->session->data['paypal_redirect_count']++;
                        }
                    } else {
                        $this->session->data['paypal_redirect_count'] = 1;
                    }
                    
                    if ($this->config->get('pp_express_test') == 1) {
                        $this->redirect('https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=' . $this->session->data['paypal']['token']);
                    } else {
                        $this->redirect('https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=' . $this->session->data['paypal']['token']);
                    }
                }
                
                $this->session->data['error'] = $result['L_LONGMESSAGE0'];
                $this->redirect($this->url->link('payment/pp_express/expressConfirm', '', 'SSL'));
            }
        } else {
            $this->redirect($redirect);
        }
    }

    public function checkout() {
        if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
            $this->redirect($this->url->link('checkout/cart'));
        }

        $this->load->model('payment/pp_express');
        $this->load->model('tool/image');
        $this->load->model('checkout/order');
        
        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
        
        $max_amount = $this->currency->convert($order_info['total'], $this->config->get('config_currency'), 'USD');
        $max_amount = min($max_amount * 1.25, 10000);
        $max_amount = $this->currency->format($max_amount, $this->currency->getCode(), '', false);

        $data = array(
            'METHOD' => 'SetExpressCheckout',
            'MAXAMT' => $max_amount,
            'RETURNURL' => $this->url->link('payment/pp_express/checkoutReturn', '', 'SSL'),
            'CANCELURL' => $this->url->link('checkout/checkout', '', 'SSL'),
            'REQCONFIRMSHIPPING' => 0,
            'NOSHIPPING' => 1,
            'LOCALECODE' => 'EN',
            'LANDINGPAGE' => 'Login',
            'HDRIMG' => $this->model_tool_image->resize($this->config->get('pp_express_logo'), 790, 90),
            'HDRBORDERCOLOR' => $this->config->get('pp_express_border_colour'),
            'HDRBACKCOLOR' => $this->config->get('pp_express_header_colour'),
            'PAYFLOWCOLOR' => $this->config->get('pp_express_page_colour'),
            'CHANNELTYPE' => 'Merchant',
            'ALLOWNOTE' => $this->config->get('pp_express_allow_note'),
        );

        $data = array_merge($data, $this->model_payment_pp_express->paymentRequestInfo());

        $result = $this->model_payment_pp_express->call($data);
        
        /**
         * If a failed PayPal setup happens, handle it.
         */
        if(!isset($result['TOKEN'])) {            
            $this->session->data['error'] = $result['L_LONGMESSAGE0'];
            /**
             * Unable to add error message to user as the session errors/success are not
             * used on the cart or checkout pages - need to be added?
             * If PayPal debug log is off then still log error to normal error log.
             */
            if($this->config->get('pp_express_debug') == 0) {
                $this->log->write(serialize($result));
            }

            $this->redirect($this->url->link('checkout/checkout', '', 'SSL'));
        }

        $this->session->data['paypal']['token'] = $result['TOKEN'];

        if ($this->config->get('pp_express_test') == 1) {
            header('Location: https://www.sandbox.paypal.com/cgiâ€‘bin/webscr?cmd=_express-checkout&token=' . $result['TOKEN'].'&useraction=commit');
        } else {
            header('Location: https://www.paypal.com/cgiâ€‘bin/webscr?cmd=_express-checkout&token=' . $result['TOKEN'].'&useraction=commit');
        }
    }

    public function checkoutReturn() {
        $this->language->load('payment/pp_express');
        /**
         * Get the details
         */
        $this->load->model('payment/pp_express');
        $this->load->model('checkout/order');

        $data = array(
            'METHOD' => 'GetExpressCheckoutDetails',
            'TOKEN' => $this->session->data['paypal']['token'],
        );

        $result = $this->model_payment_pp_express->call($data);
        $this->session->data['paypal']['payerid'] = $result['PAYERID'];
        $this->session->data['paypal']['result'] = $result;

        $order_id = $this->session->data['order_id'];

        $paypal_data = array(
            'TOKEN' => $this->session->data['paypal']['token'],
            'PAYERID' => $this->session->data['paypal']['payerid'],
            'METHOD' => 'DoExpressCheckoutPayment',
            'PAYMENTREQUEST_0_NOTIFYURL' => $this->url->link('payment/pp_express/ipn', '', 'SSL'),
            'RETURNFMFDETAILS' => 1,
        );

        $paypal_data = array_merge($paypal_data, $this->model_payment_pp_express->paymentRequestInfo());
        
        $result = $this->model_payment_pp_express->call($paypal_data);

        if($result['ACK'] == 'Success') {
            //handle order status
            switch($result['PAYMENTINFO_0_PAYMENTSTATUS']) {
                case 'Canceled_Reversal':
                    $order_status_id = $this->config->get('pp_express_canceled_reversal_status_id');
                    break;
                case 'Completed':
                    $order_status_id = $this->config->get('pp_express_completed_status_id');
                    break;
                case 'Denied':
                    $order_status_id = $this->config->get('pp_express_denied_status_id');
                    break;
                case 'Expired':
                    $order_status_id = $this->config->get('pp_express_expired_status_id');
                    break;
                case 'Failed':
                    $order_status_id = $this->config->get('pp_express_failed_status_id');
                    break;
                case 'Pending':
                    $order_status_id = $this->config->get('pp_express_pending_status_id');
                    break;
                case 'Processed':
                    $order_status_id = $this->config->get('pp_express_processed_status_id');
                    break;
                case 'Refunded':
                    $order_status_id = $this->config->get('pp_express_refunded_status_id');
                    break;
                case 'Reversed':
                    $order_status_id = $this->config->get('pp_express_reversed_status_id');
                    break;
                case 'Voided':
                    $order_status_id = $this->config->get('pp_express_voided_status_id');
                    break;
            }

            $this->model_checkout_order->confirm($order_id, $order_status_id);

            //add order to paypal table
            $paypal_order_data = array(
                'order_id' => $order_id,
                'capture_status' => ($this->config->get('pp_express_method') == 'Sale' ? 'Complete' : 'NotComplete'),
                'currency_code' => $result['PAYMENTINFO_0_CURRENCYCODE'],
                'authorization_id' => $result['PAYMENTINFO_0_TRANSACTIONID'],
                'total' => $result['PAYMENTINFO_0_AMT'],
            );
            $paypal_order_id = $this->model_payment_pp_express->addOrder($paypal_order_data);

            //add transaction to paypal transaction table
            $paypal_transaction_data = array(
                'paypal_order_id' => $paypal_order_id,
                'transaction_id' => $result['PAYMENTINFO_0_TRANSACTIONID'],
                'parent_transaction_id' => '',
                'note' => '',
                'msgsubid' => '',
                'receipt_id' => (isset($result['PAYMENTINFO_0_RECEIPTID']) ? $result['PAYMENTINFO_0_RECEIPTID'] : ''),
                'payment_type' => $result['PAYMENTINFO_0_PAYMENTTYPE'],
                'payment_status' => $result['PAYMENTINFO_0_PAYMENTSTATUS'],
                'pending_reason' => $result['PAYMENTINFO_0_PENDINGREASON'],
                'transaction_entity' => ($this->config->get('pp_express_method') == 'Sale' ? 'payment' : 'auth'),
                'amount' => $result['PAYMENTINFO_0_AMT'],
                'debug_data' => json_encode($result),
            );
            $this->model_payment_pp_express->addTransaction($paypal_transaction_data);

            $recurring_products = $this->cart->getRecurringProducts();
            
            //loop through any products that are recurring items
            if(!empty($recurring_products)) {

                $this->load->model('checkout/recurring');

                $billing_period = array(
                    'day' => 'Day',
                    'week' => 'Week',
                    'semi_month' => 'SemiMonth',
                    'month' => 'Month',
                    'year' => 'Year'
                );
                
                foreach($recurring_products as $item) {
                    $data = array(
                        'METHOD' => 'CreateRecurringPaymentsProfile',
                        'TOKEN' => $this->session->data['paypal']['token'],
                        'PROFILESTARTDATE' => gmdate("Y-m-d\TH:i:s\Z", mktime(gmdate("H"), gmdate("i")+5, gmdate("s"), gmdate("m"), gmdate("d"), gmdate("y"))),
                        'BILLINGPERIOD' => $billing_period[$item['recurring_frequency']],
                        'BILLINGFREQUENCY' => $item['recurring_cycle'],
                        'TOTALBILLINGCYCLES' => $item['recurring_duration'],
                        'AMT' => $this->currency->format($this->tax->calculate($item['recurring_price'], $item['tax_class_id'], $this->config->get('config_tax')), false, false, false) * $item['quantity'],
                        'CURRENCYCODE' => $this->currency->getCode(),
                    );

                    //trial information
                    if($item['recurring_trial'] == 1) {
                        $data_trial = array(
                            'TRIALBILLINGPERIOD' => $billing_period[$item['recurring_trial_frequency']],
                            'TRIALBILLINGFREQUENCY' => $item['recurring_trial_cycle'],
                            'TRIALTOTALBILLINGCYCLES' => $item['recurring_trial_duration'],
                            'TRIALAMT' => $this->currency->format($this->tax->calculate($item['recurring_trial_price'], $item['tax_class_id'], $this->config->get('config_tax')), false, false, false) * $item['quantity'],
                        );

                        $trial_amt = $this->currency->format($this->tax->calculate($item['recurring_trial_price'], $item['tax_class_id'], $this->config->get('config_tax')), false, false, false) * $item['quantity'].' '.$this->currency->getCode();
                        $trial_text =  sprintf($this->language->get('text_trial'), $trial_amt, $item['recurring_trial_cycle'], $item['recurring_trial_frequency'], $item['recurring_trial_duration']);

                        $data = array_merge($data, $data_trial);
                    } else {
                        $trial_text = '';
                    }

                    $recurring_amt = $this->currency->format($this->tax->calculate($item['recurring_price'], $item['tax_class_id'], $this->config->get('config_tax')), false, false, false)  * $item['quantity'].' '.$this->currency->getCode();
                    $recurring_description = $trial_text.sprintf($this->language->get('text_recurring'), $recurring_amt, $item['recurring_cycle'], $item['recurring_frequency']);

                    if($item['recurring_duration'] > 0) {
                        $recurring_description .= sprintf($this->language->get('text_length'), $item['recurring_duration']);
                    }

                    //create new profile and set to pending status as no payment has been made yet.
                    $recurring_id = $this->model_checkout_recurring->create($item, $order_id, $recurring_description);

                    $data['PROFILEREFERENCE'] = $recurring_id;
                    $data['DESC'] = $recurring_description;

                    $result = $this->model_payment_pp_express->call($data);

                    if(isset($result['PROFILEID'])) {
                        $this->model_checkout_recurring->addReference($recurring_id, $result['PROFILEID']);
                    } else {
                        // there was an error creating the profile, need to log and also alert admin / user

                        
                    }
                }
            }

            if(isset($result['REDIRECTREQUIRED']) && $result['REDIRECTREQUIRED'] == true) { //- handle german redirect here
                $this->redirect('https://www.paypal.com/cgi-bin/webscr?cmd=_complete-express-checkout&token='.$this->session->data['paypal']['token']);
            } else {
                $this->redirect($this->url->link('checkout/success'));
            }
        } else {
            
            if ($result['L_ERRORCODE0'] == '10486') {
                if (isset($this->session->data['paypal_redirect_count'])) {

                    if ($this->session->data['paypal_redirect_count'] == 2) {
                        $this->session->data['paypal_redirect_count'] = 0;
                        $this->session->data['error'] = $this->language->get('error_too_many_failures');
                        $this->redirect($this->url->link('checkout/checkout', '', 'SSL'));
                    } else {
                        $this->session->data['paypal_redirect_count']++;
                    }
                } else {
                    $this->session->data['paypal_redirect_count'] = 1;
                }

                if ($this->config->get('pp_express_test') == 1) {
                    $this->redirect('https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=' . $this->session->data['paypal']['token']);
                } else {
                    $this->redirect('https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=' . $this->session->data['paypal']['token']);
                }
            }
            
            $this->language->load('payment/pp_express');

            $this->data['breadcrumbs'] = array();

            $this->data['breadcrumbs'][] = array(
                'href' => $this->url->link('common/home'),
                'text' => $this->language->get('text_home'),
                'separator' => false
            );

            $this->data['breadcrumbs'][] = array(
                'href' => $this->url->link('checkout/cart'),
                'text' => $this->language->get('text_cart'),
                'separator' => $this->language->get('text_separator')
            );

            $this->data['heading_title'] = $this->language->get('error_heading_title');

            $this->data['text_error'] = '<div class="warning">'.$result['L_ERRORCODE0'].' : '.$result['L_LONGMESSAGE0'].'</div>';

            $this->data['button_continue'] = $this->language->get('button_continue');

            $this->data['continue'] = $this->url->link('checkout/cart');

            unset($this->session->data['success']);

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
                $this->template = $this->config->get('config_template') . '/template/error/not_found.tpl';
            } else {
                $this->template = 'default/template/error/not_found.tpl';
            }

            $this->children = array(
                'common/column_left',
                'common/column_right',
                'common/content_top',
                'common/content_bottom',
                'common/footer',
                'common/header'
            );

            $this->response->setOutput($this->render());
        }
    }

    public function ipn() {
        $this->load->model('payment/pp_express');
        $this->load->model('account/recurring');

        $request = 'cmd=_notify-validate';

        foreach ($_POST as $key => $value) {
            $request .= '&' . $key . '=' . urlencode(stripslashes($value));
        }

        if ($this->config->get('pp_express_test') == 1) {
            $curl = curl_init('https://www.sandbox.paypal.com/cgi-bin/webscr');
        } else {
            $curl = curl_init('https://www.paypal.com/cgi-bin/webscr');
        }

        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $response = trim(curl_exec($curl));

        if (!$response) {
            $this->model_payment_pp_express->log(array('error' => curl_error($curl),'error_no' => curl_errno($curl)), 'Curl failed');
        }

        $this->model_payment_pp_express->log(array('request' => $request,'response' => $response), 'IPN data');

        if ( (string)$response == "VERIFIED" )  {

            $this->log->write((isset($this->request->post['transaction_entity']) ? $this->request->post['transaction_entity'] : ''));

            if(isset($this->request->post['txn_id'])) {
                $transaction = $this->model_payment_pp_express->getTransactionRow($this->request->post['txn_id']);
            } else {
                $transaction = false;
            }

            if(isset($this->request->post['parent_txn_id'])) {
                $parent_transaction = $this->model_payment_pp_express->getTransactionRow($this->request->post['parent_txn_id']);
            } else {
                $parent_transaction = false;
            }

            if($transaction) {
                //transaction exists, check for cleared payment or updates etc
                $this->log->write('Transaction exists');
                
                //if the transaction is pending but the new status is completed
                if($transaction['payment_status'] != $this->request->post['payment_status']) {
                    $this->db->query("UPDATE `" . DB_PREFIX . "paypal_order_transaction` SET `payment_status` = '" . $this->request->post['payment_status'] . "' WHERE `transaction_id` = '" . $this->db->escape($transaction['transaction_id']) . "' LIMIT 1");


                }elseif($transaction['payment_status'] == 'Pending' && ($transaction['pending_reason'] != $this->request->post['pending_reason'])) {
                    //payment is still pending but the pending reason has changed, update it.
                    $this->db->query("UPDATE `" . DB_PREFIX . "paypal_order_transaction` SET `pending_reason` = '" . $this->request->post['pending_reason'] . "' WHERE `transaction_id` = '" . $this->db->escape($transaction['transaction_id']) . "' LIMIT 1");
                }
            } else {
                $this->log->write('Transaction does not exist');
                if($parent_transaction) {
                    $this->log->write('Parent transaction exists');
                    //parent transaction exists

                    //insert new related transaction
                    $transaction = array(
                        'paypal_order_id' => $parent_transaction['paypal_order_id'],
                        'transaction_id' => $this->request->post['txn_id'],
                        'parent_transaction_id' => $this->request->post['parent_txn_id'],
                        'note' => '',
                        'msgsubid' => '',
                        'receipt_id' => (isset($this->request->post['receipt_id']) ? $this->request->post['receipt_id'] : ''),
                        'payment_type' => (isset($this->request->post['payment_type']) ? $this->request->post['payment_type'] : ''),
                        'payment_status' => (isset($this->request->post['payment_status']) ? $this->request->post['payment_status'] : ''),
                        'pending_reason' => (isset($this->request->post['pending_reason']) ? $this->request->post['pending_reason'] : ''),
                        'amount' => $this->request->post['mc_gross'],
                        'debug_data' => json_encode($this->request->post),
                        'transaction_entity' => (isset($this->request->post['transaction_entity']) ? $this->request->post['transaction_entity'] : ''),
                    );

                    $this->model_payment_pp_express->addTransaction($transaction);

                    /**
                     * If there has been a refund, log this against the parent transaction.
                     */
                    if(isset($this->request->post['payment_status']) && $this->request->post['payment_status'] == 'Refunded') {
                        if(($this->request->post['mc_gross'] * -1) == $parent_transaction['amount']) {
                            $this->db->query("UPDATE `" . DB_PREFIX . "paypal_order_transaction` SET `payment_status` = 'Refunded' WHERE `transaction_id` = '" . $this->db->escape($parent_transaction['transaction_id']) . "' LIMIT 1");
                        } else {
                            $this->db->query("UPDATE `" . DB_PREFIX . "paypal_order_transaction` SET `payment_status` = 'Partially-Refunded' WHERE `transaction_id` = '" . $this->db->escape($parent_transaction['transaction_id']) . "' LIMIT 1");
                        }
                    }

                    /**
                     * If the capture payment is now complete
                     */
                    if(isset($this->request->post['auth_status']) && $this->request->post['auth_status'] == 'Completed' && $parent_transaction['payment_status'] == 'Pending') {
                        $captured = number_format($this->model_payment_pp_express->totalCaptured($parent_transaction['paypal_order_id']), 2);
                        $refunded = number_format($this->model_payment_pp_express->totalRefundedOrder($parent_transaction['paypal_order_id']), 2);
                        $remaining = number_format($parent_transaction['amount'] - $captured + $refunded, 2);

                        $this->log->write('Captured: '.$captured);
                        $this->log->write('Refunded: '.$refunded);
                        $this->log->write('Remaining: '.$remaining);

                        if($remaining > 0.00) {
                            $transaction = array(
                                'paypal_order_id' => $parent_transaction['paypal_order_id'],
                                'transaction_id' => '',
                                'parent_transaction_id' => $this->request->post['parent_txn_id'],
                                'note' => '',
                                'msgsubid' => '',
                                'receipt_id' => '',
                                'payment_type' => '',
                                'payment_status' => 'Void',
                                'pending_reason' => '',
                                'amount' => '',
                                'debug_data' => 'Voided after capture',
                                'transaction_entity' => 'auth'
                            );

                            $this->model_payment_pp_express->addTransaction($transaction);
                        }

                        $this->model_payment_pp_express->updateOrder('Complete', $parent_transaction['order_id']);
                    }

                } else {
                    //parent transaction doesn't exists, need to investigate?
                    $this->log->write('Parent transaction not found');
                }
            }

            /*
             * Subscription payments
             *
             * profile ID should always exist if its a recurring payment transaction.
             *
             * also the reference will match a recurring payment ID
             */
            if (isset($this->request->post['txn_type'])) {
                //payment
                if ($this->request->post['txn_type'] == 'recurring_payment') {
                    $profile = $this->model_account_recurring->getProfileByRef($this->request->post['recurring_payment_id']);

                    if ($profile != false) {
                        $this->db->query("INSERT INTO `" . DB_PREFIX . "order_recurring_transaction` SET `order_recurring_id` = '" . (int) $profile['order_recurring_id'] . "', `created` = NOW(), `amount` = '" . (float) $this->request->post['amount'] . "', `type` = '1'");

                        //as there was a payment the profile is active, ensure it is set to active (may be been suspended before)
                        if ($profile['status'] != 1) {
                            $this->db->query("UPDATE `" . DB_PREFIX . "order_recurring` SET `status` = 2 WHERE `order_recurring_id` = '" . (int) $profile['order_recurring_id'] . "'");
                        }
                    }
                }

                //suspend
                if ($this->request->post['txn_type'] == 'recurring_payment_suspended') {
                    $profile = $this->model_account_recurring->getProfileByRef($this->request->post['recurring_payment_id']);

                    if ($profile != false) {
                        $this->db->query("INSERT INTO `" . DB_PREFIX . "order_recurring_transaction` SET `order_recurring_id` = '" . (int) $profile['order_recurring_id'] . "', `created` = NOW(), `type` = '6'");
                        $this->db->query("UPDATE `" . DB_PREFIX . "order_recurring` SET `status` = 3 WHERE `order_recurring_id` = '" . (int) $profile['order_recurring_id'] . "' LIMIT 1");
                    }
                }

                //suspend due to max failed
                if ($this->request->post['txn_type'] == 'recurring_payment_suspended_due_to_max_failed_payment') {
                    $profile = $this->model_account_recurring->getProfileByRef($this->request->post['recurring_payment_id']);

                    if ($profile != false) {
                        $this->db->query("INSERT INTO `" . DB_PREFIX . "order_recurring_transaction` SET `order_recurring_id` = '" . (int) $profile['order_recurring_id'] . "', `created` = NOW(), `type` = '7'");
                        $this->db->query("UPDATE `" . DB_PREFIX . "order_recurring` SET `status` = 3 WHERE `order_recurring_id` = '" . (int) $profile['order_recurring_id'] . "' LIMIT 1");
                    }
                }

                //payment failed
                if ($this->request->post['txn_type'] == 'recurring_payment_failed') {
                    $profile = $this->model_account_recurring->getProfileByRef($this->request->post['recurring_payment_id']);

                    if ($profile != false) {
                        $this->db->query("INSERT INTO `" . DB_PREFIX . "order_recurring_transaction` SET `order_recurring_id` = '" . (int) $profile['order_recurring_id'] . "', `created` = NOW(), `type` = '4'");
                    }
                }

                //outstanding payment failed
                if ($this->request->post['txn_type'] == 'recurring_payment_outstanding_payment_failed') {
                    $profile = $this->model_account_recurring->getProfileByRef($this->request->post['recurring_payment_id']);

                    if ($profile != false) {
                        $this->db->query("INSERT INTO `" . DB_PREFIX . "order_recurring_transaction` SET `order_recurring_id` = '" . (int) $profile['order_recurring_id'] . "', `created` = NOW(), `type` = '8'");
                    }
                }

                //outstanding payment
                if ($this->request->post['txn_type'] == 'recurring_payment_outstanding_payment') {
                    $profile = $this->model_account_recurring->getProfileByRef($this->request->post['recurring_payment_id']);

                    if ($profile != false) {
                        $this->db->query("INSERT INTO `" . DB_PREFIX . "order_recurring_transaction` SET `order_recurring_id` = '" . (int) $profile['order_recurring_id'] . "', `created` = NOW(), `amount` = '" . (float) $this->request->post['amount'] . "', `type` = '2'");

                        //as there was a payment the profile is active, ensure it is set to active (may be been suspended before)
                        if ($profile['status'] != 1) {
                            $this->db->query("UPDATE `" . DB_PREFIX . "order_recurring` SET `status` = 2 WHERE `order_recurring_id` = '" . (int) $profile['order_recurring_id'] . "'");
                        }
                    }
                }

                //created
                if ($this->request->post['txn_type'] == 'recurring_payment_profile_created') {
                    $profile = $this->model_account_recurring->getProfileByRef($this->request->post['recurring_payment_id']);

                    if ($profile != false) {
                        $this->db->query("INSERT INTO `" . DB_PREFIX . "order_recurring_transaction` SET `order_recurring_id` = '" . (int) $profile['order_recurring_id'] . "', `created` = NOW(), `type` = '0'");

                        if ($profile['status'] != 1) {
                            $this->db->query("UPDATE `" . DB_PREFIX . "order_recurring` SET `status` = 2 WHERE `order_recurring_id` = '" . (int) $profile['order_recurring_id'] . "'");
                        }
                    }
                }

                //cancelled
                if ($this->request->post['txn_type'] == 'recurring_payment_profile_cancel') {
                    $profile = $this->model_account_recurring->getProfileByRef($this->request->post['recurring_payment_id']);

                    if ($profile != false && $profile['status'] != 3) {
                        $this->db->query("INSERT INTO `" . DB_PREFIX . "order_recurring_transaction` SET `order_recurring_id` = '" . (int) $profile['order_recurring_id'] . "', `created` = NOW(), `type` = '5'");
                        $this->db->query("UPDATE `" . DB_PREFIX . "order_recurring` SET `status` = 4 WHERE `order_recurring_id` = '" . (int) $profile['order_recurring_id'] . "' LIMIT 1");
                    }
                }

                //skipped
                if ($this->request->post['txn_type'] == 'recurring_payment_skipped') {
                    $profile = $this->model_account_recurring->getProfileByRef($this->request->post['recurring_payment_id']);

                    if ($profile != false) {
                        $this->db->query("INSERT INTO `" . DB_PREFIX . "order_recurring_transaction` SET `order_recurring_id` = '" . (int) $profile['order_recurring_id'] . "', `created` = NOW(), `type` = '3'");
                    }
                }

                //expired
                if ($this->request->post['txn_type'] == 'recurring_payment_expired') {
                    $profile = $this->model_account_recurring->getProfileByRef($this->request->post['recurring_payment_id']);

                    if ($profile != false) {
                        $this->db->query("INSERT INTO `" . DB_PREFIX . "order_recurring_transaction` SET `order_recurring_id` = '" . (int) $profile['order_recurring_id'] . "', `created` = NOW(), `type` = '9'");
                        $this->db->query("UPDATE `" . DB_PREFIX . "order_recurring` SET `status` = 5 WHERE `order_recurring_id` = '" . (int) $profile['order_recurring_id'] . "' LIMIT 1");
                    }
                }
            }
        }elseif( (string)$response == "INVALID" ) {
            $this->model_payment_pp_express->log(array('IPN was invalid'), 'IPN fail');
        } else {
            $this->log->write('string unknown ');
        }

        header("HTTP/1.1 200 Ok");
    }

    public function shipping() {
        $this->shippingValidate($this->request->post['shipping_method']);

        $this->redirect($this->url->link('payment/pp_express/expressConfirm'));
    }

    protected function shippingValidate($code) {
        $this->language->load('checkout/cart');
        $this->language->load('payment/pp_express');

        if (empty($code)) {
            $this->session->data['error_warning'] = $this->language->get('error_shipping');
            return false;
        } else {
            $shipping = explode('.', $code);

            if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {
                $this->session->data['error_warning'] = $this->language->get('error_shipping');
                return false;
            } else {
                $this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];
                $this->session->data['success'] = $this->language->get('text_shipping_updated');
                return true;
            }
        }
    }

    public function recurringCancel() {
        //cancel an active profile

        $this->load->model('account/recurring');
        $this->load->model('payment/pp_express');
        $this->language->load('account/recurring');

        $profile = $this->model_account_recurring->getProfile($this->request->get['recurring_id']);

        if ($profile && !empty($profile['profile_reference'])) {

            $result = $this->model_payment_pp_express->recurringCancel($profile['profile_reference']);

            if (isset($result['PROFILEID'])) {
                $this->db->query("INSERT INTO `" . DB_PREFIX . "order_recurring_transaction` SET `order_recurring_id` = '" . (int) $profile['order_recurring_id'] . "', `created` = NOW(), `type` = '5'");
                $this->db->query("UPDATE `" . DB_PREFIX . "order_recurring` SET `status` = 4 WHERE `order_recurring_id` = '" . (int) $profile['order_recurring_id'] . "' LIMIT 1");

                $this->session->data['success'] = $this->language->get('success_cancelled');
            } else {
                $this->session->data['error'] = sprintf($this->language->get('error_not_cancelled'), $result['L_LONGMESSAGE0']);
            }
        } else {
            $this->session->data['error'] = $this->language->get('error_not_found');
        }

        $this->redirect($this->url->link('account/recurring/info', 'recurring_id=' . $this->request->get['recurring_id'], 'SSL'));
    }
    
    protected function validateCoupon() {
        $this->load->model('checkout/coupon');

        $coupon_info = $this->model_checkout_coupon->getCoupon($this->request->post['coupon']);

        $error = '';
        
        if (!$coupon_info) {
            $error = $this->language->get('error_coupon');
        }

        if (!$error) {
            return true;
        } else {
            $this->session->data['error_warning'] = $error;
            return false;
        }
    }

    protected function validateVoucher() {
        $this->load->model('checkout/voucher');

        $voucher_info = $this->model_checkout_voucher->getVoucher($this->request->post['voucher']);

        $error = '';
        
        if (!$voucher_info) {
            $error = $this->language->get('error_voucher');
        }

        if (!$error) {
            return true;
        } else {
            $this->session->data['error_warning'] = $this->language->get('error_voucher');;
            return false;
        }
    }

    protected function validateReward() {
        $points = $this->customer->getRewardPoints();

        $points_total = 0;

        foreach ($this->cart->getProducts() as $product) {
            if ($product['points']) {
                $points_total += $product['points'];
            }
        }
        
        $error = '';

        if (empty($this->request->post['reward'])) {
            $error = $this->language->get('error_reward');
        }

        if ($this->request->post['reward'] > $points) {
            $error = sprintf($this->language->get('error_points'), $this->request->post['reward']);
        }

        if ($this->request->post['reward'] > $points_total) {
            $error = sprintf($this->language->get('error_maximum'), $points_total);
        }

        if (!$error) {
            return true;
        } else {
            $this->session->data['error_warning'] = $error;
            return false;
        }
    }
}
?>