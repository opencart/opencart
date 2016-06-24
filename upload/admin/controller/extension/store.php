<?php
class ControllerExtensionStore extends Controller {
	public function index() {
		$this->load->language('extension/store');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'], true)
		);
		
		$data['heading_title'] = $this->language->get('heading_title');
        
		$data['text_list'] = $this->language->get('text_list');
		$data['text_all'] = $this->language->get('text_all');
		$data['text_license'] = $this->language->get('text_license');
		$data['text_free'] = $this->language->get('text_free');
		$data['text_paid'] = $this->language->get('text_paid');
		$data['text_category'] = $this->language->get('text_category');
		$data['text_theme'] = $this->language->get('text_theme');
		$data['text_payment'] = $this->language->get('text_payment');
		$data['text_shipping'] = $this->language->get('text_shipping');
		$data['text_module'] = $this->language->get('text_module');
		$data['text_total'] = $this->language->get('text_total');
		$data['text_feed'] = $this->language->get('text_feed');
		$data['text_report'] = $this->language->get('text_report');
		$data['text_other'] = $this->language->get('text_other');

		$data['token'] = $this->session->data['token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('extension/store_list', $data));
	}
	
	public function store() {
		$this->load->language('extension/store');

		$json = array();
				
		$url = '';
		/*
		$url  = '?api_key=' . $this->config->get('config_api_key'); 
		
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['search'])) {
			$url .= '&search=' . $this->request->get['search'];
		}		
		
		if (isset($this->request->get['tags'])) {
			$url .= '&tags=' . $this->request->get['tags'];
		}		
		*/
		//echo HTTP_TEST;// . $url
		$curl = curl_init(HTTP_TEST);
				
		curl_setopt($curl, CURLOPT_PORT, 443);
		//curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_PORT, 80);
		//curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
			
		$response = curl_exec($curl);
		
		echo $response;
/*
		curl_close($curl);

		if (!$response) {
			$json['error'] = curl_error($curl) . '(' . curl_errno($curl) . ')';
		} else {
			$json = json_decode($response);
		}		
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));				
	*/
	}
	
	public function info() {
		$this->load->language('extension/store');

		$json = array();
				
		$url = '';
		
		$curl = curl_init('https://extension.opencart.com');
		
		$request  = '?api_key=' . $this->config->get('config_api_key'); 
		
		if (isset($this->request->get['sort'])) {
			$request .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$request .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$request .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['search'])) {
			$request .= '&search=' . $this->request->get['search'];
		}		
		
		if (isset($this->request->get['tags'])) {
			$request .= '&tags=' . $this->request->get['tags'];
		}		
				
		curl_setopt($curl, CURLOPT_PORT, 443);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
				
		$response = curl_exec($curl);

		curl_close($curl);

		if (!$response) {
			$json['error'] = curl_error($curl) . '(' . curl_errno($curl) . ')';
		} else {
			$json = json_decode($response);
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));		
	}		
}
