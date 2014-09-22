<?php
class ControllerOpenbayEbayTemplate extends Controller {
	private $error = array();

	public function listAll() {
		$data = $this->load->language('openbay/ebay_template');

		$this->load->model('openbay/ebay_template');

		$this->document->setTitle($data['heading_title']);
		$this->document->addScript('view/javascript/openbay/js/faq.js');

		if (isset($this->session->data['error'])) {
			$data['error_warning'] = $this->session->data['error'];
			unset($this->session->data['error']);
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['insert']  = $this->url->link('openbay/ebay_template/add', 'token=' . $this->session->data['token'], 'SSL');
		$data['templates'] = $this->model_openbay_ebay_template->getAll();
		$data['token']    = $this->session->data['token'];

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => $this->language->get('text_home'),
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('extension/openbay', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => $this->language->get('text_openbay'),
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('openbay/ebay', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => $this->language->get('text_ebay'),
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('openbay/ebay_template/listAll', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => $this->language->get('heading_title'),
		);

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('openbay/ebay_template_list.tpl', $data));
	}

	public function add() {
		$data = $this->load->language('openbay/ebay_template');

		$this->load->model('openbay/ebay_template');

		$data['page_title']   = $data['text_title_list_add'];
		$data['btn_save']     = $this->url->link('openbay/ebay_template/add', 'token=' . $this->session->data['token'], 'SSL');
		$data['cancel']       = $this->url->link('openbay/ebay_template/listAll', 'token=' . $this->session->data['token'], 'SSL');

		if ($this->request->post && $this->templateValidate()) {
			$this->session->data['success'] = $data['text_added'];

			$this->model_openbay_ebay_template->add($this->request->post);

			$this->response->redirect($this->url->link('openbay/ebay_template/listAll&token=' . $this->session->data['token'], 'SSL'));
		}

		$this->templateForm($data);
	}

	public function delete() {
		$this->load->language('openbay/ebay_template');
		$this->load->model('openbay/ebay_template');

		if (!$this->user->hasPermission('modify', 'openbay/ebay_template')) {
			$this->error['warning'] = $this->language->get('error_permission');
		} else {
			if (isset($this->request->get['template_id'])) {
				$this->model_openbay_ebay_template->delete($this->request->get['template_id']);

				$this->session->data['success'] = $this->language->get('text_deleted');
			}
		}
		$this->response->redirect($this->url->link('openbay/ebay_template/listAll&token=' . $this->session->data['token'], 'SSL'));
	}

	public function edit() {
		$data = $this->load->language('openbay/ebay_template');

		$this->load->model('openbay/ebay_template');

		$data['page_title']   = $data['text_title_list_edit'];
		$data['btn_save']     = $this->url->link('openbay/ebay_template/edit', 'token=' . $this->session->data['token'], 'SSL');
		$data['cancel']       = $this->url->link('openbay/ebay_template/listAll', 'token=' . $this->session->data['token'], 'SSL');

		if ($this->request->post && $this->templateValidate()) {

			$this->session->data['success'] = $data['text_updated'];

			$this->model_openbay_ebay_template->edit($this->request->post['template_id'], $this->request->post);

			$this->response->redirect($this->url->link('openbay/ebay_template/listAll&token=' . $this->session->data['token'], 'SSL'));
		}

		$this->templateForm($data);
	}

	public function templateForm($data) {
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
		$this->document->addStyle('view/javascript/openbay/css/codemirror.css');
		$this->document->addScript('view/javascript/openbay/js/codemirror.js');
		$this->document->addScript('view/javascript/openbay/js/faq.js');

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
			'href' => $this->url->link('openbay/ebay', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => 'eBay',
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('openbay/ebay/listAll', 'token=' . $this->session->data['token'], 'SSL'),
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
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('openbay/ebay_template_form.tpl', $data));
	}

	private function templateValidate() {
		if (!$this->user->hasPermission('modify', 'openbay/ebay_template')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ($this->request->post['name'] == '') {
			$this->error['warning'] = $this->language->get('error_name');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}