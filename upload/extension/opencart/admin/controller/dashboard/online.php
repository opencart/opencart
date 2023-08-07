<?php
namespace Opencart\Admin\Controller\Extension\Opencart\Dashboard;
/**
 * Class Online
 *
 * @package Opencart\Admin\Controller\Extension\Opencart\Dashboard
 */
class Online extends \Opencart\System\Engine\Controller {
	/**
	 * @return void
	 */
	public function index(): void {
		$this->load->language('extension/opencart/dashboard/online');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=dashboard')
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/opencart/dashboard/online', 'user_token=' . $this->session->data['user_token'])
		];

		$data['save'] = $this->url->link('extension/opencart/dashboard/online.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=dashboard');

		$data['dashboard_online_width'] = $this->config->get('dashboard_online_width');

		$data['columns'] = [];

		for ($i = 3; $i <= 12; $i++) {
			$data['columns'][] = $i;
		}

		$data['dashboard_online_status'] = $this->config->get('dashboard_online_status');
		$data['dashboard_online_sort_order'] = $this->config->get('dashboard_online_sort_order');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/opencart/dashboard/online_form', $data));
	}

	/**
	 * @return void
	 */
	public function save(): void {
		$this->load->language('extension/opencart/dashboard/online');

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/opencart/dashboard/online')) {
			$json['error']  = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting('dashboard_online', $this->request->post);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * @return string
	 */
	public function dashboard(): string {
		$this->load->language('extension/opencart/dashboard/online');

		$this->load->model('report/online');

		// Customers Online
		$online_total = $this->model_report_online->getTotalOnline();

		if ($online_total > 1000000000000) {
			$data['total'] = round($online_total / 1000000000000, 1) . 'T';
		} elseif ($online_total > 1000000000) {
			$data['total'] = round($online_total / 1000000000, 1) . 'B';
		} elseif ($online_total > 1000000) {
			$data['total'] = round($online_total / 1000000, 1) . 'M';
		} elseif ($online_total > 1000) {
			$data['total'] = round($online_total / 1000, 1) . 'K';
		} else {
			$data['total'] = $online_total;
		}

		$data['online'] = $this->url->link('report/online', 'user_token=' . $this->session->data['user_token']);

		return $this->load->view('extension/opencart/dashboard/online_info', $data);
	}
}
