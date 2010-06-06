<?php
class ControllerCatalogInformation extends Controller { 
	private $error = array();

	public function index() {
		$this->load->language('catalog/information');

		$this->document->title = $this->language->get('heading_title');
		 
		$this->load->model('catalog/information');

		$this->getList();
	}

	public function insert() {
		$this->load->language('catalog/information');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('catalog/information');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_information->addInformation($this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$this->redirect(HTTPS_SERVER . 'index.php?route=catalog/information&token=' . $this->session->data['token'] . $url);
		}

		$this->getForm();
	}

	public function update() {
		$this->load->language('catalog/information');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('catalog/information');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_information->editInformation($this->request->get['information_id'], $this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$this->redirect(HTTPS_SERVER . 'index.php?route=catalog/information&token=' . $this->session->data['token'] . $url);
		}

		$this->getForm();
	}
 
	public function delete() {
		$this->load->language('catalog/information');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('catalog/information');
		
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $information_id) {
				$this->model_catalog_information->deleteInformation($information_id);
			}
			
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$this->redirect(HTTPS_SERVER . 'index.php?route=catalog/information&token=' . $this->session->data['token'] . $url);
		}

		$this->getList();
	}

	private function getList() {
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'id.title';
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}
		
		$url = '';
			
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=catalog/information&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
							
		$this->data['insert'] = HTTPS_SERVER . 'index.php?route=catalog/information/insert&token=' . $this->session->data['token'] . $url;
		$this->data['delete'] = HTTPS_SERVER . 'index.php?route=catalog/information/delete&token=' . $this->session->data['token'] . $url;	

		$this->data['informations'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$information_total = $this->model_catalog_information->getTotalInformations();
	
		$results = $this->model_catalog_information->getInformations($data);
 
    	foreach ($results as $result) {
			$action = array();
						
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => HTTPS_SERVER . 'index.php?route=catalog/information/update&token=' . $this->session->data['token'] . '&information_id=' . $result['information_id'] . $url
			);
						
			$this->data['informations'][] = array(
				'information_id' => $result['information_id'],
				'title'      => $result['title'],
				'sort_order' => $result['sort_order'],
				'selected'   => isset($this->request->post['selected']) && in_array($result['information_id'], $this->request->post['selected']),
				'action'     => $action
			);
		}	
	
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_title'] = $this->language->get('column_title');
		$this->data['column_sort_order'] = $this->language->get('column_sort_order');
		$this->data['column_action'] = $this->language->get('column_action');		
		
		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
 
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
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
		
		$this->data['sort_title'] = HTTPS_SERVER . 'index.php?route=catalog/information&token=' . $this->session->data['token'] . '&sort=id.title' . $url;
		$this->data['sort_sort_order'] = HTTPS_SERVER . 'index.php?route=catalog/information&token=' . $this->session->data['token'] . '&sort=i.sort_order' . $url;
		
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $information_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = HTTPS_SERVER . 'index.php?route=catalog/information&token=' . $this->session->data['token'] . $url . '&page={page}';
			
		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		
		$this->template = 'catalog/information_list.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	private function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_default'] = $this->language->get('text_default');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
    	$this->data['text_disabled'] = $this->language->get('text_disabled');
		
		$this->data['entry_title'] = $this->language->get('entry_title');
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['entry_store'] = $this->language->get('entry_store');
		$this->data['entry_keyword'] = $this->language->get('entry_keyword');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_status'] = $this->language->get('entry_status');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['token'] = $this->session->data['token'];

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['title'])) {
			$this->data['error_title'] = $this->error['title'];
		} else {
			$this->data['error_title'] = '';
		}
		
	 	if (isset($this->error['description'])) {
			$this->data['error_description'] = $this->error['description'];
		} else {
			$this->data['error_description'] = '';
		}
		
		$url = '';
			
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=catalog/information&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
							
		if (!isset($this->request->get['information_id'])) {
			$this->data['action'] = HTTPS_SERVER . 'index.php?route=catalog/information/insert&token=' . $this->session->data['token'] . $url;
		} else {
			$this->data['action'] = HTTPS_SERVER . 'index.php?route=catalog/information/update&token=' . $this->session->data['token'] . '&information_id=' . $this->request->get['information_id'] . $url;
		}
		
		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=catalog/information&token=' . $this->session->data['token'] . $url;

		if (isset($this->request->get['information_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$information_info = $this->model_catalog_information->getInformation($this->request->get['information_id']);
		}
		
		$this->load->model('localisation/language');
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		
		if (isset($this->request->post['information_description'])) {
			$this->data['information_description'] = $this->request->post['information_description'];
		} elseif (isset($this->request->get['information_id'])) {
			$this->data['information_description'] = $this->model_catalog_information->getInformationDescriptions($this->request->get['information_id']);
		} else {
			$this->data['information_description'] = array();
		}

		if (isset($this->request->post['status'])) {
			$this->data['status'] = $this->request->post['status'];
		} elseif (isset($information_info)) {
			$this->data['status'] = $information_info['status'];
		} else {
			$this->data['status'] = 1;
		}
		
		$this->load->model('setting/store');
		
		$this->data['stores'] = $this->model_setting_store->getStores();
		
		if (isset($this->request->post['information_store'])) {
			$this->data['information_store'] = $this->request->post['information_store'];
		} elseif (isset($information_info)) {
			$this->data['information_store'] = $this->model_catalog_information->getInformationStores($this->request->get['information_id']);
		} else {
			$this->data['information_store'] = array(0);
		}		
		
		if (isset($this->request->post['keyword'])) {
			$this->data['keyword'] = $this->request->post['keyword'];
		} elseif (isset($information_info)) {
			$this->data['keyword'] = $information_info['keyword'];
		} else {
			$this->data['keyword'] = '';
		}
		
		if (isset($this->request->post['sort_order'])) {
			$this->data['sort_order'] = $this->request->post['sort_order'];
		} elseif (isset($information_info)) {
			$this->data['sort_order'] = $information_info['sort_order'];
		} else {
			$this->data['sort_order'] = '';
		}
		
		$this->template = 'catalog/information_form.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/information')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['information_description'] as $language_id => $value) {
			if ((strlen(utf8_decode($value['title'])) < 3) || (strlen(utf8_decode($value['title'])) > 32)) {
				$this->error['title'][$language_id] = $this->language->get('error_title');
			}
		
			if (strlen(utf8_decode($value['description'])) < 3) {
				$this->error['description'][$language_id] = $this->language->get('error_description');
			}
		}

		if (!$this->error) {
			return TRUE;
		} else {
			if (!isset($this->error['warning'])) {
				$this->error['warning'] = $this->language->get('error_required_data');
			}
			return FALSE;
		}
	}

	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/information')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('setting/store');
		
		foreach ($this->request->post['selected'] as $information_id) {
			if ($this->config->get('config_account_id') == $information_id) {
				$this->error['warning'] = $this->language->get('error_account');
			}
			
			if ($this->config->get('config_checkout_id') == $information_id) {
				$this->error['warning'] = $this->language->get('error_checkout');
			}
			
			$store_total = $this->model_setting_store->getTotalStoresByInformationId($information_id);

			if ($store_total) {
				$this->error['warning'] = sprintf($this->language->get('error_store'), $store_total);
			}
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
?>