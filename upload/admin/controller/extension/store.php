<?php
class ControllerExtensionStore extends Controller {
	public function index() {
		$this->load->language('extension/extension');

		$json = array();
				
		$url = '';
		
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
		
		$curl = curl_init('http://www.opencart.com' . $url);
				
		curl_setopt($curl, CURLOPT_PORT, 443);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
		curl_setopt($curl, CURLOPT_POST, 1);
		//curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
				
		$response = curl_exec($curl);

		curl_close($curl);

		if (!$response) {
			$json['error'] = curl_error($curl) . '(' . curl_errno($curl) . ')';
		}
		
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));		
	}
	
	public function info() {
		$this->load->language('extension/extension');

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
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));		
	}		
}
