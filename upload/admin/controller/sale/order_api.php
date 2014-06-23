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
				//	'override'   => true
				);
				
				curl_setopt($curl, CURLOPT_URL, $url . 'index.php?route=api/cart/add');
				curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($product_data));

				$response = curl_exec($curl);				
				
				echo $response;
				
				if (isset($response['error'])) {
					$json['error'] = $response['error'];
					
					break;	
				}
			}
		}
		
		/*
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
			
			if (isset($response['error'])) {
				$json['error'] = $response['error'];
			}			
		}		
		
		// Vouchers
		if (isset($this->request->post['order_voucher'])) {
			foreach ($this->request->post['order_voucher'] as $order_voucher) {
			
				$response = $curl->post($url . 'index.php?route=api/voucher/add', http_build_query($order_voucher));
			
				if (isset($response['error'])) {
					$json['error']['voucher'] = $response['error'];
					break;	
				}
			}
		}
		
		// Coupon
		if ($this->request->post['coupon']) {
			$response = $curl->post($url . 'index.php?route=api/coupon',  $this->request->post);
			
			if (isset($response['error'])) {
				$json['error']['coupon'] = $response['error'];
			}			
		}
		
		// Voucher
		if ($this->request->post['voucher']) {
			$response = $curl->post($url . 'index.php?route=api/voucher',  $this->request->post);
		
			if (isset($response['error'])) {
				$json['error']['voucher'] = $response['error'];
			}				
		}
		
		// Reward Points
		if ($this->request->post['reward']) {
			$response = $curl->post($url . 'index.php?route=api/reward', $this->request->post);
		
			if (isset($response['error'])) {
				$json['error']['reward'] = $response['error'];
			}		
		}
		*/
						
		// Customer
		curl_setopt($curl, CURLOPT_URL, $url . 'index.php?route=api/customer');
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($this->request->post));
						
		$response = curl_exec($curl);
		
		echo $response;
		
		if (isset($response['error'])) {
			$json['error']['payment'] = $response['error'];
		}		
				
		// Payment Address
		curl_setopt($curl, CURLOPT_URL, $url . 'index.php?route=api/payment/address');
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($this->request->post['payment_address']));
		
		$response = curl_exec($curl);
		
		echo $response;
		
		if (isset($response['error'])) {
			$json['error']['payment'] = $response['error'];
		}
		
		// Shipping Address
		curl_setopt($curl, CURLOPT_URL, $url . 'index.php?route=api/shipping/address');
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($this->request->post['shipping_address']));
		
		$response = curl_exec($curl);
		
		echo $response;
		
		if (isset($response['error'])) {
			$json['error']['shipping_address'] = $response['error'];
		}
		
		/*
		// Shipping Methods		
		$response = $curl->post($url . 'index.php?route=api/shipping/methods');
		
		if (isset($response['error'])) {
			$json['error']['shipping_method'] = $response['error'];
		} else {
			$json['shipping_methods'] = $response;
		}
		
		// Shipping Method
		$response = $curl->post($url . 'index.php?route=api/shipping/method');
		
		if (isset($response['error'])) {
			$json['error']['shipping_method'] = $response['error'];
		}
		
		// Payment Methods		
		$response = $curl->post($url . 'index.php?route=api/payment/methods');
		
		if (isset($response['error'])) {
			$json['error']['payment_method'] = $response['error'];
		} else {
			$json['payment_methods'] = $response;
		}
		
		// Payment Methods	
		$response = $curl->post($url . 'index.php?route=api/payment/method');
		
		if (isset($response['error'])) {
			$json['error']['payment_method'] = $response['error'];
		}	

		// Order
		if (!$json['error']) {
			$response = $curl->post($url . 'index.php?route=api/order/add');
			
			if ($response['error']) {
				$json['error']['payment_method'] = $response['error'];
			}			
		}
		
		

		$response = curl_exec($this->curl);

		if (!$response) {
			$this->log->write(curl_error($curl) . '(' . curl_errno($curl) . ')');
		} else {
			return json_decode($response);
		}		
		
		
		
			

		
		
		
	
		// Get Products
		$response = $curl->get($url . 'index.php?route=api/cart/products');
		
		// Get Order Totals
		$response = $curl->get($url . 'index.php?route=api/cart/totals');

		// Set Customer Details
		$response = $curl->post($url . 'index.php?route=api/order/add', $this->request->post);
		
		if ($response['error']) {
			$json['error'] = $response['error'];
		}	
		*/
		curl_close($curl);
		
		$this->response->setOutput(json_encode($json));		
	}
}