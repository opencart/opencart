<?php
namespace Opencart\Admin\Controller\Extension\Paythefly\Payment;

/**
 * Class PayTheFly
 *
 * Admin controller for PayTheFly crypto payment gateway.
 * Handles configuration, install/uninstall, and order management.
 *
 * @package Opencart\Admin\Controller\Extension\Paythefly\Payment
 */
class Paythefly extends \Opencart\System\Engine\Controller {
	/**
	 * Index - Render the configuration page
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('extension/paythefly/payment/paythefly');

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
			'href' => $this->url->link('extension/paythefly/payment/paythefly', 'user_token=' . $this->session->data['user_token'])
		];

		$data['save'] = $this->url->link('extension/paythefly/payment/paythefly.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment');

		// PayTheFly Configuration
		$data['payment_paythefly_project_id'] = $this->config->get('payment_paythefly_project_id');
		$data['payment_paythefly_project_key'] = $this->config->get('payment_paythefly_project_key');

		// Chain configuration
		$data['payment_paythefly_chain'] = $this->config->get('payment_paythefly_chain') ?: 'bsc';
		$data['payment_paythefly_contract_address'] = $this->config->get('payment_paythefly_contract_address');
		$data['payment_paythefly_token_address'] = $this->config->get('payment_paythefly_token_address');
		$data['payment_paythefly_token_decimals'] = $this->config->get('payment_paythefly_token_decimals') ?: '18';

		// Deadline offset (seconds from now)
		$data['payment_paythefly_deadline_offset'] = $this->config->get('payment_paythefly_deadline_offset') ?: '3600';

		// Signing private key (for EIP-712 - stored securely)
		$data['payment_paythefly_private_key'] = $this->config->get('payment_paythefly_private_key');

		// Order Statuses
		$data['payment_paythefly_pending_status_id'] = (int)($this->config->get('payment_paythefly_pending_status_id') ?: 1);
		$data['payment_paythefly_confirmed_status_id'] = (int)($this->config->get('payment_paythefly_confirmed_status_id') ?: 2);
		$data['payment_paythefly_failed_status_id'] = (int)($this->config->get('payment_paythefly_failed_status_id') ?: 10);

		$this->load->model('localisation/order_status');
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		// Geo Zone
		$data['payment_paythefly_geo_zone_id'] = $this->config->get('payment_paythefly_geo_zone_id');

		$this->load->model('localisation/geo_zone');
		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		$data['payment_paythefly_status'] = $this->config->get('payment_paythefly_status');
		$data['payment_paythefly_sort_order'] = $this->config->get('payment_paythefly_sort_order');

		// Debug mode
		$data['payment_paythefly_debug'] = $this->config->get('payment_paythefly_debug');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/paythefly/payment/paythefly', $data));
	}

	/**
	 * Save - Handle form submission
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('extension/paythefly/payment/paythefly');

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/paythefly/payment/paythefly')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		// Validate required fields
		if (empty($this->request->post['payment_paythefly_project_id'])) {
			$json['error']['project_id'] = $this->language->get('error_project_id');
		}

		if (empty($this->request->post['payment_paythefly_project_key'])) {
			$json['error']['project_key'] = $this->language->get('error_project_key');
		}

		if (empty($this->request->post['payment_paythefly_contract_address'])) {
			$json['error']['contract_address'] = $this->language->get('error_contract_address');
		}

		if (empty($this->request->post['payment_paythefly_private_key'])) {
			$json['error']['private_key'] = $this->language->get('error_private_key');
		}

		if (!$json) {
			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting('payment_paythefly', $this->request->post);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Install - Create database tables and register events
	 *
	 * @return void
	 */
	public function install(): void {
		if ($this->user->hasPermission('modify', 'extension/paythefly/payment/paythefly')) {
			$this->load->model('extension/paythefly/payment/paythefly');
			$this->model_extension_paythefly_payment_paythefly->install();
		}
	}

	/**
	 * Uninstall - Remove database tables
	 *
	 * @return void
	 */
	public function uninstall(): void {
		if ($this->user->hasPermission('modify', 'extension/paythefly/payment/paythefly')) {
			$this->load->model('extension/paythefly/payment/paythefly');
			$this->model_extension_paythefly_payment_paythefly->uninstall();
		}
	}
}
