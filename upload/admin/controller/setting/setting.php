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
		$this->data['entry_meta_description'] = $this->language->get('entry_meta_description');
		$this->data['entry_logo'] = $this->language->get('entry_logo');
		$this->data['entry_welcome'] = $this->language->get('entry_welcome');
		$this->data['entry_owner'] = $this->language->get('entry_owner');
		$this->data['entry_address'] = $this->language->get('entry_address');
		$this->data['entry_email'] = $this->language->get('entry_email');
		$this->data['entry_telephone'] = $this->language->get('entry_telephone');
		$this->data['entry_fax'] = $this->language->get('entry_fax');
		$this->data['entry_template'] = $this->language->get('entry_template');
		$this->data['entry_country'] = $this->language->get('entry_country');
		$this->data['entry_zone'] = $this->language->get('entry_zone');
		$this->data['entry_language'] = $this->language->get('entry_language');
		$this->data['entry_admin_language'] = $this->language->get('entry_admin_language');
		$this->data['entry_currency'] = $this->language->get('entry_currency');
		$this->data['entry_currency_auto'] = $this->language->get('entry_currency_auto');
		$this->data['entry_tax'] = $this->language->get('entry_tax');
		$this->data['entry_weight'] = $this->language->get('entry_weight');
		$this->data['entry_alert_mail'] = $this->language->get('entry_alert_mail');
		$this->data['entry_account'] = $this->language->get('entry_account');
		$this->data['entry_checkout'] = $this->language->get('entry_checkout');
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');
		$this->data['entry_stock_check'] = $this->language->get('entry_stock_check');
		$this->data['entry_stock_checkout'] = $this->language->get('entry_stock_checkout');
		$this->data['entry_stock_subtract'] = $this->language->get('entry_stock_subtract');
		$this->data['entry_stock_status'] = $this->language->get('entry_stock_status');
		$this->data['entry_download'] = $this->language->get('entry_download');
		$this->data['entry_download_status'] = $this->language->get('entry_download_status');
		$this->data['entry_ssl'] = $this->language->get('entry_ssl');
		$this->data['entry_encryption'] = $this->language->get('entry_encryption');
		$this->data['entry_seo_url'] = $this->language->get('entry_seo_url');
		$this->data['entry_compression'] = $this->language->get('entry_compression');
		$this->data['entry_parse_time'] = $this->language->get('entry_parse_time');

 		$this->data['help_currency_auto'] = $this->language->get('help_currency_auto');
		$this->data['help_alert_mail'] = $this->language->get('help_alert_mail');
		$this->data['help_account'] = $this->language->get('help_account');
		$this->data['help_checkout'] = $this->language->get('help_checkout');
		$this->data['help_order_status'] = $this->language->get('help_order_status');
		$this->data['help_stock_check'] = $this->language->get('help_stock_check');
		$this->data['help_stock_checkout'] = $this->language->get('help_stock_checkout');
		$this->data['help_stock_subtract'] = $this->language->get('help_stock_subtract');
		$this->data['help_download_status'] = $this->language->get('help_download_status');
		$this->data['help_ssl'] = $this->language->get('help_ssl'); 
		$this->data['help_encryption'] = $this->language->get('help_encryption'); 
		$this->data['help_seo_url'] = $this->language->get('help_seo_url'); 
		$this->data['help_compression'] = $this->language->get('help_compression');
		$this->data['help_parse_time'] = $this->language->get('help_parse_time');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_shop'] = $this->language->get('tab_shop');
		$this->data['tab_server'] = $this->language->get('tab_server');
		$this->data['tab_admin'] = $this->language->get('tab_admin');
		$this->data['tab_local'] = $this->language->get('tab_local');
		$this->data['tab_option'] = $this->language->get('tab_option');

		$this->data['error_warning'] = @$this->error['warning'];
		
		$this->data['error_store'] = @$this->error['store'];
		$this->data['error_meta_description'] = @$this->error['meta_description'];
		
		$this->load->model('localisation/language');
		
		$languages = $this->model_localisation_language->getLanguages();
		 
		foreach ($languages as $language) {
			$this->data['error_welcome_' . $language['language_id']] = @$this->error['welcome_' . $language['language_id']];
		}
		
		$this->data['error_owner'] = @$this->error['owner'];
		$this->data['error_address'] = @$this->error['address'];
		$this->data['error_email'] = @$this->error['email'];
		$this->data['error_telephone'] = @$this->error['telephone'];
		$this->data['error_encryption'] = @$this->error['encryption'];

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

		if (isset($this->request->post['config_meta_description'])) {
			$this->data['config_meta_description'] = $this->request->post['config_meta_description'];
		} else {
			$this->data['config_meta_description'] = $this->config->get('config_meta_description');
		}

		if (isset($this->request->post['config_logo'])) {
			$this->data['config_logo'] = $this->request->post['config_logo'];
		} elseif ($this->config->get('config_logo')) {
			$this->data['config_logo'] = $this->config->get('config_logo');
		} else {
			$this->data['config_logo'] = 'no_image.jpg';
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
		
		$this->data['templates'] = array();
		
		$directories = glob(DIR_CATALOG . 'view/theme/*', GLOB_ONLYDIR);
		
		foreach ($directories as $directory) {
			$this->data['templates'][] = array(
				'name'  => basename($directory),
				'value' => basename($directory) . '/template/'
			);
		}
		
		if (isset($this->request->post['config_template'])) {
			$this->data['config_template'] = $this->request->post['config_template'];
		} else {
			$this->data['config_template'] = $this->config->get('config_template');
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

		if (isset($this->request->post['config_admin_language'])) {
			$this->data['config_admin_language'] = $this->request->post['config_admin_language'];
		} else {
			$this->data['config_admin_language'] = $this->config->get('config_admin_language');
		}
		
		$this->load->model('localisation/language');
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['config_currency'])) {
			$this->data['config_currency'] = $this->request->post['config_currency'];
		} else {
			$this->data['config_currency'] = $this->config->get('config_currency');
		}

		if (isset($this->request->post['config_currency_auto'])) {
			$this->data['config_currency_auto'] = $this->request->post['config_currency_auto'];
		} else {
			$this->data['config_currency_auto'] = $this->config->get('config_currency_auto');
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

		if (isset($this->request->post['config_alert_mail'])) {
			$this->data['config_alert_mail'] = $this->request->post['config_alert_mail'];
		} else {
			$this->data['config_alert_mail'] = $this->config->get('config_alert_mail');
		}
		
		if (isset($this->request->post['config_account'])) {
			$this->data['config_account'] = $this->request->post['config_account'];
		} else {
			$this->data['config_account'] = $this->config->get('config_account');
		}
		
		if (isset($this->request->post['config_checkout'])) {
			$this->data['config_checkout'] = $this->request->post['config_checkout'];
		} else {
			$this->data['config_checkout'] = $this->config->get('config_checkout');
		}

		$this->load->model('catalog/information');
		
		$this->data['informations'] = $this->model_catalog_information->getInformations();

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
		
		if (isset($this->request->post['config_ssl'])) {
			$this->data['config_ssl'] = $this->request->post['config_ssl'];
		} else {
			$this->data['config_ssl'] = $this->config->get('config_ssl');
		}

		if (isset($this->request->post['config_encryption'])) {
			$this->data['config_encryption'] = $this->request->post['config_encryption'];
		} else {
			$this->data['config_encryption'] = $this->config->get('config_encryption');
		}
		
		if (isset($this->request->post['config_seo_url'])) {
			$this->data['config_seo_url'] = $this->request->post['config_seo_url'];
		} else {
			$this->data['config_seo_url'] = $this->config->get('config_seo_url');
		}
		
		if (isset($this->request->post['config_compression'])) {
			$this->data['config_compression'] = $this->request->post['config_compression']; 
		} else {
			$this->data['config_compression'] = $this->config->get('config_compression');
		}
		
		if (isset($this->request->post['config_parse_time'])) {
			$this->data['config_parse_time'] = $this->request->post['config_parse_time'];
		} else {
			$this->data['config_parse_time'] = $this->config->get('config_parse_time');
		}

		$this->id       = 'content'; 
		$this->template = 'setting/setting.tpl';
		$this->layout   = 'common/layout';
				
		$this->render();
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'setting/setting')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['config_store']) {
			$this->error['store'] = $this->language->get('error_store');
		}				

      	if (strlen(utf8_decode($this->request->post['config_meta_description'])) > 66) {
        	$this->error['meta_description'] = $this->language->get('error_meta_description');
      	}

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();
		
		foreach ($languages as $language) {
			if (!$this->request->post['config_welcome_' . $language['language_id']]) {
				$this->error['welcome_' . $language['language_id']] = $this->language->get('error_welcome');
			}	
		}

		if ((strlen(utf8_decode($this->request->post['config_owner'])) < 3) || (strlen(utf8_decode($this->request->post['config_owner'])) > 32)) {
			$this->error['owner'] = $this->language->get('error_owner');
		}

		if ((strlen(utf8_decode($this->request->post['config_address'])) < 3) || (strlen(utf8_decode($this->request->post['config_address'])) > 128)) {
			$this->error['address'] = $this->language->get('error_address');
		}
		
    	if ((strlen(utf8_decode($this->request->post['config_email'])) > 32) || (!eregi('^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$', $this->request->post['config_email']))) {
      		$this->error['email'] = $this->language->get('error_email');
    	}

    	if ((strlen(utf8_decode($this->request->post['config_telephone'])) < 3) || (strlen(utf8_decode($this->request->post['config_telephone'])) > 32)) {
      		$this->error['telephone'] = $this->language->get('error_telephone');
    	}

		if (!@$this->request->post['config_encryption']) {
			$this->error['encryption'] = $this->language->get('error_encryption');
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
	
	public function logo() {
		if (isset($this->request->get['logo'])) {
			$this->load->helper('image');
		
			if (@$this->request->server['HTTPS'] != 'on') {
				$image = HTTP_IMAGE . basename($this->request->get['logo']);
			} else {
				$image = HTTPS_IMAGE . basename($this->request->get['logo']);
			}					
		
			$this->response->setOutput('<img src="' . $image . '" alt="" title="" style="border: 1px solid #EEEEEE;" />');
		}
	}
	
	public function template() {
		$directory = DIR_CATALOG . '/view/theme/' . dirname($this->request->get['template']) . '/';
 
		if (file_exists($directory . 'preview.png')) {
			$image = HTTP_CATALOG . 'catalog/view/theme/' . dirname($this->request->get['template']) . '/preview.png';
		} else {
			$image = HTTP_IMAGE . 'no_image.jpg';
		}
		
		$this->response->setOutput('<img src="' . $image . '" alt="" title="" style="border: 1px solid #EEEEEE;" />');
	}	
}
?>