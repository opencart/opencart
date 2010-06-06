<?php
class ControllerSaleCustomerGroup extends Controller {
	private $error = array();
 
	public function index() {
		$this->load->language('sale/customer_group');
 
		$this->document->title = $this->language->get('heading_title');
 		
		$this->load->model('sale/customer_group');
		
		$this->getList();
	}

	public function insert() {
		$this->load->language('sale/customer_group');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('sale/customer_group');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_sale_customer_group->addCustomerGroup($this->request->post);
			
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
			
			$this->redirect(HTTPS_SERVER . 'index.php?route=sale/customer_group&token=' . $this->session->data['token'] . $url);
		}

		$this->getForm();
	}

	public function update() {
		$this->load->language('sale/customer_group');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('sale/customer_group');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_sale_customer_group->editCustomerGroup($this->request->get['customer_group_id'], $this->request->post);
			
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
			
			$this->redirect(HTTPS_SERVER . 'index.php?route=sale/customer_group&token=' . $this->session->data['token'] . $url);
		}

		$this->getForm();
	}

	public function delete() { 
		$this->load->language('sale/customer_group');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('sale/customer_group');
		
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
      		foreach ($this->request->post['selected'] as $customer_group_id) {
				$this->model_sale_customer_group->deleteCustomerGroup($customer_group_id);	
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
			
			$this->redirect(HTTPS_SERVER . 'index.php?route=sale/customer_group&token=' . $this->session->data['token'] . $url);
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
			$sort = 'name';
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
       		'href'      => HTTPS_SERVER . 'index.php?route=sale/customer_group&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
							
		$this->data['insert'] = HTTPS_SERVER . 'index.php?route=sale/customer_group/insert&token=' . $this->session->data['token'] . $url;
		$this->data['delete'] = HTTPS_SERVER . 'index.php?route=sale/customer_group/delete&token=' . $this->session->data['token'] . $url;	
	
		$this->data['customer_groups'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$customer_group_total = $this->model_sale_customer_group->getTotalCustomerGroups();
		
		$results = $this->model_sale_customer_group->getCustomerGroups($data);

		foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => HTTPS_SERVER . 'index.php?route=sale/customer_group/update&token=' . $this->session->data['token'] . '&customer_group_id=' . $result['customer_group_id'] . $url
			);		
		
			$this->data['customer_groups'][] = array(
				'customer_group_id' => $result['customer_group_id'],
				'name'              => $result['name'] . (($result['customer_group_id'] == $this->config->get('config_customer_group_id')) ? $this->language->get('text_default') : NULL),
				'selected'          => isset($this->request->post['selected']) && in_array($result['customer_group_id'], $this->request->post['selected']),
				'action'            => $action
			);
		}	
	
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_name'] = $this->language->get('column_name');
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

		$this->data['sort_name'] = HTTPS_SERVER . 'index.php?route=sale/customer_group&token=' . $this->session->data['token'] . '&sort=name' . $url;
		
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
				
		$pagination = new Pagination();
		$pagination->total = $customer_group_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = HTTPS_SERVER . 'index.php?route=sale/customer_group&token=' . $this->session->data['token'] . $url . '&page={page}';
		
		$this->data['pagination'] = $pagination->render();				

		$this->data['sort'] = $sort; 
		$this->data['order'] = $order;
		
		$this->template = 'sale/customer_group_list.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
 	}

	private function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['entry_name'] = $this->language->get('entry_name');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_general'] = $this->language->get('tab_general');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['name'])) {
			$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = '';
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
       		'href'      => HTTPS_SERVER . 'index.php?route=sale/customer_group&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
			
		if (!isset($this->request->get['customer_group_id'])) {
			$this->data['action'] = HTTPS_SERVER . 'index.php?route=sale/customer_group/insert&token=' . $this->session->data['token'] . $url;
		} else {
			$this->data['action'] = HTTPS_SERVER . 'index.php?route=sale/customer_group/update&token=' . $this->session->data['token'] . '&customer_group_id=' . $this->request->get['customer_group_id'] . $url;
		}
		  
    	$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=sale/customer_group&token=' . $this->session->data['token'] . $url;

		if (isset($this->request->get['customer_group_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$customer_group_info = $this->model_sale_customer_group->getCustomerGroup($this->request->get['customer_group_id']);
		}

		if (isset($this->request->post['name'])) {
			$this->data['name'] = $this->request->post['name'];
		} elseif (isset($customer_group_info)) {
			$this->data['name'] = $customer_group_info['name'];
		} else {
			$this->data['name'] = '';
		}
			
		$this->template = 'sale/customer_group_form.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression')); 
	}

	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'sale/customer_group')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((strlen(utf8_decode($this->request->post['name'])) < 3) || (strlen(utf8_decode($this->request->post['name'])) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'sale/customer_group')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		$this->load->model('setting/store');
		$this->load->model('sale/customer');
      	
		foreach ($this->request->post['selected'] as $customer_group_id) {
    		if ($this->config->get('config_customer_group_id') == $customer_group_id) {
	  			$this->error['warning'] = $this->language->get('error_default');	
			}  
			
			$store_total = $this->model_setting_store->getTotalStoresByCustomerGroupId($customer_group_id);

			if ($store_total) {
				$this->error['warning'] = sprintf($this->language->get('error_store'), $store_total);
			}
			
			$customer_total = $this->model_sale_customer->getTotalCustomersByCustomerGroupId($customer_group_id);

			if ($customer_total) {
				$this->error['warning'] = sprintf($this->language->get('error_customer'), $customer_total);
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