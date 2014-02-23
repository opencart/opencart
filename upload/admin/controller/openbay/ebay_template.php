<?php
class ControllerOpenbayEbayTemplate extends Controller {
	private $error = array();

	public function listAll() {
		$data = $this->load->language('openbay/ebay_template');

		$this->load->model('openbay/ebay_template');

		$this->document->setTitle($data['lang_title_list']);
		$this->document->addStyle('view/stylesheet/openbay.css');
		$this->document->addScript('view/javascript/openbay/faq.js');

		if (isset($this->session->data['error'])) {
			$data['error_warning'] = $this->session->data['error'];
			unset($this->session->data['error']);
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		}

		$data['btn_add']  = $this->url->link('openbay/ebay_template/add', 'token=' . $this->session->data['token'], 'SSL');
		$data['templates'] = $this->model_openbay_ebay_template->getAll();
		$data['token']    = $this->session->data['token'];

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => $this->language->get('text_home'),
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('extension/openbay', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => $this->language->get('lang_openbay'),
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('openbay/openbay', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => $this->language->get('lang_ebay'),
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('openbay/ebay_template/listAll', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => $this->language->get('lang_heading'),
		);

		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));

		$data['header'] = $this->load->controller('common/header');
		$data['menu'] = $this->load->controller('common/menu');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('openbay/ebay_template_list.tpl', $data));
	}

	public function add() {
		$data = $this->load->language('openbay/ebay_template');

		$this->load->model('openbay/ebay_template');

		$data['page_title']   = $data['lang_title_list_add'];
		$data['btn_save']     = $this->url->link('openbay/ebay_template/add', 'token=' . $this->session->data['token'], 'SSL');
		$data['cancel']       = $this->url->link('openbay/ebay_template/listAll', 'token=' . $this->session->data['token'], 'SSL');

		if ($this->request->post && $this->templateValidate()) {
			$this->session->data['success'] = $data['lang_added'];

			$this->model_openbay_ebay_template->add($this->request->post);

			$this->response->redirect($this->url->link('openbay/ebay_template/listAll&token=' . $this->session->data['token'], 'SSL'));
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
		$this->response->redirect($this->url->link('openbay/ebay_template/listAll&token=' . $this->session->data['token'], 'SSL'));
	}

	public function edit() {
		$data = $this->load->language('openbay/ebay_template');

		$this->load->model('openbay/ebay_template');

		$data['page_title']   = $data['lang_title_list_edit'];
		$data['btn_save']     = $this->url->link('openbay/ebay_template/edit', 'token=' . $this->session->data['token'], 'SSL');
		$data['cancel']       = $this->url->link('openbay/ebay_template/listAll', 'token=' . $this->session->data['token'], 'SSL');

		if ($this->request->post && $this->templateValidate()) {

			$this->session->data['success'] = $data['lang_updated'];

			$this->model_openbay_ebay_template->edit($this->request->post['template_id'], $this->request->post);

			$this->response->redirect($this->url->link('openbay/ebay_template/listAll&token=' . $this->session->data['token'], 'SSL'));
		}

		$this->templateForm();
	}

	public function templateForm() {
		$this->load->model('openbay/ebay');

		$data['token'] = $this->session->data['token'];

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->request->get['template_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$template_info = $this->model_openbay_ebay_template->get($this->request->get['template_id']);
		}

		$this->document->setTitle($data['page_title']);
		$this->document->addStyle('view/stylesheet/openbay.css');
		$this->document->addStyle('view/stylesheet/codemirror.css');
		$this->document->addScript('view/javascript/openbay/codemirror.js');
		$this->document->addScript('view/javascript/openbay/faq.js');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => $this->language->get('text_home'),
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('extension/openbay', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => 'OpenBay Pro',
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('openbay/openbay', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => 'eBay',
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('openbay/openbay/listAll', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => 'Profiles',
		);

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($template_info)) {
			$data['name'] = $template_info['name'];
		} else {
			$data['name'] = '';
		}

		if (isset($this->request->post['html'])) {
			$data['html'] = $this->request->post['html'];
		} elseif (!empty($template_info)) {
			$data['html'] = $template_info['html'];
		} else {
			$data['html'] = '';
		}

		if (isset($this->request->get['template_id'])) {
			$data['template_id'] = $this->request->get['template_id'];
		} else {
			$data['template_id'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['menu'] = $this->load->controller('common/menu');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('openbay/ebay_template_form.tpl', $data));
	}

	private function templateValidate() {
		if (!$this->user->hasPermission('modify', 'openbay/ebay_template')) {
			$this->error['warning'] = $this->language->get('invalid_permission');
		}

		if ($this->request->post['name'] == '') {
			$this->error['name'] = $this->language->get('lang_error_name');
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