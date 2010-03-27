<?php 
class ControllerCommonHeader extends Controller {
	protected function index() {
		$this->load->language('common/header');

		$this->data['title'] = $this->document->title;
		
		$this->data['base'] = HTTPS_SERVER;
		$this->data['charset'] = $this->language->get('charset');
		$this->data['lang'] = $this->language->get('code');	
		$this->data['direction'] = $this->language->get('direction');
		$this->data['links'] = $this->document->links;	
		$this->data['styles'] = $this->document->styles;
		$this->data['scripts'] = $this->document->scripts;
		$this->data['breadcrumbs'] = $this->document->breadcrumbs;
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_affiliate'] = $this->language->get('text_affiliate');
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
		$this->data['text_front'] = $this->language->get('text_front');
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
		$this->data['text_system'] = $this->language->get('text_system');
		$this->data['text_tax_class'] = $this->language->get('text_tax_class');
		$this->data['text_total'] = $this->language->get('text_total');
		$this->data['text_user'] = $this->language->get('text_user');
		$this->data['text_user_group'] = $this->language->get('text_user_group');
		$this->data['text_users'] = $this->language->get('text_users');
      	$this->data['text_documentation'] = $this->language->get('text_documentation');
      	$this->data['text_weight_class'] = $this->language->get('text_weight_class');
		$this->data['text_length_class'] = $this->language->get('text_length_class');
		$this->data['text_opencart'] = $this->language->get('text_opencart');
      	$this->data['text_zone'] = $this->language->get('text_zone');
		
		if ($this->user->isLogged()) {
			$this->data['logged'] = sprintf($this->language->get('text_logged'), $this->user->getUserName());
		} else {
			$this->data['logged'] = '';
		}
		
		$this->data['affiliate'] = HTTPS_SERVER . 'index.php?route=extension/affiliate';
		$this->data['backup'] = HTTPS_SERVER . 'index.php?route=tool/backup';
		$this->data['category'] = HTTPS_SERVER . 'index.php?route=catalog/category';
		$this->data['country'] = HTTPS_SERVER . 'index.php?route=localisation/country';
		$this->data['currency'] = HTTPS_SERVER . 'index.php?route=localisation/currency';
		$this->data['coupon'] = HTTPS_SERVER . 'index.php?route=sale/coupon';
		$this->data['customer'] = HTTPS_SERVER . 'index.php?route=sale/customer';
		$this->data['customer_group'] = HTTPS_SERVER . 'index.php?route=sale/customer_group';
		$this->data['download'] = HTTPS_SERVER . 'index.php?route=catalog/download';
		$this->data['error_log'] = HTTPS_SERVER . 'index.php?route=tool/error_log';
		$this->data['feed'] = HTTPS_SERVER . 'index.php?route=extension/feed';			
		
		$this->data['stores'] = array();
		
		$this->load->model('setting/store');
		
		$results = $this->model_setting_store->getStores();
		
		foreach ($results as $result) {
			$this->data['stores'][] = array(
				'name' => $result['name'],
				'href' => $result['url']
			);
		}
		
		$this->data['geo_zone'] = HTTPS_SERVER . 'index.php?route=localisation/geo_zone';
		$this->data['home'] = HTTPS_SERVER . 'index.php?route=common/home'; 
		$this->data['information'] = HTTPS_SERVER . 'index.php?route=catalog/information';
		$this->data['language'] = HTTPS_SERVER . 'index.php?route=localisation/language';
		$this->data['logout'] = HTTPS_SERVER . 'index.php?route=common/logout';
		$this->data['contact'] = HTTPS_SERVER . 'index.php?route=sale/contact';
		$this->data['manufacturer'] = HTTPS_SERVER . 'index.php?route=catalog/manufacturer';
		$this->data['module'] = HTTPS_SERVER . 'index.php?route=extension/module';
		$this->data['order'] = HTTPS_SERVER . 'index.php?route=sale/order';
		$this->data['order_status'] = HTTPS_SERVER . 'index.php?route=localisation/order_status';
		$this->data['payment'] = HTTPS_SERVER . 'index.php?route=extension/payment';
		$this->data['product'] = HTTPS_SERVER . 'index.php?route=catalog/product';
		$this->data['report_purchased'] = HTTPS_SERVER . 'index.php?route=report/purchased';
		$this->data['report_sale'] = HTTPS_SERVER . 'index.php?route=report/sale';
      	$this->data['report_viewed'] = HTTPS_SERVER . 'index.php?route=report/viewed';
		$this->data['review'] = HTTPS_SERVER . 'index.php?route=catalog/review';
		$this->data['shipping'] = HTTPS_SERVER . 'index.php?route=extension/shipping';
		$this->data['setting'] = HTTPS_SERVER . 'index.php?route=setting/setting';
		$this->data['store'] = HTTP_CATALOG;
		$this->data['stock_status'] = HTTPS_SERVER . 'index.php?route=localisation/stock_status';
      	$this->data['tax_class'] = HTTPS_SERVER . 'index.php?route=localisation/tax_class';
		$this->data['total'] = HTTPS_SERVER . 'index.php?route=extension/total';
		$this->data['user'] = HTTPS_SERVER . 'index.php?route=user/user';
      	$this->data['user_group'] = HTTPS_SERVER . 'index.php?route=user/user_permission';
      	$this->data['weight_class'] = HTTPS_SERVER . 'index.php?route=localisation/weight_class';
		$this->data['length_class'] = HTTPS_SERVER . 'index.php?route=localisation/length_class';
      	$this->data['zone'] = HTTPS_SERVER . 'index.php?route=localisation/zone';

		$this->id       = 'header';
		$this->template = 'common/header.tpl';
		
		$this->render();
	}
}
?>