<?php
class ControllerExtensionTotalLowOrderFee extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/total/low_order_fee');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('total_low_order_fee', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=total', true));
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
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=total', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/total/low_order_fee', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/total/low_order_fee', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=total', true);

		if (isset($this->request->post['total_low_order_fee_total'])) {
			$data['total_low_order_fee_total'] = $this->request->post['total_low_order_fee_total'];
		} else {
			$data['total_low_order_fee_total'] = $this->config->get('total_low_order_fee_total');
		}

		if (isset($this->request->post['total_low_order_fee_fee'])) {
			$data['total_low_order_fee_fee'] = $this->request->post['total_low_order_fee_fee'];
		} else {
			$data['total_low_order_fee_fee'] = $this->config->get('total_low_order_fee_fee');
		}

		if (isset($this->request->post['total_low_order_fee_tax_class_id'])) {
			$data['total_low_order_fee_tax_class_id'] = $this->request->post['total_low_order_fee_tax_class_id'];
		} else {
			$data['total_low_order_fee_tax_class_id'] = $this->config->get('total_low_order_fee_tax_class_id');
		}

		$this->load->model('localisation/tax_class');

		$data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		if (isset($this->request->post['total_low_order_fee_status'])) {
			$data['total_low_order_fee_status'] = $this->request->post['total_low_order_fee_status'];
		} else {
			$data['total_low_order_fee_status'] = $this->config->get('total_low_order_fee_status');
		}

		if (isset($this->request->post['total_low_order_fee_sort_order'])) {
			$data['total_low_order_fee_sort_order'] = $this->request->post['total_low_order_fee_sort_order'];
		} else {
			$data['total_low_order_fee_sort_order'] = $this->config->get('total_low_order_fee_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/total/low_order_fee', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/total/low_order_fee')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}