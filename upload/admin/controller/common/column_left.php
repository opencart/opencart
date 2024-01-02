<?php
namespace Opencart\Admin\Controller\Common;
/**
 * Class Column Left
 *
 * @package Opencart\Admin\Controller\Common
 */
class ColumnLeft extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return string
	 */
	public function index(): string {
		if ($this->jwt->validateToken()) {
			$this->load->language('common/column_left');

			// Create a 3 level menu array
			// Level 2 cannot have children

			// Menu
			$data['menus'][] = [
				'id'       => 'menu-dashboard',
				'icon'     => 'fas fa-home',
				'name'     => $this->language->get('text_dashboard'),
				'href'     => $this->url->link('common/dashboard'),
				'children' => []
			];

			// Catalog
			$catalog = [];

			if ($this->user->hasPermission('access', 'catalog/category')) {
				$catalog[] = [
					'name'     => $this->language->get('text_category'),
					'href'     => $this->url->link('catalog/category'),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'catalog/product')) {
				$catalog[] = [
					'name'     => $this->language->get('text_product'),
					'href'     => $this->url->link('catalog/product'),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'catalog/subscription_plan')) {
				$catalog[] = [
					'name'     => $this->language->get('text_subscription_plan'),
					'href'     => $this->url->link('catalog/subscription_plan'),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'catalog/filter')) {
				$catalog[] = [
					'name'     => $this->language->get('text_filter'),
					'href'     => $this->url->link('catalog/filter'),
					'children' => []
				];
			}

			// Attributes
			$attribute = [];

			if ($this->user->hasPermission('access', 'catalog/attribute')) {
				$attribute[] = [
					'name'     => $this->language->get('text_attribute'),
					'href'     => $this->url->link('catalog/attribute'),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'catalog/attribute_group')) {
				$attribute[] = [
					'name'     => $this->language->get('text_attribute_group'),
					'href'     => $this->url->link('catalog/attribute_group'),
					'children' => []
				];
			}

			if ($attribute) {
				$catalog[] = [
					'name'     => $this->language->get('text_attribute'),
					'href'     => '',
					'children' => $attribute
				];
			}

			if ($this->user->hasPermission('access', 'catalog/option')) {
				$catalog[] = [
					'name'     => $this->language->get('text_option'),
					'href'     => $this->url->link('catalog/option'),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'catalog/manufacturer')) {
				$catalog[] = [
					'name'     => $this->language->get('text_manufacturer'),
					'href'     => $this->url->link('catalog/manufacturer'),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'catalog/download')) {
				$catalog[] = [
					'name'     => $this->language->get('text_download'),
					'href'     => $this->url->link('catalog/download'),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'catalog/review')) {
				$catalog[] = [
					'name'     => $this->language->get('text_review'),
					'href'     => $this->url->link('catalog/review'),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'catalog/information')) {
				$catalog[] = [
					'name'     => $this->language->get('text_information'),
					'href'     => $this->url->link('catalog/information'),
					'children' => []
				];
			}

			if ($catalog) {
				$data['menus'][] = [
					'id'       => 'menu-catalog',
					'icon'     => 'fa-solid fa-tag',
					'name'     => $this->language->get('text_catalog'),
					'href'     => '',
					'children' => $catalog
				];
			}

			$cms = [];

			if ($this->user->hasPermission('access', 'cms/topic')) {
				$cms[] = [
					'name'     => $this->language->get('text_topic'),
					'href'     => $this->url->link('cms/topic'),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'cms/article')) {
				$cms[] = [
					'name'     => $this->language->get('text_article'),
					'href'     => $this->url->link('cms/article'),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'cms/comment')) {
				$cms[] = [
					'name'     => $this->language->get('text_comment'),
					'href'     => $this->url->link('cms/comment'),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'cms/antispam')) {
				$cms[] = [
					'name'     => $this->language->get('text_antispam'),
					'href'     => $this->url->link('cms/antispam'),
					'children' => []
				];
			}

			if ($cms) {
				$data['menus'][] = [
					'id'       => 'menu-cms',
					'icon'     => 'fa-regular fa-newspaper',
					'name'     => $this->language->get('text_cms'),
					'href'     => '',
					'children' => $cms
				];
			}

			// Extension
			$marketplace = [];

			if ($this->user->hasPermission('access', 'marketplace/marketplace')) {
				$marketplace[] = [
					'name'     => $this->language->get('text_marketplace'),
					'href'     => $this->url->link('marketplace/marketplace'),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'marketplace/installer')) {
				$marketplace[] = [
					'name'     => $this->language->get('text_installer'),
					'href'     => $this->url->link('marketplace/installer'),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'marketplace/extension')) {
				$marketplace[] = [
					'name'     => $this->language->get('text_extension'),
					'href'     => $this->url->link('marketplace/extension'),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'marketplace/modification')) {
				$marketplace[] = [
					'name'     => $this->language->get('text_modification'),
					'href'     => $this->url->link('marketplace/modification'),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'marketplace/startup')) {
				$marketplace[] = [
					'name'     => $this->language->get('text_startup'),
					'href'     => $this->url->link('marketplace/startup'),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'marketplace/event')) {
				$marketplace[] = [
					'name'     => $this->language->get('text_event'),
					'href'     => $this->url->link('marketplace/event'),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'marketplace/cron')) {
				$marketplace[] = [
					'name'     => $this->language->get('text_cron'),
					'href'     => $this->url->link('marketplace/cron'),
					'children' => []
				];
			}

			if ($marketplace) {
				$data['menus'][] = [
					'id'       => 'menu-extension',
					'icon'     => 'fas fa-puzzle-piece',
					'name'     => $this->language->get('text_extension'),
					'href'     => '',
					'children' => $marketplace
				];
			}

			// Design
			$design = [];

			if ($this->user->hasPermission('access', 'design/layout')) {
				$design[] = [
					'name'     => $this->language->get('text_layout'),
					'href'     => $this->url->link('design/layout'),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'design/theme')) {
				$design[] = [
					'name'     => $this->language->get('text_theme'),
					'href'     => $this->url->link('design/theme'),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'design/translation')) {
				$design[] = [
					'name'     => $this->language->get('text_language_editor'),
					'href'     => $this->url->link('design/translation'),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'design/banner')) {
				$design[] = [
					'name'     => $this->language->get('text_banner'),
					'href'     => $this->url->link('design/banner'),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'design/seo_url')) {
				$design[] = [
					'name'     => $this->language->get('text_seo_url'),
					'href'     => $this->url->link('design/seo_url'),
					'children' => []
				];
			}

			if ($design) {
				$data['menus'][] = [
					'id'       => 'menu-design',
					'icon'     => 'fas fa-desktop',
					'name'     => $this->language->get('text_design'),
					'href'     => '',
					'children' => $design
				];
			}

			// Sales
			$sale = [];

			if ($this->user->hasPermission('access', 'sale/order')) {
				$sale[] = [
					'name'     => $this->language->get('text_order'),
					'href'     => $this->url->link('sale/order'),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'sale/subscription')) {
				$sale[] = [
					'name'     => $this->language->get('text_subscription'),
					'href'     => $this->url->link('sale/subscription'),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'sale/returns')) {
				$sale[] = [
					'name'     => $this->language->get('text_return'),
					'href'     => $this->url->link('sale/returns'),
					'children' => []
				];
			}

			// Voucher
			$voucher = [];

			if ($this->user->hasPermission('access', 'sale/voucher')) {
				$voucher[] = [
					'name'     => $this->language->get('text_voucher'),
					'href'     => $this->url->link('sale/voucher'),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'sale/voucher_theme')) {
				$voucher[] = [
					'name'     => $this->language->get('text_voucher_theme'),
					'href'     => $this->url->link('sale/voucher_theme'),
					'children' => []
				];
			}

			if ($voucher) {
				$sale[] = [
					'name'     => $this->language->get('text_voucher'),
					'href'     => '',
					'children' => $voucher
				];
			}

			if ($sale) {
				$data['menus'][] = [
					'id'       => 'menu-sale',
					'icon'     => 'fas fa-shopping-cart',
					'name'     => $this->language->get('text_sale'),
					'href'     => '',
					'children' => $sale
				];
			}

			// Customer
			$customer = [];

			if ($this->user->hasPermission('access', 'customer/customer')) {
				$customer[] = [
					'name'     => $this->language->get('text_customer'),
					'href'     => $this->url->link('customer/customer'),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'customer/customer_group')) {
				$customer[] = [
					'name'     => $this->language->get('text_customer_group'),
					'href'     => $this->url->link('customer/customer_group'),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'customer/customer_approval')) {
				$customer[] = [
					'name'     => $this->language->get('text_customer_approval'),
					'href'     => $this->url->link('customer/customer_approval'),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'customer/gdpr')) {
				$customer[] = [
					'name'     => $this->language->get('text_gdpr'),
					'href'     => $this->url->link('customer/gdpr'),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'customer/custom_field')) {
				$customer[] = [
					'name'     => $this->language->get('text_custom_field'),
					'href'     => $this->url->link('customer/custom_field'),
					'children' => []
				];
			}

			if ($customer) {
				$data['menus'][] = [
					'id'       => 'menu-customer',
					'icon'     => 'fas fa-user',
					'name'     => $this->language->get('text_customer'),
					'href'     => '',
					'children' => $customer
				];
			}

			// Marketing
			$marketing = [];

			if ($this->user->hasPermission('access', 'marketing/affiliate')) {
				$marketing[] = [
					'name'     => $this->language->get('text_affiliate'),
					'href'     => $this->url->link('marketing/affiliate'),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'marketing/marketing')) {
				$marketing[] = [
					'name'     => $this->language->get('text_marketing'),
					'href'     => $this->url->link('marketing/marketing'),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'marketing/coupon')) {
				$marketing[] = [
					'name'     => $this->language->get('text_coupon'),
					'href'     => $this->url->link('marketing/coupon'),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'marketing/contact')) {
				$marketing[] = [
					'name'     => $this->language->get('text_contact'),
					'href'     => $this->url->link('marketing/contact'),
					'children' => []
				];
			}

			if ($marketing) {
				$data['menus'][] = [
					'id'       => 'menu-marketing',
					'icon'     => 'fas fa-share-alt',
					'name'     => $this->language->get('text_marketing'),
					'href'     => '',
					'children' => $marketing
				];
			}

			// Anti-Fraud
			$fraud = [];

			$this->load->model('setting/extension');

			$results = $this->model_setting_extension->getExtensionsByType('fraud');

			foreach ($results as $result) {
				if ($this->config->get('fraud_' . $result['code'] . '_status')) {
					$this->load->language('extension/' . $result['extension'] . '/fraud/' . $result['code'], $result['code']);

					$fraud[] = [
						'name'     => $this->language->get($result['code'] . '_heading_title'),
						'href'     => $this->url->link('extension/' . $result['extension'] . '/fraud/' . $result['code'], 'user_token=' . $this->session->data['user_token']),
						'children' => []
					];
				}
			}

			if ($fraud) {
				$data['menus'][] = [
					'id'       => 'menu-fraud',
					'icon'     => 'fas fa-share-alt',
					'name'     => $this->language->get('text_antifraud'),
					'href'     => '',
					'children' => $fraud
				];
			}

			$marketing = [];

			if ($this->user->hasPermission('access', 'marketing/affiliate')) {
				$marketing[] = [
					'name'     => $this->language->get('text_affiliate'),
					'href'     => $this->url->link('marketing/affiliate', 'user_token=' . $this->session->data['user_token']),
					'children' => []
				];
			}

			// System
			$system = [];

			if ($this->user->hasPermission('access', 'setting/setting')) {
				$system[] = [
					'name'     => $this->language->get('text_setting'),
					'href'     => $this->url->link('setting/store'),
					'children' => []
				];
			}

			// Users
			$user = [];

			if ($this->user->hasPermission('access', 'user/user')) {
				$user[] = [
					'name'     => $this->language->get('text_users'),
					'href'     => $this->url->link('user/user'),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'user/user_permission')) {
				$user[] = [
					'name'     => $this->language->get('text_user_group'),
					'href'     => $this->url->link('user/user_permission'),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'user/api')) {
				$user[] = [
					'name'     => $this->language->get('text_api'),
					'href'     => $this->url->link('user/api'),
					'children' => []
				];
			}

			if ($user) {
				$system[] = [
					'name'     => $this->language->get('text_users'),
					'href'     => '',
					'children' => $user
				];
			}

			// Localisation
			$localisation = [];

			if ($this->user->hasPermission('access', 'localisation/location')) {
				$localisation[] = [
					'name'     => $this->language->get('text_location'),
					'href'     => $this->url->link('localisation/location'),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'localisation/language')) {
				$localisation[] = [
					'name'     => $this->language->get('text_language'),
					'href'     => $this->url->link('localisation/language'),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'localisation/currency')) {
				$localisation[] = [
					'name'     => $this->language->get('text_currency'),
					'href'     => $this->url->link('localisation/currency'),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'localisation/stock_status')) {
				$localisation[] = [
					'name'     => $this->language->get('text_stock_status'),
					'href'     => $this->url->link('localisation/stock_status'),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'localisation/order_status')) {
				$localisation[] = [
					'name'     => $this->language->get('text_order_status'),
					'href'     => $this->url->link('localisation/order_status'),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'localisation/subscription_status')) {
				$localisation[] = [
					'name'     => $this->language->get('text_subscription_status'),
					'href'     => $this->url->link('localisation/subscription_status'),
					'children' => []
				];
			}

			// Returns
			$returns = [];

			if ($this->user->hasPermission('access', 'localisation/return_status')) {
				$returns[] = [
					'name'     => $this->language->get('text_return_status'),
					'href'     => $this->url->link('localisation/return_status'),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'localisation/return_action')) {
				$returns[] = [
					'name'     => $this->language->get('text_return_action'),
					'href'     => $this->url->link('localisation/return_action'),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'localisation/return_reason')) {
				$returns[] = [
					'name'     => $this->language->get('text_return_reason'),
					'href'     => $this->url->link('localisation/return_reason'),
					'children' => []
				];
			}

			if ($returns) {
				$localisation[] = [
					'name'     => $this->language->get('text_return'),
					'href'     => '',
					'children' => $returns
				];
			}

			if ($this->user->hasPermission('access', 'localisation/country')) {
				$localisation[] = [
					'name'     => $this->language->get('text_country'),
					'href'     => $this->url->link('localisation/country'),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'localisation/zone')) {
				$localisation[] = [
					'name'     => $this->language->get('text_zone'),
					'href'     => $this->url->link('localisation/zone'),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'localisation/geo_zone')) {
				$localisation[] = [
					'name'     => $this->language->get('text_geo_zone'),
					'href'     => $this->url->link('localisation/geo_zone'),
					'children' => []
				];
			}

			// Tax
			$tax = [];

			if ($this->user->hasPermission('access', 'localisation/tax_class')) {
				$tax[] = [
					'name'     => $this->language->get('text_tax_class'),
					'href'     => $this->url->link('localisation/tax_class'),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'localisation/tax_rate')) {
				$tax[] = [
					'name'     => $this->language->get('text_tax_rate'),
					'href'     => $this->url->link('localisation/tax_rate'),
					'children' => []
				];
			}

			if ($tax) {
				$localisation[] = [
					'name'     => $this->language->get('text_tax'),
					'href'     => '',
					'children' => $tax
				];
			}

			if ($this->user->hasPermission('access', 'localisation/length_class')) {
				$localisation[] = [
					'name'     => $this->language->get('text_length_class'),
					'href'     => $this->url->link('localisation/length_class'),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'localisation/weight_class')) {
				$localisation[] = [
					'name'     => $this->language->get('text_weight_class'),
					'href'     => $this->url->link('localisation/weight_class'),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'localisation/address_format')) {
				$localisation[] = [
					'name'     => $this->language->get('text_address_format'),
					'href'     => $this->url->link('localisation/address_format'),
					'children' => []
				];
			}

			if ($localisation) {
				$system[] = [
					'name'     => $this->language->get('text_localisation'),
					'href'     => '',
					'children' => $localisation
				];
			}

			// Tools
			$maintenance = [];

			if ($this->user->hasPermission('access', 'tool/upgrade')) {
				$maintenance[] = [
					'name'     => $this->language->get('text_upgrade'),
					'href'     => $this->url->link('tool/upgrade'),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'tool/backup')) {
				$maintenance[] = [
					'name'     => $this->language->get('text_backup'),
					'href'     => $this->url->link('tool/backup'),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'tool/upload')) {
				$maintenance[] = [
					'name'     => $this->language->get('text_upload'),
					'href'     => $this->url->link('tool/upload'),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'tool/log')) {
				$maintenance[] = [
					'name'     => $this->language->get('text_log'),
					'href'     => $this->url->link('tool/log'),
					'children' => []
				];
			}

			if ($maintenance) {
				$system[] = [
					'name'     => $this->language->get('text_maintenance'),
					'href'     => '',
					'children' => $maintenance
				];
			}

			if ($system) {
				$data['menus'][] = [
					'id'       => 'menu-system',
					'icon'     => 'fas fa-cog',
					'name'     => $this->language->get('text_system'),
					'href'     => '',
					'children' => $system
				];
			}

			$report = [];

			if ($this->user->hasPermission('access', 'report/report')) {
				$report[] = [
					'name'     => $this->language->get('text_reports'),
					'href'     => $this->url->link('report/report'),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'report/online')) {
				$report[] = [
					'name'     => $this->language->get('text_online'),
					'href'     => $this->url->link('report/online'),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'report/statistics')) {
				$report[] = [
					'name'     => $this->language->get('text_statistics'),
					'href'     => $this->url->link('report/statistics'),
					'children' => []
				];
			}

			if ($report) {
				$data['menus'][] = [
					'id'       => 'menu-report',
					'icon'     => 'fas fa-chart-bar',
					'name'     => $this->language->get('text_reports'),
					'href'     => '',
					'children' => $report
				];
			}

			// Stats
			if ($this->user->hasPermission('access', 'report/statistics')) {
				$this->load->model('sale/order');

				$order_total = (float)$this->model_sale_order->getTotalOrders();

				$this->load->model('report/statistics');

				$complete_total = (float)$this->model_report_statistics->getValue('order_complete');

				if ($complete_total && $order_total) {
					$data['complete_status'] = round(($complete_total / $order_total) * 100);
				} else {
					$data['complete_status'] = 0;
				}

				$processing_total = (float)$this->model_report_statistics->getValue('order_processing');

				if ($processing_total && $order_total) {
					$data['processing_status'] = round(($processing_total / $order_total) * 100);
				} else {
					$data['processing_status'] = 0;
				}

				$other_total = (float)$this->model_report_statistics->getValue('order_other');

				if ($other_total && $order_total) {
					$data['other_status'] = round(($other_total / $order_total) * 100);
				} else {
					$data['other_status'] = 0;
				}

				$data['statistics_status'] = true;
			} else {
				$data['statistics_status'] = false;
			}

			return $this->load->view('common/column_left', $data);
		} else {
			return '';
		}
	}
}
