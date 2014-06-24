<?php
class ControllerSaleOrderApi extends Controller {	
	public function refresh() {
		$this->load->language('sale/order');

		$json = array();
		
		$this->load->model('setting/store');
		
		$store_info = $this->model_setting_store->getStore($this->request->post['store_id']);
		
		if ($store_info) {
			$url = $store_info['url'];
		} else {
			$url = HTTP_CATALOG;
		}
		
		$curl = curl_init();
		
		curl_setopt($curl, CURLOPT_HEADER, 0);
		//curl_setopt($curl, CURLOPT_PORT, 443);
		curl_setopt($curl, CURLOPT_FORBID_REUSE, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_TIMEOUT, 60);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		
		// Customer
		$customer_data = array(
			'customer_id'       => $this->request->post['customer_id'],
			'customer_group_id' => $this->request->post['customer_group_id'], 
			'firstname'         => $this->request->post['firstname'],
			'lastname'          => $this->request->post['lastname'],
			'email'             => $this->request->post['email'],
			'telephone'         => $this->request->post['telephone'],
			'fax'               => $this->request->post['fax'],
			'custom_field'      => isset($this->request->post['custom_field']) ? $this->request->post['custom_field'] : array(),
		);
		
		curl_setopt($curl, CURLOPT_URL, $url . 'index.php?route=api/customer');
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($customer_data));
						
		$response = curl_exec($curl);
		
		echo 'Customer<br />';
		echo $response . '<br />';
		
		if (isset($response['error'])) {
			$json['error']['payment'] = $response['error'];
		}		
				
		// Payment Address
		$payment_address = array(
			'firstname'    => $this->request->post['payment_firstname'],
			'lastname'     => $this->request->post['payment_lastname'],
			'company'      => $this->request->post['payment_company'],
			'address_1'    => $this->request->post['payment_address_1'],
			'address_2'    => $this->request->post['payment_address_2'],
			'postcode'     => $this->request->post['payment_postcode'],
			'city'         => $this->request->post['payment_city'],
			'zone_id'      => $this->request->post['payment_zone_id'],
			'country_id'   => $this->request->post['payment_country_id'],
			'custom_field' => isset($this->request->post['payment_custom_field']) ? $this->request->post['payment_custom_field'] : array()
		);
				
		curl_setopt($curl, CURLOPT_URL, $url . 'index.php?route=api/payment/address');
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($payment_address));
		
		$response = curl_exec($curl);

		echo 'Payment Address<br />';
		echo $response . '<br />';
				
		if (isset($response['error'])) {
			$json['error']['payment'] = $response['error'];
		}
		
		// Shipping Address
		$shipping_address = array(
			'firstname'    => $this->request->post['shipping_firstname'],
			'lastname'     => $this->request->post['shipping_lastname'],
			'company'      => $this->request->post['shipping_company'],
			'address_1'    => $this->request->post['shipping_address_1'],
			'address_2'    => $this->request->post['shipping_address_2'],
			'postcode'     => $this->request->post['shipping_postcode'],
			'city'         => $this->request->post['shipping_city'],
			'zone_id'      => $this->request->post['shipping_zone_id'],
			'country_id'   => $this->request->post['shipping_country_id'],
			'custom_field' => isset($this->request->post['shipping_custom_field']) ? $this->request->post['shipping_custom_field'] : array()
		);		
		
		curl_setopt($curl, CURLOPT_URL, $url . 'index.php?route=api/shipping/address');
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($shipping_address));
		
		$response = curl_exec($curl);
		
		echo 'Shipping Address<br />';
		echo $response . '<br />';
		
		if (isset($response['error'])) {
			$json['error']['shipping_address'] = $response['error'];
		}		
		
		// Products
		if (isset($this->request->post['order_product'])) {
			foreach ($this->request->post['order_product'] as $order_product) {
				if (isset($order_product['order_option'])) {
					$option = $order_product['order_option'];
				} else {
					$option = array();
				}
							
				$product_data = array(
					'product_id' => $order_product['product_id'],
					'option'     => $option,
					'quantity'   => $order_product['quantity'],
					'override'   => true
				);
				
				curl_setopt($curl, CURLOPT_URL, $url . 'index.php?route=api/cart/add');
				curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($product_data));

				$response = curl_exec($curl);				
				
				echo 'Products<br />';
				echo $response . '<br />';
				
				if (isset($response['error'])) {
					$json['error'] = $response['error'];
					
					break;	
				}
			}
		}

		// Add to cart
		if (isset($this->request->post['product_id'])) {
			if (isset($this->request->post['quantity'])) {
				$quantity = $this->request->post['quantity'];
			} else {
				$quantity = 1;
			}
						
			if (isset($this->request->post['option'])) {
				$option = $this->request->post['option'];
			} else {
				$option = array();
			}
			
			$product_data = array(
				'product_id' => $this->request->post['product_id'],
				'option'     => $option,
				'quantity'   => $quantity
			);
			
			curl_setopt($curl, CURLOPT_URL, $url . 'index.php?route=api/cart/add');
			curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($product_data));			
			
			$response = curl_exec($curl);				
		
			echo 'Add to cart<br />';
			echo $response . '<br />';
					
			if (isset($response['error'])) {
				$json['error'] = $response['error'];
			}			
		}		
		
		// Vouchers
		if (isset($this->request->post['order_voucher'])) {
			foreach ($this->request->post['order_voucher'] as $order_voucher) {
				curl_setopt($curl, CURLOPT_URL, $url . 'index.php?route=api/voucher/add');
				curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($order_voucher));
				
				$response = curl_exec($curl);		
		
				echo 'Vouchers<br />';
				echo $response . '<br />';
					
				if (isset($response['error'])) {
					$json['error']['voucher'] = $response['error'];
					
					break;	
				}
			}
		}
		
		// Add to cart
		if (isset($this->request->post['to_name']) || isset($this->request->post['to_email']) || isset($this->request->post['from_name']) || isset($this->request->post['from_email'])) {
			$voucher_data = array(
				'to_name'          => $this->request->post['to_name'],
				'to_email'         => $this->request->post['to_email'],
				'from_name'        => $this->request->post['from_name'],
				'from_email'       => $this->request->post['from_email'],
				'voucher_theme_id' => $this->request->post['voucher_theme_id'],
				'message'          => $this->request->post['message'],
				'amount'           => $this->request->post['amount']
			);
			
			curl_setopt($curl, CURLOPT_URL, $url . 'index.php?route=api/voucher/add');
			curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($voucher_data));
			
			$response = curl_exec($curl);		
			
			echo 'Add to cart<br />';
			echo $response . '<br />';
					
			if (isset($response['error'])) {
				$json['error']['voucher'] = $response['error'];
			}			
		}
		
		// Coupon
		if ($this->request->post['coupon']) {
			curl_setopt($curl, CURLOPT_URL, $url . 'index.php?route=api/coupon');
			curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array('coupon' => $this->request->post['coupon'])));		
			
			$response = curl_exec($curl);	
			
			echo 'Coupon<br />';
			echo $response . '<br />';
						
			if (isset($response['error'])) {
				$json['error']['coupon'] = $response['error'];
			}			
		}
		
		// Voucher
		if ($this->request->post['voucher']) {
			curl_setopt($curl, CURLOPT_URL, $url . 'index.php?route=api/voucher');
			curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array('voucher' => $this->request->post['voucher'])));		
			
			$response = curl_exec($curl);
		
			echo 'Voucher<br />';
			echo $response . '<br />';
					
			if (isset($response['error'])) {
				$json['error']['voucher'] = $response['error'];
			}				
		}
		
		// Reward Points
		if ($this->request->post['reward']) {
			curl_setopt($curl, CURLOPT_URL, $url . 'index.php?route=api/reward');
			curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array('reward' => $this->request->post['reward'])));		

			$response = curl_exec($curl);
			
			echo 'Reward Points<br />';
			echo $response . '<br />';
					
			if (isset($response['error'])) {
				$json['error']['reward'] = $response['error'];
			}
		}
						
		// Shipping Methods	
		curl_setopt($curl, CURLOPT_URL, $url . 'index.php?route=api/shipping/methods');
		curl_setopt($curl, CURLOPT_POSTFIELDS, '');
			
		$response = curl_exec($curl);
			
		echo 'Shipping Methods<br />';
		echo $response . '<br />';
					
		if (isset($response['error'])) {
			$json['error']['shipping_method'] = $response['error'];
		} else {
			$json['shipping_methods'] = $response;
		}
		
		// Shipping Method
		curl_setopt($curl, CURLOPT_URL, $url . 'index.php?route=api/shipping/method');
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array('shipping_method' => $this->request->post['shipping_code'])));		
		
		$response = curl_exec($curl);
			
		echo 'Shipping Method<br />';
		echo $response . '<br />';
					
		if (isset($response['error'])) {
			$json['error']['shipping_method'] = $response['error'];
		}
		
		// Payment Methods		
		curl_setopt($curl, CURLOPT_URL, $url . 'index.php?route=api/payment/methods');
		curl_setopt($curl, CURLOPT_POSTFIELDS, '');		
		
		$response = curl_exec($curl);	
		
		echo 'Payment Methods<br />';
		echo $response . '<br />';
					
		if (isset($response['error'])) {
			$json['error']['payment_method'] = $response['error'];
		} else {
			$json['payment_methods'] = $response;
		}
		
		// Payment Method
		curl_setopt($curl, CURLOPT_URL, $url . 'index.php?route=api/payment/method');
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array('payment_method' => $this->request->post['payment_code'])));		
				
		$response = curl_exec($curl);
			
		echo 'Payment Method<br />';
		echo $response . '<br />';
					
		if (isset($response['error'])) {
			$json['error']['payment_method'] = $response['error'];
		}	

		// Order
		//if (!$json['error']) {
			//$response = $curl->post($url . 'index.php?route=api/order/add');
						
			//if ($response['error']) {
			//	$json['error']['payment_method'] = $response['error'];
			//}			
		//}
		
		//$response = curl_exec($curl);
		/*
		if (!$response) {
			$this->log->write(curl_error($curl) . '(' . curl_errno($curl) . ')');
		} else {
			return json_decode($response);
		}		
		
	
		// Get Products
		$response = $curl->get($url . 'index.php?route=api/cart/products');
		
		// Get Order Totals
		$response = $curl->get($url . 'index.php?route=api/cart/totals');

		
		if ($response['error']) {
			$json['error'] = $response['error'];
		}	
		*/
		
		curl_close($curl);
		
		$this->response->setOutput(json_encode($json));		
	}
}