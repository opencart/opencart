<?php
class ControllerSettingStore extends Controller {
	private $error = array(); 
      
  	public function insert() {
    	$this->load->language('setting/store');

    	$this->document->title = $this->language->get('heading_title'); 
		
		$this->load->model('setting/store');
		
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$store_id = $this->model_setting_store->addStore($this->request->post);
	  		
			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->redirect(HTTPS_SERVER . 'index.php?route=setting/store/update&token=' . $this->session->data['token'] . '&store_id=' . $store_id);
    	}
	
    	$this->getForm();
  	}

  	public function update() {
    	$this->load->language('setting/store');

    	$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('setting/store');
	
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_setting_store->editStore($this->request->get['store_id'], $this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->redirect(HTTPS_SERVER . 'index.php?route=setting/store/update&token=' . $this->session->data['token'] . '&store_id=' . $this->request->get['store_id']);
		}

    	$this->getForm();
  	}

  	public function delete() {
    	$this->load->language('setting/store');

    	$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('setting/store');
		
		if (isset($this->request->get['store_id']) && $this->validateDelete()) {
			$this->model_setting_store->deleteStore($this->request->get['store_id']);

			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->redirect(HTTPS_SERVER . 'index.php?route=setting/setting&token=' . $this->session->data['token']);
		}

    	$this->getForm();
  	}
 
	public function getForm() { 
		$this->data['heading_title'] = $this->language->get('heading_title');
		 
		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_image_manager'] = $this->language->get('text_image_manager');
		
		$this->data['text_edit_store'] = $this->language->get('text_edit_store');
		
		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_url'] = $this->language->get('entry_url');		
		$this->data['entry_title'] = $this->language->get('entry_title');
		$this->data['entry_meta_description'] = $this->language->get('entry_meta_description');
		$this->data['entry_template'] = $this->language->get('entry_template');
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['entry_country'] = $this->language->get('entry_country');
		$this->data['entry_zone'] = $this->language->get('entry_zone');
		$this->data['entry_language'] = $this->language->get('entry_language');
		$this->data['entry_currency'] = $this->language->get('entry_currency');
		$this->data['entry_tax'] = $this->language->get('entry_tax');
		$this->data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$this->data['entry_customer_price'] = $this->language->get('entry_customer_price');
		$this->data['entry_customer_approval'] = $this->language->get('entry_customer_approval');
		$this->data['entry_guest_checkout'] = $this->language->get('entry_guest_checkout');
		$this->data['entry_account'] = $this->language->get('entry_account');
		$this->data['entry_checkout'] = $this->language->get('entry_checkout');
		$this->data['entry_stock_display'] = $this->language->get('entry_stock_display');
		$this->data['entry_stock_checkout'] = $this->language->get('entry_stock_checkout');
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');
		$this->data['entry_logo'] = $this->language->get('entry_logo');
		$this->data['entry_icon'] = $this->language->get('entry_icon');
		$this->data['entry_image_thumb'] = $this->language->get('entry_image_thumb');
		$this->data['entry_image_popup'] = $this->language->get('entry_image_popup');
		$this->data['entry_image_category'] = $this->language->get('entry_image_category');
		$this->data['entry_image_product'] = $this->language->get('entry_image_product');
		$this->data['entry_image_additional'] = $this->language->get('entry_image_additional');
		$this->data['entry_image_related'] = $this->language->get('entry_image_related');
		$this->data['entry_image_cart'] = $this->language->get('entry_image_cart');
		$this->data['entry_ssl'] = $this->language->get('entry_ssl');
		$this->data['entry_catalog_limit'] = $this->language->get('entry_catalog_limit');
		$this->data['entry_cart_weight'] = $this->language->get('entry_cart_weight');
		$this->data['entry_review'] = $this->language->get('entry_review');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_store'] = $this->language->get('button_add_store');
		$this->data['button_delete_store'] = $this->language->get('button_delete_store');

		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['tab_store'] = $this->language->get('tab_store');
		$this->data['tab_local'] = $this->language->get('tab_local');
		$this->data['tab_option'] = $this->language->get('tab_option');
		$this->data['tab_image'] = $this->language->get('tab_image');
		$this->data['tab_server'] = $this->language->get('tab_server');
		
		$this->data['token'] = $this->session->data['token'];
		
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
		
 		if (isset($this->error['url'])) {
			$this->data['error_url'] = $this->error['url'];
		} else {
			$this->data['error_url'] = '';
		}

 		if (isset($this->error['title'])) {
			$this->data['error_title'] = $this->error['title'];
		} else {
			$this->data['error_title'] = '';
		}

 		if (isset($this->error['image_thumb'])) {
			$this->data['error_image_thumb'] = $this->error['image_thumb'];
		} else {
			$this->data['error_image_thumb'] = '';
		}
		
 		if (isset($this->error['image_popup'])) {
			$this->data['error_image_popup'] = $this->error['image_popup'];
		} else {
			$this->data['error_image_popup'] = '';
		}
		
 		if (isset($this->error['image_category'])) {
			$this->data['error_image_category'] = $this->error['image_category'];
		} else {
			$this->data['error_image_category'] = '';
		}
		
 		if (isset($this->error['image_product'])) {
			$this->data['error_image_product'] = $this->error['image_product'];
		} else {
			$this->data['error_image_product'] = '';
		}
		
 		if (isset($this->error['image_additional'])) {
			$this->data['error_image_additional'] = $this->error['image_additional'];
		} else {
			$this->data['error_image_additional'] = '';
		}	
		
 		if (isset($this->error['image_related'])) {
			$this->data['error_image_related'] = $this->error['image_related'];
		} else {
			$this->data['error_image_related'] = '';
		}
		
 		if (isset($this->error['image_cart'])) {
			$this->data['error_image_cart'] = $this->error['image_cart'];
		} else {
			$this->data['error_image_cart'] = '';
		}
		
		if (isset($this->error['catalog_limit'])) {
			$this->data['error_catalog_limit'] = $this->error['catalog_limit'];
		} else {
			$this->data['error_catalog_limit'] = '';
		}
		
  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=setting/store&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		
		$this->data['insert'] = HTTPS_SERVER . 'index.php?route=setting/store/insert&token=' . $this->session->data['token'];
		
		if (isset($this->request->get['store_id'])) {
			$this->data['delete'] = HTTPS_SERVER . 'index.php?route=setting/store/delete&token=' . $this->session->data['token'] . '&store_id=' . $this->request->get['store_id'];
		} else {
			$this->data['delete'] = '';
		}
		
		if (!isset($this->request->get['store_id'])) {
			$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=setting/setting&token=' . $this->session->data['token'];
		} else {
			$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=setting/store/update&token=' . $this->session->data['token'] . '&store_id=' . $this->request->get['store_id'];
		}
		
		if (!isset($this->request->get['store_id'])) {
			$this->data['action'] = HTTPS_SERVER . 'index.php?route=setting/store/insert&token=' . $this->session->data['token'];
		} else {
			$this->data['action'] = HTTPS_SERVER . 'index.php?route=setting/store/update&token=' . $this->session->data['token'] . '&store_id=' . $this->request->get['store_id'];
		}
		
		$this->data['stores'] = array();

		$this->data['stores'][] = array(
			'store_id' => 0,
			'name'     => $this->language->get('text_default'),
			'href'     => HTTPS_SERVER . 'index.php?route=setting/setting&token=' . $this->session->data['token']
		); 
		
		$this->load->model('setting/store');
		
		$results = $this->model_setting_store->getStores();
		
		foreach ($results as $result) {
			$this->data['stores'][] = array(
				'store_id' => $result['store_id'],
				'name'     => $result['name'],
				'href'     => HTTPS_SERVER . 'index.php?route=setting/store/update&token=' . $this->session->data['token'] . '&store_id=' . $result['store_id']
			); 
		}
		
		if (isset($this->request->get['store_id'])) {
			$this->data['store_id'] = $this->request->get['store_id'];
		} else {
			$this->data['store_id'] = 0;
		}
	
		if (isset($this->request->get['store_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$store_info = $this->model_setting_store->getStore($this->request->get['store_id']);
    	}
		
		if (isset($this->request->post['name'])) {
			$this->data['name'] = $this->request->post['name'];
		} elseif (isset($store_info)) {
			$this->data['name'] = $store_info['name'];
		} else {
			$this->data['name'] = '';
		}
		
		if (isset($this->request->post['url'])) {
			$this->data['url'] = $this->request->post['url'];
		} elseif (isset($store_info)) {
			$this->data['url'] = $store_info['url'];
		} else {
			$this->data['url'] = '';
		}

		if (isset($this->request->post['title'])) {
			$this->data['title'] = $this->request->post['title'];
		} elseif (isset($store_info)) {
			$this->data['title'] = $store_info['title'];
		} else {
			$this->data['title'] = '';
		}
		
		if (isset($this->request->post['meta_description'])) {
			$this->data['meta_description'] = $this->request->post['meta_description'];
		} elseif (isset($store_info)) {
			$this->data['meta_description'] = $store_info['meta_description'];		
		} else {
			$this->data['meta_description'] = '';
		}
		
		$this->data['templates'] = array();

		$directories = glob(DIR_CATALOG . 'view/theme/*', GLOB_ONLYDIR);
		
		foreach ($directories as $directory) {
			$this->data['templates'][] = basename($directory);
		}
		
		if (isset($this->request->post['template'])) {
			$this->data['template'] = $this->request->post['template'];
		} elseif (isset($store_info)) {
			$this->data['template'] = $store_info['template'];			
		} else {
			$this->data['template'] = '';
		}
		
		$this->load->model('localisation/language');
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		
		if (isset($this->request->post['store_description'])) {
			$this->data['store_description'] = $this->request->post['store_description'];
		} elseif (isset($store_info)) {
			$this->data['store_description'] = $this->model_setting_store->getStoreDescriptions($this->request->get['store_id']);
		} else {
			$this->data['store_description'] = array();
		}
		
		if (isset($this->request->post['country_id'])) {
			$this->data['country_id'] = $this->request->post['country_id'];
		} elseif (isset($store_info)) {
			$this->data['country_id'] = $store_info['country_id'];		
		} else {
			$this->data['country_id'] = '';
		}
		
		$this->load->model('localisation/country');
		
		$this->data['countries'] = $this->model_localisation_country->getCountries();

		if (isset($this->request->post['zone_id'])) {
			$this->data['zone_id'] = $this->request->post['zone_id'];
		} elseif (isset($store_info)) {
			$this->data['zone_id'] = $store_info['zone_id'];				
		} else {
			$this->data['zone_id'] = '';
		}

		if (isset($this->request->post['language'])) {
			$this->data['language_code'] = $this->request->post['language'];
		} elseif (isset($store_info)) {
			$this->data['language_code'] = $store_info['language'];			
		} else {
			$this->data['language_code'] = $this->config->get('config_language');
		}
		
		$this->load->model('localisation/language');
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['currency'])) {
			$this->data['currency_code'] = $this->request->post['currency'];
		} elseif (isset($store_info)) {
			$this->data['currency_code'] = $store_info['currency'];			
		} else {
			$this->data['currency_code'] = $this->config->get('config_currency');
		}
		
		$this->load->model('localisation/currency');
		
		$this->data['currencies'] = $this->model_localisation_currency->getCurrencies();
		
		if (isset($this->request->post['tax'])) {
			$this->data['tax'] = $this->request->post['tax'];
		} elseif (isset($store_info)) {
			$this->data['tax'] = $store_info['tax'];			
		} else {
			$this->data['tax'] = '';
		}

		$this->load->model('sale/customer_group');
		
		$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
		
		if (isset($this->request->post['customer_group_id'])) {
			$this->data['customer_group_id'] = $this->request->post['customer_group_id'];
		} elseif (isset($store_info)) {
			$this->data['customer_group_id'] = $store_info['customer_group_id'];			
		} else {
			$this->data['customer_group_id'] = '';
		}
		
		if (isset($this->request->post['customer_price'])) {
			$this->data['customer_price'] = $this->request->post['customer_price'];
		} elseif (isset($store_info)) {
			$this->data['customer_price'] = $store_info['customer_price'];			
		} else {
			$this->data['customer_price'] = '';
		}
		
		if (isset($this->request->post['customer_approval'])) {
			$this->data['customer_approval'] = $this->request->post['customer_approval'];
		} elseif (isset($store_info)) {
			$this->data['customer_approval'] = $store_info['customer_approval'];			
		} else {
			$this->data['customer_approval'] = '';
		}
		
		if (isset($this->request->post['guest_checkout'])) {
			$this->data['guest_checkout'] = $this->request->post['guest_checkout'];
		} elseif (isset($store_info)) {
			$this->data['guest_checkout'] = $store_info['guest_checkout'];		
		} else {
			$this->data['guest_checkout'] = '';
		}
		
		if (isset($this->request->post['account_id'])) {
			$this->data['account_id'] = $this->request->post['account_id'];
		} elseif (isset($store_info)) {
			$this->data['account_id'] = $store_info['account_id'];			
		} else {
			$this->data['account_id'] = '';
		}
		
		if (isset($this->request->post['checkout_id'])) {
			$this->data['checkout_id'] = $this->request->post['checkout_id'];
		} elseif (isset($store_info)) {
			$this->data['checkout_id'] = $store_info['checkout_id'];		
		} else {
			$this->data['checkout_id'] = '';
		}

		$this->load->model('catalog/information');
		
		$this->data['informations'] = $this->model_catalog_information->getInformations();

		if (isset($this->request->post['stock_display'])) {
			$this->data['stock_display'] = $this->request->post['stock_display'];
		} elseif (isset($store_info)) {
			$this->data['stock_display'] = $store_info['stock_display'];			
		} else {
			$this->data['stock_display'] = '';
		}

		if (isset($this->request->post['stock_checkout'])) {
			$this->data['stock_checkout'] = $this->request->post['stock_checkout'];
		} elseif (isset($store_info)) {
			$this->data['stock_checkout'] = $store_info['stock_checkout'];		
		} else {
			$this->data['stock_checkout'] = '';
		}
		
		if (isset($this->request->post['catalog_limit'])) {
			$this->data['catalog_limit'] = $this->request->post['catalog_limit'];
		} elseif (isset($store_info)) {
			$this->data['catalog_limit'] = $store_info['catalog_limit'];	
		} else {
			$this->data['catalog_limit'] = '12';
		}
		
		if (isset($this->request->post['cart_weight'])) {
			$this->data['cart_weight'] = $this->request->post['cart_weight'];
		} elseif (isset($store_info)) {
			$this->data['cart_weight'] = $store_info['cart_weight'];	
		} else {
			$this->data['cart_weight'] = '';
		}
		
		$this->load->model('localisation/order_status');
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['order_status_id'])) {
			$this->data['order_status_id'] = $this->request->post['order_status_id'];
		} elseif (isset($store_info)) {
			$this->data['order_status_id'] = $store_info['order_status_id'];		
		} else {
			$this->data['order_status_id'] = '';
		}
		
		$this->load->model('tool/image');

		if (isset($this->request->post['logo'])) {
			$this->data['logo'] = $this->request->post['logo'];
		} elseif (isset($store_info)) {
			$this->data['logo'] = $store_info['logo'];			
		} else {
			$this->data['logo'] = '';
		}

		if (isset($store_info['logo']) && file_exists(DIR_IMAGE . $store_info['logo'])) {
			$this->data['preview_logo'] = HTTPS_IMAGE . $store_info['logo'];		
		} else {
			$this->data['preview_logo'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}

		if (isset($this->request->post['icon'])) {
			$this->data['icon'] = $this->request->post['icon'];
		} elseif (isset($store_info)) {
			$this->data['icon'] = $store_info['icon'];			
		} else {
			$this->data['icon'] = '';
		}
		
		if (isset($store_info['icon']) && file_exists(DIR_IMAGE . $store_info['icon'])) {
			$this->data['preview_icon'] = HTTPS_IMAGE . $store_info['icon'];
		} else {
			$this->data['preview_icon'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}
		
		if (isset($this->request->post['image_thumb_width'])) {
			$this->data['image_thumb_width'] = $this->request->post['image_thumb_width'];
		} elseif (isset($store_info)) {
			$this->data['image_thumb_width'] = $store_info['image_thumb_width'];			
		} else {
			$this->data['image_thumb_width'] = 250;
		}
		
		if (isset($this->request->post['image_thumb_height'])) {
			$this->data['image_thumb_height'] = $this->request->post['image_thumb_height'];
		} elseif (isset($store_info)) {
			$this->data['image_thumb_height'] = $store_info['image_thumb_height'];				
		} else {
			$this->data['image_thumb_height'] = 250;
		}
		
		if (isset($this->request->post['image_popup_width'])) {
			$this->data['image_popup_width'] = $this->request->post['image_popup_width'];
		} elseif (isset($store_info)) {
			$this->data['image_popup_width'] = $store_info['image_popup_width'];			
		} else {
			$this->data['image_popup_width'] = 500;
		}
		
		if (isset($this->request->post['image_popup_height'])) {
			$this->data['image_popup_height'] = $this->request->post['image_popup_height'];
		} elseif (isset($store_info)) {
			$this->data['image_popup_height'] = $store_info['image_popup_height'];			
		} else {
			$this->data['image_popup_height'] = 500;
		}

		if (isset($this->request->post['image_category_width'])) {
			$this->data['image_category_width'] = $this->request->post['image_category_width'];
		} elseif (isset($store_info)) {
			$this->data['image_category_width'] = $store_info['image_category_width'];			
		} else {
			$this->data['image_category_width'] = 120;
		}
		
		if (isset($this->request->post['image_category_height'])) {
			$this->data['image_category_height'] = $this->request->post['image_category_height'];
		} elseif (isset($store_info)) {
			$this->data['image_category_height'] = $store_info['image_category_height'];			
		} else {
			$this->data['image_category_height'] = 120;
		}
		
		if (isset($this->request->post['image_product_width'])) {
			$this->data['image_product_width'] = $this->request->post['image_product_width'];
		} elseif (isset($store_info)) {
			$this->data['image_product_width'] = $store_info['image_product_width'];		
		} else {
			$this->data['image_product_width'] = 120;
		}
		
		if (isset($this->request->post['image_product_height'])) {
			$this->data['image_product_height'] = $this->request->post['image_product_height'];
		} elseif (isset($store_info)) {
			$this->data['image_product_height'] = $store_info['image_product_height'];		
		} else {
			$this->data['image_product_height'] = 120;
		}

		if (isset($this->request->post['image_additional_width'])) {
			$this->data['image_additional_width'] = $this->request->post['image_additional_width'];
		} elseif (isset($store_info)) {
			$this->data['image_additional_width'] = $store_info['image_additional_width'];			
		} else {
			$this->data['image_additional_width'] = 150;
		}
		
		if (isset($this->request->post['image_additional_height'])) {
			$this->data['image_additional_height'] = $this->request->post['image_additional_height'];
		} elseif (isset($store_info)) {
			$this->data['image_additional_height'] = $store_info['image_additional_height'];				
		} else {
			$this->data['image_additional_height'] = 150;
		}
		
		if (isset($this->request->post['image_related_width'])) {
			$this->data['image_related_width'] = $this->request->post['image_related_width'];
		} elseif (isset($store_info)) {
			$this->data['image_related_width'] = $store_info['image_related_width'];		
		} else {
			$this->data['image_related_width'] = 120;
		}
		
		if (isset($this->request->post['image_related_height'])) {
			$this->data['image_related_height'] = $this->request->post['image_related_height'];
		} elseif (isset($store_info)) {
			$this->data['image_related_height'] = $store_info['image_related_height'];			
		} else {
			$this->data['image_related_height'] = 120;
		}
		
		if (isset($this->request->post['image_cart_width'])) {
			$this->data['image_cart_width'] = $this->request->post['image_cart_width'];
		} elseif (isset($store_info)) {
			$this->data['image_cart_width'] = $store_info['image_cart_width'];			
		} else {
			$this->data['image_cart_width'] = 75;
		}
		
		if (isset($this->request->post['image_cart_height'])) {
			$this->data['image_cart_height'] = $this->request->post['image_cart_height'];
		} elseif (isset($store_info)) {
			$this->data['image_cart_height'] = $store_info['image_cart_height'];			
		} else {
			$this->data['image_cart_height'] = 75;
		}

		if (isset($this->request->post['ssl'])) {
			$this->data['ssl'] = $this->request->post['ssl'];
		} elseif (isset($store_info)) {
			$this->data['ssl'] = $store_info['ssl'];
		} else {
			$this->data['ssl'] = '';
		}
		
		$this->template = 'setting/store.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'setting/store')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		if (!$this->request->post['name']) {
			$this->error['name'] = $this->language->get('error_name');
		}	
		
		if (!$this->request->post['url']) {
			$this->error['url'] = $this->language->get('error_url');
		}	
		
		if (!$this->request->post['title']) {
			$this->error['title'] = $this->language->get('error_title');
		}	
		
		if (!$this->request->post['image_thumb_width'] || !$this->request->post['image_thumb_height']) {
			$this->error['image_thumb'] = $this->language->get('error_image_thumb');
		}	
		
		if (!$this->request->post['image_popup_width'] || !$this->request->post['image_popup_height']) {
			$this->error['image_popup'] = $this->language->get('error_image_popup');
		}	
		
		if (!$this->request->post['image_category_width'] || !$this->request->post['image_category_height']) {
			$this->error['image_category'] = $this->language->get('error_image_category');
		}
		
		if (!$this->request->post['image_product_width'] || !$this->request->post['image_product_height']) {
			$this->error['image_product'] = $this->language->get('error_image_product');
		}
		
		if (!$this->request->post['image_additional_width'] || !$this->request->post['image_additional_height']) {
			$this->error['image_additional'] = $this->language->get('error_image_additional');
		}
		
		if (!$this->request->post['image_related_width'] || !$this->request->post['image_related_height']) {
			$this->error['image_related'] = $this->language->get('error_image_related');
		}
		
		if (!$this->request->post['image_cart_width'] || !$this->request->post['image_cart_height']) {
			$this->error['image_cart'] = $this->language->get('error_image_cart');
		}
		
		if (!$this->request->post['catalog_limit']) {
			$this->error['catalog_limit'] = $this->language->get('error_limit');
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
		if (!$this->user->hasPermission('modify', 'setting/store')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		$this->load->model('sale/order');
		
		$store_total = $this->model_sale_order->getTotalOrdersByStoreId($this->request->get['store_id']);

		if ($store_total) {
			$this->error['warning'] = sprintf($this->language->get('error_store'), $store_total);
		}	
		
		if (!$this->error) {
			return TRUE; 
		} else {
			return FALSE;
		}
	}

	public function zone() {
		$output = '';
		
		$this->load->model('localisation/zone');
		
		$results = $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']);
		
		foreach ($results as $result) {
			$output .= '<option value="' . $result['zone_id'] . '"';

			if (isset($this->request->get['zone_id']) && ($this->request->get['zone_id'] == $result['zone_id'])) {
				$output .= ' selected="selected"';
			}

			$output .= '>' . $result['name'] . '</option>';
		}

		if (!$results) {
			$output .= '<option value="0">' . $this->language->get('text_none') . '</option>';
		}

		$this->response->setOutput($output, $this->config->get('config_compression'));
	}
	
	public function template() {
		$template = basename($this->request->get['template']);
		
		if (file_exists(DIR_IMAGE . 'templates/' . $template . '.png')) {
			$image = HTTPS_IMAGE . 'templates/' . $template . '.png';
		} else {
			$image = HTTPS_IMAGE . 'no_image.jpg';
		}
		
		$this->response->setOutput('<img src="' . $image . '" alt="" title="" style="border: 1px solid #EEEEEE;" />');
	}		
}
?>