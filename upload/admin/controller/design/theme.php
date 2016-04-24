<?php
class ControllerDesignTheme extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('design/theme');

		$this->document->setTitle($this->language->get('heading_title'));

		// Codemirror
		$this->document->addStyle('//cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/codemirror.css');
		$this->document->addStyle('//cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/theme/monokai.css');
		$this->document->addScript('//cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/codemirror.js');
		$this->document->addScript('//cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/mode/xml/xml.js');
		$this->document->addScript('//cdnjs.cloudflare.com/ajax/libs/codemirror/2.36.0/formatting.js');
		
		$this->load->model('design/theme');

		$this->getList();
	}

	public function delete() {
		$this->load->language('design/theme');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('design/theme');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $theme_id) {
				$this->model_design_theme->deleteTheme($theme_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('design/theme', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList() {
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('design/theme', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['add'] = $this->url->link('design/theme/add', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('design/theme/delete', 'token=' . $this->session->data['token'] . $url, true);

		$data['themes'] = array();

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_code'] = $this->language->get('column_code');
		$data['column_store'] = $this->language->get('column_store');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('design/theme', $data));
	}

	protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_module'] = $this->language->get('text_module');

		$data['text_default'] = $this->language->get('text_default');
		$data['text_content_top'] = $this->language->get('text_content_top');
		$data['text_content_bottom'] = $this->language->get('text_content_bottom');
		$data['text_column_left'] = $this->language->get('text_column_left');
		$data['text_column_right'] = $this->language->get('text_column_right');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_store'] = $this->language->get('entry_store');
		$data['entry_route'] = $this->language->get('entry_route');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_route_add'] = $this->language->get('button_route_add');
		$data['button_remove'] = $this->language->get('button_remove');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('design/theme', 'token=' . $this->session->data['token'] . $url, true)
		);

		if (!isset($this->request->get['theme_id'])) {
			$data['action'] = $this->url->link('design/theme/add', 'token=' . $this->session->data['token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('design/theme/edit', 'token=' . $this->session->data['token'] . '&theme_id=' . $this->request->get['theme_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('design/theme', 'token=' . $this->session->data['token'] . $url, true);

		if (isset($this->request->get['theme_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$theme_info = $this->model_design_theme->getTheme($this->request->get['theme_id']);
		}

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($theme_info)) {
			$data['name'] = $theme_info['name'];
		} else {
			$data['name'] = '';
		}

		$this->load->model('setting/store');

		$data['stores'] = $this->model_setting_store->getStores();



		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('design/theme_form', $data));
	}

	public function directory() {
		$directory = DIR_CATALOG . 'view/theme/';
		
		if (is_file($directory . $language_info['code'] . '/' . $code . '.php') && substr(str_replace('\\', '/', realpath($directory . $language_info['code'] . '/' . $code . '.php')), 0, strlen($directory)) == $directory) {
			$_ = array();
					
			include($directory . $language_info['code'] . '/' . $code . '.php');	
			
			if (isset($_[$key])) {
				$json = $_[$key];
			}
		}	
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'design/theme')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'design/theme')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}
