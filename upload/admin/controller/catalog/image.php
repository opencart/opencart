<?php 
class ControllerCatalogImage extends Controller { 
	  private $error = array();
	 
	  public function index() {
		  $this->language->load('catalog/image');
	  
		  $this->document->setTitle($this->language->get('heading_title'));
		  
		  $this->load->model('catalog/image');
		  
		  $this->getList();
	  }
              
  	public function insert() {
		$this->language->load('catalog/image');
	
    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('catalog/image');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
      		$this->model_catalog_image->addImage($this->request->post);
		  	
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
						
      		$this->redirect($this->url->link('catalog/image', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
	
    	$this->getForm();
  	}

  	public function update() {
		$this->language->load('catalog/image');
	
    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('catalog/image');
		
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
	  		$this->model_catalog_image->editImage($this->request->get['image_id'], $this->request->post);
			
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
			
			$this->redirect($this->url->link('catalog/image', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    	}
	
    	$this->getForm();
  	}

  	public function delete() {
		$this->language->load('catalog/image');
	
    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('catalog/image');
		
    	if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $image_id) {
				$this->model_catalog_image->deleteImage($image_id);
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
			
			$this->redirect($this->url->link('catalog/image', 'token=' . $this->session->data['token'] . $url, 'SSL'));
   		}
	
    	$this->getList();
  	}
    
  	protected function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'id.name';
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
			'href' => $this->url->link('catalog/image', 'token=' . $this->session->data['token'] . $url, 'SSL')
   		);
							
		$this->data['insert'] = $this->url->link('catalog/image/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('catalog/image/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');	

		$this->data['images'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$this->load->model('tool/image');
		
		$image_total = $this->model_catalog_image->getTotalImages();
	
		$results = $this->model_catalog_image->getImages($data);
 
    	foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'icon' => 'pencil',
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('catalog/image/update', 'token=' . $this->session->data['token'] . '&image_id=' . $result['image_id'] . $url, 'SSL')
			);
			
			if (is_file(DIR_IMAGE . $result['filename'])) {
				$image = $this->model_tool_image->resize($result['filename'], 40, 40);
			} else {
				$image = $this->model_tool_image->resize('no_image.jpg', 40, 40);
			}
									
			$this->data['images'][] = array(
				'image_id'      => $result['image_id'],
				'image'         => $image,
				'name'          => $result['name'],
				'date_added'    => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'date_modified' => date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
				'selected'      => isset($this->request->post['selected']) && in_array($result['image_id'], $this->request->post['selected']),
				'action'        => $action
			);
		}	
	
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_confirm'] = $this->language->get('text_confirm');

		$this->data['column_image'] = $this->language->get('column_image');
		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_date_modified'] = $this->language->get('column_date_modified');
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
		
		$this->data['sort_name'] = $this->url->link('catalog/image', 'token=' . $this->session->data['token'] . '&sort=id.name' . $url, 'SSL');
		$this->data['sort_date_added'] = $this->url->link('catalog/image', 'token=' . $this->session->data['token'] . '&sort=i.date_added' . $url, 'SSL');
		$this->data['sort_date_modified'] = $this->url->link('catalog/image', 'token=' . $this->session->data['token'] . '&sort=i.date_modified' . $url, 'SSL');
		
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $image_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->url = $this->url->link('catalog/image', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();
		
		$this->data['results'] = sprintf($this->language->get('text_pagination'), ($image_total) ? (($page - 1) * $this->config->get('config_admin_limit')) + 1 : 0, ((($page - 1) * $this->config->get('config_admin_limit')) > ($image_total - $this->config->get('config_admin_limit'))) ? $image_total : ((($page - 1) * $this->config->get('config_admin_limit')) + $this->config->get('config_admin_limit')), $image_total, $image_total, ceil($image_total / $this->config->get('config_admin_limit')));

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'catalog/image_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
  	}
  
  	protected function getForm() {
     	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_image'] = $this->language->get('entry_image');
		$this->data['entry_filename'] = $this->language->get('entry_filename');
		$this->data['entry_tag'] = $this->language->get('entry_tag');

		$this->data['help_filename'] = $this->language->get('help_filename');
		$this->data['help_tag'] = $this->language->get('help_tag');

    	$this->data['button_save'] = $this->language->get('button_save');
    	$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_upload'] = $this->language->get('button_upload');
     
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
		
 		if (isset($this->error['tag'])) {
			$this->data['error_tag'] = $this->error['tag'];
		} else {
			$this->data['error_tag'] = '';
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
			'href' => $this->url->link('catalog/image', 'token=' . $this->session->data['token'] . $url, 'SSL')
   		);
		
		if (!isset($this->request->get['image_id'])) {
			$this->data['action'] = $this->url->link('catalog/image/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('catalog/image/update', 'token=' . $this->session->data['token'] . '&image_id=' . $this->request->get['image_id'] . $url, 'SSL');
		}
			
		$this->data['cancel'] = $this->url->link('catalog/image', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['image_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$image_info = $this->model_catalog_image->getImage($this->request->get['image_id']);
		}

  		$this->data['token'] = $this->session->data['token'];
				
		$this->load->model('localisation/language');
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		
		if (isset($this->request->post['image_description'])) {
			$this->data['image_description'] = $this->request->post['image_description'];
		} elseif (isset($this->request->get['image_id'])) {
			$this->data['image_description'] = $this->model_catalog_image->getImageDescriptions($this->request->get['image_id']);
		} else {
			$this->data['image_description'] = array();
		}

		if (isset($this->request->post['filename'])) {
			$this->data['filename'] = $this->request->post['filename'];
		} elseif (!empty($image_info)) {
			$this->data['filename'] = $image_info['filename'];
		} else {
			$this->data['filename'] = '';
		}
		
		$this->load->model('tool/image');
		
		if (isset($this->request->post['filename']) && is_file(DIR_IMAGE . $this->request->post['filename'])) {
			$this->data['thumb'] = $this->model_tool_image->resize($this->request->post['filename'], 100, 100);
		} elseif (!empty($image_info) && $image_info['filename'] && is_file(DIR_IMAGE . $image_info['filename'])) {
			$this->data['thumb'] = $this->model_tool_image->resize($image_info['filename'], 100, 100);
		} else {
			$this->data['thumb'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}
				
		if (isset($this->request->post['tag'])) {
			$this->data['tag'] = $this->request->post['tag'];
		} elseif (!empty($image_info)) {
			$this->data['tag'] = $image_info['tag'];
		} else {
			$this->data['tag'] = '';
		}
			
		$this->template = 'catalog/image_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());	
  	}
  	
	protected function validateForm() {
    	if (!$this->user->hasPermission('modify', 'catalog/image')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
	
    	foreach ($this->request->post['image_description'] as $language_id => $value) {
      		if ((utf8_strlen($value['name']) < 3) || (utf8_strlen($value['name']) > 64)) {
        		$this->error['name'][$language_id] = $this->language->get('error_name');
      		}
    	}
		
		if (!$this->request->post['tag']) {
			$this->error['tag'] = $this->language->get('error_tag');
		}
		
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}

  	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/image')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
		
		$this->load->model('catalog/category');
		
		$this->load->model('catalog/product');
		
		foreach ($this->request->post['selected'] as $image_id) {
			$category_total = $this->model_catalog_category->getTotalCategoriesByImageId($image_id);

			if ($category_total) {
				$this->error['warning'] = sprintf($this->language->get('error_category'), $category_total);
			}
						
			$product_total = $this->model_catalog_product->getTotalProductsByImageId($image_id);

			if ($product_total) {
				$this->error['warning'] = sprintf($this->language->get('error_product'), $product_total);
			}
	  	}
		
		if (!$this->error) { 
	  		return true;
		} else {
	  		return false;
		}
  	}
	
	public function upload() {
		$this->language->load('catalog/image');
		
		$json = array();
    	
		if (!$this->user->hasPermission('modify', 'catalog/image')) {
      		$json['error'] = $this->language->get('error_permission');
    	}	
		
		if (!$json) {
			if (!empty($this->request->files['file']['name'])) {
				// Sanitize the filename
				$filename = basename(html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8'));
				
				// Validate the filename length
				if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 255)) {
					$json['error'] = $this->language->get('error_filename');
				}	  	
				
				// Allowed file extension types
				$allowed = array(
					'jpg',
					'jpeg',
					'gif',
					'png'
				);
								
				if (!in_array(substr(strrchr(strtolower($filename), '.'), 1), $allowed)) {
					$json['error'] = $this->language->get('error_filetype');
				}	
				
				// Allowed file mime types		
				$allowed = array(
					'image/jpeg',
					'image/pjpeg',
					'image/png',
					'image/x-png',
					'image/gif'
				);
								
				if (!in_array($this->request->files['file']['type'], $allowed)) {
					$json['error'] = $this->language->get('error_filetype');
				}
				
				// Return any upload error			
				if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
					$json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
				}
			} else {
				$json['error'] = $this->language->get('error_upload');
			}
		}
		
		// Create directory to put image in
		if (!$json) {
			if (!is_dir(DIR_IMAGE . date('Y'))) {
				mkdir(DIR_IMAGE . date('Y'));
			} 
			
			if (!is_dir(DIR_IMAGE . date('Y') . '/' . date('m'))) {
				mkdir(DIR_IMAGE . date('Y') . '/' . date('m'));
			}
			
			if (!is_dir(DIR_IMAGE . date('Y') . '/' . date('m'))) {
				$json['directory'] = sprintf($this->language->get('error_directory'), date('Y') . '/' . date('m'));
			}		
		}
		
		if (!$json && is_uploaded_file($this->request->files['file']['tmp_name'])) {
			move_uploaded_file($this->request->files['file']['tmp_name'], DIR_IMAGE . date('Y') . '/' . date('m') . '/' . $filename);
		
			$json['filename'] = date('Y') . '/' . date('m') . '/' . $filename;
			
			$this->load->model('tool/image');
			
			$json['thumb'] = $this->model_tool_image->resize(date('Y') . '/' . date('m') . '/' . $filename, 100, 100);

			$json['success'] = $this->language->get('text_upload');
		}
						
		$this->response->setOutput(json_encode($json));
	}
	
	public function autocomplete() {
		$json = array();
		
		if (isset($this->request->get['filter_name'])) {
			$this->load->model('catalog/image');
			
			$data = array(
				'filter_name' => $this->request->get['filter_name'],
				'start'       => 0,
				'limit'       => 5
			);
			
			$json = array();
			
			$results = $this->model_catalog_image->getAttributes($data);
			
			foreach ($results as $result) {
				$json[] = array(
					'image_id' => $result['image_id'], 
					'name'     => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
				);		
			}		
		}

		$sort_order = array();
	  
		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->setOutput(json_encode($json));
	}		  
}
?>