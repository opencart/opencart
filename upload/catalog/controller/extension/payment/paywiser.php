<?php
class ControllerExtensionPaymentPaywiser extends Controller {
	public function index() {
		$this->load->language('extension/payment/paywiser');

		$data['text_testmode'] = $this->language->get('text_testmode');		

		$data['button_confirm'] = $this->language->get('button_confirm');

		$data['testmode'] = $this->config->get('payment_paywiser_test');

		$data['action'] = '';

		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		if ($order_info) {
			$data['action'] = $this->url->link('extension/payment/paywiser/callback&action=order&id='.$this->session->data['order_id'], '', true);

			return $this->load->view('extension/payment/paywiser', $data);

		}
	}

	public function callback() {

		if(isset($this->session->data['order_id'])) {
			$order_id = $this->session->data['order_id'];
		} else if (isset($this->request->get['id'])) {
			$order_id = $this->request->get['id'];
		} else {
			$order_id = 0;
		}		

		if (isset($this->request->get['action'])) {
			$action = $this->request->get['action'];
		} else {
			$action = 0;
		}		

		$this->load->language('extension/payment/paywiser');

		$this->load->model('checkout/order');
	
		if($action == 'order') {

			$order_info = $this->model_checkout_order->getOrder($order_id);
	
			if ($order_info) {
	
				$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('config_order_status_id'));
	
				if ($this->config->get('payment_paywiser_test')) {
					$url = 'https://'.$this->config->get('payment_paywiser_api_key').':@gateway.paywiser.eu/PaymentGatewayTest/PayWiserPG/InitWebPayment';
				} else {
					$url = 'https://'.$this->config->get('payment_paywiser_api_key').':@gateway.paywiser.eu/PaymentGateway/PayWiserPG/InitWebPayment';
				}
		
				$data['products'] = array();
	
				$i = 1;
/*
				foreach ($this->cart->getProducts() as $product) {
	
					$data['products'][] = array(
						"Position" 		  => $i,
						'Description' 	  => html_entity_decode($product['name'], ENT_QUOTES, 'UTF-8'),
						'Quantity' 		  => $product['quantity'],
						"Unit" 			  => "Pcs",
						'Price'    		  => $this->currency->format($product['price'], $order_info['currency_code'], false, false),
						"DiscountPercent" => "0.00",
						"VATPercent" 	  => "0.00",		
						"Sum" 			  => number_format($this->currency->format($product['price'], $order_info['currency_code'], false, false)*$product['quantity'], 4),
						"Currency" 		  => $order_info['currency_code']
					);
					$i++;

				}	
*/
	
#				$total = $this->currency->format($order_info['total'] - $this->cart->getSubTotal(), $order_info['currency_code'], false, false);

				$total = number_format($order_info['total']*$order_info['currency_value'], 2);

	
				if ($total > 0) {
					$data['products'][] = array(
							"Position" 		  => $i,
							'Description' 	  => $this->language->get('text_total'),
							'Quantity' 		  => 1,
							"Unit"			  => "Pcs",
							'Price'   		  => $total,
							"DiscountPercent" => "0.00",
							"VATPercent"	  => "0.00",		
							"Sum" 			  => number_format($total, 4),
							"Currency" 		  => $order_info['currency_code']
					);	
				}

				$languages = array(
					'en-gb' => 'en',
					'sl-si' => 'sl',
					'danish' => 'da',
					'de-de' => 'de',
					'hr-hr' => 'hr',
					'it-it' => 'it',
				);
				
		
				$lang = 'en';
				if (isset($languages[strtolower($this->language->get('code'))])) {
					$lang = $languages[strtolower($this->language->get('code'))];
				}			 

				$total = str_replace(',', '', str_replace('.', '', $total));
	
		
				$postdata = array(  
								"ReferenceID" => "order-".$order_id."-".date('YmdHis'),
								"Amount" => $total,
								"Currency" => $order_info['currency_code'],
								"OrderNumber" => $order_id,
								"StatementText" => html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'),
								"StatementDescription" => html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'),
								"Items" => $data['products'],
								"WebFormLanguage" => $lang,
								"Redirect" => array(
													"SuccessURL" => $this->url->link('extension/payment/paywiser/callback&action=success&id='.$order_id, '', true),
													"FailURL" => $this->url->link('extension/payment/paywiser/callback&action=fail&id='.$order_id, '', true),
													"Type" => "1"
													)
								);

				$postdata = json_encode($postdata);
	
				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_HEADER, false);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/plain'));
				curl_setopt($ch, CURLOPT_TIMEOUT, 60);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	
				$content = curl_exec($ch);
				curl_close($ch);
	
	
				if($content = json_decode($content)) {
					$paywiser_url = $content->PaymentURL;
					$status = $content->StatusCode;
									
					if($status == 0) {
						header('Location: '.$paywiser_url);
						exit;
					} else {

						$order_status_id = $this->config->get('payment_paywiser_failed_status_id');
						$this->model_checkout_order->addOrderHistory($order_id, $order_status_id);
						
						header('Location: '.$this->url->link('checkout/checkout', '', true));
						exit;

					}
									
				} 
			
			}

		} else if($action == 'success') {

			$order_status_id = $this->config->get('payment_paywiser_completed_status_id');
			$this->model_checkout_order->addOrderHistory($order_id, $order_status_id);
			
			header('Location: '.$this->url->link('checkout/success'));
			exit;

		} else {

			$order_status_id = $this->config->get('payment_paywiser_failed_status_id');
			$this->model_checkout_order->addOrderHistory($order_id, $order_status_id);
			
			header('Location: '.$this->url->link('checkout/checkout', '', true));
			exit;
		
		}

	}
}
?>
