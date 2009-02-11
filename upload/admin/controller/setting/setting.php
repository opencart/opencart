<?php
class ControllerSettingSetting extends Controller {
	private $error = array();
 
	public function index() { 
		$this->load->language('setting/setting'); 

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting('config', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->https('setting/setting'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_none'] = $this->language->get('text_none');

		$this->data['entry_store'] = $this->language->get('entry_store');
		$this->data['entry_welcome'] = $this->language->get('entry_welcome');
		$this->data['entry_owner'] = $this->language->get('entry_owner');
		$this->data['entry_address'] = $this->language->get('entry_address');
		$this->data['entry_email'] = $this->language->get('entry_email');
		$this->data['entry_telephone'] = $this->language->get('entry_telephone');
		$this->data['entry_fax'] = $this->language->get('entry_fax');
		$this->data['entry_url_alias'] = $this->language->get('entry_url_alias');
		$this->data['entry_parse_time'] = $this->language->get('entry_parse_time');
		$this->data['entry_ssl'] = $this->language->get('entry_ssl');
		$this->data['entry_country'] = $this->language->get('entry_country');
		$this->data['entry_zone'] = $this->language->get('entry_zone');
		$this->data['entry_language'] = $this->language->get('entry_language');
		$this->data['entry_currency'] = $this->language->get('entry_currency');
		$this->data['entry_tax'] = $this->language->get('entry_tax');
		$this->data['entry_weight'] = $this->language->get('entry_weight');
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');
		$this->data['entry_stock_check'] = $this->language->get('entry_stock_check');
		$this->data['entry_stock_checkout'] = $this->language->get('entry_stock_checkout');
		$this->data['entry_stock_subtract'] = $this->language->get('entry_stock_subtract');
		$this->data['entry_stock_status'] = $this->language->get('entry_stock_status');
		$this->data['entry_download'] = $this->language->get('entry_download');
		$this->data['entry_download_status'] = $this->language->get('entry_download_status');
		$this->data['entry_cache'] = $this->language->get('entry_cache');
		$this->data['entry_compression'] = $this->language->get('entry_compression');
 
		$this->data['help_order_status'] = $this->language->get('help_order_status');
		$this->data['help_stock_check'] = $this->language->get('help_stock_check');
		$this->data['help_stock_checkout'] = $this->language->get('help_stock_checkout');
		$this->data['help_stock_subtract'] = $this->language->get('help_stock_subtract');
		$this->data['help_download_status'] = $this->language->get('help_download_status');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_shop'] = $this->language->get('tab_shop');
		$this->data['tab_admin'] = $this->language->get('tab_admin');
		$this->data['tab_local'] = $this->language->get('tab_local');
		$this->data['tab_option'] = $this->language->get('tab_option');
		$this->data['tab_cache'] = $this->language->get('tab_cache');

		$this->data['error_warning'] = @$this->error['warning'];
		
		$this->data['error_store'] = @$this->error['store'];
		
		$this->load->model('localisation/language');
		
		$languages = $this->model_localisation_language->getLanguages();
		 
		foreach ($languages as $language) {
			$this->data['error_welcome_' . $language['language_id']] = @$this->error['welcome_' . $language['language_id']];
		}
		
		$this->data['error_owner'] = @$this->error['owner'];
		$this->data['error_address'] = @$this->error['address'];
		$this->data['error_email'] = @$this->error['email'];
		$this->data['error_telephone'] = @$this->error['telephone'];

  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('setting/setting'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->data['success'] = @$this->session->data['success'];
		
		unset($this->session->data['success']);
		
		$this->data['action'] = $this->url->https('setting/setting');
		
		$this->data['cancel'] = $this->url->https('setting/setting');
		
		if (isset($this->request->post['config_store'])) {
			$this->data['config_store'] = $this->request->post['config_store'];
		} else {
			$this->data['config_store'] = $this->config->get('config_store');
		}
			
		foreach ($languages as $language) {
			if (isset($this->request->post['config_welcome_' . $language['language_id']])) {
				$this->data['config_welcome_' . $language['language_id']] = $this->request->post['config_welcome_' . $language['language_id']];
			} else {
				$this->data['config_welcome_' . $language['language_id']] = $this->config->get('config_welcome_' . $language['language_id']);
			}
		}

		if (isset($this->request->post['config_owner'])) {
			$this->data['config_owner'] = $this->request->post['config_owner'];
		} else {
			$this->data['config_owner'] = $this->config->get('config_owner');
		}

		if (isset($this->request->post['config_address'])) {
			$this->data['config_address'] = $this->request->post['config_address'];
		} else {
			$this->data['config_address'] = $this->config->get('config_address');
		}
		
		if (isset($this->request->post['config_email'])) {
			$this->data['config_email'] = $this->request->post['config_email'];
		} else {
			$this->data['config_email'] = $this->config->get('config_email');
		}
		
		if (isset($this->request->post['config_telephone'])) {
			$this->data['config_telephone'] = $this->request->post['config_telephone'];
		} else {
			$this->data['config_telephone'] = $this->config->get('config_telephone');
		}

		if (isset($this->request->post['config_fax'])) {
			$this->data['config_fax'] = $this->request->post['config_fax'];
		} else {
			$this->data['config_fax'] = $this->config->get('config_fax');
		}

		if (isset($this->request->post['config_url_alias'])) {
			$this->data['config_url_alias'] = $this->request->post['config_url_alias'];
		} else {
			$this->data['config_url_alias'] = $this->config->get('config_url_alias');
		}
		
		if (isset($this->request->post['config_parse_time'])) {
			$this->data['config_parse_time'] = $this->request->post['config_parse_time'];
		} else {
			$this->data['config_parse_time'] = $this->config->get('config_parse_time');
		}
		
		if (isset($this->request->post['config_ssl'])) {
			$this->data['config_ssl'] = $this->request->post['config_ssl'];
		} else {
			$this->data['config_ssl'] = $this->config->get('config_ssl');
		}
		
		if (isset($this->request->post['config_country_id'])) {
			$this->data['config_country_id'] = $this->request->post['config_country_id'];
		} else {
			$this->data['config_country_id'] = $this->config->get('config_country_id');
		}
		
		$this->load->model('localisation/country');
		
		$this->data['countries'] = $this->model_localisation_country->getCountries();

		if (isset($this->request->post['config_zone_id'])) {
			$this->data['config_zone_id'] = $this->request->post['config_zone_id'];
		} else {
			$this->data['config_zone_id'] = $this->config->get('config_zone_id');
		}

		if (isset($this->request->post['config_language'])) {
			$this->data['config_language'] = $this->request->post['config_language'];
		} else {
			$this->data['config_language'] = $this->config->get('config_language');
		}
		
		$this->load->model('localisation/language');
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['config_currency'])) {
			$this->data['config_currency'] = $this->request->post['config_currency'];
		} else {
			$this->data['config_currency'] = $this->config->get('config_currency');
		}
		
		$this->load->model('localisation/currency');
		
		$this->data['currencies'] = $this->model_localisation_currency->getCurrencies();

		if (isset($this->request->post['config_weight_class_id'])) {
			$this->data['config_weight_class_id'] = $this->request->post['config_weight_class_id'];
		} else {
			$this->data['config_weight_class_id'] = $this->config->get('config_weight_class_id');
		}
		
		$this->load->model('localisation/weight_class');
		
		$this->data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();
				
		if (isset($this->request->post['config_tax'])) {
			$this->data['config_tax'] = $this->request->post['config_tax'];
		} else {
			$this->data['config_tax'] = $this->config->get('config_tax');
		}

		if (isset($this->request->post['config_stock_check'])) {
			$this->data['config_stock_check'] = $this->request->post['config_stock_check'];
		} else {
			$this->data['config_stock_check'] = $this->config->get('config_stock_check');
		}

		if (isset($this->request->post['config_stock_checkout'])) {
			$this->data['config_stock_checkout'] = $this->request->post['config_stock_checkout'];
		} else {
			$this->data['config_stock_checkout'] = $this->config->get('config_stock_checkout');
		}

		if (isset($this->request->post['config_stock_subtract'])) {
			$this->data['config_stock_subtract'] = $this->request->post['config_stock_subtract'];
		} else {
			$this->data['config_stock_subtract'] = $this->config->get('config_stock_subtract');
		}

		$this->load->model('localisation/order_status');
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['config_order_status_id'])) {
			$this->data['config_order_status_id'] = $this->request->post['config_order_status_id'];
		} else {
			$this->data['config_order_status_id'] = $this->config->get('config_order_status_id');
		}
		
		$this->load->model('localisation/stock_status');
		
		$this->data['stock_statuses'] = $this->model_localisation_stock_status->getStockStatuses();

		if (isset($this->request->post['config_stock_status_id'])) {
			$this->data['config_stock_status_id'] = $this->request->post['config_stock_status_id'];
		} else {
			$this->data['config_stock_status_id'] = $this->config->get('config_stock_status_id');
		}
		
		if (isset($this->request->post['config_download'])) {
			$this->data['config_download'] = $this->request->post['config_download'];
		} else {
			$this->data['config_download'] = $this->config->get('config_download');
		}

		if (isset($this->request->post['config_download_status'])) {
			$this->data['config_download_status'] = $this->request->post['config_download_status'];
		} else {
			$this->data['config_download_status'] = $this->config->get('config_download_status');
		}
		
		if (isset($this->request->post['config_cache'])) {
			$this->data['config_cache'] = $this->request->post['config_cache'];
		} else {
			$this->data['config_cache'] = $this->config->get('config_cache');
		}
				
		if (isset($this->request->post['config_compression'])) {
			$this->data['config_compression'] = $this->request->post['config_compression']; 
		} else {
			$this->data['config_compression'] = $this->config->get('config_compression');
		}
		
		$this->id       = 'content'; 
		$this->template = 'setting/setting.tpl';
		$this->layout   = 'module/layout';
				
		$this->render();
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'setting/setting')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['config_store']) {
			$this->error['store'] = $this->language->get('error_store');
		}				
		
		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();
		
		foreach ($languages as $language) {
			if (!$this->request->post['config_welcome_' . $language['language_id']]) {
				$this->error['welcome_' . $language['language_id']] = $this->language->get('error_welcome');
			}	
		}

		if ((strlen($this->request->post['config_owner']) < 3) || (strlen($this->request->post['config_owner']) > 32)) {
			$this->error['owner'] = $this->language->get('error_owner');
		}

		if ((strlen($this->request->post['config_address']) < 3) || (strlen($this->request->post['config_address']) > 128)) {
			$this->error['address'] = $this->language->get('error_address');
		}
		
    	if ((strlen($this->request->post['config_email']) > 32) || (!eregi('^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$', $this->request->post['config_email']))) {
      		$this->error['email'] = $this->language->get('error_email');
    	}

    	if ((strlen($this->request->post['config_telephone']) < 3) || (strlen($this->request->post['config_telephone']) > 32)) {
      		$this->error['telephone'] = $this->language->get('error_telephone');
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
		
		$results = $this->model_localisation_zone->getZonesByCountryId(@$this->request->get['country_id']);
		
		foreach ($results as $result) {
			$output .= '<option value="' . $result['zone_id'] . '"';

			if (@$this->request->get['zone_id'] == $result['zone_id']) {
				$output .= ' selected="selected"';
			}

			$output .= '>' . $result['name'] . '</option>';
		}

		if (!$results) {
			$output .= '<option value="0">' . $this->language->get('text_none') . '</option>';
		}

		$this->response->setOutput($output);
	}
}
?>