<?php
class ControllerExtensionDashboardSale extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/dashboard/sale');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('dashboard_sale', $this->request->post);

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
			'href' => $this->url->link('extension/dashboard/sale', 'user_token=' . $this->session->data['user_token'])
		);

		$data['action'] = $this->url->link('extension/dashboard/sale', 'user_token=' . $this->session->data['user_token']);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=dashboard');

		if (isset($this->request->post['dashboard_sale_width'])) {
			$data['dashboard_sale_width'] = $this->request->post['dashboard_sale_width'];
		} else {
			$data['dashboard_sale_width'] = $this->config->get('dashboard_sale_width');
		}
	
		$data['columns'] = array();
		
		for ($i = 3; $i <= 12; $i++) {
			$data['columns'][] = $i;
		}
				
		if (isset($this->request->post['dashboard_sale_status'])) {
			$data['dashboard_sale_status'] = $this->request->post['dashboard_sale_status'];
		} else {
			$data['dashboard_sale_status'] = $this->config->get('dashboard_sale_status');
		}

		if (isset($this->request->post['dashboard_sale_sort_order'])) {
			$data['dashboard_sale_sort_order'] = $this->request->post['dashboard_sale_sort_order'];
		} else {
			$data['dashboard_sale_sort_order'] = $this->config->get('dashboard_sale_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/dashboard/sale_form', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/dashboard/sale')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
	
	public function dashboard() {
		$this->load->language('extension/dashboard/sale');

		$data['user_token'] = $this->session->data['user_token'];

		$this->load->model('extension/dashboard/sale');

		$today = $this->model_extension_dashboard_sale->getTotalSales(array('filter_date_added' => date('Y-m-d', strtotime('-1 day'))));

		$yesterday = $this->model_extension_dashboard_sale->getTotalSales(array('filter_date_added' => date('Y-m-d', strtotime('-2 day'))));

		$difference = $today - $yesterday;

		if ($difference && (int)$today) {
			$data['percentage'] = round(($difference / $today) * 100);
		} else {
			$data['percentage'] = 0;
		}

		$sale_total = $this->model_extension_dashboard_sale->getTotalSales();

		if ($sale_total > 1000000000000) {
			$data['total'] = round($sale_total / 1000000000000, 1) . 'T';
		} elseif ($sale_total > 1000000000) {
			$data['total'] = round($sale_total / 1000000000, 1) . 'B';
		} elseif ($sale_total > 1000000) {
			$data['total'] = round($sale_total / 1000000, 1) . 'M';
		} elseif ($sale_total > 1000) {
			$data['total'] = round($sale_total / 1000, 1) . 'K';
		} else {
			$data['total'] = round($sale_total);
		}

		$data['sale'] = $this->url->link('sale/order', 'user_token=' . $this->session->data['user_token']);

		return $this->load->view('extension/dashboard/sale_info', $data);
	}
}
