<?php 
class ControllerCommonHeader extends Controller {
	protected function index() {
		$this->load->language('common/header');

		$this->data['title'] = $this->document->title;
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$this->data['base'] = HTTPS_SERVER;
		} else {
			$this->data['base'] = HTTP_SERVER;
		}
		
		$this->data['charset'] = $this->language->get('charset');
		$this->data['lang'] = $this->language->get('code');	
		$this->data['direction'] = $this->language->get('direction');
		$this->data['links'] = $this->document->links;	
		$this->data['styles'] = $this->document->styles;
		$this->data['scripts'] = $this->document->scripts;
		$this->data['breadcrumbs'] = $this->document->breadcrumbs;
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_backup'] = $this->language->get('text_backup');
		$this->data['text_catalog'] = $this->language->get('text_catalog');
		$this->data['text_category'] = $this->language->get('text_category');
		$this->data['text_country'] = $this->language->get('text_country');
		$this->data['text_coupon'] = $this->language->get('text_coupon');
		$this->data['text_currency'] = $this->language->get('text_currency');			
		$this->data['text_customer'] = $this->language->get('text_customer');
		$this->data['text_customer_group'] = $this->language->get('text_customer_group');
		$this->data['text_sale'] = $this->language->get('text_sale');
		$this->data['text_download'] = $this->language->get('text_download');
		$this->data['text_error_log'] = $this->language->get('text_error_log');
		$this->data['text_extension'] = $this->language->get('text_extension');
		$this->data['text_feed'] = $this->language->get('text_feed');
		$this->data['text_geo_zone'] = $this->language->get('text_geo_zone');
		$this->data['text_dashboard'] = $this->language->get('text_dashboard');
		$this->data['text_help'] = $this->language->get('text_help');
		$this->data['text_information'] = $this->language->get('text_information');
		$this->data['text_language'] = $this->language->get('text_language');
      	$this->data['text_localisation'] = $this->language->get('text_localisation');
		$this->data['text_logout'] = $this->language->get('text_logout');
		$this->data['text_contact'] = $this->language->get('text_contact');
		$this->data['text_manufacturer'] = $this->language->get('text_manufacturer');
		$this->data['text_module'] = $this->language->get('text_module');
		$this->data['text_order'] = $this->language->get('text_order');
		$this->data['text_order_status'] = $this->language->get('text_order_status');
		$this->data['text_payment'] = $this->language->get('text_payment');
		$this->data['text_product'] = $this->language->get('text_product'); 
		$this->data['text_reports'] = $this->language->get('text_reports');
		$this->data['text_report_purchased'] = $this->language->get('text_report_purchased');     		
		$this->data['text_report_sale'] = $this->language->get('text_report_sale');
      	$this->data['text_report_viewed'] = $this->language->get('text_report_viewed');
		$this->data['text_review'] = $this->language->get('text_review');
		$this->data['text_support'] = $this->language->get('text_support');
		$this->data['text_shipping'] = $this->language->get('text_shipping');		
     	$this->data['text_setting'] = $this->language->get('text_setting');
		$this->data['text_stock_status'] = $this->language->get('text_stock_status');
		$this->data['text_store'] = $this->language->get('text_store');
		$this->data['text_system'] = $this->language->get('text_system');
		$this->data['text_tax_class'] = $this->language->get('text_tax_class');
		$this->data['text_total'] = $this->language->get('text_total');
		$this->data['text_user'] = $this->language->get('text_user');
		$this->data['text_user_group'] = $this->language->get('text_user_group');
		$this->data['text_users'] = $this->language->get('text_users');
      	$this->data['text_documentation'] = $this->language->get('text_documentation');
      	$this->data['text_weight_class'] = $this->language->get('text_weight_class');
		$this->data['text_measurement_class'] = $this->language->get('text_measurement_class');
		$this->data['text_opencart'] = $this->language->get('text_opencart');
      	$this->data['text_zone'] = $this->language->get('text_zone');
		
		if ($this->user->isLogged()) {
			$this->data['logged'] = sprintf($this->language->get('text_logged'), $this->user->getUserName());
		} else {
			$this->data['logged'] = '';
		}
		 
		$this->data['backup'] = $this->url->https('tool/backup');
		$this->data['category'] = $this->url->https('catalog/category');
		$this->data['country'] = $this->url->https('localisation/country');
		$this->data['currency'] = $this->url->https('localisation/currency');
		$this->data['coupon'] = $this->url->https('sale/coupon');
		$this->data['customer'] = $this->url->https('sale/customer');
		$this->data['customer_group'] = $this->url->https('sale/customer_group');
		$this->data['download'] = $this->url->https('catalog/download');
		$this->data['error_log'] = $this->url->https('tool/error_log');
		$this->data['feed'] = $this->url->https('extension/feed');			
		$this->data['geo_zone'] = $this->url->https('localisation/geo_zone');
		$this->data['home'] = $this->url->https('common/home'); 
		$this->data['information'] = $this->url->https('catalog/information');
		$this->data['language'] = $this->url->https('localisation/language');
		$this->data['logout'] = $this->url->https('common/logout');
		$this->data['contact'] = $this->url->https('sale/contact');
		$this->data['manufacturer'] = $this->url->https('catalog/manufacturer');
		$this->data['module'] = $this->url->https('extension/module');
		$this->data['order'] = $this->url->https('sale/order');
		$this->data['order_status'] = $this->url->https('localisation/order_status');
		$this->data['payment'] = $this->url->https('extension/payment');
		$this->data['product'] = $this->url->https('catalog/product');
		$this->data['report_purchased'] = $this->url->https('report/purchased');
		$this->data['report_sale'] = $this->url->https('report/sale');
      	$this->data['report_viewed'] = $this->url->https('report/viewed');
		$this->data['review'] = $this->url->https('catalog/review');
		$this->data['shipping'] = $this->url->https('extension/shipping');
		$this->data['setting'] = $this->url->https('setting/setting');
		$this->data['stock_status'] = $this->url->https('localisation/stock_status');
		$this->data['store'] = HTTP_CATALOG;
      	$this->data['tax_class'] = $this->url->https('localisation/tax_class');
		$this->data['total'] = $this->url->https('extension/total');
		$this->data['user'] = $this->url->https('user/user');
      	$this->data['user_group'] = $this->url->https('user/user_permission');
      	$this->data['weight_class'] = $this->url->https('localisation/weight_class');
		$this->data['measurement_class'] = $this->url->https('localisation/measurement_class');
      	$this->data['zone'] = $this->url->https('localisation/zone');

		$this->id       = 'header';
		$this->template = 'common/header.tpl';
		
		$this->render();
	}
}
?>