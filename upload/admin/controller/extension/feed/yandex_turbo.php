<?php
class ControllerExtensionFeedYandexTurbo extends Controller {
	private $error = array();
	
	private $allowed = array('RUR', 'RUB', 'USD', 'EUR', 'BYR', 'BYN', 'KZT', 'UAH');
	
	public function index() {
		$this->load->language('extension/feed/yandex_turbo');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('feed_yandex_turbo', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=feed', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=feed', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/feed/yandex_turbo', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/feed/yandex_turbo', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=feed', true);
		
		$this->load->model('localisation/currency');
		$currencies = $this->model_localisation_currency->getCurrencies();
		$allowed_currencies = array_flip($this->allowed);
		$data['currencies'] = array_intersect_key($currencies, $allowed_currencies);

		if (isset($this->request->post['feed_yandex_turbo_status'])) {
			$data['feed_yandex_turbo_status'] = $this->request->post['feed_yandex_turbo_status'];
		} else {
			$data['feed_yandex_turbo_status'] = $this->config->get('feed_yandex_turbo_status');
		}
		
		if (isset($this->request->post['feed_yandex_turbo_currency'])) {
			$data['feed_yandex_turbo_currency'] = $this->request->post['feed_yandex_turbo_currency'];
		} else {
			$data['feed_yandex_turbo_currency'] = $this->config->get('feed_yandex_turbo_currency');
		}
		
		$data['entry_currency'] = $this->language->get('entry_currency');
		$data['entry_made'] = $this->language->get('entry_made');

		$data['data_feed'] = HTTP_CATALOG . 'index.php?route=extension/feed/yandex_turbo';

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/feed/yandex_turbo', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/feed/yandex_turbo')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}