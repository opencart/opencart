<?php
namespace Opencart\Admin\Controller\Extension\Opencart\Dashboard;
/**
 * Class Activity
 *
 * @package Opencart\Admin\Controller\Extension\Opencart\Dashboard
 */
class Activity extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('extension/opencart/dashboard/activity');

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
			'href' => $this->url->link('extension/opencart/dashboard/activity', 'user_token=' . $this->session->data['user_token'])
		];

		$data['save'] = $this->url->link('extension/opencart/dashboard/activity.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=dashboard');

		$data['dashboard_activity_width'] = $this->config->get('dashboard_activity_width');

		$data['columns'] = [];

		for ($i = 3; $i <= 12; $i++) {
			$data['columns'][] = $i;
		}

		$data['dashboard_activity_status'] = $this->config->get('dashboard_activity_status');
		$data['dashboard_activity_sort_order'] = $this->config->get('dashboard_activity_sort_order');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/opencart/dashboard/activity_form', $data));
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('extension/opencart/dashboard/activity');

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/opencart/dashboard/activity')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			// Setting
			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting('dashboard_activity', $this->request->post);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Dashboard
	 *
	 * @return string
	 */
	public function dashboard(): string {
		$this->load->language('extension/opencart/dashboard/activity');

		// Activities
		$data['activities'] = [];

		$this->load->model('extension/opencart/report/activity');

		$results = $this->model_extension_opencart_report_activity->getActivities();

		foreach ($results as $result) {
			$comment = vsprintf($this->language->get('text_activity_' . $result['key']), json_decode($result['data'], true));

			$find = [
				'customer_id=',
				'order_id=',
				'return_id='
			];

			$replace = [
				$this->url->link('customer/customer.form', 'user_token=' . $this->session->data['user_token'] . '&customer_id='),
				$this->url->link('sale/order.info', 'user_token=' . $this->session->data['user_token'] . '&order_id='),
				$this->url->link('sale/return.form', 'user_token=' . $this->session->data['user_token'] . '&return_id=')
			];

			$data['activities'][] = [
				'comment'    => str_replace($find, $replace, $comment),
				'date_added' => date($this->language->get('datetime_format'), strtotime($result['date_added']))
			];
		}

		$data['user_token'] = $this->session->data['user_token'];

		return $this->load->view('extension/opencart/dashboard/activity_info', $data);
	}
}
