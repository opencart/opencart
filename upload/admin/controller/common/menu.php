<?php
class ControllerCommonMenu extends Controller {
	public function index() {
		$this->load->language('common/menu');

		// Create a 3 level menu array
		// Level 2 can not have children

		// Menu
		$data['menus'][] = array(
			'id'       => 'menu-dashboard',
			'icon'	   => 'fa-dashboard',
			'name'	   => $this->language->get('text_dashboard'),
			'href'     => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true),
			'children' => array()
		);

		// Catalog
		// Attribute
		$attribute_children = array(
			'catalog/attribute'	  => array(),
			'catalog/attribute_group' => array()
		);

		$catalog_children = array(
			'catalog/category'     => array(),
			'catalog/product'      => array(),
			'catalog/recurring'    => array(),
			'catalog/filter'       => array(),
			'attribute'	       => $attribute_children,
			'catalog/option'       => array(),
			'catalog/manufacturer' => array(),
			'catalog/download'     => array(),
			'catalog/review'       => array(),
			'catalog/information'  => array()
		);

		$catalog = $this->menu($catalog_children);

		if ($catalog) {
			$data['menus'][] = array(
				'id'       => 'menu-catalog',
				'icon'	   => 'fa-tags',
				'name'	   => $this->language->get('text_catalog'),
				'href'     => '',
				'children' => $catalog
			);
		}

		// Extension
		$extension_children = array(
			'extension/extension'	 => array(),
			'extension/modification' => array(),
			'extension/event'	 => array()
		);

		$extension = $this->menu($extension_children);

		if ($extension) {
			$data['menus'][] = array(
				'id'       => 'menu-extension',
				'icon'	   => 'fa-puzzle-piece',
				'name'	   => $this->language->get('text_extension'),
				'href'     => '',
				'children' => $extension
			);
		}

		// Design
		$design_children = array(
			'design/layout'	     => array(),
			'design/menu'	     => array(),
			'design/theme'       => array(),
			'design/translation' => array(),
			'design/banner'	     => array()
		);

		$design = $this->menu($design_children);

		if ($design) {
			$data['menus'][] = array(
				'id'       => 'menu-design',
				'icon'	   => 'fa-television',
				'name'	   => $this->language->get('text_design'),
				'href'     => '',
				'children' => $design
			);
		}

		// Sales
		// Voucher
		$voucher_children = array(
			'sale/voucher'	      => array(),
			 'sale/voucher_theme' => array()
		);

		$sale_children = array(
			'sale/order'     => array(),
			'sale/recurring' => array(),
			'sale/return'    => array(),
			'voucher'	 => $voucher_children
		);

		$sale = $this->menu($sale_children);

		if ($sale) {
			$data['menus'][] = array(
				'id'       => 'menu-sale',
				'icon'	   => 'fa-shopping-cart',
				'name'	   => $this->language->get('text_sale'),
				'href'     => '',
				'children' => $sale
			);
		}

		// Customer
		$customer_children = array(
			'customer/customer'       => array(),
			'customer/customer_group' => array(),
			'customer/custom_field'   => array()
		);

		$customer = $this->menu($customer_children);

		if ($customer) {
			$data['menus'][] = array(
				'id'       => 'menu-customer',
				'icon'	   => 'fa-user',
				'name'	   => $this->language->get('text_customer'),
				'href'     => '',
				'children' => $customer
			);
		}

		// Marketing
		$marketing_children = array(
			'marketing/marketing' => array(),
			'marketing/affiliate' => array(),
			'marketing/coupon'    => array(),
			'marketing/contact'   => array()
		);

		$marketing = $this->menu($marketing_children);

		if ($marketing) {
			$data['menus'][] = array(
				'id'       => 'menu-marketing',
				'icon'	   => 'fa-share-alt',
				'name'	   => $this->language->get('text_marketing'),
				'href'     => '',
				'children' => $marketing
			);
		}

		// System
		// Users
		$user_children = array(
			'user/user'	       => array(),
			'user/user_permission' => array(),
			'user/api'	       => array()
		);

		// Localisation
		// Returns
		$return_children = array(
			'localisation/return_status' => array(),
			'localisation/return_action' => array(),
			'localisation/return_reason' => array()
		);

		// Tax
		$tax_children = array(
			'localisation/tax_class' => array(),
			'localisation/tax_rate'	 => array()
		);

		$localisation_children = array(
			'localisation/location'	    => array(),
			'localisation/language'	    => array(),
			'localisation/currency'	    => array(),
			'localisation/stock_status' => array(),
			'localisation/order_status' => array(),
			'return'		    => $return_children,
			'localisation/country'	    => array(),
			'localisation/zone'	    => array(),
			'localisation/geo_zone'	    => array(),
			'tax'			    => $tax_children,
			'localisation/length_class' => array(),
			'localisation/weight_class' => array()
		);

		// Tools
		$tool_children = array(
			'tool/upload' => array(),
			'tool/backup' => array(),
			'tool/log'    => array()
		);

		$system_children = array(
			'setting/setting' => array(),
			'user'		  => $user_children,
			'localisation'	  => $localisation_children,
			'tool'		  => $tool_children
		);

		$system = $this->menu($system_children);

		if ($system) {
			$data['menus'][] = array(
				'id'       => 'menu-system',
				'icon'	   => 'fa-cog',
				'name'	   => $this->language->get('text_system'),
				'href'     => '',
				'children' => $system
			);
		}

		// Report
		// Report Sales
		$report_sale_children = array(
			'report/sale_order'    => array(),
			'report/sale_tax'      => array(),
			'report/sale_shipping' => array(),
			'report/sale_return'   => array(),
			'report/sale_coupon'   => array()
		);

		// Report Products
		$report_product_children = array(
			'report/product_viewed'    => array(),
			'report/product_purchased' => array()
		);

		// Report Customers
		$report_customer_children = array(
			'report/customer_online'   => array(),
			'report/customer_activity' => array(),
			'report/customer_order'    => array(),
			'report/customer_reward'   => array(),
			'report/customer_credit'   => array()
		);

		// Report Marketing
		$report_marketing_children = array(
			'report/marketing'	   => array(),
			'report/affiliate'	    => array(),
			'report/affiliate_activity' => array()
		);

		$report_children = array(
			'sale'	    => $report_sale_children,
			'product'   => $report_product_children,
			'customer'  => $report_customer_children,
			'marketing' => $report_marketing_children
		);

		$report = $this->menu($report_children);

		if ($report) {
			$data['menus'][] = array(
				'id'       => 'menu-report',
				'icon'	   => 'fa-bar-chart-o',
				'name'	   => $this->language->get('text_reports'),
				'href'     => '',
				'children' => $report
			);
		}

		return $this->load->view('common/menu', $data);
	}

	private function menu($menu) {
		$childrens = array();

		foreach ($menu as $key => $new_level) {
			if ($new_level) {
				$level_childrens = $this->menu($new_level);

				if ($level_childrens) {
					$childrens[] = array(
						'name'	   => $this->language->get('text_' . $key),
						'href'     => '',
						'children' => $level_childrens
					);
				}
			} else {
				if ($this->user->hasPermission('access', $key)) {
					$part = explode('/', $key);

					$childrens[] = array(
						'name'	   => $this->language->get('text_' . $part[1]),
						'href'     => $this->url->link($key, 'token=' . $this->session->data['token'], true),
						'children' => array()
					);
				}
			}
		}

		return $childrens;
	}
}
