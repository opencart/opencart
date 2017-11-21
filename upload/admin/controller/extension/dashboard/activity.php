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

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=dashboard'));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = ''; 
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=dashboard')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/dashboard/activity', 'user_token=' . $this->session->data['user_token'])
		);

		$data['action'] = $this->url->link('extension/dashboard/activity', 'user_token=' . $this->session->data['user_token']);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=dashboard');

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

		$data['user_token'] = $this->session->data['user_token'];

		$data['activities'] = array();

		$this->load->model('extension/dashboard/activity');

		$results = $this->model_extension_dashboard_activity->getActivities();

		foreach ($results as $result) {
			$comment = vsprintf($this->language->get('text_activity_' . $result['key']), json_decode($result['data'], true));

			$find = array(
				'customer_id=',
				'order_id=',
				'return_id='
			);

			$replace = array(
				$this->url->link('customer/customer/edit', 'user_token=' . $this->session->data['user_token'] . '&customer_id='),
				$this->url->link('sale/order/info', 'user_token=' . $this->session->data['user_token'] . '&order_id='),
				$this->url->link('sale/return/edit', 'user_token=' . $this->session->data['user_token'] . '&return_id=')
			);

			$data['activities'][] = array(
				'comment'    => str_replace($find, $replace, $comment),
				'date_added' => date($this->language->get('datetime_format'), strtotime($result['date_added']))
			);
		}

		return $this->load->view('extension/dashboard/activity_info', $data);
	}
}