<?php    
class ControllerCatalogManufacturer extends Controller { 
	private $error = array();
  
  	public function index() {
		$this->load->language('catalog/manufacturer');
		
		$this->document->title = $this->language->get('heading_title');
		 
		$this->load->model('catalog/manufacturer');
		
    	$this->getList();
  	}
  
  	public function insert() {
		$this->load->language('catalog/manufacturer');

    	$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('catalog/manufacturer');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_manufacturer->addManufacturer($this->request->post);

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
			
			$this->redirect(HTTPS_SERVER . 'index.php?route=catalog/manufacturer&token=' . $this->session->data['token'] . $url);
		}
    
    	$this->getForm();
  	} 
   
  	public function update() {
		$this->load->language('catalog/manufacturer');

    	$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('catalog/manufacturer');
		
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_manufacturer->editManufacturer($this->request->get['manufacturer_id'], $this->request->post);

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
			
			$this->redirect(HTTPS_SERVER . 'index.php?route=catalog/manufacturer&token=' . $this->session->data['token'] . $url);
		}
    
    	$this->getForm();
  	}   

  	public function delete() {
		$this->load->language('catalog/manufacturer');

    	$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('catalog/manufacturer');
			
    	if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $manufacturer_id) {
				$this->model_catalog_manufacturer->deleteManufacturer($manufacturer_id);
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
			
			$this->redirect(HTTPS_SERVER . 'index.php?route=catalog/manufacturer&token=' . $this->session->data['token'] . $url);
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
       		'href'      => HTTPS_SERVER . 'index.php?route=catalog/manufacturer&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
							
		$this->data['insert'] = HTTPS_SERVER . 'index.php?route=catalog/manufacturer/insert&token=' . $this->session->data['token'] . $url;
		$this->data['delete'] = HTTPS_SERVER . 'index.php?route=catalog/manufacturer/delete&token=' . $this->session->data['token'] . $url;	

		$this->data['manufacturers'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$manufacturer_total = $this->model_catalog_manufacturer->getTotalManufacturers();
	
		$results = $this->model_catalog_manufacturer->getManufacturers($data);
 
    	foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => HTTPS_SERVER . 'index.php?route=catalog/manufacturer/update&token=' . $this->session->data['token'] . '&manufacturer_id=' . $result['manufacturer_id'] . $url
			);
						
			$this->data['manufacturers'][] = array(
				'manufacturer_id' => $result['manufacturer_id'],
				'name'            => $result['name'],
				'sort_order'      => $result['sort_order'],
				'selected'        => isset($this->request->post['selected']) && in_array($result['manufacturer_id'], $this->request->post['selected']),
				'action'          => $action
			);
		}	
	
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_name'] = $this->language->get('column_name');
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
		
		$this->data['sort_name'] = HTTPS_SERVER . 'index.php?route=catalog/manufacturer&token=' . $this->session->data['token'] . '&sort=name' . $url;
		$this->data['sort_sort_order'] = HTTPS_SERVER . 'index.php?route=catalog/manufacturer&token=' . $this->session->data['token'] . '&sort=sort_order' . $url;
		
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $manufacturer_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = HTTPS_SERVER . 'index.php?route=catalog/manufacturer&token=' . $this->session->data['token'] . $url . '&page={page}';
			
		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		
		$this->template = 'catalog/manufacturer_list.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
  
  	private function getForm() {
    	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['text_enabled'] = $this->language->get('text_enabled');
    	$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_default'] = $this->language->get('text_default');
    	$this->data['text_image_manager'] = $this->language->get('text_image_manager');
		
		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_store'] = $this->language->get('entry_store');
		$this->data['entry_keyword'] = $this->language->get('entry_keyword');
    	$this->data['entry_image'] = $this->language->get('entry_image');
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
       		'href'      => HTTPS_SERVER . 'index.php?route=catalog/manufacturer&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
							
		if (!isset($this->request->get['manufacturer_id'])) {
			$this->data['action'] = HTTPS_SERVER . 'index.php?route=catalog/manufacturer/insert&token=' . $this->session->data['token'] . $url;
		} else {
			$this->data['action'] = HTTPS_SERVER . 'index.php?route=catalog/manufacturer/update&token=' . $this->session->data['token'] . '&manufacturer_id=' . $this->request->get['manufacturer_id'] . $url;
		}
		
		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=catalog/manufacturer&token=' . $this->session->data['token'] . $url;

		$this->data['token'] = $this->session->data['token'];
		
    	if (isset($this->request->get['manufacturer_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($this->request->get['manufacturer_id']);
    	}

    	if (isset($this->request->post['name'])) {
      		$this->data['name'] = $this->request->post['name'];
    	} elseif (isset($manufacturer_info)) {
			$this->data['name'] = $manufacturer_info['name'];
		} else {	
      		$this->data['name'] = '';
    	}
		
		$this->load->model('setting/store');
		
		$this->data['stores'] = $this->model_setting_store->getStores();
		
		if (isset($this->request->post['manufacturer_store'])) {
			$this->data['manufacturer_store'] = $this->request->post['manufacturer_store'];
		} elseif (isset($manufacturer_info)) {
			$this->data['manufacturer_store'] = $this->model_catalog_manufacturer->getManufacturerStores($this->request->get['manufacturer_id']);
		} else {
			$this->data['manufacturer_store'] = array(0);
		}	
		
		if (isset($this->request->post['keyword'])) {
			$this->data['keyword'] = $this->request->post['keyword'];
		} elseif (isset($manufacturer_info)) {
			$this->data['keyword'] = $manufacturer_info['keyword'];
		} else {
			$this->data['keyword'] = '';
		}

		if (isset($this->request->post['image'])) {
			$this->data['image'] = $this->request->post['image'];
		} elseif (isset($manufacturer_info)) {
			$this->data['image'] = $manufacturer_info['image'];
		} else {
			$this->data['image'] = '';
		}
		
		$this->load->model('tool/image');

		if (isset($manufacturer_info) && $manufacturer_info['image'] && file_exists(DIR_IMAGE . $manufacturer_info['image'])) {
			$this->data['preview'] = $this->model_tool_image->resize($manufacturer_info['image'], 100, 100);
		} else {
			$this->data['preview'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}
		
		if (isset($this->request->post['sort_order'])) {
      		$this->data['sort_order'] = $this->request->post['sort_order'];
    	} elseif (isset($manufacturer_info)) {
			$this->data['sort_order'] = $manufacturer_info['sort_order'];
		} else {
      		$this->data['sort_order'] = '';
    	}
		
		$this->template = 'catalog/manufacturer_form.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}  
	 
  	private function validateForm() {
    	if (!$this->user->hasPermission('modify', 'catalog/manufacturer')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}

    	if ((strlen(utf8_decode($this->request->post['name'])) < 3) || (strlen(utf8_decode($this->request->post['name'])) > 32)) {
      		$this->error['name'] = $this->language->get('error_name');
    	}
		
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
  	}    

  	private function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'catalog/manufacturer')) {
			$this->error['warning'] = $this->language->get('error_permission');
    	}	
		
		$this->load->model('catalog/product');

		foreach ($this->request->post['selected'] as $manufacturer_id) {
  			$product_total = $this->model_catalog_product->getTotalProductsByManufacturerId($manufacturer_id);
    
			if ($product_total) {
	  			$this->error['warning'] = sprintf($this->language->get('error_product'), $product_total);	
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