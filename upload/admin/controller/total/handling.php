<?php 
class ControllerTotalHandling extends Controller { 
	private $error = array(); 

	public function index() { 
		$this->language->load('total/handling');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('handling', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_none'] = $this->language->get('text_none');

		$this->data['entry_total'] = $this->language->get('entry_total');
		$this->data['entry_fee'] = $this->language->get('entry_fee');
		$this->data['entry_tax_class'] = $this->language->get('entry_tax_class');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_total'),
			'href'      => $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('total/handling', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['action'] = $this->url->link('total/handling', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['cancel'] = $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['handling_total'])) {
			$this->data['handling_total'] = $this->request->post['handling_total'];
		} else {
			$this->data['handling_total'] = $this->config->get('handling_total');
		}

		if (isset($this->request->post['handling_fee'])) {
			$this->data['handling_fee'] = $this->request->post['handling_fee'];
		} else {
			$this->data['handling_fee'] = $this->config->get('handling_fee');
		}

		if (isset($this->request->post['handling_tax_class_id'])) {
			$this->data['handling_tax_class_id'] = $this->request->post['handling_tax_class_id'];
		} else {
			$this->data['handling_tax_class_id'] = $this->config->get('handling_tax_class_id');
		}

		$this->load->model('localisation/tax_class');

		$this->data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		if (isset($this->request->post['handling_status'])) {
			$this->data['handling_status'] = $this->request->post['handling_status'];
		} else {
			$this->data['handling_status'] = $this->config->get('handling_status');
		}

		if (isset($this->request->post['handling_sort_order'])) {
			$this->data['handling_sort_order'] = $this->request->post['handling_sort_order'];
		} else {
			$this->data['handling_sort_order'] = $this->config->get('handling_sort_order');
		}

		$this->template = 'total/handling.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'total/handling')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>