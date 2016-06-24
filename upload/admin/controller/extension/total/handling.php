<?php
class ControllerExtensionTotalHandling extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/total/handling');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('handling', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=total', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_none'] = $this->language->get('text_none');

		$data['entry_total'] = $this->language->get('entry_total');
		$data['entry_fee'] = $this->language->get('entry_fee');
		$data['entry_tax_class'] = $this->language->get('entry_tax_class');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['help_total'] = $this->language->get('help_total');

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
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=total', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/total/handling', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('extension/total/handling', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=total', true);

		if (isset($this->request->post['handling_total'])) {
			$data['handling_total'] = $this->request->post['handling_total'];
		} else {
			$data['handling_total'] = $this->config->get('handling_total');
		}

		if (isset($this->request->post['handling_fee'])) {
			$data['handling_fee'] = $this->request->post['handling_fee'];
		} else {
			$data['handling_fee'] = $this->config->get('handling_fee');
		}

		if (isset($this->request->post['handling_tax_class_id'])) {
			$data['handling_tax_class_id'] = $this->request->post['handling_tax_class_id'];
		} else {
			$data['handling_tax_class_id'] = $this->config->get('handling_tax_class_id');
		}

		$this->load->model('localisation/tax_class');

		$data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		if (isset($this->request->post['handling_status'])) {
			$data['handling_status'] = $this->request->post['handling_status'];
		} else {
			$data['handling_status'] = $this->config->get('handling_status');
		}

		if (isset($this->request->post['handling_sort_order'])) {
			$data['handling_sort_order'] = $this->request->post['handling_sort_order'];
		} else {
			$data['handling_sort_order'] = $this->config->get('handling_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/total/handling', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/total/handling')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}