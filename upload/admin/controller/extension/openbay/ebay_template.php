<?php
class ControllerExtensionOpenbayEbayTemplate extends Controller {
	private $error = array();

	public function listAll() {
		$data = $this->load->language('extension/openbay/ebay_template');

		$this->load->model('extension/openbay/ebay_template');

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

		$data['add'] = $this->url->link('extension/openbay/ebay_template/add', 'user_token=' . $this->session->data['user_token'], true);
		$data['cancel'] = $this->url->link('extension/openbay/ebay', 'user_token=' . $this->session->data['user_token'], true);

		$data['templates'] = $this->model_extension_openbay_ebay_template->getAll();
		$data['user_token'] = $this->session->data['user_token'];

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true),
			'text' => $this->language->get('text_home'),
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('marketplace/openbay', 'user_token=' . $this->session->data['user_token'], true),
			'text' => $this->language->get('text_openbay'),
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('extension/openbay/ebay', 'user_token=' . $this->session->data['user_token'], true),
			'text' => $this->language->get('text_ebay'),
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('extension/openbay/ebay_template/listAll', 'user_token=' . $this->session->data['user_token'], true),
			'text' => $this->language->get('heading_title'),
		);

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/openbay/ebay_template_list', $data));
	}

	public function add() {
		$this->load->model('extension/openbay/ebay_template');

		$data = $this->load->language('extension/openbay/ebay_template');

		$data['page_title']   = $data['heading_title'];
		$data['btn_save']     = $this->url->link('extension/openbay/ebay_template/add', 'user_token=' . $this->session->data['user_token'], true);
		$data['cancel']       = $this->url->link('extension/openbay/ebay_template/listAll', 'user_token=' . $this->session->data['user_token'], true);

		if ($this->request->post && $this->templateValidate()) {
			$this->session->data['success'] = $data['text_added'];

			$this->model_extension_openbay_ebay_template->add($this->request->post);

			$this->response->redirect($this->url->link('extension/openbay/ebay_template/listAll', 'user_token=' . $this->session->data['user_token'], true));
		}

		$this->templateForm($data);
	}

	public function delete() {
		$this->load->language('extension/openbay/ebay_template');
		$this->load->model('extension/openbay/ebay_template');

		if (!$this->user->hasPermission('modify', 'extension/openbay/ebay_template')) {
			$this->error['warning'] = $this->language->get('error_permission');
		} else {
			if (isset($this->request->get['template_id'])) {
				$this->model_extension_openbay_ebay_template->delete($this->request->get['template_id']);

				$this->session->data['success'] = $this->language->get('text_deleted');
			}
		}
		$this->response->redirect($this->url->link('extension/openbay/ebay_template/listAll', 'user_token=' . $this->session->data['user_token'], true));
	}

	public function edit() {
		$this->load->model('extension/openbay/ebay_template');

		$data = $this->load->language('extension/openbay/ebay_template');

		$this->document->setTitle($data['heading_title']);

		$data['btn_save']     = $this->url->link('extension/openbay/ebay_template/edit', 'user_token=' . $this->session->data['user_token'], true);
		$data['cancel']       = $this->url->link('extension/openbay/ebay_template/listAll', 'user_token=' . $this->session->data['user_token'], true);

		if ($this->request->post && $this->templateValidate()) {

			$this->session->data['success'] = $data['text_updated'];

			$this->model_extension_openbay_ebay_template->edit($this->request->post['template_id'], $this->request->post);

			$this->response->redirect($this->url->link('extension/openbay/ebay_template/listAll', 'user_token=' . $this->session->data['user_token'], true));
		}

		$this->templateForm($data);
	}

	public function templateForm($data) {
		$this->load->model('extension/openbay/ebay');

		$data['user_token'] = $this->session->data['user_token'];

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->request->get['template_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$template_info = $this->model_extension_openbay_ebay_template->get($this->request->get['template_id']);
			$data['text_manage'] = $this->language->get('text_edit');
		} else {
			$data['text_manage'] = $this->language->get('text_add');
		}

		$this->document->setTitle($data['heading_title']);

        $this->document->addStyle('view/javascript/codemirror/lib/codemirror.css');
        $this->document->addStyle('view/javascript/codemirror/theme/monokai.css');
        $this->document->addScript('view/javascript/codemirror/lib/codemirror.js');
        $this->document->addScript('view/javascript/codemirror/lib/formatting.js');
        $this->document->addScript('view/javascript/codemirror/lib/xml.js');

		$this->document->addScript('view/javascript/openbay/js/faq.js');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true),
			'text' => $this->language->get('text_home'),
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('marketplace/openbay', 'user_token=' . $this->session->data['user_token'], true),
			'text' => 'OpenBay Pro',
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('extension/openbay/ebay', 'user_token=' . $this->session->data['user_token'], true),
			'text' => 'eBay',
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('extension/openbay/ebay_template/listAll', 'user_token=' . $this->session->data['user_token'], true),
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

		$this->response->setOutput($this->load->view('extension/openbay/ebay_template_form', $data));
	}

	private function templateValidate() {
		if (!$this->user->hasPermission('modify', 'extension/openbay/ebay_template')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ($this->request->post['name'] == '') {
			$this->error['warning'] = $this->language->get('error_name');
		}

		return !$this->error;
	}
}
