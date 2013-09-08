<?php
class ControllerExtensionModification extends Controller {
	private $error = array();
   
  	public function index() {
		$this->language->load('extension/modification');
	
    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/modification');
		
    	$this->getList();
  	}
		
  	public function insert() {
		$this->language->load('extension/modification');
	
    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/modification');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
      		$this->model_setting_modification->addModification($this->request->post);
		  	
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
						
      		$this->redirect($this->url->link('extension/modification', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
	
    	$this->getForm();
  	}

  	public function update() {
		$this->language->load('extension/modification');
	
    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/modification');
		
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
	  		$this->model_setting_modification->editModification($this->request->get['modification_id'], $this->request->post);
			
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
			
			$this->redirect($this->url->link('extension/modification', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    	}
	
    	$this->getForm();
  	}

  	public function delete() {
		$this->language->load('extension/modification');
	
    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/modification');
		
    	if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $modification_id) {
				$this->model_setting_modification->deleteModification($modification_id);
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
			
			$this->redirect($this->url->link('extension/modification', 'token=' . $this->session->data['token'] . $url, 'SSL'));
   		}
	
    	$this->getList();
  	}
	
  	public function refresh() {
		$this->language->load('extension/modification');
	
    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/modification');
		
    	if ($this->validateForm()) {
			$this->modification->clear();
			
			$this->modification->load(DIR_SYSTEM . 'modification.xml');
			
			$results = $this->model_setting_modification->getModifications();
			
			foreach ($results as $result) {
				$this->modification->addModification($result['code']);
			}
			
			$this->modification->write();
			
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
			
			$this->redirect($this->url->link('extension/modification', 'token=' . $this->session->data['token'] . $url, 'SSL'));
   		}
	
    	$this->getList();
  	}	
	
  	public function clear() {
		$this->language->load('extension/modification');
	
    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/modification');
		
    	if ($this->validateDelete()) {
			$this->modification->clear();

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
			
			$this->redirect($this->url->link('extension/modification', 'token=' . $this->session->data['token'] . $url, 'SSL'));
   		}
	
    	$this->getList();
  	}	
		
	protected function getList() {
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

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/modification', 'token=' . $this->session->data['token'] . $url, 'SSL')
   		);
		
		$this->data['refresh'] = $this->url->link('extension/modification/refresh', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['clear'] = $this->url->link('extension/modification/clear', 'token=' . $this->session->data['token'] . $url, 'SSL');				
		$this->data['insert'] = $this->url->link('extension/modification/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('extension/modification/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');	

		$this->data['modifications'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$modification_total = $this->model_setting_modification->getTotalModifications();
	
		$results = $this->model_setting_modification->getModifications($data);
 
    	foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'icon' => 'pencil',
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('extension/modification/update', 'token=' . $this->session->data['token'] . '&modification_id=' . $result['modification_id'] . $url, 'SSL')
			);
						
			$this->data['modifications'][] = array(
				'modification_id' => $result['modification_id'],
				'name'            => $result['name'],
				'author'          => $result['author'],
				'status'          => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'sort_order'      => $result['sort_order'],
				'date_added'      => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'date_modified'   => date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
				'selected'        => isset($this->request->post['selected']) && in_array($result['modification_id'], $this->request->post['selected']),
				'action'          => $action
			);
		}			
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_confirm'] = $this->language->get('text_confirm');

		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_author'] = $this->language->get('column_author');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_sort_order'] = $this->language->get('column_sort_order');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_date_modified'] = $this->language->get('column_date_modified');
		$this->data['column_action'] = $this->language->get('column_action');
		
		$this->data['button_refresh'] = $this->language->get('button_refresh');
		$this->data['button_clear'] = $this->language->get('button_clear');
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
		
		$this->data['sort_name'] = $this->url->link('extension/modification', 'token=' . $this->session->data['token'] . '&sort=name' . $url, 'SSL');
		$this->data['sort_author'] = $this->url->link('extension/modification', 'token=' . $this->session->data['token'] . '&sort=author' . $url, 'SSL');
		$this->data['sort_status'] = $this->url->link('extension/modification', 'token=' . $this->session->data['token'] . '&sort=status' . $url, 'SSL');
		$this->data['sort_sort_order'] = $this->url->link('extension/modification', 'token=' . $this->session->data['token'] . '&sort=sort_order' . $url, 'SSL');
		$this->data['sort_date_added'] = $this->url->link('extension/modification', 'token=' . $this->session->data['token'] . '&sort=date_added' . $url, 'SSL');
		$this->data['sort_date_modified'] = $this->url->link('extension/modification', 'token=' . $this->session->data['token'] . '&sort=date_modified' . $url, 'SSL');
		
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $modification_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->url = $this->url->link('extension/modification', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();
		
		$this->data['results'] = sprintf($this->language->get('text_pagination'), ($modification_total) ? (($page - 1) * $this->config->get('config_admin_limit')) + 1 : 0, ((($page - 1) * $this->config->get('config_admin_limit')) > ($modification_total - $this->config->get('config_admin_limit'))) ? $modification_total : ((($page - 1) * $this->config->get('config_admin_limit')) + $this->config->get('config_admin_limit')), $modification_total, ceil($modification_total / $this->config->get('config_admin_limit')));

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		
		$this->template = 'extension/modification_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}
	
  	protected function getForm() {
     	$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		
		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_author'] = $this->language->get('entry_author');
		$this->data['entry_version'] = $this->language->get('entry_version');
    	$this->data['entry_code'] = $this->language->get('entry_code');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');

    	$this->data['button_save'] = $this->language->get('button_save');
    	$this->data['button_cancel'] = $this->language->get('button_cancel');
	
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['name'])) {
			$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = array();
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

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/modification', 'token=' . $this->session->data['token'] . $url, 'SSL')
   		);
		
		if (!isset($this->request->get['modification_id'])) {
			$this->data['action'] = $this->url->link('extension/modification/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('extension/modification/update', 'token=' . $this->session->data['token'] . '&modification_id=' . $this->request->get['modification_id'] . $url, 'SSL');
		}
			
		$this->data['cancel'] = $this->url->link('extension/modification', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['modification_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$modification_info = $this->model_setting_modification->getModification($this->request->get['modification_id']);
		}
		
		$this->data['token'] = $this->session->data['token'];
		
		if (isset($this->request->post['name'])) {
			$this->data['name'] = $this->request->post['name'];
		} elseif (!empty($modification_info)) {
			$this->data['name'] = $modification_info['name'];
		} else {
			$this->data['name'] = '';
		}
		
		if (isset($this->request->post['author'])) {
			$this->data['author'] = $this->request->post['author'];
		} elseif (!empty($modification_info)) {
			$this->data['author'] = $modification_info['author'];
		} else {
			$this->data['author'] = '';
		}

		if (isset($this->request->post['version'])) {
			$this->data['version'] = $this->request->post['version'];
		} elseif (!empty($modification_info)) {
			$this->data['version'] = $modification_info['version'];
		} else {
			$this->data['version'] = '';
		}
						
		if (isset($this->request->post['code'])) {
			$this->data['code'] = $this->request->post['code'];
		} elseif (!empty($modification_info)) {
			$this->data['code'] = $modification_info['code'];
		} else {
			$this->data['code'] = '';
		}
				
		if (isset($this->request->post['status'])) {
			$this->data['status'] = $this->request->post['status'];
		} elseif (!empty($modification_info)) {
			$this->data['status'] = $modification_info['status'];
		} else {
			$this->data['status'] = '';
		}	
		
		if (isset($this->request->post['sort_order'])) {
			$this->data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($modification_info)) {
			$this->data['sort_order'] = $modification_info['sort_order'];
		} else {
			$this->data['sort_order'] = '';
		}	
						
		$this->template = 'extension/modification_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());	
  	}
	
	protected function validateForm() {
    	if (!$this->user->hasPermission('modify', 'extension/modification')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
		
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}

  	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'extension/modification')) {
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
