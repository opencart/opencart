<?php
/**
 * Class Opayo
 *
 * @package Opencart\Admin\Controller\Extension\Opencart\Payment
 */
namespace Opencart\Admin\Controller\Extension\Opayo\Payment;
class Opayo extends \Opencart\System\Engine\Controller {
	/**
	 * @var array<string, string>
	 */
	private array $error = [];
	private string $separator = '';

	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('extension/opayo/payment/opayo');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_extensions'),
			'href' => $this->url->link('marketplace/opencart/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment')
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/opayo/payment/opayo', 'user_token=' . $this->session->data['user_token'])
		];

		$data['save'] = $this->url->link('extension/opayo/payment/opayo' . $this->separator . 'save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment');

		$server = HTTP_SERVER;
		$catalog = HTTP_CATALOG;

		// Setting
		$_config = new \Opencart\System\Engine\Config();
		$_config->addPath(DIR_EXTENSION . 'opayo/system/config/');
		$_config->load('opayo');

		$data['setting'] = $_config->get('opayo_setting');

		$data['setting'] = array_replace_recursive((array)$data['setting'], (array)$this->config->get('payment_opayo_setting'));

		$data['vendor'] = $this->config->get('payment_opayo_vendor');

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$data['geo_zone_id'] = $this->config->get('payment_opayo_geo_zone_id');

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		$data['status'] = $this->config->get('payment_opayo_status');
		$data['sort_order'] = $this->config->get('payment_opayo_sort_order');

		if (!$data['setting']['cron']['token']) {
			$data['setting']['cron']['token'] = sha1(uniqid(mt_rand(), 1));
		}

		if (!$data['setting']['cron']['url']) {
			$data['setting']['cron']['url'] = $catalog . 'index.php?route=extension/opayo/payment' . $this->separator . 'cron&token=' . $data['setting']['cron']['token'];
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/opayo/payment/opayo', $data));
	}

	public function save(): void {
		$this->load->language('extension/opayo/payment/opayo');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting('payment_opayo', $this->request->post);

			$data['success'] = $this->language->get('success_save');
		}

		$data['error'] = $this->error;

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
	}

	/**
	 * Install
	 *
	 * @return void
	 */
	public function install(): void {
		$this->load->model('extension/opayo/payment/opayo');

		$this->model_extension_opayo_payment_opayo->install();
	}

	/**
	 * Uninstall
	 *
	 * @return void
	 */
	public function uninstall(): void {
		$this->load->model('extension/opayo/payment/opayo');

		$this->model_extension_opayo_payment_opayo->uninstall();
	}

	/**
	 * Order
	 *
	 * @return string
	 */
	public function order(): string {
		if ($this->config->get('payment_opayo_status')) {
			$this->load->model('extension/opayo/payment/opayo');

			$opayo_order = $this->model_extension_opayo_payment_opayo->getOrder($this->request->get['order_id']);

			if (!empty($opayo_order)) {
				$this->load->language('extension/opayo/payment/opayo');

				$opayo_order['total_released'] = $this->model_extension_opayo_payment_opayo->getTotalReleased($opayo_order['opayo_order_id']);

				$opayo_order['total_formatted'] = $this->currency->format($opayo_order['total'], $opayo_order['currency_code'], false, false);
				$opayo_order['total_released_formatted'] = $this->currency->format($opayo_order['total_released'], $opayo_order['currency_code'], false, false);

				$data['opayo_order'] = $opayo_order;
				$data['auto_settle'] = $opayo_order['settle_type'];

				$data['order_id'] = $this->request->get['order_id'];

				$data['user_token'] = $this->request->get['user_token'];

				$data['separator'] = $this->separator;

				return $this->load->view('extension/opayo/payment/order', $data);
			}
		}

		return '';
	}

	/**
	 * Void
	 *
	 * @return void
	 */
	public function void(): void {
		$this->load->language('extension/opayo/payment/opayo');

		$json = [];

		if (!empty($this->request->post['order_id'])) {
			$this->load->model('extension/opayo/payment/opayo');

			$opayo_order = $this->model_extension_opayo_payment_opayo->getOrder($this->request->post['order_id']);

			$void_response = $this->model_extension_opayo_payment_opayo->void($this->request->post['order_id']);

			$this->model_extension_opayo_payment_opayo->log('Void result', $void_response);

			if (!empty($void_response) && $void_response['Status'] == 'OK') {
				$this->model_extension_opayo_payment_opayo->addOrderTransaction($opayo_order['opayo_order_id'], 'void', 0.00);
				$this->model_extension_opayo_payment_opayo->updateVoidStatus($opayo_order['opayo_order_id'], 1);

				$json['msg'] = $this->language->get('success_void_ok');

				$json['data'] = [];
				$json['data']['date_added'] = date("Y-m-d H:i:s");
				$json['error'] = false;
			} else {
				$json['error'] = true;
				$json['msg'] = isset($void_response['StatuesDetail']) && !empty($void_response['StatuesDetail']) ? (string)$void_response['StatuesDetail'] : 'Unable to void';
			}
		} else {
			$json['error'] = true;
			$json['msg'] = 'Missing data';
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Release
	 *
	 * @return void
	 */
	public function release(): void {
		$this->load->language('extension/opayo/payment/opayo');

		$json = [];

		if (!empty($this->request->post['order_id']) && !empty($this->request->post['amount']) && $this->request->post['amount'] > 0) {
			$this->load->model('extension/opayo/payment/opayo');

			$opayo_order = $this->model_extension_opayo_payment_opayo->getOrder($this->request->post['order_id']);

			$release_response = $this->model_extension_opayo_payment_opayo->release($this->request->post['order_id'], $this->request->post['amount']);

			$this->model_extension_opayo_payment_opayo->log('Release result', $release_response);

			if (!empty($release_response) && $release_response['Status'] == 'OK') {
				$this->model_extension_opayo_payment_opayo->addOrderTransaction($opayo_order['opayo_order_id'], 'payment', $this->request->post['amount']);

				$total_released = $this->model_extension_opayo_payment_opayo->getTotalReleased($opayo_order['opayo_order_id']);

				if ($total_released >= $opayo_order['total'] || $opayo_order['settle_type'] == 0) {
					$this->model_extension_opayo_payment_opayo->updateReleaseStatus($opayo_order['opayo_order_id'], 1);
					$release_status = 1;
					$json['msg'] = $this->language->get('success_release_ok_order');
				} else {
					$release_status = 0;
					$json['msg'] = $this->language->get('success_release_ok');
				}

				$json['data'] = [];

				$json['data']['date_added'] = date('Y-m-d H:i:s');
				$json['data']['amount'] = $this->request->post['amount'];
				$json['data']['release_status'] = $release_status;
				$json['data']['total'] = (float)$total_released;

				$json['error'] = false;
			} else {
				$json['error'] = true;

				$json['msg'] = isset($release_response['StatusDetail']) && !empty($release_response['StatusDetail']) ? (string)$release_response['StatusDetail'] : 'Unable to release';
			}
		} else {
			$json['error'] = true;

			$json['msg'] = $this->language->get('error_data_missing');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Rebate
	 *
	 * @return void
	 */
	public function rebate(): void {
		$this->load->language('extension/opayo/payment/opayo');

		$json = [];

		if (!empty($this->request->post['order_id'])) {
			$this->load->model('extension/opayo/payment/opayo');

			$opayo_order = $this->model_extension_opayo_payment_opayo->getOrder($this->request->post['order_id']);

			$rebate_response = $this->model_extension_opayo_payment_opayo->rebate($this->request->post['order_id'], $this->request->post['amount']);

			$this->model_extension_opayo_payment_opayo->log('Rebate result', $rebate_response);

			if (!empty($rebate_response) && $rebate_response['Status'] == 'OK') {
				$this->model_extension_opayo_payment_opayo->addOrderTransaction($opayo_order['opayo_order_id'], 'rebate', $this->request->post['amount'] * -1);

				$total_rebated = $this->model_extension_opayo_payment_opayo->getTotalRebated($opayo_order['opayo_order_id']);
				$total_released = $this->model_extension_opayo_payment_opayo->getTotalReleased($opayo_order['opayo_order_id']);

				if ($total_released <= 0 && $opayo_order['release_status'] == 1) {
					$this->model_extension_opayo_payment_opayo->updateRebateStatus($opayo_order['opayo_order_id'], 1);
					$rebate_status = 1;
					$json['msg'] = $this->language->get('success_rebate_ok_order');
				} else {
					$rebate_status = 0;
					$json['msg'] = $this->language->get('success_rebate_ok');
				}

				$json['data'] = [];

				$json['data']['date_added'] = date('Y-m-d H:i:s');
				$json['data']['amount'] = $this->request->post['amount'] * -1;
				$json['data']['total_released'] = (float)$total_released;
				$json['data']['total_rebated'] = (float)$total_rebated;
				$json['data']['rebate_status'] = $rebate_status;

				$json['error'] = false;
			} else {
				$json['error'] = true;

				$json['msg'] = isset($rebate_response['StatusDetail']) && !empty($rebate_response['StatusDetail']) ? (string)$rebate_response['StatusDetail'] : 'Unable to rebate';
			}
		} else {
			$json['error'] = true;

			$json['msg'] = 'Missing data';
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Validate
	 *
	 * @return bool
	 */
	private function validate(): bool {
		if (!$this->user->hasPermission('modify', 'extension/opayo/payment/opayo')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->request->post['payment_opayo_vendor'] = trim($this->request->post['payment_opayo_vendor']);

		if (!$this->request->post['payment_opayo_vendor']) {
			$this->error['vendor'] = $this->language->get('error_vendor');
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}
}
