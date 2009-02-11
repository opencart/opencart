<?php 
class ControllerCatalogImage extends Controller { 
	private $error = array();
   
  	public function index() {
		$this->load->language('catalog/image');

    	$this->document->title = $this->language->get('heading_title');
	
		$this->load->model('catalog/image');
	 
    	$this->getList();
  	}
  	        
  	public function insert() {
		$this->load->language('catalog/image');
    
    	$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('catalog/image');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateForm())) {
			$this->model_catalog_image->addImage(array_merge($this->request->post, $this->request->files));
			
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
			
			$this->redirect($this->url->https('catalog/image' . $url));
		}
	
    	$this->getForm();
  	}

  	public function update() {
		$this->load->language('catalog/image');

    	$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('catalog/image');
	
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateForm())) {
			$this->model_catalog_image->editImage($this->request->get['image_id'], array_merge($this->request->post, $this->request->files));
	  		
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
			
			$this->redirect($this->url->https('catalog/image' . $url));
		}

    	$this->getForm();
  	}

  	public function delete() {
		$this->load->language('catalog/image');
 
    	$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('catalog/image');
			
    	if ((isset($this->request->post['delete'])) && ($this->validateDelete())) {	  
			foreach ($this->request->post['delete'] as $image_id) {
				$this->model_catalog_image->deleteImage($image_id);
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
			
			$this->redirect($this->url->https('catalog/image' . $url));
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
       		'href'      => $this->url->https('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('catalog/image' . $url),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
							
		$this->data['insert'] = $this->url->https('catalog/image/insert' . $url);
		$this->data['delete'] = $this->url->https('catalog/image/delete' . $url);	

		$this->data['images'] = array();
		
		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * 10,
			'limit' => 10
		);
		
		$image_total = $this->model_catalog_image->getTotalImages();
	
		$results = $this->model_catalog_image->getImages($data);
 
    	foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => $this->language->get('text_view'),
				'href' => $this->url->https('catalog/image/view&image_id=' . $result['image_id'] . $url)
			);
						
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->https('catalog/image/update&image_id=' . $result['image_id'] . $url)
			);
						
			$this->data['images'][] = array(
				'image_id' => $result['image_id'],
				'title'    => $result['title'],
				'filename' => $result['filename'],
				'delete'   => in_array($result['image_id'], (array)@$this->request->post['delete']),
				'action'   => $action
			);
		}	
	
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_title'] = $this->language->get('column_title');
		$this->data['column_filename'] = $this->language->get('column_filename');
		$this->data['column_action'] = $this->language->get('column_action');		
		
		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
 
		$this->data['error_warning'] = @$this->error['warning'];
		
		$this->data['success'] = @$this->session->data['success'];
		
		unset($this->session->data['success']);

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=' .  'DESC';
		} else {
			$url .= '&order=' .  'ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$this->data['sort_title'] = $this->url->https('catalog/image&sort=id.title' . $url);
		$this->data['sort_filename'] = $this->url->https('catalog/image&sort=i.filename' . $url);
		
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
		$pagination->limit = 10; 
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->https('catalog/image' . $url . '&page=%s');
			
		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->id       = 'content';
		$this->template = 'catalog/image_list.tpl';
		$this->layout   = 'module/layout';
				
		$this->render();
	}
  
  	private function getForm() {
    	$this->data['heading_title'] = $this->language->get('heading_title');
   
    	$this->data['entry_title'] = $this->language->get('entry_title');
    	$this->data['entry_filename'] = $this->language->get('entry_filename');
  
    	$this->data['button_save'] = $this->language->get('button_save');
    	$this->data['button_cancel'] = $this->language->get('button_cancel');
  
    	$this->data['tab_general'] = $this->language->get('tab_general');
    
		$this->data['error_warning'] = @$this->error['warning'];
    	$this->data['error_title'] = @$this->error['title'];
    	$this->data['error_file'] = @$this->error['file'];

   		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('catalog/image'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
			  
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
							
		if (!isset($this->request->get['image_id'])) {
			$this->data['action'] = $this->url->https('catalog/image/insert' . $url);
		} else {
			$this->data['action'] = $this->url->https('catalog/image/update&image_id=' . $this->request->get['image_id'] . $url);
		}
		
		$this->data['cancel'] = $this->url->https('catalog/image' . $url);
 		
		$this->load->model('localisation/language');
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();

    	if ((isset($this->request->get['image_id'])) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$image_info = $this->model_catalog_image->getImage($this->request->get['image_id']);
    	}

		if (isset($this->request->post['image_description'])) {
			$this->data['image_description'] = $this->request->post['image_description'];
		} elseif (isset($this->request->get['image_id'])) {
			$this->data['image_description'] = $this->model_catalog_image->getImageDescriptions($this->request->get['image_id']);
		} else {
			$this->data['image_description'] = array();
		}   	
 
		$this->id       = 'content';
		$this->template = 'catalog/image_form.tpl';
		$this->layout   = 'module/layout';
		
 		$this->render();		
  	}
	
  	private function validateForm() { 
    	if (!$this->user->hasPermission('modify', 'catalog/image')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
	
    	foreach ($this->request->post['image_description'] as $language_id => $value) {
      		if ((strlen($value['title']) < 3) || (strlen($value['title']) > 64)) {
        		$this->error['title'][$language_id] = $this->language->get('error_title');
      		}
    	}
		
		if ((!isset($this->request->get['image_id'])) && (!is_uploaded_file($this->request->files['image']['tmp_name']))) {
			$this->error['file'] = $this->language->get('error_required');
		}
    	
		if (is_uploaded_file($this->request->files['image']['tmp_name'])) {
	  		if ((strlen($this->request->files['image']['name']) < 3) || (strlen($this->request->files['image']['name']) > 128)) {
        		$this->error['file'] = $this->language->get('error_filename');
	  		}
	    	
			if (substr(strrchr($this->request->files['image']['name'], '.'), 1) == 'php') {
       	   		$this->error['file'] = $this->language->get('error_filetype');
       		}
						
			if ($this->request->files['image']['error'] != UPLOAD_ERR_OK) {
				$this->error['warning'] = $this->language->get('error_upload_' . $this->request->files['image']['error']);
			}
			
			if (!$this->error) {
				if (!@move_uploaded_file($this->request->files['image']['tmp_name'], DIR_IMAGE . basename($this->request->files['image']['name']))) {
					@unlink($this->request->files['image']['tmp_name']);

	    			$this->error['file'] = $this->language->get('error_upload');
	  			}
			}	
		}
		
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
  	}

  	private function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'catalog/image')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}	
		
		$this->load->model('catalog/product');
		$this->load->model('catalog/category');
		$this->load->model('catalog/manufacturer');
		
		foreach ($this->request->post['delete'] as $image_id) {
  			$product_total = $this->model_catalog_product->getTotalProductsByImageId($image_id);
    
			if ($product_total) {
	  			$this->error['warning'] = sprintf($this->language->get('error_product'), $product_total);	
			}	
		
  			$category_total = $this->model_catalog_category->getTotalCategoriesByImageId($image_id);
    
			if ($category_total) {
	  			$this->error['warning'] = sprintf($this->language->get('error_category'), $category_total);	
			}	
		
  			$manufacturer_total = $this->model_catalog_manufacturer->getTotalManufacturersByImageId($image_id);
    
			if ($manufacturer_total) {
	  			$this->error['warning'] = sprintf($this->language->get('error_manufacturer'), $manufacturer_total);	
			}	
		}		
						  	  	 
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		} 
  	}
	
	public function view() {
		$this->load->language('catalog/image');
		
		$this->document->title = $this->language->get('heading_title');
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['button_back'] = $this->language->get('button_back');

   		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('catalog/image'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->load->model('catalog/image');
		
		$image_info = $this->model_catalog_image->getImage($this->request->get['image_id']);
		
		$this->data['title'] = $image_info['title'];
		
		$this->load->helper('image');
		
		$this->data['image'] = HelperImage::resize($image_info['filename'], 350, 350);
		
		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
							
		$this->data['back'] = $this->url->https('catalog/image' . $url);
							
		$this->id       = 'content';
		$this->template = 'catalog/image_view.tpl';
		$this->layout   = 'module/layout';
				
		$this->render();		
	}
    
	public function upload() {
		$this->load->language('catalog/image');
		
		$this->load->model('catalog/image');	
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateForm())) {
			$this->model_catalog_image->addImage(array_merge($this->request->post, $this->request->files));
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->redirect($this->url->https('catalog/image/upload'));
		}

		$this->data['title'] = $this->language->get('heading_title');
		$this->data['base'] = (@$this->request->server['HTTPS'] != 'on') ? HTTP_SERVER : HTTPS_SERVER;
		$this->data['direction'] = $this->language->get('direction');
		$this->data['language'] = $this->language->get('code');					
		
		$this->data['action'] = $this->url->https('catalog/image/upload');

    	$this->data['entry_title'] = $this->language->get('entry_title');
    	$this->data['entry_filename'] = $this->language->get('entry_filename');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_close'] = $this->language->get('button_close');

		$this->data['error_warning'] = @$this->error['warning'];
    	$this->data['error_title'] = @$this->error['title'];
    	$this->data['error_file'] = @$this->error['file'];

		$this->data['success'] = @$this->session->data['success'];
		
		unset($this->session->data['success']);
		
		$this->load->model('localisation/language');
								
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
				
		if (isset($this->request->post['image_description'])) {
			$this->data['image_description'] = $this->request->post['image_description'];
		} elseif (isset($this->request->get['image_id'])) {
			$this->data['image_description'] = $this->model_catalog_image->getImageDescription($this->request->get['image_id']);
		} else {
			$this->data['image_description'] = array();
		}   	
  
    	if (isset($this->request->post['filename'])) {
      		$this->data['filename'] = $this->request->post['filename'];
    	} else {
      		$this->data['filename'] = @$image_info['filename'];
    	}
		
		$this->template = 'catalog/image_upload.tpl';
		
 		$this->render();					
	}
	
	public function image() {
		$output = '';
		
		$this->load->model('catalog/image');
		
		$results = $this->model_catalog_image->getImages();
		
		foreach ($results as $result) {
			$output .= '<option value="' . $result['image_id'] . '"';

			if (@$this->request->get['image_id'] == $result['image_id']) {
				$output .= ' selected="selected"';
			}

			$output .= '>' . $result['title'] . '</option>';
		}

		$this->response->setOutput($output);
	}
	
	function thumb() {
		$this->load->model('catalog/image');
		$this->load->helper('image');
		
		$image_info = $this->model_catalog_image->getImage($this->request->get['image_id']);
		
		if ($image_info) {
			$this->response->setOutput('<img src="' . HelperImage::resize($image_info['filename'], 100, 100) . '" title="' . $image_info['title'] . '" />');
		}
	}		
}
?>