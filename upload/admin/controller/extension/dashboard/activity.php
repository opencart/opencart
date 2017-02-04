<?php
class ControllerExtensionDashboardActivity extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/dashboard/activity');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('dashboard_activity', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=dashboard', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_width'] = $this->language->get('entry_width');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=dashboard', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/dashboard/activity', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('extension/dashboard/activity', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=dashboard', true);

		if (isset($this->request->post['dashboard_activity_width'])) {
			$data['dashboard_activity_width'] = $this->request->post['dashboard_activity_width'];
		} else {
			$data['dashboard_activity_width'] = $this->config->get('dashboard_activity_width');
		}
		
		$data['columns'] = array();
		
		for ($i = 3; $i <= 12; $i++) {
			$data['columns'][] = $i;
		}
		
		if (isset($this->request->post['dashboard_activity_status'])) {
			$data['dashboard_activity_status'] = $this->request->post['dashboard_activity_status'];
		} else {
			$data['dashboard_activity_status'] = $this->config->get('dashboard_activity_status');
		}

		if (isset($this->request->post['dashboard_activity_sort_order'])) {
			$data['dashboard_activity_sort_order'] = $this->request->post['dashboard_activity_sort_order'];
		} else {
			$data['dashboard_activity_sort_order'] = $this->config->get('dashboard_activity_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/dashboard/activity_form', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/dashboard/activity')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
	
	public function dashboard() {
		$this->load->language('extension/dashboard/activity');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_no_results'] = $this->language->get('text_no_results');

		$data['token'] = $this->session->data['token'];

		$data['activities'] = array();

		$this->load->model('report/activity');

		$results = $this->model_report_activity->getActivities();

		foreach ($results as $result) {
			$comment = vsprintf($this->language->get('text_' . $result['key']), json_decode($result['data'], true));

			$find = array(
				'customer_id=',
				'order_id=',
				'affiliate_id=',
				'return_id='
			);

			$replace = array(
				$this->url->link('customer/customer/edit', 'token=' . $this->session->data['token'] . '&customer_id=', true),
				$this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=', true),
				$this->url->link('marketing/affiliate/edit', 'token=' . $this->session->data['token'] . '&affiliate_id=', true),
				$this->url->link('sale/return/edit', 'token=' . $this->session->data['token'] . '&return_id=', true)
			);

			$data['activities'][] = array(
				'comment'    => str_replace($find, $replace, $comment),
				'date_added' => date($this->language->get('datetime_format'), strtotime($result['date_added']))
			);
		}

		return $this->load->view('extension/dashboard/activity_info', $data);
	}
}
