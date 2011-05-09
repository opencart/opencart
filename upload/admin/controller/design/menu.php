<?php 
class ControllerDesignMenu extends Controller {
	private $error = array();
 
	public function index() {
		$this->load->language('design/menu');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('design/menu');
		
		$this->getList();
	}

	public function insert() {
		$this->load->language('design/menu');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('design/menu');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_design_menu->addMenu($this->request->post);
			
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
			
			$this->redirect($this->url->link('design/menu', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function update() {
		$this->load->language('design/menu');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('design/menu');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_design_menu->editMenu($this->request->get['menu_id'], $this->request->post);

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
					
			$this->redirect($this->url->link('design/menu', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}
 
	public function delete() {
		$this->load->language('design/menu');
 
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('design/menu');
		
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $menu_id) {
				$this->model_design_menu->deleteMenu($menu_id);
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

			$this->redirect($this->url->link('design/menu', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	private function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'md.name';
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
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('design/menu', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['insert'] = $this->url->link('design/menu/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('design/menu/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
		 
		$this->data['menus'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$menu_total = $this->model_design_menu->getTotalMenus();
		
		$results = $this->model_design_menu->getMenus($data);
		
		foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('design/menu/update', 'token=' . $this->session->data['token'] . '&menu_id=' . $result['menu_id'] . $url, 'SSL')
			);

			$this->data['menus'][] = array(
				'menu_id'    => $result['menu_id'],
				'name'       => $result['name'],	
				'store'      => $result['store'],	
				'sort_order' => $result['sort_order'],				
				'selected'   => isset($this->request->post['selected']) && in_array($result['menu_id'], $this->request->post['selected']),				
				'action'     => $action
			);
		}

		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		
		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_store'] = $this->language->get('column_store');
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
		
		$this->data['sort_name'] = $this->url->link('design/menu', 'token=' . $this->session->data['token'] . '&sort=md.name' . $url, 'SSL');
		$this->data['sort_store'] = $this->url->link('design/menu', 'token=' . $this->session->data['token'] . '&sort=store' . $url, 'SSL');
		$this->data['sort_sort_order'] = $this->url->link('design/menu', 'token=' . $this->session->data['token'] . '&sort=m.sort_order' . $url, 'SSL');
		
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $menu_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('design/menu', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();
		
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'design/menu_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer',
		);
				
		$this->response->setOutput($this->render());
	}

	private function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_default'] = $this->language->get('text_default');
				
		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_store'] = $this->language->get('entry_store');
		$this->data['entry_heading'] = $this->language->get('entry_heading');
		$this->data['entry_link'] = $this->language->get('entry_link');
		$this->data['entry_column'] = $this->language->get('entry_column');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_link'] = $this->language->get('button_add_link');
		$this->data['button_remove'] = $this->language->get('button_remove');

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
		
 		if (isset($this->error['column'])) {
			$this->data['error_column'] = $this->error['column'];
		} else {
			$this->data['error_column'] = '';
		}
		
 		if (isset($this->error['menu_link'])) {
			$this->data['error_menu_link'] = $this->error['menu_link'];
		} else {
			$this->data['error_menu_link'] = array();
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
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('design/menu', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);
							
		if (!isset($this->request->get['menu_id'])) { 
			$this->data['action'] = $this->url->link('design/menu/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('design/menu/update', 'token=' . $this->session->data['token'] . '&menu_id=' . $this->request->get['menu_id'] . $url, 'SSL');
		}
		
		$this->data['cancel'] = $this->url->link('design/menu', 'token=' . $this->session->data['token'] . $url, 'SSL');
		
		if (isset($this->request->get['menu_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$menu_info = $this->model_design_menu->getMenu($this->request->get['menu_id']);
		}
				
		$this->load->model('localisation/language');
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		
		if (isset($this->request->post['menu_description'])) {
			$this->data['menu_description'] = $this->request->post['menu_description'];
		} elseif (isset($this->request->get['menu_id'])) {
			$this->data['menu_description'] = $this->model_design_menu->getMenuDescriptions($this->request->get['menu_id']);
		} else {
			$this->data['menu_description'] = array();
		}	
		
		if (isset($this->request->post['store_id'])) {
			$this->data['store_id'] = $this->request->post['store_id'];
		} elseif (isset($menu_info)) {
			$this->data['store_id'] = $menu_info['store_id'];
		} else {
			$this->data['store_id'] = 0;
		}
				
		if (isset($this->request->post['link'])) {
			$this->data['link'] = $this->request->post['link'];
		} elseif (isset($menu_info)) {
			$this->data['link'] = $menu_info['link'];
		} else {
			$this->data['link'] = '';
		}
		
		if (isset($this->request->post['column'])) {
			$this->data['column'] = $this->request->post['column'];
		} elseif (isset($menu_info)) {
			$this->data['column'] = $menu_info['column'];
		} else {
			$this->data['column'] = 1;
		}
		
		if (isset($this->request->post['sort_order'])) {
			$this->data['sort_order'] = $this->request->post['sort_order'];
		} elseif (isset($menu_info)) {
			$this->data['sort_order'] = $menu_info['sort_order'];
		} else {
			$this->data['sort_order'] = 0;
		}
		
		if (isset($this->request->post['menu_link'])) {
			$this->data['menu_links'] = $this->request->post['menu_link'];
		} elseif (isset($this->request->get['menu_id'])) {
			$this->data['menu_links'] = $this->model_design_menu->getMenuLinks($this->request->get['menu_id']);
		} else {
			$this->data['menu_links'] = array();
		}
	
		$this->template = 'design/menu_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer',
		);
				
		$this->response->setOutput($this->render());
	}

	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'design/menu')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['menu_description'] as $language_id => $value) {
			if ((strlen(utf8_decode($value['name'])) < 3) || (strlen(utf8_decode($value['name'])) > 255)) {
				$this->error['name'][$language_id] = $this->language->get('error_name');
			}
		}
		
		if (!$this->request->post['column']) {
			$this->error['column'] = $this->language->get('error_column');
		}
					
		if (isset($this->request->post['menu_link'])) {
			foreach ($this->request->post['menu_link'] as $menu_link_id => $menu_link) {
				foreach ($menu_link['menu_link_description'] as $language_id => $menu_link_description) {
					if ((strlen(utf8_decode($menu_link_description['name'])) < 2) || (strlen(utf8_decode($menu_link_description['name'])) > 255)) {
						$this->error['menu_link'][$menu_link_id][$language_id] = $this->language->get('error_name'); 
					}					
				}
			}	
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'design/menu')) {
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