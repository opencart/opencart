<?php
class ControllerOpenbayEbayTemplate extends Controller {
	private $error = array();

	public function listAll() {
		$this->data = array_merge($this->data, $this->load->language('openbay/ebay_template'));

		$this->load->model('openbay/ebay_template');

		$this->document->setTitle($this->data['lang_title_list']);
		$this->document->addStyle('view/stylesheet/openbay.css');
		$this->document->addScript('view/javascript/openbay/faq.js');

		$this->template = 'openbay/ebay_template_list.tpl';

		$this->children = array(
			'common/header',
			'common/footer'
		);

		if (isset($this->session->data['error'])) {
			$this->data['error_warning'] = $this->session->data['error'];
			unset($this->session->data['error']);
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		}

		$this->data['btn_add']  = $this->url->link('openbay/ebay_template/add', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['templates'] = $this->model_openbay_ebay_template->getAll();
		$this->data['token']    = $this->session->data['token'];

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => $this->language->get('text_home'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'href' => $this->url->link('extension/openbay', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => $this->language->get('lang_openbay'),
			'separator' => ' :: '
		);

		$this->data['breadcrumbs'][] = array(
			'href' => $this->url->link('openbay/openbay', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => $this->language->get('lang_ebay'),
			'separator' => ' :: '
		);

		$this->data['breadcrumbs'][] = array(
			'href' => $this->url->link('openbay/ebay_template/listAll', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => $this->language->get('lang_heading'),
			'separator' => ' :: '
		);

		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
	}

	public function add() {
		$this->data = array_merge($this->data, $this->load->language('openbay/ebay_template'));

		$this->load->model('openbay/ebay_template');

		$this->data['page_title']   = $this->data['lang_title_list_add'];
		$this->data['btn_save']     = $this->url->link('openbay/ebay_template/add', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['cancel']       = $this->url->link('openbay/ebay_template/listAll', 'token=' . $this->session->data['token'], 'SSL');

		if ($this->request->post && $this->templateValidate()) {
			$this->session->data['success'] = $this->data['lang_added'];

			$this->model_openbay_ebay_template->add($this->request->post);

			$this->redirect($this->url->link('openbay/ebay_template/listAll&token=' . $this->session->data['token'], 'SSL'));
		}

		$this->templateForm();
	}

	public function delete() {
		$this->load->model('openbay/ebay_template');

		if (!$this->user->hasPermission('modify', 'openbay/ebay_template')) {
			$this->error['warning'] = $this->language->get('invalid_permission');
		}else{
			if (isset($this->request->get['template_id'])) {
				$this->model_openbay_ebay_template->delete($this->request->get['template_id']);
			}
		}
		$this->redirect($this->url->link('openbay/ebay_template/listAll&token=' . $this->session->data['token'], 'SSL'));
	}

	public function edit() {
		$this->data = array_merge($this->data, $this->load->language('openbay/ebay_template'));

		$this->load->model('openbay/ebay_template');

		$this->data['page_title']   = $this->data['lang_title_list_edit'];
		$this->data['btn_save']     = $this->url->link('openbay/ebay_template/edit', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['cancel']       = $this->url->link('openbay/ebay_template/listAll', 'token=' . $this->session->data['token'], 'SSL');

		if ($this->request->post && $this->templateValidate()) {

			$this->session->data['success'] = $this->data['lang_updated'];

			$this->model_openbay_ebay_template->edit($this->request->post['template_id'], $this->request->post);

			$this->redirect($this->url->link('openbay/ebay_template/listAll&token=' . $this->session->data['token'], 'SSL'));
		}

		$this->templateForm();
	}

	public function templateForm() {
		$this->load->model('openbay/ebay');

		$this->data['token'] = $this->session->data['token'];

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->request->get['template_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$template_info = $this->model_openbay_ebay_template->get($this->request->get['template_id']);
		}

		$this->document->setTitle($this->data['page_title']);
		$this->document->addStyle('view/stylesheet/openbay.css');
		$this->document->addStyle('view/stylesheet/codemirror.css');
		$this->document->addScript('view/javascript/openbay/codemirror.js');
		$this->document->addScript('view/javascript/openbay/faq.js');

		$this->template = 'openbay/ebay_template_form.tpl';

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => $this->language->get('text_home'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'href' => $this->url->link('extension/openbay', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => 'OpenBay Pro',
			'separator' => ' :: '
		);

		$this->data['breadcrumbs'][] = array(
			'href' => $this->url->link('openbay/openbay', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => 'eBay',
			'separator' => ' :: '
		);

		$this->data['breadcrumbs'][] = array(
			'href' => $this->url->link('openbay/openbay/listAll', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => 'Profiles',
			'separator' => ' :: '
		);

		if (isset($this->request->post['name'])) {
			$this->data['name'] = $this->request->post['name'];
		} elseif (!empty($template_info)) {
			$this->data['name'] = $template_info['name'];
		} else {
			$this->data['name'] = '';
		}

		if (isset($this->request->post['html'])) {
			$this->data['html'] = $this->request->post['html'];
		} elseif (!empty($template_info)) {
			$this->data['html'] = $template_info['html'];
		} else {
			$this->data['html'] = '';
		}

		if (isset($this->request->get['template_id'])) {
			$this->data['template_id'] = $this->request->get['template_id'];
		} else {
			$this->data['template_id'] = '';
		}

		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
	}

	private function templateValidate() {
		if (!$this->user->hasPermission('modify', 'openbay/ebay_template')) {
			$this->error['warning'] = $this->language->get('invalid_permission');
		}

		if ($this->request->post['name'] == '') {
			$this->error['name'] = $this->data['lang_error_name'];
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
?>