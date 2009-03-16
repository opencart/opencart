<?php 
class ControllerCatalogProduct extends Controller {
	private $error = array(); 
     
  	public function index() {
		$this->load->language('catalog/product');
    	
		$this->document->title = $this->language->get('heading_title'); 
		
		$this->load->model('catalog/product');
		
		$this->getList();
  	}
  
  	public function insert() {
    	$this->load->language('catalog/product');

    	$this->document->title = $this->language->get('heading_title'); 
		
		$this->load->model('catalog/product');
		
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateForm())) {
			$this->model_catalog_product->addProduct($this->request->post);
	  		
			$this->session->data['success'] = $this->language->get('text_success');
	  
			$url = '';
			
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}
		
			if (isset($this->request->get['filter_model'])) {
				$url .= '&filter_model=' . $this->request->get['filter_model'];
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}
					
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$this->redirect($this->url->https('catalog/product' . $url));
    	}
	
    	$this->getForm();
  	}

  	public function update() {
    	$this->load->language('catalog/product');

    	$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('catalog/product');
	
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateForm())) {
			$this->model_catalog_product->editProduct($this->request->get['product_id'], $this->request->post);
		
			$this->session->data['success'] = $this->language->get('text_success');
	  		
			$url = '';
			
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}
		
			if (isset($this->request->get['filter_model'])) {
				$url .= '&filter_model=' . $this->request->get['filter_model'];
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}
					
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$this->redirect($this->url->https('catalog/product' . $url));
		
		}

    	$this->getForm();
  	}

  	public function delete() {
    	$this->load->language('catalog/product');

    	$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('catalog/product');
		
		if ((isset($this->request->post['delete'])) && ($this->validateDelete())) {
			foreach ($this->request->post['delete'] as $product_id) {
				$this->model_catalog_product->deleteProduct($product_id);
	  		}
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$url = '';
			
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}
		
			if (isset($this->request->get['filter_model'])) {
				$url .= '&filter_model=' . $this->request->get['filter_model'];
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}
					
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$this->redirect($this->url->https('catalog/product' . $url));
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
			$sort = 'pd.name';
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		$url = '';
						
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		
		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . $this->request->get['filter_model'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
						
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
       		'href'      => $this->url->https('catalog/product' . $url),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
				
		$this->data['insert'] = $this->url->https('catalog/product/insert' . $url);
		$this->data['delete'] = $this->url->https('catalog/product/delete' . $url);
										
    	$this->data['products'] = array();

		$data = array(
			'name'	 => @$this->request->get['filter_name'], 
			'model'	 => @$this->request->get['filter_model'], 
			'status' => @$this->request->get['filter_status'],
			'sort'   => $sort,
			'order'  => $order,
			'start'  => ($page - 1) * 10,
			'limit'  => 10
		);
		
		$product_total = $this->model_catalog_product->getTotalProducts($data);
			
		$results = $this->model_catalog_product->getProducts($data);
				    	
		foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->https('catalog/product/update&product_id=' . $result['product_id'] . $url)
			);
			
      		$this->data['products'][] = array(
				'product_id' => $result['product_id'],
				'name'       => $result['name'],
				'model'      => $result['model'],
				'status'     => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'sort_order' => $result['sort_order'],
				'delete'     => in_array($result['product_id'], (array)@$this->request->post['delete']),
				'action'     => $action
			);
    	}
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_name'] = $this->language->get('column_name');
    	$this->data['column_model'] = $this->language->get('column_model');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_sort_order'] = $this->language->get('column_sort_order');
		$this->data['column_action'] = $this->language->get('column_action');

		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['button_filter'] = $this->language->get('button_filter');
 
		$this->data['error_warning'] = @$this->error['warning'];

		$this->data['success'] = @$this->session->data['success'];
		
		unset($this->session->data['success']);

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		
		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . $this->request->get['filter_model'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
								
		if ($order == 'ASC') {
			$url .= '&order=' .  'DESC';
		} else {
			$url .= '&order=' .  'ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
					
		$this->data['sort_name'] = $this->url->https('catalog/product&sort=pd.name' . $url);
		$this->data['sort_model'] = $this->url->https('catalog/product&sort=p.model' . $url);
		$this->data['sort_status'] = $this->url->https('catalog/product&sort=p.status' . $url);
		$this->data['sort_order'] = $this->url->https('catalog/product&sort=p.sort_order' . $url);
		
		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		
		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . $this->request->get['filter_model'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
				
		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = 10; 
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->https('catalog/product' . $url . '&page=%s');
			
		$this->data['pagination'] = $pagination->render();
	
		$this->data['filter_name'] = @$this->request->get['filter_name'];
		$this->data['filter_model'] = @$this->request->get['filter_model'];
		$this->data['filter_status'] = @$this->request->get['filter_status'];
		
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->id       = 'content';
		$this->template = 'catalog/product_list.tpl';
		$this->layout   = 'module/layout';
				
		$this->render();
  	}

  	private function getForm() {
    	$this->data['heading_title'] = $this->language->get('heading_title');
 
    	$this->data['text_enabled'] = $this->language->get('text_enabled');
    	$this->data['text_disabled'] = $this->language->get('text_disabled');
    	$this->data['text_none'] = $this->language->get('text_none');
    	$this->data['text_yes'] = $this->language->get('text_yes');
    	$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_plus'] = $this->language->get('text_plus');
		$this->data['text_minus'] = $this->language->get('text_minus');

		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_description'] = $this->language->get('entry_description');
    	$this->data['entry_model'] = $this->language->get('entry_model');
		$this->data['entry_manufacturer'] = $this->language->get('entry_manufacturer');
    	$this->data['entry_shipping'] = $this->language->get('entry_shipping');
    	$this->data['entry_date_available'] = $this->language->get('entry_date_available');
    	$this->data['entry_quantity'] = $this->language->get('entry_quantity');
		$this->data['entry_stock_status'] = $this->language->get('entry_stock_status');
    	$this->data['entry_status'] = $this->language->get('entry_status');
    	$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
    	$this->data['entry_tax_class'] = $this->language->get('entry_tax_class');
    	$this->data['entry_price'] = $this->language->get('entry_price');
    	$this->data['entry_weight_class'] = $this->language->get('entry_weight_class');
    	$this->data['entry_weight'] = $this->language->get('entry_weight');
    	$this->data['entry_image'] = $this->language->get('entry_image');
    	$this->data['entry_download'] = $this->language->get('entry_download');
    	$this->data['entry_category'] = $this->language->get('entry_category');
		$this->data['entry_option'] = $this->language->get('entry_option');
		$this->data['entry_option_value'] = $this->language->get('entry_option_value');
		$this->data['entry_prefix'] = $this->language->get('entry_prefix');
		$this->data['entry_discount'] = $this->language->get('entry_discount');
		
    	$this->data['button_save'] = $this->language->get('button_save');
    	$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_option'] = $this->language->get('button_add_option');
		$this->data['button_add_option_value'] = $this->language->get('button_add_option_value');
		$this->data['button_add_discount'] = $this->language->get('button_add_discount');
		$this->data['button_add_image'] = $this->language->get('button_add_image');
		$this->data['button_remove'] = $this->language->get('button_remove');
		
    	$this->data['tab_general'] = $this->language->get('tab_general');
    	$this->data['tab_data'] = $this->language->get('tab_data');
		$this->data['tab_discount'] = $this->language->get('tab_discount');
		$this->data['tab_option'] = $this->language->get('tab_option');
    	$this->data['tab_image'] = $this->language->get('tab_image');
 
    	$this->data['error_warning'] = @$this->error['warning'];
    	$this->data['error_name'] = @$this->error['name'];
    	$this->data['error_description'] = @$this->error['description'];
    	$this->data['error_model'] = @$this->error['model'];
		$this->data['error_date_available'] = @$this->error['date_available'];

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		
		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . $this->request->get['filter_model'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
								
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
       		'href'      => $this->url->https('catalog/product' . $url),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
									
		if (!isset($this->request->get['product_id'])) {
			$this->data['action'] = $this->url->https('catalog/product/insert' . $url);
		} else {
			$this->data['action'] = $this->url->https('catalog/product/update&product_id=' . $this->request->get['product_id'] . $url);
		}
		
		$this->data['cancel'] = $this->url->https('catalog/product' . $url);

		if ((isset($this->request->get['product_id'])) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$product_info = $this->model_catalog_product->getProduct($this->request->get['product_id']);
    	}

		$this->load->model('localisation/language');
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		
		if (isset($this->request->post['product_description'])) {
			$this->data['product_description'] = $this->request->post['product_description'];
		} elseif (isset($product_info)) {
			$this->data['product_description'] = $this->model_catalog_product->getProductDescriptions($this->request->get['product_id']);
		} else {
			$this->data['product_description'] = array();
		}
		
		if (isset($this->request->post['model'])) {
      		$this->data['model'] = $this->request->post['model'];
    	} else {
      		$this->data['model'] = @$product_info['model'];
    	}

		if (isset($this->request->post['image'])) {
			$this->data['image'] = $this->request->post['image'];
		} else {
			$this->data['image'] = $product_info['image'];
		}
		
		$this->load->helper('image');
		
		if (isset($this->request->post['image'])) {
			$this->data['preview'] = HelperImage::resize($this->request->post['image'], 100, 100);
		} elseif (@$product_info['image']) {
			$this->data['preview'] = HelperImage::resize($product_info['image'], 100, 100);
		} else {
			$this->data['preview'] = HelperImage::resize('no_image.jpg', 100, 100);
		}
		
		$this->data['no_image'] = HelperImage::resize('no_image.jpg', 100, 100);
	
		$this->load->model('catalog/manufacturer');
		
    	$this->data['manufacturers'] = $this->model_catalog_manufacturer->getManufacturers();

    	if (isset($this->request->post['manufacturer_id'])) {
      		$this->data['manufacturer_id'] = $this->request->post['manufacturer_id'];
    	} else {
      		$this->data['manufacturer_id'] = @$product_info['manufacturer_id'];
    	} 
		
    	if (isset($this->request->post['shipping'])) {
      		$this->data['shipping'] = $this->request->post['shipping'];
    	} else {
      		$this->data['shipping'] = @$product_info['shipping'];
    	}
      	
		if (isset($this->request->post['date_available'])) {
       		$this->data['date_available'] = $this->request->post['date_available'];
		} elseif (@$product_info['date_available']) {
			$this->data['date_available'] = date('Y-m-d', strtotime($product_info['date_available']));
		} else {
			$this->data['date_available'] = date('Y-m-d', time());
		}
											
    	if (isset($this->request->post['quantity'])) {
      		$this->data['quantity'] = $this->request->post['quantity'];
    	} else {
      		$this->data['quantity'] = @$product_info['quantity'];
    	}

		$this->load->model('localisation/stock_status');
		
		$this->data['stock_statuses'] = $this->model_localisation_stock_status->getStockStatuses();
    	
		if (isset($this->request->post['stock_status_id'])) {
      		$this->data['stock_status_id'] = $this->request->post['stock_status_id'];
    	} else if (isset($product_info['stock_status_id'])) {
      		$this->data['stock_status_id'] = $product_info['stock_status_id'];
    	} else {
			$this->data['stock_status_id'] = $this->config->get('config_stock_status_id');
		}
		
    	if (isset($this->request->post['price'])) {
      		$this->data['price'] = $this->request->post['price'];
    	} else {
      		$this->data['price'] = @$product_info['price'];
    	}
  
    	if (isset($this->request->post['sort_order'])) {
      		$this->data['sort_order'] = $this->request->post['sort_order'];
    	} else {
      		$this->data['sort_order'] = @$product_info['sort_order'];
    	}

    	if (isset($this->request->post['status'])) {
      		$this->data['status'] = $this->request->post['status'];
    	} else {
      		$this->data['status'] = @$product_info['status'];
    	}
		
		$this->load->model('localisation/tax_class');
		
		$this->data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();
    	
		if (isset($this->request->post['tax_class_id'])) {
      		$this->data['tax_class_id'] = $this->request->post['tax_class_id'];
    	} else {
      		$this->data['tax_class_id'] = @$product_info['tax_class_id'];
    	}

    	if (isset($this->request->post['weight'])) {
      		$this->data['weight'] = $this->request->post['weight'];
    	} else {
      		$this->data['weight'] = @$product_info['weight'];
    	} 
		
		$this->load->model('localisation/weight_class');
		
		$this->data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();
    	
		if (isset($this->request->post['weight_class_id'])) {
      		$this->data['weight_class_id'] = $this->request->post['weight_class_id'];
    	} elseif (isset($product_info['weight_class_id'])) {
      		$this->data['weight_class_id'] = $product_info['weight_class_id'];
    	} else {
      		$this->data['weight_class_id'] = $this->config->get('config_weight_class_id');
    	}

		if (isset($this->request->post['product_option'])) {
			$this->data['product_options'] = $this->request->post['product_option'];
		} elseif (isset($product_info)) {
			$this->data['product_options'] = $this->model_catalog_product->getProductOptions($this->request->get['product_id']);
		} else {
			$this->data['product_options'] = array();
		}
		
		if (isset($this->request->post['product_discount'])) {
			$this->data['product_discounts'] = $this->request->post['product_discount'];
		} elseif (isset($product_info)) {
			$this->data['product_discounts'] = $this->model_catalog_product->getProductDiscounts($this->request->get['product_id']);
		} else {
			$this->data['product_discounts'] = array();
		}

		$this->data['product_images'] = array();
		
		if (isset($this->request->post['product_image'])) {
			foreach ($this->request->post['product_image'] as $image) {
				$this->data['product_images'][] = array(
					'file'  => $image,
					'image' => HelperImage::resize($image, 100, 100)
				);
			}
		} elseif (isset($product_info)) {
			$results = $this->model_catalog_product->getProductImages($this->request->get['product_id']);
			
			foreach ($results as $result) {
				$this->data['product_images'][] = array(
					'file'  => $result,
					'image' => HelperImage::resize($result, 100, 100)
				);
			}
		}		

		$this->load->model('catalog/download');
		
		$this->data['downloads'] = $this->model_catalog_download->getDownloads();
		
		if (isset($this->request->post['product_download'])) {
			$this->data['product_download'] = (array)@$this->request->post['product_download'];
		} elseif (isset($product_info)) {
			$this->data['product_download'] = $this->model_catalog_product->getProductDownloads($this->request->get['product_id']);
		} else {
			$this->data['product_download'] = array();
		}		
		
		$this->load->model('catalog/category');
				
		$this->data['categories'] = $this->model_catalog_category->getCategories(0);
		
		if (isset($this->request->post['product_category'])) {
			$this->data['product_category'] = (array)@$this->request->post['product_category'];
		} elseif (isset($product_info)) {
			$this->data['product_category'] = $this->model_catalog_product->getProductCategories($this->request->get['product_id']);
		} else {
			$this->data['product_category'] = array();
		}		
 
		$this->id       = 'content';
		$this->template = 'catalog/product_form.tpl';
		$this->layout   = 'module/layout';
		
 		$this->render();
  	} 
	
  	private function validateForm() { 
    	if (!$this->user->hasPermission('modify', 'catalog/product')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}

    	foreach ($this->request->post['product_description'] as $language_id => $value) {
      		if ((strlen($value['name']) < 3) || (strlen($value['name']) > 255)) {
        		$this->error['name'][$language_id] = $this->language->get('error_name');
      		}
			
      		if (strlen($value['description']) < 3) {
        		$this->error['description'][$language_id] = $this->language->get('error_description');
      		}
    	}
		
    	if ((strlen($this->request->post['model']) < 3) || (strlen($this->request->post['model']) > 24)) {
      		$this->error['model'] = $this->language->get('error_model');
    	}
		
    	if (!$this->error) {
      		return TRUE;
    	} else {
      		return FALSE;
    	}
  	}

  	private function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'catalog/product')) {
      		$this->error['warning'] = $this->language->get('error_permission');  
    	}
		
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
  	}	
}
?>