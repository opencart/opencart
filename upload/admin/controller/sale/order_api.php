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
	
	public function api() {
		$this->load->model('setting/store');
		
		if ($this->request->post['store_id']) {
			
		} else {
				
		}
		
		$store_info = $this->model_setting_store->getStore($this->request->post['store_id']);
		
		if ($store_info) {
			$url = $store_info['url'];
		} else {
			$url = HTTP_CATALOG;	
		}
		
		// Make curl request
		$curl = curl_init($url . 'index.php?route=' . $this->request->get['call']);
				
				
		curl_setopt($curl, CURLOPT_PORT, 443);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_FORBID_REUSE, 0);
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $this->request->post);

		$response = curl_exec($curl);

		curl_close($curl);

		if (!$response) {
			$this->log->write(curl_error($curl) . '(' . curl_errno($curl) . ')');
		}	
	}
	
	public function paymentMethods() {
		$this->load->language('sale/order');
		
		$json = array();
		
		$store_info = $this->model_setting_store->getStore($this->request->post['store_id']);
		
		if ($store_info) {
			$url = $store_info['url'];
		} else {
			$url = HTTP_CATALOG;
		}
		
		// Add tocart
		if (isset($this->request->post['order_product'])) {
			foreach ($this->request->post['order_product'] as $product) {
				$curl->post($url . 'index.php?route=api/cart/add', $product);
			
				if () {
					
					break;	
				}
			}
		}
		
		// Coupon
		
		// Voucher
		
		// Reward Points
		
		
				
		// Payment Address
		$this->load->library('curl');
		
		$curl = new Curl();
		
		$payment_address = array(
			'address_id'   => $this->request->post['address_id'],
			'firstname'    => $this->request->post['firstname'],
			'lastname'     => $this->request->post['lastname'],
			'company'      => $this->request->post['company'],
			'address_1'    => $this->request->post['address_1'],
			'address_2'    => $this->request->post['address_2'],
			'postcode'     => $this->request->post['postcode'],
			'city'         => $this->request->post['city'],
			'zone_id'      => $this->request->post['zone_id'],
			'country_id'   => $this->request->post['country_id'],
			'custom_field' => $this->request->post['custom_field']
		);
		
		// Shipping Address
		
		$json = $curl->post($url . 'index.php?route=api/order/setpaymentaddress', $payment_address);
		
		if (isset($json['error'])) {
				
		}		
		

		
		$curl->close();
		
		$curl->get($url . 'index.php?route=api/payment/methods');
		
		$this->response->setOutput(json_encode($json));	
	}
	
	public function shippingMethods() {
		
	}
	
	public function calculate() {
		
	}
	
	public function addOrder() {
		
	}
}