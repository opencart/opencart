<?php
class ControllerSettingStore extends Controller {
	private $error = array(); 

	public function index() {
		$this->language->load('setting/store');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/store');

		$this->getList();
	}

	public function insert() {
		$this->language->load('setting/store');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/store');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$store_id = $this->model_setting_store->addStore($this->request->post);

			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting('config', $this->request->post, $store_id);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('setting/store', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->getForm();
	}

	public function update() {
		$this->language->load('setting/store');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/store');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_setting_store->editStore($this->request->get['store_id'], $this->request->post);

			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting('config', $this->request->post, $this->request->get['store_id']);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('setting/store', 'token=' . $this->session->data['token'] . '&store_id=' . $this->request->get['store_id'], 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->language->load('setting/store');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/store');

		$this->load->model('setting/setting');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $store_id) {
				$this->model_setting_store->deleteStore($store_id);

				$this->model_setting_setting->deleteSetting('config', $store_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('setting/store', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {
		$url = '';

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
			'href'      => $this->url->link('setting/store', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['insert'] = $this->url->link('setting/store/insert', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['delete'] = $this->url->link('setting/store/delete', 'token=' . $this->session->data['token'], 'SSL');	

		$this->data['stores'] = array();

		$action = array();

		$action[] = array(
			'text' => $this->language->get('text_edit'),
			'href' => $this->url->link('setting/setting', 'token=' . $this->session->data['token'], 'SSL')
		);

		$this->data['stores'][] = array(
			'store_id' => 0,
			'name'     => $this->config->get('config_name') . $this->language->get('text_default'),
			'url'      => HTTP_CATALOG,
			'selected' => isset($this->request->post['selected']) && in_array(0, $this->request->post['selected']),
			'action'   => $action
		);

		$store_total = $this->model_setting_store->getTotalStores();

		$results = $this->model_setting_store->getStores();

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('setting/store/update', 'token=' . $this->session->data['token'] . '&store_id=' . $result['store_id'], 'SSL')
			);

			$this->data['stores'][] = array(
				'store_id' => $result['store_id'],
				'name'     => $result['name'],
				'url'      => $result['url'],
				'selected' => isset($this->request->post['selected']) && in_array($result['store_id'], $this->request->post['selected']),
				'action'   => $action
			);
		}	

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_url'] = $this->language->get('column_url');
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

		$this->template = 'setting/store_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function getForm() { 
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_items'] = $this->language->get('text_items');
		$this->data['text_tax'] = $this->language->get('text_tax');
		$this->data['text_account'] = $this->language->get('text_account');
		$this->data['text_checkout'] = $this->language->get('text_checkout');
		$this->data['text_stock'] = $this->language->get('text_stock');				
		$this->data['text_image_manager'] = $this->language->get('text_image_manager');
		$this->data['text_browse'] = $this->language->get('text_browse');
		$this->data['text_clear'] = $this->language->get('text_clear');			
		$this->data['text_shipping'] = $this->language->get('text_shipping');	
		$this->data['text_payment'] = $this->language->get('text_payment');	

		$this->data['entry_url'] = $this->language->get('entry_url');
		$this->data['entry_ssl'] = $this->language->get('entry_ssl');	
		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_owner'] = $this->language->get('entry_owner');
		$this->data['entry_address'] = $this->language->get('entry_address');
		$this->data['entry_email'] = $this->language->get('entry_email');
		$this->data['entry_telephone'] = $this->language->get('entry_telephone');
		$this->data['entry_fax'] = $this->language->get('entry_fax');				
		$this->data['entry_title'] = $this->language->get('entry_title');
		$this->data['entry_meta_description'] = $this->language->get('entry_meta_description');
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['entry_template'] = $this->language->get('entry_template');
		$this->data['entry_country'] = $this->language->get('entry_country');
		$this->data['entry_zone'] = $this->language->get('entry_zone');
		$this->data['entry_language'] = $this->language->get('entry_language');
		$this->data['entry_currency'] = $this->language->get('entry_currency');
		$this->data['entry_catalog_limit'] = $this->language->get('entry_catalog_limit');
		$this->data['entry_tax'] = $this->language->get('entry_tax');
		$this->data['entry_tax_default'] = $this->language->get('entry_tax_default');
		$this->data['entry_tax_customer'] = $this->language->get('entry_tax_customer');		
		$this->data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$this->data['entry_customer_group_display'] = $this->language->get('entry_customer_group_display');
		$this->data['entry_customer_price'] = $this->language->get('entry_customer_price');
		$this->data['entry_account'] = $this->language->get('entry_account');
		$this->data['entry_cart_weight'] = $this->language->get('entry_cart_weight');
		$this->data['entry_guest_checkout'] = $this->language->get('entry_guest_checkout');
		$this->data['entry_checkout'] = $this->language->get('entry_checkout');
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');
		$this->data['entry_stock_display'] = $this->language->get('entry_stock_display');
		$this->data['entry_stock_checkout'] = $this->language->get('entry_stock_checkout');
		$this->data['entry_ajax_cart'] = $this->language->get('entry_ajax_cart');
		$this->data['entry_logo'] = $this->language->get('entry_logo');
		$this->data['entry_icon'] = $this->language->get('entry_icon');
		$this->data['entry_image_category'] = $this->language->get('entry_image_category');
		$this->data['entry_image_thumb'] = $this->language->get('entry_image_thumb');
		$this->data['entry_image_popup'] = $this->language->get('entry_image_popup');
		$this->data['entry_image_product'] = $this->language->get('entry_image_product');
		$this->data['entry_image_additional'] = $this->language->get('entry_image_additional');
		$this->data['entry_image_related'] = $this->language->get('entry_image_related');
		$this->data['entry_image_compare'] = $this->language->get('entry_image_compare');
		$this->data['entry_image_wishlist'] = $this->language->get('entry_image_wishlist');
		$this->data['entry_image_cart'] = $this->language->get('entry_image_cart');
		$this->data['entry_secure'] = $this->language->get('entry_secure');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['tab_store'] = $this->language->get('tab_store');
		$this->data['tab_local'] = $this->language->get('tab_local');
		$this->data['tab_option'] = $this->language->get('tab_option');
		$this->data['tab_image'] = $this->language->get('tab_image');
		$this->data['tab_server'] = $this->language->get('tab_server');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['url'])) {
			$this->data['error_url'] = $this->error['url'];
		} else {
			$this->data['error_url'] = '';
		}

		if (isset($this->error['name'])) {
			$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = '';
		}

		if (isset($this->error['owner'])) {
			$this->data['error_owner'] = $this->error['owner'];
		} else {
			$this->data['error_owner'] = '';
		}

		if (isset($this->error['address'])) {
			$this->data['error_address'] = $this->error['address'];
		} else {
			$this->data['error_address'] = '';
		}

		if (isset($this->error['email'])) {
			$this->data['error_email'] = $this->error['email'];
		} else {
			$this->data['error_email'] = '';
		}

		if (isset($this->error['telephone'])) {
			$this->data['error_telephone'] = $this->error['telephone'];
		} else {
			$this->data['error_telephone'] = '';
		}

		if (isset($this->error['title'])) {
			$this->data['error_title'] = $this->error['title'];
		} else {
			$this->data['error_title'] = '';
		}

		if (isset($this->error['customer_group_display'])) {
			$this->data['error_customer_group_display'] = $this->error['customer_group_display'];
		} else {
			$this->data['error_customer_group_display'] = '';
		}

		if (isset($this->error['image_category'])) {
			$this->data['error_image_category'] = $this->error['image_category'];
		} else {
			$this->data['error_image_category'] = '';
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

		if (isset($this->error['image_compare'])) {
			$this->data['error_image_compare'] = $this->error['image_compare'];
		} else {
			$this->data['error_image_compare'] = '';
		}

		if (isset($this->error['image_wishlist'])) {
			$this->data['error_image_wishlist'] = $this->error['image_wishlist'];
		} else {
			$this->data['error_image_wishlist'] = '';
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

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('setting/store', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		if (!isset($this->request->get['store_id'])) {
			$this->data['action'] = $this->url->link('setting/store/insert', 'token=' . $this->session->data['token'], 'SSL');
		} else {
			$this->data['action'] = $this->url->link('setting/store/update', 'token=' . $this->session->data['token'] . '&store_id=' . $this->request->get['store_id'], 'SSL');
		}

		$this->data['cancel'] = $this->url->link('setting/store', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->get['store_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$this->load->model('setting/setting');

			$store_info = $this->model_setting_setting->getSetting('config', $this->request->get['store_id']);
		}

		$this->data['token'] = $this->session->data['token'];

		if (isset($this->request->post['config_url'])) {
			$this->data['config_url'] = $this->request->post['config_url'];
		} elseif (isset($store_info['config_url'])) {
			$this->data['config_url'] = $store_info['config_url'];
		} else {
			$this->data['config_url'] = '';
		}

		if (isset($this->request->post['config_ssl'])) {
			$this->data['config_ssl'] = $this->request->post['config_ssl'];
		} elseif (isset($store_info['config_ssl'])) {
			$this->data['config_ssl'] = $store_info['config_ssl'];
		} else {
			$this->data['config_ssl'] = '';
		}

		if (isset($this->request->post['config_name'])) {
			$this->data['config_name'] = $this->request->post['config_name'];
		} elseif (isset($store_info['config_name'])) {
			$this->data['config_name'] = $store_info['config_name'];
		} else {
			$this->data['config_name'] = '';
		}

		if (isset($this->request->post['config_owner'])) {
			$this->data['config_owner'] = $this->request->post['config_owner'];
		} elseif (isset($store_info['config_owner'])) {
			$this->data['config_owner'] = $store_info['config_owner'];		
		} else {
			$this->data['config_owner'] = '';
		}

		if (isset($this->request->post['config_address'])) {
			$this->data['config_address'] = $this->request->post['config_address'];
		} elseif (isset($store_info['config_address'])) {
			$this->data['config_address'] = $store_info['config_address'];		
		} else {
			$this->data['config_address'] = '';
		}

		if (isset($this->request->post['config_email'])) {
			$this->data['config_email'] = $this->request->post['config_email'];
		} elseif (isset($store_info['config_email'])) {
			$this->data['config_email'] = $store_info['config_email'];		
		} else {
			$this->data['config_email'] = '';
		}

		if (isset($this->request->post['config_telephone'])) {
			$this->data['config_telephone'] = $this->request->post['config_telephone'];
		} elseif (isset($store_info['config_telephone'])) {
			$this->data['config_telephone'] = $store_info['config_telephone'];		
		} else {
			$this->data['config_telephone'] = '';
		}

		if (isset($this->request->post['config_fax'])) {
			$this->data['config_fax'] = $this->request->post['config_fax'];
		} elseif (isset($store_info['config_fax'])) {
			$this->data['config_fax'] = $store_info['config_fax'];		
		} else {
			$this->data['config_fax'] = '';
		}

		if (isset($this->request->post['config_title'])) {
			$this->data['config_title'] = $this->request->post['config_title'];
		} elseif (isset($store_info['config_title'])) {
			$this->data['config_title'] = $store_info['config_title'];
		} else {
			$this->data['config_title'] = '';
		}

		if (isset($this->request->post['config_meta_description'])) {
			$this->data['config_meta_description'] = $this->request->post['config_meta_description'];
		} elseif (isset($store_info['config_meta_description'])) {
			$this->data['config_meta_description'] = $store_info['config_meta_description'];		
		} else {
			$this->data['config_meta_description'] = '';
		}

		if (isset($this->request->post['config_layout_id'])) {
			$this->data['config_layout_id'] = $this->request->post['config_layout_id'];
		} elseif (isset($store_info['config_layout_id'])) {
			$this->data['config_layout_id'] = $store_info['config_layout_id'];
		} else {
			$this->data['config_layout_id'] = '';
		}

		$this->load->model('design/layout');

		$this->data['layouts'] = $this->model_design_layout->getLayouts();

		if (isset($this->request->post['config_template'])) {
			$this->data['config_template'] = $this->request->post['config_template'];
		} elseif (isset($store_info['config_template'])) {
			$this->data['config_template'] = $store_info['config_template'];
		} else {
			$this->data['config_template'] = '';
		}

		$this->data['templates'] = array();

		$directories = glob(DIR_CATALOG . 'view/theme/*', GLOB_ONLYDIR);

		foreach ($directories as $directory) {
			$this->data['templates'][] = basename($directory);
		}	

		if (isset($this->request->post['config_country_id'])) {
			$this->data['config_country_id'] = $this->request->post['config_country_id'];
		} elseif (isset($store_info['config_country_id'])) {
			$this->data['config_country_id'] = $store_info['config_country_id'];		
		} else {
			$this->data['config_country_id'] = $this->config->get('config_country_id');
		}

		$this->load->model('localisation/country');

		$this->data['countries'] = $this->model_localisation_country->getCountries();

		if (isset($this->request->post['config_zone_id'])) {
			$this->data['config_zone_id'] = $this->request->post['config_zone_id'];
		} elseif (isset($store_info['config_zone_id'])) {
			$this->data['config_zone_id'] = $store_info['config_zone_id'];				
		} else {
			$this->data['config_zone_id'] = $this->config->get('config_zone_id');
		}

		if (isset($this->request->post['config_language'])) {
			$this->data['config_language'] = $this->request->post['config_language'];
		} elseif (isset($store_info['config_language'])) {
			$this->data['config_language'] = $store_info['config_language'];			
		} else {
			$this->data['config_language'] = $this->config->get('config_language');
		}

		$this->load->model('localisation/language');

		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['config_currency'])) {
			$this->data['config_currency'] = $this->request->post['config_currency'];
		} elseif (isset($store_info['config_currency'])) {
			$this->data['config_currency'] = $store_info['config_currency'];			
		} else {
			$this->data['config_currency'] = $this->config->get('config_currency');
		}

		$this->load->model('localisation/currency');

		$this->data['currencies'] = $this->model_localisation_currency->getCurrencies();

		if (isset($this->request->post['config_catalog_limit'])) {
			$this->data['config_catalog_limit'] = $this->request->post['config_catalog_limit'];
		} elseif (isset($store_info['config_catalog_limit'])) {
			$this->data['config_catalog_limit'] = $store_info['config_catalog_limit'];	
		} else {
			$this->data['config_catalog_limit'] = '12';
		}		

		if (isset($this->request->post['config_tax'])) {
			$this->data['config_tax'] = $this->request->post['config_tax'];
		} elseif (isset($store_info['config_tax'])) {
			$this->data['config_tax'] = $store_info['config_tax'];			
		} else {
			$this->data['config_tax'] = '';
		}

		if (isset($this->request->post['config_tax_default'])) {
			$this->data['config_tax_default'] = $this->request->post['config_tax_default'];
		} elseif (isset($store_info['config_tax_default'])) {
			$this->data['config_tax_default'] = $store_info['config_tax_default'];			
		} else {
			$this->data['config_tax_default'] = '';
		}

		if (isset($this->request->post['config_tax_customer'])) {
			$this->data['config_tax_customer'] = $this->request->post['config_tax_customer'];
		} elseif (isset($store_info['config_tax_customer'])) {
			$this->data['config_tax_customer'] = $store_info['config_tax_customer'];			
		} else {
			$this->data['config_tax_customer'] = '';
		}

		if (isset($this->request->post['config_customer_group_id'])) {
			$this->data['config_customer_group_id'] = $this->request->post['config_customer_group_id'];
		} elseif (isset($store_info['config_customer_group_id'])) {
			$this->data['config_customer_group_id'] = $store_info['config_customer_group_id'];			
		} else {
			$this->data['config_customer_group_id'] = '';
		}

		$this->load->model('sale/customer_group');

		$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();

		if (isset($this->request->post['config_customer_group_display'])) {
			$this->data['config_customer_group_display'] = $this->request->post['config_customer_group_display'];
		} elseif (isset($store_info['config_customer_group_display'])) {
			$this->data['config_customer_group_display'] = $store_info['config_customer_group_display'];	
		} else {
			$this->data['config_customer_group_display'] = array();			
		}

		if (isset($this->request->post['config_customer_price'])) {
			$this->data['config_customer_price'] = $this->request->post['config_customer_price'];
		} elseif (isset($store_info['config_customer_price'])) {
			$this->data['config_customer_price'] = $store_info['config_customer_price'];			
		} else {
			$this->data['config_customer_price'] = '';
		}

		if (isset($this->request->post['config_account_id'])) {
			$this->data['config_account_id'] = $this->request->post['config_account_id'];
		} elseif (isset($store_info['config_account_id'])) {
			$this->data['config_account_id'] = $store_info['config_account_id'];			
		} else {
			$this->data['config_account_id'] = '';
		}		

		$this->load->model('catalog/information');

		$this->data['informations'] = $this->model_catalog_information->getInformations();

		if (isset($this->request->post['config_cart_weight'])) {
			$this->data['config_cart_weight'] = $this->request->post['config_cart_weight'];
		} elseif (isset($store_info['config_cart_weight'])) {
			$this->data['config_cart_weight'] = $store_info['config_cart_weight'];	
		} else {
			$this->data['config_cart_weight'] = '';
		}

		if (isset($this->request->post['config_guest_checkout'])) {
			$this->data['config_guest_checkout'] = $this->request->post['config_guest_checkout'];
		} elseif (isset($store_info['config_guest_checkout'])) {
			$this->data['config_guest_checkout'] = $store_info['config_guest_checkout'];		
		} else {
			$this->data['config_guest_checkout'] = '';
		}

		if (isset($this->request->post['config_checkout_id'])) {
			$this->data['config_checkout_id'] = $this->request->post['config_checkout_id'];
		} elseif (isset($store_info['config_checkout_id'])) {
			$this->data['config_checkout_id'] = $store_info['config_checkout_id'];		
		} else {
			$this->data['config_checkout_id'] = '';
		}

		if (isset($this->request->post['config_order_status_id'])) {
			$this->data['config_order_status_id'] = $this->request->post['config_order_status_id'];
		} elseif (isset($store_info['config_order_status_id'])) {
			$this->data['config_order_status_id'] = $store_info['config_order_status_id'];		
		} else {
			$this->data['config_order_status_id'] = '';
		}

		$this->load->model('localisation/order_status');

		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['config_stock_display'])) {
			$this->data['config_stock_display'] = $this->request->post['config_stock_display'];
		} elseif (isset($store_info['config_stock_display'])) {
			$this->data['config_stock_display'] = $store_info['config_stock_display'];			
		} else {
			$this->data['config_stock_display'] = '';
		}

		if (isset($this->request->post['config_stock_checkout'])) {
			$this->data['config_stock_checkout'] = $this->request->post['config_stock_checkout'];
		} elseif (isset($store_info['config_stock_checkout'])) {
			$this->data['config_stock_checkout'] = $store_info['config_stock_checkout'];		
		} else {
			$this->data['config_stock_checkout'] = '';
		}

		$this->load->model('tool/image');

		if (isset($this->request->post['config_logo'])) {
			$this->data['config_logo'] = $this->request->post['config_logo'];
		} elseif (isset($store_info['config_logo'])) {
			$this->data['config_logo'] = $store_info['config_logo'];			
		} else {
			$this->data['config_logo'] = '';
		}

		if (isset($store_info['config_logo']) && file_exists(DIR_IMAGE . $store_info['config_logo']) && is_file(DIR_IMAGE . $store_info['config_logo'])) {
			$this->data['logo'] = $this->model_tool_image->resize($store_info['config_logo'], 100, 100);		
		} else {
			$this->data['logo'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}

		if (isset($this->request->post['config_icon'])) {
			$this->data['config_icon'] = $this->request->post['config_icon'];
		} elseif (isset($store_info['config_icon'])) {
			$this->data['config_icon'] = $store_info['config_icon'];			
		} else {
			$this->data['config_icon'] = '';
		}

		if (isset($store_info['config_icon']) && file_exists(DIR_IMAGE . $store_info['config_icon']) && is_file(DIR_IMAGE . $store_info['config_icon'])) {
			$this->data['icon'] = $this->model_tool_image->resize($store_info['config_icon'], 100, 100);
		} else {
			$this->data['icon'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}

		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);

		if (isset($this->request->post['config_image_category_height'])) {
			$this->data['config_image_category_height'] = $this->request->post['config_image_category_height'];
		} elseif (isset($store_info['config_image_category_height'])) {
			$this->data['config_image_category_height'] = $store_info['config_image_category_height'];			
		} else {
			$this->data['config_image_category_height'] = 80;
		}	

		if (isset($this->request->post['config_image_thumb_width'])) {
			$this->data['config_image_thumb_width'] = $this->request->post['config_image_thumb_width'];
		} elseif (isset($store_info['config_image_thumb_width'])) {
			$this->data['config_image_thumb_width'] = $store_info['config_image_thumb_width'];			
		} else {
			$this->data['config_image_thumb_width'] = 228;
		}

		if (isset($this->request->post['config_image_thumb_height'])) {
			$this->data['config_image_thumb_height'] = $this->request->post['config_image_thumb_height'];
		} elseif (isset($store_info['config_image_thumb_height'])) {
			$this->data['config_image_thumb_height'] = $store_info['config_image_thumb_height'];				
		} else {
			$this->data['config_image_thumb_height'] = 228;
		}

		if (isset($this->request->post['config_image_popup_width'])) {
			$this->data['config_image_popup_width'] = $this->request->post['config_image_popup_width'];
		} elseif (isset($store_info['config_image_popup_width'])) {
			$this->data['config_image_popup_width'] = $store_info['config_image_popup_width'];			
		} else {
			$this->data['config_image_popup_width'] = 500;
		}

		if (isset($this->request->post['config_image_popup_height'])) {
			$this->data['config_image_popup_height'] = $this->request->post['config_image_popup_height'];
		} elseif (isset($store_info['config_image_popup_height'])) {
			$this->data['config_image_popup_height'] = $store_info['config_image_popup_height'];			
		} else {
			$this->data['config_image_popup_height'] = 500;
		}

		if (isset($this->request->post['config_image_product_width'])) {
			$this->data['config_image_product_width'] = $this->request->post['config_image_product_width'];
		} elseif (isset($store_info['config_image_product_width'])) {
			$this->data['config_image_product_width'] = $store_info['config_image_product_width'];		
		} else {
			$this->data['config_image_product_width'] = 80;
		}

		if (isset($this->request->post['config_image_product_height'])) {
			$this->data['config_image_product_height'] = $this->request->post['config_image_product_height'];
		} elseif (isset($store_info['config_image_product_height'])) {
			$this->data['config_image_product_height'] = $store_info['config_image_product_height'];		
		} else {
			$this->data['config_image_product_height'] = 80;
		}

		if (isset($this->request->post['config_image_category_width'])) {
			$this->data['config_image_category_width'] = $this->request->post['config_image_category_width'];
		} elseif (isset($store_info['config_image_category_width'])) {
			$this->data['config_image_category_width'] = $store_info['config_image_category_width'];			
		} else {
			$this->data['config_image_category_width'] = 80;
		}

		if (isset($this->request->post['config_image_additional_width'])) {
			$this->data['config_image_additional_width'] = $this->request->post['config_image_additional_width'];
		} elseif (isset($store_info['config_image_additional_width'])) {
			$this->data['config_image_additional_width'] = $store_info['config_image_additional_width'];			
		} else {
			$this->data['config_image_additional_width'] = 74;
		}

		if (isset($this->request->post['config_image_additional_height'])) {
			$this->data['config_image_additional_height'] = $this->request->post['config_image_additional_height'];
		} elseif (isset($store_info['config_image_additional_height'])) {
			$this->data['config_image_additional_height'] = $store_info['config_image_additional_height'];				
		} else {
			$this->data['config_image_additional_height'] = 74;
		}

		if (isset($this->request->post['config_image_related_width'])) {
			$this->data['config_image_related_width'] = $this->request->post['config_image_related_width'];
		} elseif (isset($store_info['config_image_related_width'])) {
			$this->data['config_image_related_width'] = $store_info['config_image_related_width'];		
		} else {
			$this->data['config_image_related_width'] = 80;
		}

		if (isset($this->request->post['config_image_related_height'])) {
			$this->data['config_image_related_height'] = $this->request->post['config_image_related_height'];
		} elseif (isset($store_info['config_image_related_height'])) {
			$this->data['config_image_related_height'] = $store_info['config_image_related_height'];			
		} else {
			$this->data['config_image_related_height'] = 80;
		}

		if (isset($this->request->post['config_image_compare_width'])) {
			$this->data['config_image_compare_width'] = $this->request->post['config_image_compare_width'];
		} elseif (isset($store_info['config_image_compare_width'])) {
			$this->data['config_image_compare_width'] = $store_info['config_image_compare_width'];			
		} else {
			$this->data['config_image_compare_width'] = 90;
		}

		if (isset($this->request->post['config_image_compare_height'])) {
			$this->data['config_image_compare_height'] = $this->request->post['config_image_compare_height'];
		} elseif (isset($store_info['config_image_compare_height'])) {
			$this->data['config_image_compare_height'] = $store_info['config_image_compare_height'];			
		} else {
			$this->data['config_image_compare_height'] = 90;
		}

		if (isset($this->request->post['config_image_wishlist_width'])) {
			$this->data['config_image_wishlist_width'] = $this->request->post['config_image_wishlist_width'];
		} elseif (isset($store_info['config_image_wishlist_width'])) {
			$this->data['config_image_wishlist_width'] = $store_info['config_image_wishlist_width'];			
		} else {
			$this->data['config_image_wishlist_width'] = 50;
		}

		if (isset($this->request->post['config_image_wishlist_height'])) {
			$this->data['config_image_wishlist_height'] = $this->request->post['config_image_wishlist_height'];
		} elseif (isset($store_info['config_image_wishlist_height'])) {
			$this->data['config_image_wishlist_height'] = $store_info['config_image_wishlist_height'];			
		} else {
			$this->data['config_image_wishlist_height'] = 50;
		}

		if (isset($this->request->post['config_image_cart_width'])) {
			$this->data['config_image_cart_width'] = $this->request->post['config_image_cart_width'];
		} elseif (isset($store_info['config_image_cart_width'])) {
			$this->data['config_image_cart_width'] = $store_info['config_image_cart_width'];			
		} else {
			$this->data['config_image_cart_width'] = 80;
		}

		if (isset($this->request->post['config_image_cart_height'])) {
			$this->data['config_image_cart_height'] = $this->request->post['config_image_cart_height'];
		} elseif (isset($store_info['config_image_cart_height'])) {
			$this->data['config_image_cart_height'] = $store_info['config_image_cart_height'];			
		} else {
			$this->data['config_image_cart_height'] = 80;
		}

		if (isset($this->request->post['config_secure'])) {
			$this->data['config_secure'] = $this->request->post['config_secure'];
		} elseif (isset($store_info['config_secure'])) {
			$this->data['config_secure'] = $store_info['config_secure'];
		} else {
			$this->data['config_secure'] = '';
		}

		$this->template = 'setting/store_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'setting/store')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['config_url']) {
			$this->error['url'] = $this->language->get('error_url');
		}

		if (!$this->request->post['config_name']) {
			$this->error['name'] = $this->language->get('error_name');
		}	

		if ((utf8_strlen($this->request->post['config_owner']) < 3) || (utf8_strlen($this->request->post['config_owner']) > 64)) {
			$this->error['owner'] = $this->language->get('error_owner');
		}

		if ((utf8_strlen($this->request->post['config_address']) < 3) || (utf8_strlen($this->request->post['config_address']) > 256)) {
			$this->error['address'] = $this->language->get('error_address');
		}

		if ((utf8_strlen($this->request->post['config_email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['config_email'])) {
			$this->error['email'] = $this->language->get('error_email');
		}

		if ((utf8_strlen($this->request->post['config_telephone']) < 3) || (utf8_strlen($this->request->post['config_telephone']) > 32)) {
			$this->error['telephone'] = $this->language->get('error_telephone');
		}

		if (!$this->request->post['config_title']) {
			$this->error['title'] = $this->language->get('error_title');
		}	

		if (!empty($this->request->post['config_customer_group_display']) && !in_array($this->request->post['config_customer_group_id'], $this->request->post['config_customer_group_display'])) {
			$this->error['customer_group_display'] = $this->language->get('error_customer_group_display');
		}	

		if (!$this->request->post['config_image_category_width'] || !$this->request->post['config_image_category_height']) {
			$this->error['image_category'] = $this->language->get('error_image_category');
		}

		if (!$this->request->post['config_image_thumb_width'] || !$this->request->post['config_image_thumb_height']) {
			$this->error['image_thumb'] = $this->language->get('error_image_thumb');
		}	

		if (!$this->request->post['config_image_popup_width'] || !$this->request->post['config_image_popup_height']) {
			$this->error['image_popup'] = $this->language->get('error_image_popup');
		}

		if (!$this->request->post['config_image_product_width'] || !$this->request->post['config_image_product_height']) {
			$this->error['image_product'] = $this->language->get('error_image_product');
		}

		if (!$this->request->post['config_image_additional_width'] || !$this->request->post['config_image_additional_height']) {
			$this->error['image_additional'] = $this->language->get('error_image_additional');
		}

		if (!$this->request->post['config_image_related_width'] || !$this->request->post['config_image_related_height']) {
			$this->error['image_related'] = $this->language->get('error_image_related');
		}

		if (!$this->request->post['config_image_compare_width'] || !$this->request->post['config_image_compare_height']) {
			$this->error['image_compare'] = $this->language->get('error_image_compare');
		}

		if (!$this->request->post['config_image_wishlist_width'] || !$this->request->post['config_image_wishlist_height']) {
			$this->error['image_wishlist'] = $this->language->get('error_image_wishlist');
		}

		if (!$this->request->post['config_image_cart_width'] || !$this->request->post['config_image_cart_height']) {
			$this->error['image_cart'] = $this->language->get('error_image_cart');
		}

		if (!$this->request->post['config_catalog_limit']) {
			$this->error['catalog_limit'] = $this->language->get('error_limit');
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'setting/store')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('sale/order');

		foreach ($this->request->post['selected'] as $store_id) {
			if (!$store_id) {
				$this->error['warning'] = $this->language->get('error_default');
			}

			$store_total = $this->model_sale_order->getTotalOrdersByStoreId($store_id);

			if ($store_total) {
				$this->error['warning'] = sprintf($this->language->get('error_store'), $store_total);
			}	
		}

		if (!$this->error) {
			return true; 
		} else {
			return false;
		}
	}

	public function template() {
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$server = HTTPS_CATALOG;
		} else {
			$server = HTTP_CATALOG;
		}		

		if (file_exists(DIR_IMAGE . 'templates/' . basename($this->request->get['template']) . '.png')) {
			$image = $server . 'image/templates/' . basename($this->request->get['template']) . '.png';
		} else {
			$image = $server . 'image/no_image.jpg';
		}

		$this->response->setOutput('<img src="' . $image . '" alt="" title="" style="border: 1px solid #EEEEEE;" />');
	}		

	public function country() {
		$json = array();

		$this->load->model('localisation/country');

		$country_info = $this->model_localisation_country->getCountry($this->request->get['country_id']);

		if ($country_info) {
			$this->load->model('localisation/zone');

			$json = array(
				'country_id'        => $country_info['country_id'],
				'name'              => $country_info['name'],
				'iso_code_2'        => $country_info['iso_code_2'],
				'iso_code_3'        => $country_info['iso_code_3'],
				'address_format'    => $country_info['address_format'],
				'postcode_required' => $country_info['postcode_required'],
				'zone'              => $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']),
				'status'            => $country_info['status']		
			);
		}

		$this->response->setOutput(json_encode($json));
	}	
}
?>