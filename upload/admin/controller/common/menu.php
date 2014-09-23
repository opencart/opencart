<?php
class ControllerCommonMenu extends Controller {
	
	private $menu = array();
	
	public function index() {

		$this->load->language('common/menu');

		$data['profile'] = $this->load->controller('common/profile');
		
		// Catalog
		$this->addMenu('catalog',  $this->language->get('text_catalog'), null, 'fa-tags');
		$this->addPage('catalog/category', $this->language->get('text_category'), 'catalog');
		$this->addPage('catalog/product', $this->language->get('text_product'), 'catalog');
		$this->addPage('catalog/recurring', $this->language->get('text_recurring'), 'catalog');
		$this->addPage('catalog/filter', $this->language->get('text_filter'), 'catalog');
		
		$this->addMenu('catalog_attribute',  $this->language->get('text_attribute'), 'catalog');
		$this->addPage('catalog/attribute', $this->language->get('text_attribute'), 'catalog_attribute');
		$this->addPage('catalog/attribute_group', $this->language->get('text_attribute_group'), 'catalog_attribute');
		
		$this->addPage('catalog/option', $this->language->get('text_option'), 'catalog');
		$this->addPage('catalog/manufacturer', $this->language->get('text_manufacturer'), 'catalog');
		$this->addPage('catalog/download', $this->language->get('text_download'), 'catalog');
		$this->addPage('catalog/review', $this->language->get('text_review'), 'catalog');
		$this->addPage('catalog/information', $this->language->get('text_information'), 'catalog');

		// Extensions
		$this->addMenu('extension',  $this->language->get('text_extension'), null, 'fa-puzzle-piece');
		$this->addPage('extension/installer', $this->language->get('text_installer'), 'extension');
		$this->addPage('extension/modification', $this->language->get('text_modification'), 'extension');
		$this->addPage('extension/module', $this->language->get('text_module'), 'extension');
		$this->addPage('extension/shipping', $this->language->get('text_shipping'), 'extension');
		$this->addPage('extension/payment', $this->language->get('text_payment'), 'extension');
		$this->addPage('extension/total', $this->language->get('text_total'), 'extension');
		$this->addPage('extension/feed', $this->language->get('text_feed'), 'extension');
<<<<<<< HEAD
		
		 // FIXME: Some pages don't appear on Acccess/Modify Permissions
		if ($this->config->get('openbaypro_menu') == 1) {
			$this->addMenu('openbay',  $this->language->get('text_openbay_extension'), 'extension');
			$this->addPage('extension/openbay', $this->language->get('text_openbay_dashboard'), 'openbay');
			$this->addPage('extension/openbay/orderlist', $this->language->get('text_openbay_orders'), 'openbay');
			$this->addPage('extension/openbay/itemlist', $this->language->get('text_openbay_items'), 'openbay');
			
			if ($this->config->get('ebay_status') == 1) {
				$this->addMenu('openbay_ebay',  $this->language->get('text_openbay_ebay'), 'openbay');
				$this->addPage('openbay/ebay', $this->language->get('text_openbay_dashboard'), 'openbay_ebay');
				$this->addPage('openbay/ebay/settings', $this->language->get('text_openbay_settings'), 'openbay_ebay');
				$this->addPage('openbay/ebay/viewitemlinks', $this->language->get('text_openbay_links'), 'openbay_ebay');
				$this->addPage('openbay/ebay/vieworderimport', $this->language->get('text_openbay_order_import'), 'openbay_ebay');
			}
			
			if ($this->config->get('amazon_status') == 1) {
				$this->addMenu('openbay_amazon',  $this->language->get('text_openbay_amazon'), 'openbay');
				$this->addPage('openbay/amazon', $this->language->get('text_openbay_dashboard'), 'openbay_amazon');
				$this->addPage('openbay/amazon/settings', $this->language->get('text_openbay_settings'), 'openbay_amazon');
				$this->addPage('openbay/amazon/itemlinks', $this->language->get('text_openbay_links'), 'openbay_amazon');
			}
			
			if ($this->config->get('amazonus_status') == 1) {
				$this->addMenu('openbay_amazonus',  $this->language->get('text_openbay_amazonus'), 'openbay');
				$this->addPage('openbay/amazonus', $this->language->get('text_openbay_dashboard'), 'openbay_amazonus');
				$this->addPage('openbay/amazonus/settings', $this->language->get('text_openbay_settings'), 'openbay_amazonus');
				$this->addPage('openbay/amazonus/itemlinks', $this->language->get('text_openbay_links'), 'openbay_amazonus');
			}
		}
		
=======

>>>>>>> FETCH_HEAD
		// Sales
		$this->addMenu('sale',  $this->language->get('text_sale'), null, 'fa-shopping-cart');
		$this->addPage('sale/order', $this->language->get('text_order'), 'sale');
		$this->addPage('sale/recurring', $this->language->get('text_order_recurring'), 'sale');
		$this->addPage('sale/return', $this->language->get('text_return'), 'sale');
		
		$this->addMenu('sale_customer',  $this->language->get('text_customer'), 'sale');
		$this->addPage('sale/customer', $this->language->get('text_customer'), 'sale_customer');
		$this->addPage('sale/customer_group', $this->language->get('text_customer_group'), 'sale_customer');
		$this->addPage('sale/custom_field', $this->language->get('text_custom_field'), 'sale_customer');
		$this->addPage('sale/customer_ban_ip', $this->language->get('text_customer_ban_ip'), 'sale_customer');

		$this->addMenu('sale_voucher',  $this->language->get('text_voucher'), 'sale');
		$this->addPage('sale/voucher', $this->language->get('text_voucher'), 'sale_voucher');
		$this->addPage('sale/voucher_theme', $this->language->get('text_voucher_theme'), 'sale_voucher');

		$this->addMenu('sale_paypal', $this->language->get('text_paypal'), 'sale');
		$this->addPage('payment/pp_express/search', $this->language->get('text_paypal_search'), 'sale_paypal'); // FIXME: This doesn't appear on Acccess/Modify Permissions

		// Marketing
		$this->addMenu('marketing',  $this->language->get('text_marketing'), null, 'fa-share-alt');
		$this->addPage('marketing/marketing', $this->language->get('text_marketing'), 'marketing');
		$this->addPage('marketing/affiliate', $this->language->get('text_affiliate'), 'marketing');
		$this->addPage('marketing/coupon', $this->language->get('text_coupon'), 'marketing');	
		$this->addPage('marketing/contact', $this->language->get('text_contact'), 'marketing');
			
		// System
		$this->addMenu('system',  $this->language->get('text_system'), null, 'fa-cog');
		$this->addPage('setting/store', $this->language->get('text_setting'), 'system');
		
		$this->addMenu('system_design',  $this->language->get('text_design'), 'system');
		$this->addPage('design/layout', $this->language->get('text_layout'), 'system_design');
		$this->addPage('design/banner', $this->language->get('text_banner'), 'system_design');
		
		$this->addMenu('system_user',  $this->language->get('text_users'), 'system');
		$this->addPage('user/user', $this->language->get('text_user'), 'system_user');
		$this->addPage('user/user_permission', $this->language->get('text_user_group'), 'system_user');
		$this->addPage('user/api', $this->language->get('text_api'), 'system_user');

		$this->addMenu('system_localisation',  $this->language->get('text_localisation'), 'system');
		$this->addPage('localisation/location', $this->language->get('text_location'), 'system_localisation');
		$this->addPage('localisation/language', $this->language->get('text_language'), 'system_localisation');
		$this->addPage('localisation/currency', $this->language->get('text_currency'), 'system_localisation');
		$this->addPage('localisation/stock_status', $this->language->get('text_stock_status'), 'system_localisation');
		$this->addPage('localisation/order_status', $this->language->get('text_order_status'), 'system_localisation');

		$this->addMenu('system_localisation_return',  $this->language->get('text_return'), 'system_localisation');
		$this->addPage('localisation/return_status', $this->language->get('text_return_status'), 'system_localisation_return');
		$this->addPage('localisation/return_action', $this->language->get('text_return_action'), 'system_localisation_return');
		$this->addPage('localisation/return_reason', $this->language->get('text_return_reason'), 'system_localisation_return');
<<<<<<< HEAD

		$this->addPage('localisation/country', $this->language->get('text_country'), 'system_localisation');
		$this->addPage('localisation/zone', $this->language->get('text_zone'), 'system_localisation');			
		$this->addPage('localisation/geo_zone', $this->language->get('text_geo_zone'), 'system_localisation');

=======

		$this->addPage('localisation/country', $this->language->get('text_country'), 'system_localisation');
		$this->addPage('localisation/zone', $this->language->get('text_zone'), 'system_localisation');			
		$this->addPage('localisation/geo_zone', $this->language->get('text_geo_zone'), 'system_localisation');

>>>>>>> FETCH_HEAD
		$this->addMenu('system_localisation_tax',  $this->language->get('text_tax'), 'system_localisation');
		$this->addPage('localisation/tax_class', $this->language->get('text_tax_class'), 'system_localisation_tax');
		$this->addPage('localisation/tax_rate', $this->language->get('text_tax_rate'), 'system_localisation_tax');
		
		$this->addPage('localisation/length_class', $this->language->get('text_length_class'), 'system_localisation');
		$this->addPage('localisation/weight_class', $this->language->get('text_weight_class'), 'system_localisation');

		// Tools
		$this->addMenu('tools',  $this->language->get('text_tools'), null, 'fa-wrench');
		$this->addPage('tool/upload', $this->language->get('text_upload'), 'tools');
		$this->addPage('tool/backup', $this->language->get('text_backup'), 'tools');
		$this->addPage('tool/error_log', $this->language->get('text_error_log'), 'tools');
		
		// Reports
		$this->addMenu('reports',  $this->language->get('text_reports'), null, 'fa-bar-chart-o');
		
		$this->addMenu('reports_sale',  $this->language->get('text_sale'), 'reports');
		$this->addPage('report/sale_order', $this->language->get('text_report_sale_order'), 'reports_sale');
		$this->addPage('report/sale_tax', $this->language->get('text_report_sale_tax'), 'reports_sale');
		$this->addPage('report/sale_shipping', $this->language->get('text_report_sale_shipping'), 'reports_sale');
		$this->addPage('report/sale_return', $this->language->get('text_report_sale_return'), 'reports_sale');
		$this->addPage('report/sale_coupon', $this->language->get('text_report_sale_coupon'), 'reports_sale');
	
		$this->addMenu('reports_product',  $this->language->get('text_product'), 'reports');
		$this->addPage('report/product_viewed', $this->language->get('text_report_product_viewed'), 'reports_product');
		$this->addPage('report/product_purchased', $this->language->get('text_report_product_purchased'), 'reports_product');
		
		$this->addMenu('reports_customer',  $this->language->get('text_customer'), 'reports');
		$this->addPage('report/customer_online', $this->language->get('text_report_customer_online'), 'reports_customer');
		$this->addPage('report/customer_activity', $this->language->get('text_report_customer_activity'), 'reports_customer');
		$this->addPage('report/customer_order', $this->language->get('text_report_customer_order'), 'reports_customer');
		$this->addPage('report/customer_reward', $this->language->get('text_report_customer_reward'), 'reports_customer');
		$this->addPage('report/customer_credit', $this->language->get('text_report_customer_credit'), 'reports_customer');
		
		$this->addMenu('reports_marketing',  $this->language->get('text_marketing'), 'reports');
		$this->addPage('report/marketing', $this->language->get('text_marketing'), 'reports_marketing');
		$this->addPage('report/affiliate', $this->language->get('text_affiliate'), 'reports_marketing');
		$this->addPage('report/affiliate_activity', $this->language->get('text_affiliate_activity'), 'reports_marketing');

		// Menu Output
		$data['menu'] = $this->getMenu();
		
		return $this->load->view('common/menu.tpl', $data);

	}
	
	/*
	*  Function to Add Menu or Sub-Menu
	*
	*  @param	{string}	$name - A name to identify the menu
	*  @param	{string}	$title - Menu title
	*  @param	{string}	$parent - Parent menu name when is a sub-menu
	*  @param	{string}	$icon - CSS class to set icon, not used in sub-menus
	*/
	private function addMenu($name, $title, $parent = null, $icon = null) {
		array_push($this->menu, array(
			'parent' => $parent,
			'name' => $name,
			'title' => $title,
			'route' => null,
			'params' => array(),
			'icon' => $icon
		));
	}

	/*
	*  Function to Add Pages
	*
	*  @param	{string}	$route - Page route
	*  @param	{string}	$title - Page title
	*  @param	{string}	$parent - Parent menu name when exists a connection
	*  @param	{array}		$params - URL params to be passed, token is already setted
<<<<<<< HEAD
	*/
	private function addPage($route, $title, $parent = null, $params = array()) {
=======
	*  @param	{string}	$icon - CSS class to set icon, not used in sub-menus
	*/
	private function addPage($route, $title, $parent = null, $params = array(), $icon = null) {
>>>>>>> FETCH_HEAD
		$params['token'] = $this->session->data['token'];
		array_push($this->menu, array(
			'parent' => $parent,
			'name' => null,
			'title' => $title,
			'route' => $route,
			'params' => $params,
<<<<<<< HEAD
			'icon' => null
=======
			'icon' => $icon
>>>>>>> FETCH_HEAD
		));
	}
	
	private function getMenu() {
		$this->checkPermissions();
		$this->cleanupNodes();
		return $this->buildList();
	}

	private function buildList($parent = null, $html = null) {

		if (empty($html)) {
			$html = PHP_EOL . '<ul id="menu">';
<<<<<<< HEAD
			$html .= PHP_EOL . '<li id="menu-dashboard"><a href="' . $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL') . '"><i class="fa fa-dashboard fa-fw"></i> <span>' . $this->language->get('text_dashboard') . '</span></a></li>';
=======
			$html .= PHP_EOL . '<li id="menu-dashboard"><a href="' . $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL') . '"><i class="fa fa-home fa-fw"></i> ' . $this->language->get('text_dashboard') . '</a></li>';
>>>>>>> FETCH_HEAD
		} else {
			$html .= PHP_EOL . '<ul>';		
		}
		
		foreach ($this->getChildren($parent) as $item) {
		
			if (!empty($item['name'])) {
				$children = $this->getChildren($item['name']);
				if (empty($children))
					continue;

			}
		
			// First level
			if ($parent == null) {

<<<<<<< HEAD
				$html .= PHP_EOL . '<li id="menu-' . $item['name'] . '"><a class="parent">';
				$html .= '<i class="fa fa-fw ' . (empty($item['icon']) ? 'fa-circle' : $item['icon']) . '"></i> ';
				$html .= '<span>' . $item['title'] . '</span></a>';
=======
				$html .= PHP_EOL . '<li id="menu-' . $item['name'] . '"><a ';
				$html .= empty($item['route']) ? 'class="parent">' : 'href="' . $this->url->link($item['route'], $this->buildParams($item['params']), 'SSL') . '">';
				$html .= empty($item['icon']) ? null : '<i class="fa fa-fw ' . $item['icon'] . '"></i> ';
				$html .= $item['title'] . '</a>';
>>>>>>> FETCH_HEAD
				
				if (!empty($item['name']))
					$html = $this->buildList($item['name'], $html);
				
				$html .= '</li>';
			
			// Next levels
			} else {

				$html .= PHP_EOL . '<li><a ';
				$html .= empty($item['route']) ? 'class="parent">' : 'href="' . $this->url->link($item['route'], $this->buildParams($item['params']), 'SSL') . '">';
				$html .= $item['title'] . '</a>';
				
				if (!empty($item['name']))
					$html = $this->buildList($item['name'], $html);
				
				$html .= '</li>';

			}		

		}
		
		$html .= PHP_EOL .'</ul>' . PHP_EOL;	
		
		return $html;
	}
	
	private function getChildren($parent) {
		$output = array();
		foreach ($this->menu as $key => $item) {
			if($item['parent'] == $parent)
				$output[$key] = $item;		
		}		
		return $output;
	}
	
	private function checkPermissions() {
		foreach ($this->menu as $key => $item) {
			if (empty($item['route']) || $item['route'] == 'common/dashboard')
				continue;
			if ($this->user->hasPermission('access', $item['route']))
				continue;
			unset($this->menu[$key]);
		}
	}

	private function cleanupNodes($parent = null) {
		foreach($this->getChildren($parent) as $key => $item) {
			if (!empty($item['name'])) {
				$result = $this->cleanupNodes($item['name']);
				if (empty($result))
					unset($this->menu[$key]);
			}
		}
		return count($this->getChildren($parent));
	}

	private function buildParams($params) {
		foreach($params as $key => $value) {
			$params[$key] = "$key=$value";
		}
<<<<<<< HEAD
		return implode('&amp;', $params);
=======
		return implode('&', $params);
>>>>>>> FETCH_HEAD
	}
	
}
