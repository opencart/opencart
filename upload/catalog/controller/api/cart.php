<?php
class ControllerApiCart extends Controller {
	public function add() {
		$this->load->language('api/order');
		
		$json = array();
			

			// Settings
			$this->load->model('setting/setting');
			
			$settings = $this->model_setting_setting->getSetting('config', $this->request->post['store_id']);
			
			foreach ($settings as $key => $value) {
				$this->config->set($key, $value);
			}
			
    		// Customer
			if ($this->request->post['customer_id']) {
				$this->load->model('account/customer');

				$customer_info = $this->model_account_customer->getCustomer($this->request->post['customer_id']);

				if ($customer_info) {
					$this->customer->login($customer_info['email'], '', true);
					$this->cart->clear();
				} else {
					$json['error']['warning'] = $this->language->get('error_customer');
				}
			} else {
				// Customer Group
				$this->config->set('config_customer_group_id', $this->request->post['customer_group_id']);
			}

			// Products
			$this->load->model('catalog/product');
			
			if (isset($this->request->post['order_product'])) {
				foreach ($this->request->post['order_product'] as $order_product) {
					$product_info = $this->model_catalog_product->getProduct($order_product['product_id']);
				
					if ($product_info) {	
						$option_data = array();
						
						if (isset($order_product['order_option'])) {
							foreach ($order_product['order_option'] as $option) {
								if ($option['type'] == 'select' || $option['type'] == 'radio' || $option['type'] == 'image') { 
									$option_data[$option['product_option_id']] = $option['product_option_value_id'];
								} elseif ($option['type'] == 'checkbox') {
									$option_data[$option['product_option_id']][] = $option['product_option_value_id'];
								} elseif ($option['type'] == 'text' || $option['type'] == 'textarea' || $option['type'] == 'file' || $option['type'] == 'date' || $option['type'] == 'datetime' || $option['type'] == 'time') {
									$option_data[$option['product_option_id']] = $option['value'];						
								}
							}
						}
															
						$this->cart->add($order_product['product_id'], $order_product['quantity'], $option_data);
					}
				}
			}
			
			// Add new product
			if (isset($this->request->post['product_id'])) {
				$product_info = $this->model_catalog_product->getProduct($this->request->post['product_id']);

				if ($product_info) {
					if (isset($this->request->post['quantity'])) {
						$quantity = $this->request->post['quantity'];
					} else {
						$quantity = 1;
					}
																
					if (isset($this->request->post['option'])) {
						$option = array_filter($this->request->post['option']);
					} else {
						$option = array();	
					}
					
					$product_options = $this->model_catalog_product->getProductOptions($this->request->post['product_id']);
					
					foreach ($product_options as $product_option) {
						if ($product_option['required'] && empty($option[$product_option['product_option_id']])) {
							$json['error']['product']['option'][$product_option['product_option_id']] = sprintf($this->language->get('error_required'), $product_option['name']);
						}
					}
					
					if (!isset($json['error']['product']['option'])) {
						$this->cart->add($this->request->post['product_id'], $quantity, $option);
					}
				} else {
					$json['error']['product']['store'] = $this->language->get('error_store');
				}
			}
			
			// Stock
			if (!$this->cart->hasStock() && (!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning'))) {
				$json['error']['product']['stock'] = $this->language->get('error_stock');
			}
			
			// Tax
			if ($this->cart->hasShipping()) {
				$this->tax->setShippingAddress($this->request->post['shipping_country_id'], $this->request->post['shipping_zone_id']);
			} else {
				$this->tax->setShippingAddress($this->config->get('config_country_id'), $this->config->get('config_zone_id'));
			}
			
			$this->tax->setPaymentAddress($this->request->post['payment_country_id'], $this->request->post['payment_zone_id']);				
			$this->tax->setStoreAddress($this->config->get('config_country_id'), $this->config->get('config_zone_id'));	
						
			// Products
			$json['order_product'] = array();
			
			$products = $this->cart->getProducts();
			
			foreach ($products as $product) {
				$product_total = 0;
					
				foreach ($products as $product_2) {
					if ($product_2['product_id'] == $product['product_id']) {
						$product_total += $product_2['quantity'];
					}
				}	
								
				if ($product['minimum'] > $product_total) {
					$json['error']['product']['minimum'][] = sprintf($this->language->get('error_minimum'), $product['name'], $product['minimum']);
				}	
								
				$option_data = array();

				foreach ($product['option'] as $option) {
					$option_data[] = array(
						'product_option_id'       => $option['product_option_id'],
						'product_option_value_id' => $option['product_option_value_id'],
						'name'                    => $option['name'],
						'value'                   => $option['value'],
						'type'                    => $option['type']
					);
				}
		
				$download_data = array();
				
				foreach ($product['download'] as $download) {
					$download_data[] = array(
						'name'     => $download['name'],
						'filename' => $download['filename'],
						'mask'     => $download['mask']
					);
				}
								
				$json['order_product'][] = array(
					'product_id' => $product['product_id'],
					'name'       => $product['name'],
					'model'      => $product['model'], 
					'option'     => $option_data,
					'download'   => $download_data,
					'quantity'   => $product['quantity'],
					'stock'      => $product['stock'],
					'price'      => $product['price'],	
					'total'      => $product['total'],	
					'tax'        => $this->tax->getTax($product['price'], $product['tax_class_id']),
					'reward'     => $product['reward']				
				);
			}
			
			// Voucher
			$this->session->data['vouchers'] = array();
			
			if (isset($this->request->post['order_voucher'])) {
				foreach ($this->request->post['order_voucher'] as $voucher) {
					$this->session->data['vouchers'][] = array(
						'voucher_id'       => $voucher['voucher_id'],
						'description'      => $voucher['description'],
						'code'             => substr(md5(mt_rand()), 0, 10),
						'from_name'        => $voucher['from_name'],
						'from_email'       => $voucher['from_email'],
						'to_name'          => $voucher['to_name'],
						'to_email'         => $voucher['to_email'],
						'voucher_theme_id' => $voucher['voucher_theme_id'], 
						'message'          => $voucher['message'],
						'amount'           => $voucher['amount']    
					);
				}
			}

			// Add a new voucher if set
			if (isset($this->request->post['from_name']) && isset($this->request->post['from_email']) && isset($this->request->post['to_name']) && isset($this->request->post['to_email']) && isset($this->request->post['amount'])) {
				if ((utf8_strlen($this->request->post['from_name']) < 1) || (utf8_strlen($this->request->post['from_name']) > 64)) {
					$json['error']['vouchers']['from_name'] = $this->language->get('error_from_name');
				}  
			
				if ((utf8_strlen($this->request->post['from_email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['from_email'])) {
					$json['error']['vouchers']['from_email'] = $this->language->get('error_email');
				}
			
				if ((utf8_strlen($this->request->post['to_name']) < 1) || (utf8_strlen($this->request->post['to_name']) > 64)) {
					$json['error']['vouchers']['to_name'] = $this->language->get('error_to_name');
				}       
			
				if ((utf8_strlen($this->request->post['to_email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['to_email'])) {
					$json['error']['vouchers']['to_email'] = $this->language->get('error_email');
				}
							
				if (($this->request->post['amount'] < $this->config->get('config_voucher_min')) || ($this->request->post['amount'] > $this->config->get('config_voucher_max'))) {
					$json['error']['vouchers']['amount'] = sprintf($this->language->get('error_amount'), $this->currency->format($this->config->get('config_voucher_min')), $this->currency->format($this->config->get('config_voucher_max')));
				}
				
				if (!isset($json['error']['vouchers'])) { 
					$voucher_data = array(
						'order_id'         => 0,
						'code'             => substr(md5(mt_rand()), 0, 10),
						'from_name'        => $this->request->post['from_name'],
						'from_email'       => $this->request->post['from_email'],
						'to_name'          => $this->request->post['to_name'],
						'to_email'         => $this->request->post['to_email'],
						'voucher_theme_id' => $this->request->post['voucher_theme_id'], 
						'message'          => $this->request->post['message'],
						'amount'           => $this->request->post['amount'],
						'status'           => true             
					); 
					
					$this->load->model('checkout/voucher');
					
					$voucher_id = $this->model_checkout_voucher->addVoucher(0, $voucher_data);  
									
					$this->session->data['vouchers'][] = array(
						'voucher_id'       => $voucher_id,
						'description'      => sprintf($this->language->get('text_for'), $this->currency->format($this->request->post['amount'], $this->config->get('config_currency')), $this->request->post['to_name']),
						'code'             => substr(md5(mt_rand()), 0, 10),
						'from_name'        => $this->request->post['from_name'],
						'from_email'       => $this->request->post['from_email'],
						'to_name'          => $this->request->post['to_name'],
						'to_email'         => $this->request->post['to_email'],
						'voucher_theme_id' => $this->request->post['voucher_theme_id'], 
						'message'          => $this->request->post['message'],
						'amount'           => $this->request->post['amount']            
					); 
				}
			}
			
			$json['order_voucher'] = array();
					
			foreach ($this->session->data['vouchers'] as $voucher) {
				$json['order_voucher'][] = array(
					'voucher_id'       => $voucher['voucher_id'],
					'description'      => $voucher['description'],
					'code'             => $voucher['code'],
					'from_name'        => $voucher['from_name'],
					'from_email'       => $voucher['from_email'],
					'to_name'          => $voucher['to_name'],
					'to_email'         => $voucher['to_email'],
					'voucher_theme_id' => $voucher['voucher_theme_id'], 
					'message'          => $voucher['message'],
					'amount'           => $voucher['amount']    
				);
			}
						
			
			// Remove coupon, vouchers reward points history
			if (isset($this->request->get['order_id'])) {
				$this->load->model('account/order');
			
				$order_totals = $this->model_account_order->getOrderTotals($this->request->get['order_id']);
				
				foreach ($order_totals as $order_total) {
					$this->load->model('total/' . $order_total['code']);
					
					if (method_exists($this->{'model_total_' . $order_total['code']}, 'clear')) {
						$this->{'model_total_' . $order_total['code']}->clear($this->request->get['order_id']);
					}
				}			
			}
				
				// Order totals calculation to get the total amount for the payment gateways
				$order_total = array();					
				$total = 0;
				$taxes = $this->cart->getTaxes();
				
				$sort_order = array(); 
				
				$results = $this->model_setting_extension->getExtensions('total');
				
				foreach ($results as $key => $value) {
					$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
				}
				
				array_multisort($sort_order, SORT_ASC, $results);
				
				foreach ($results as $result) {
					if ($this->config->get($result['code'] . '_status')) {
						$this->load->model('total/' . $result['code']);
			
						$this->{'model_total_' . $result['code']}->getTotal($order_total, $total, $taxes);
					}	
				}				
				
				// Payment method
				$json['payment_method'] = array();
								
				$results = $this->model_setting_extension->getExtensions('payment');
		
				foreach ($results as $result) {
					if ($this->config->get($result['code'] . '_status')) {
						$this->load->model('payment/' . $result['code']);
						
						$method = $this->{'model_payment_' . $result['code']}->getMethod($address_data, $total); 
						
						if ($method) {
							$json['payment_method'][$result['code']] = $method;
						}
					}
				}
							 
				$sort_order = array(); 
			  
				foreach ($json['payment_method'] as $key => $value) {
					$sort_order[$key] = $value['sort_order'];
				}
		
				array_multisort($sort_order, SORT_ASC, $json['payment_method']);	
				
				if (!$json['payment_method']) {
					$json['error']['payment_method'] = $this->language->get('error_no_payment');
				} elseif ($this->request->post['payment_code']) {			
					if (!isset($json['payment_method'][$this->request->post['payment_code']])) {
						$json['error']['payment_method'] = $this->language->get('error_payment');
					}
				}
			}

			// Order total calculation to be feed back to the admin
			$json['order_total'] = array();					
			$total = 0;
			$taxes = $this->cart->getTaxes();
			
			$sort_order = array(); 
			
			$results = $this->model_setting_extension->getExtensions('total');
			
			foreach ($results as $key => $value) {
				$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
			}
			
			array_multisort($sort_order, SORT_ASC, $results);
			
			foreach ($results as $result) {
				if ($this->config->get($result['code'] . '_status')) {
					$this->load->model('total/' . $result['code']);
		
					$this->{'model_total_' . $result['code']}->getTotal($json['order_total'], $total, $taxes);
				}	
			}
			
			$sort_order = array(); 
		  
			foreach ($json['order_total'] as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}

			array_multisort($sort_order, SORT_ASC, $json['order_total']);				
			
			if (!isset($json['error'])) { 
				$json['success'] = $this->language->get('text_success');
			} else {
				$json['error']['warning'] = $this->language->get('error_warning');
			}
			
			// Add the coupon, vouchers and reward points back
			if (isset($this->request->get['order_id'])) {
				$this->load->model('account/order');
				
				$order_info = $this->model_account_order->getOrder($this->request->get['order_id']);
				
				if ($order_info) {
					$order_totals = $this->model_account_order->getOrderTotals($this->request->get['order_id']);
					
					foreach ($order_totals as $order_total) {
						$this->load->model('total/' . $order_total['code']);
						
						if (method_exists($this->{'model_total_' . $order_total['code']}, 'confirm')) {
							$this->{'model_total_' . $order_total['code']}->confirm($order_info, $order_total);
						}
					}
				}
			}
			
			
			// Reset everything			
			$this->cart->clear();
			$this->customer->logout();
			
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['coupon']);
			unset($this->session->data['reward']);
			unset($this->session->data['voucher']);
			unset($this->session->data['vouchers']);

	
		$this->response->setOutput(json_encode($json));	
	}		
	
	public function editOrder() {
		
	}
	
	public function deleteOrder() {
		
	}
	
	public function getOrder() {
		
	}
	
	public function confirm() {
		
	}



}