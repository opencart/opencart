<?php 
class ControllerToolUpload extends Controller { 
	private $error = array();

	public function index() {
		  $this->load->language('tool/upload');

		  $this->document->setTitle($this->language->get('heading_title'));

		  $this->load->model('tool/upload');

		  $this->getList();
	 }

	public function delete() {
		$this->load->language('tool/upload');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('tool/upload');

		if (isset($this->request->get['upload'])) {
			$selected[] = $this->request->get['upload_id'];
		} else {
			$selected = $this->request->post['selected'];
		}

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($selected as $upload_id) {
				$this->model_tool_upload->deleteUpload($upload_id);
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

			$this->response->redirect($this->url->link('tool/upload', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'ad.name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
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
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('tool/upload', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$data['insert'] = $this->url->link('tool/upload/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['delete'] = $this->url->link('tool/upload/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');	

		$data['uploads'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$upload_total = $this->model_tool_upload->getTotalUploads();

		$results = $this->model_tool_upload->getUploads($filter_data);

		foreach ($results as $result) {
			$data['uploads'][] = array(
				'upload_id'    => $result['upload_id'],
				'name'         => $result['name'],
				'filename' => $result['upload_group'],
				'sort_order'   => $result['sort_order'],
				'edit'         => $this->url->link('tool/upload/update', 'token=' . $this->session->data['token'] . '&upload_id=' . $result['upload_id'] . $url, 'SSL')
			);
		}	

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_name'] = $this->language->get('column_name');
		$data['column_upload_group'] = $this->language->get('column_upload_group');
		$data['column_sort_order'] = $this->language->get('column_sort_order');
		$data['column_action'] = $this->language->get('column_action');		

		$data['button_insert'] = $this->language->get('button_insert');
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

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_name'] = $this->url->link('tool/upload', 'token=' . $this->session->data['token'] . '&sort=ad.name' . $url, 'SSL');
		$data['sort_upload_group'] = $this->url->link('tool/upload', 'token=' . $this->session->data['token'] . '&sort=upload_group' . $url, 'SSL');
		$data['sort_sort_order'] = $this->url->link('tool/upload', 'token=' . $this->session->data['token'] . '&sort=a.sort_order' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $upload_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('tool/upload', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($upload_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($upload_total - $this->config->get('config_limit_admin'))) ? $upload_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $upload_total, $upload_total, ceil($upload_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['menu'] = $this->load->controller('common/menu');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('tool/upload_list.tpl', $data));
	}

	protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_upload_group'] = $this->language->get('entry_upload_group');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = array();
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
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('tool/upload', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		if (!isset($this->request->get['upload_id'])) {
			$data['action'] = $this->url->link('tool/upload/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('tool/upload/update', 'token=' . $this->session->data['token'] . '&upload_id=' . $this->request->get['upload_id'] . $url, 'SSL');
		}

		$data['cancel'] = $this->url->link('tool/upload', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['upload_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$upload_info = $this->model_tool_upload->getUpload($this->request->get['upload_id']);
		}

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['upload_description'])) {
			$data['upload_description'] = $this->request->post['upload_description'];
		} elseif (isset($this->request->get['upload_id'])) {
			$data['upload_description'] = $this->model_tool_upload->getUploadDescriptions($this->request->get['upload_id']);
		} else {
			$data['upload_description'] = array();
		}

		if (isset($this->request->post['upload_group_id'])) {
			$data['upload_group_id'] = $this->request->post['upload_group_id'];
		} elseif (!empty($upload_info)) {
			$data['upload_group_id'] = $upload_info['upload_group_id'];
		} else {
			$data['upload_group_id'] = '';
		}

		$this->load->model('tool/upload_group');

		$data['upload_groups'] = $this->model_tool_upload_group->getUploadGroups();	

		if (isset($this->request->post['sort_order'])) {
			$data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($upload_info)) {
			$data['sort_order'] = $upload_info['sort_order'];
		} else {
			$data['sort_order'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['menu'] = $this->load->controller('common/menu');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('tool/upload_form.tpl', $data));	
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'tool/upload')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['upload_description'] as $language_id => $value) {
			if ((utf8_strlen($value['name']) < 3) || (utf8_strlen($value['name']) > 64)) {
				$this->error['name'][$language_id] = $this->language->get('error_name');
			}
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'tool/upload')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('tool/product');

		foreach ($this->request->post['selected'] as $upload_id) {
			$product_total = $this->model_tool_product->getTotalProductsByUploadId($upload_id);

			if ($product_total) {
				$this->error['warning'] = sprintf($this->language->get('error_product'), $product_total);
			}
		}

		return !$this->error;
	}
}