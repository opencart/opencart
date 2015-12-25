<?php
class ControllerInstallStep4 extends Controller {
	public function index() {
		$this->language->load('install/step_4');
		
		$this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_step_4'] = $this->language->get('text_step_4');
		$data['text_congratulation'] = $this->language->get('text_congratulation');
		
		$data['text_catalog'] = $this->language->get('text_catalog');
		$data['text_admin'] = $this->language->get('text_admin');
		
		$data['text_loading'] = $this->language->get('text_loading');
		$data['text_store'] = $this->language->get('text_store');
		
		$data['text_mail_list'] = $this->language->get('text_mail_list');
		$data['text_mail_list_small'] = $this->language->get('text_mail_list_small');
		
		$data['text_openbay'] = $this->language->get('text_openbay');
		$data['text_maxmind'] = $this->language->get('text_maxmind');
		$data['text_more_info'] = $this->language->get('text_more_info');
		
		$data['text_facebook'] = $this->language->get('text_facebook');
		$data['text_facebook_info'] = $this->language->get('text_facebook_info');
		$data['text_facebook_link'] = $this->language->get('text_facebook_link');
		
		$data['text_forum'] = $this->language->get('text_forum');
		$data['text_forum_info'] = $this->language->get('text_forum_info');
		$data['text_forum_link'] = $this->language->get('text_forum_link');
		
		$data['text_commercial'] = $this->language->get('text_commercial');
		$data['text_commercial_info'] = $this->language->get('text_commercial_info');
		$data['text_commercial_link'] = $this->language->get('text_commercial_link');
		
		$data['text_view'] = $this->language->get('text_view');
		$data['text_download'] = $this->language->get('text_download');
		$data['text_downloads'] = $this->language->get('text_downloads');
		$data['text_price'] = $this->language->get('text_price');
		$data['text_view'] = $this->language->get('text_view');

		$data['button_join'] = $this->language->get('button_join');
		$data['button_setup'] = $this->language->get('button_setup');

		$data['error_warning'] = $this->language->get('error_warning');

		$data['maxmind'] = $this->url->link('install/maxmind');
		$data['openbay'] = $this->url->link('install/openbay');

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$languages = array();

		if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
			preg_match_all('/([a-z]{1,8}(-[a-z]{1,8})?)\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?/i', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $language_parse);

			if (count($language_parse[1])) {
				$languages = array_combine($language_parse[1], $language_parse[4]);

				foreach ($languages as $lang => $val) {
					if ($val === '') {
						$languages[$lang] = 1;
					}
				}

				arsort($languages, SORT_NUMERIC);
			}
		}

		if (!empty($languages)) {
			reset($languages);
			
			$data['language'] = key($languages);
		} else {
			$data['language'] = '';
		}

		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		$data['step'] = $this->load->controller('common/step');

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

	public function language() {
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_URL, 'http://www.opencart.com/index.php?route=extension/json/languages');
		curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1');
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_FORBID_REUSE, true);
		curl_setopt($curl, CURLOPT_TIMEOUT, 30);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_POSTFIELDS, array('language' => $this->request->post['language']));

		$response = curl_exec($curl);
		
		curl_close($curl);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput($response);
	}
}