<?php
class ControllerInstallStep4 extends Controller {
	public function index() {
		$this->language->load('install/step_4');
		
		$this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_step_4'] = $this->language->get('text_step_4');		
		$data['text_catalog'] = $this->language->get('text_catalog');
		$data['text_admin'] = $this->language->get('text_admin');
		$data['text_loading'] = $this->language->get('text_loading');
		$data['text_extension'] = $this->language->get('text_extension');
		$data['text_mail'] = $this->language->get('text_mail');
		$data['text_mail_description'] = $this->language->get('text_mail_description');
		$data['text_openbay'] = $this->language->get('text_openbay');
		$data['text_maxmind'] = $this->language->get('text_maxmind');
		$data['text_facebook'] = $this->language->get('text_facebook');
		$data['text_facebook_description'] = $this->language->get('text_facebook_description');
		$data['text_facebook_visit'] = $this->language->get('text_facebook_visit');
		$data['text_forum'] = $this->language->get('text_forum');
		$data['text_forum_description'] = $this->language->get('text_forum_description');
		$data['text_forum_visit'] = $this->language->get('text_forum_visit');
		$data['text_commercial'] = $this->language->get('text_commercial');
		$data['text_commercial_description'] = $this->language->get('text_commercial_description');
		$data['text_commercial_visit'] = $this->language->get('text_commercial_visit');
		$data['text_view'] = $this->language->get('text_view');
		$data['text_download'] = $this->language->get('text_download');
		$data['text_downloads'] = $this->language->get('text_downloads');
		$data['text_price'] = $this->language->get('text_price');
		$data['text_view'] = $this->language->get('text_view');

		$data['button_mail'] = $this->language->get('button_mail');
		$data['button_setup'] = $this->language->get('button_setup');

		$data['error_warning'] = $this->language->get('error_warning');

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['maxmind'] = $this->url->link('3rd-party/maxmind');
		$data['openbay'] = $this->url->link('3rd-party/openbay');

		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('install/step_4', $data));
	}

	public function extension() {
		$curl = curl_init();
		
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_URL, 'http://www.opencart.com/index.php?route=extension/json/extensions');
		curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1');
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_FORBID_REUSE, true);
		curl_setopt($curl, CURLOPT_TIMEOUT, 30);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_POSTFIELDS, array());
		
		$response = curl_exec($curl);
		
		curl_close($curl);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput($response);
	}
}