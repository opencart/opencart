<?php
class ControllerSaleOrder extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('sale/order');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/order');

		$this->getList();
	}

	public function insert() {
		$this->load->language('sale/order');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/order');

    	$this->getForm();
  	}

	public function update() {
		$this->load->language('sale/order');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/order');

    	$this->getForm();
  	}

  	public function delete() {
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateDelete()) {
			$this->model_sale_order->editOrder($this->request->get['order_id']);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_order_id'])) {
				$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
			}

			if (isset($this->request->get['filter_customer'])) {
				$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_order_status_id'])) {
				$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
			}

			if (isset($this->request->get['filter_total'])) {
				$url .= '&filter_total=' . $this->request->get['filter_total'];
			}

			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}

			if (isset($this->request->get['filter_date_modified'])) {
				$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('sale/order', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}
	
	
	public function paymentMethods() {

	}
	
	public function shippingMethods() {
		
	}
	
	public function refresh() {
		$this->load->language('sale/order');

		$json = array();
		
		$store_info = $this->model_setting_store->getStore($this->request->post['store_id']);
		
		if ($store_info) {
			$url = $store_info['url'];
		} else {
			$url = HTTP_CATALOG;
		}
		
		$this->load->library('curl');
		
		$curl = new Curl();
				
		// Add tocart
		if (isset($this->request->post['order_product'])) {
			foreach ($this->request->post['order_product'] as $order_product) {
				$response = $curl->post($url . 'index.php?route=api/cart/add', $order_product);
			
				if ($response['error']) {
					$json['error']['coupon'] = $response['error'];
					
					break;	
				}
			}
		}
		
		// Voucher
		if (isset($this->request->post['order_voucher'])) {
			foreach ($this->request->post['order_voucher'] as $order_voucher) {
				$response = $curl->post($url . 'index.php?route=api/voucher/add', $order_voucher);
			
				if ($response['error']) {
					$json['error']['coupon'] = $response['error'];
					break;	
				}
			}
		}
		
		// Coupon
		if ($this->request->post['coupon']) {
			$response = $curl->post($url . 'index.php?route=api/coupon',  $this->request->post);
			
			if ($response['error']) {
				$json['error']['coupon'] = $response['error'];
			}			
		}
		
		// Voucher
		if ($this->request->post['voucher']) {
			$response = $curl->post($url . 'index.php?route=api/voucher',  $this->request->post);
		
			if ($response['error']) {
				$json['error']['voucher'] = $response['error'];
			}				
		}
		
		// Reward Points
		if ($this->request->post['reward']) {
			$response = $curl->post($url . 'index.php?route=api/reward', $this->request->post);
		
			if ($response['error']) {
				$json['error']['reward'] = $response['error'];
			}		
		}	
				
		// Set Payment Address
		$payment_address = array(
			'address_id'   => $this->request->post['payment_address_id'],
			'firstname'    => $this->request->post['payment_firstname'],
			'lastname'     => $this->request->post['payment_lastname'],
			'company'      => $this->request->post['payment_company'],
			'address_1'    => $this->request->post['payment_address_1'],
			'address_2'    => $this->request->post['payment_address_2'],
			'postcode'     => $this->request->post['payment_postcode'],
			'city'         => $this->request->post['payment_city'],
			'zone_id'      => $this->request->post['payment_zone_id'],
			'country_id'   => $this->request->post['payment_country_id'],
			'custom_field' => $this->request->post['payment_custom_field']
		);
		
		$response = $curl->post($url . 'index.php?route=api/order/setpaymentaddress', $payment_address);
		
		if ($response['error']) {
			$json['error']['payment'] = $response['error'];
		}
		
		// Shipping Address
		$shipping_address = array(
			'address_id'   => $this->request->post['shipping_address_id'],
			'firstname'    => $this->request->post['shipping_firstname'],
			'lastname'     => $this->request->post['shipping_lastname'],
			'company'      => $this->request->post['shipping_company'],
			'address_1'    => $this->request->post['shipping_address_1'],
			'address_2'    => $this->request->post['shipping_address_2'],
			'postcode'     => $this->request->post['shipping_postcode'],
			'city'         => $this->request->post['shipping_city'],
			'zone_id'      => $this->request->post['shipping_zone_id'],
			'country_id'   => $this->request->post['shipping_country_id'],
			'custom_field' => $this->request->post['shipping_custom_field']
		);		
		
		$response = $curl->post($url . 'index.php?route=api/order/setshippingaddress', $payment_address);
		
		if ($response['error']) {
			$json['error']['payment'] = $response['error'];
		}
		
		// Get Shipping Methods		
		$response = $curl->post($url . 'index.php?route=api/order/getshippingmethods');
		
		if ($response['error']) {
			$json['error']['shipping'] = $response['error'];
		} else {
			$json['shipping_methods'] = $response;
		}
		
		// Set Shipping Method
		$response = $curl->post($url . 'index.php?route=api/order/setshippingmethod');
		
		if ($response['error']) {
			$json['error']['payment'] = $response['error'];
		}
		
		// Get Payment Methods		
		$response = $curl->post($url . 'index.php?route=api/order/getpaymentmethods');
		
		if ($response['error']) {
			$json['error']['payment'] = $response['error'];
		}
		
		// Set Shipping Methods	
		$response = $curl->post($url . 'index.php?route=api/order/setpaymentmethod');
		
		if ($response['error']) {
			$json['error']['shipping'] = $response['error'];
		}
		
		// Get Products
		$response = $curl->get($url . 'index.php?route=api/order/getProducts');
		
		// Get Order Totals
		$response = $curl->get($url . 'index.php?route=api/order/getTotals');

		// Set Customer Details
		$response = $curl->post($url . 'index.php?route=api/order/add', $this->request->post);
		
		if ($response['error']) {
			$json['error'] = $response['error'];
		}	
		
		$curl->close();
		
		$this->response->setOutput(json_encode($json));			
	}
	
	public function addOrder() {
		
	}
	
	public function editOrder() {
		
	}
	
	private function api($url, $data) {
		
		
		// Make curl request
		$curl = curl_init($url);
				
		
		curl_setopt($curl, CURLOPT_PORT, 443);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_FORBID_REUSE, 0);
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

		$response = curl_exec($curl);

		curl_close($curl);

		if (!$response) {
			$this->log->write(curl_error($curl) . '(' . curl_errno($curl) . ')');
		}	
	}	
}