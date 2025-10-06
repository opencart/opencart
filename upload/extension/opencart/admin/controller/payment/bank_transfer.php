<?php
namespace Opencart\Admin\Controller\Extension\Opencart\Payment;
/**
 * Class Bank Transfer
 *
 * @package Opencart\Admin\Controller\Extension\Opencart\Payment
 */
class BankTransfer extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('extension/opencart/payment/bank_transfer');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment')
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/opencart/payment/bank_transfer', 'user_token=' . $this->session->data['user_token'])
		];

		$data['save'] = $this->url->link('extension/opencart/payment/bank_transfer.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment');

		// Language
		$this->load->model('localisation/language');

		$data['payment_bank_transfer_bank'] = [];

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($languages as $language) {
			$data['payment_bank_transfer_bank'][$language['language_id']] = $this->config->get('payment_bank_transfer_bank_' . $language['language_id']);
		}

		$data['languages'] = $languages;

		// Order Status
		$data['payment_bank_transfer_order_status_id'] = (int)$this->config->get('payment_bank_transfer_order_status_id');

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		// Geo Zone
		$data['payment_bank_transfer_geo_zone_id'] = $this->config->get('payment_bank_transfer_geo_zone_id');

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		$data['payment_bank_transfer_status'] = $this->config->get('payment_bank_transfer_status');
		$data['payment_bank_transfer_sort_order'] = $this->config->get('payment_bank_transfer_sort_order');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/opencart/payment/bank_transfer', $data));
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('extension/opencart/payment/bank_transfer');

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/opencart/payment/bank_transfer')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		// Language
		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($languages as $language) {
			if (empty($this->request->post['payment_bank_transfer_bank_' . $language['language_id']])) {
				$json['error']['bank_' . $language['language_id']] = $this->language->get('error_bank');
			}
		}

		if (!$json) {
			// Setting
			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting('payment_bank_transfer', $this->request->post);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
