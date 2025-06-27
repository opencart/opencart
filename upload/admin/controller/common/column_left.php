<?php
namespace Opencart\Admin\Controller\Common;
/**
 * Class Column Left
 *
 * Can be loaded using $this->load->controller('common/column_left');
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
		if (isset($this->request->get['user_token']) && isset($this->session->data['user_token']) && ((string)$this->request->get['user_token'] == $this->session->data['user_token'])) {
			$this->load->language('common/column_left');

			// Create a 3 level menu array
			// Level 2 cannot have children

			// Menu
			$data['menus'] = [];

			$data['menus'][] = [
				'id'       => 'menu-dashboard',
				'icon'     => 'fa-solid fa-home',
				'name'     => $this->language->get('text_dashboard'),
				'href'     => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token']),
				'children' => []
			];

			$this->load->model('tool/menu');

			$results = $this->model_tool_menu->getMenus();

			$results['catalog'] = [
				'code'     => 'catalog',
				'icon'     => 'fa-solid fa-tag',
				'name'     => $this->language->get('text_catalog'),
				'type'     => 'dropdown',
				'parent'   => '',
				'children' => []
			];

			$results['cms'] = [
				'code'     => 'cms',
				'icon'     => 'fa-solid fa-newspaper',
				'name'     => $this->language->get('text_cms'),
				'type'     => 'dropdown',
				'parent'   => '',
				'children' => []
			];

			$results['extension'] = [
				'code'     => 'extension',
				'icon'     => 'fa-solid fa-puzzle-piece',
				'name'     => $this->language->get('text_extension'),
				'type'     => 'dropdown',
				'parent'   => '',
				'children' => []
			];

			$results['design'] = [
				'code'     => 'design',
				'icon'     => 'fa-solid fa-tag',
				'name'     => $this->language->get('text_design'),
				'type'     => 'dropdown',
				'parent'   => '',
				'children' => []
			];

			$results['sale'] = [
				'code'     => 'sale',
				'icon'     => 'fa-solid fa-shopping-cart',
				'name'     => $this->language->get('text_sale'),
				'type'     => 'dropdown',
				'parent'   => '',
				'children' => []
			];

			$results['customer'] = [
				'code'     => 'customer',
				'icon'     => 'fa-solid fa-user',
				'name'     => $this->language->get('text_customer'),
				'type'     => 'dropdown',
				'parent'   => '',
				'children' => []
			];

			$results['marketing'] = [
				'code'     => 'marketing',
				'icon'     => 'fa-solid fa-share-alt',
				'name'     => $this->language->get('text_marketing'),
				'type'     => 'dropdown',
				'parent'   => '',
				'children' => []
			];

			$results['system'] = [
				'code'     => 'system',
				'icon'     => 'fa-solid fa-cog',
				'name'     => $this->language->get('text_system'),
				'type'     => 'dropdown',
				'parent'   => '',
				'children' => []
			];

			$results['report'] = [
				'code'     => 'report',
				'icon'     => 'fa-solid fa-chart-bar',
				'name'     => $this->language->get('text_reports'),
				'type'     => 'dropdown',
				'parent'   => '',
				'children' => []
			];

			foreach ($results as $result) {
				if (!array_key_exists($result['code'], $data['menus'])) {
					$data['menus'][$result['code']] = $result;

					if ($result['type'] == 'link') {
						$data['menus'][$result['code']]['href'] = $this->url->link($result['route'], 'user_token=' . $this->session->data['user_token']);
					}
				} else {
					$data['menus'][$result['code']] = array_merge($data['menus'][$result['code']], $result);
				}

				// add to parent
				if ($result['parent']) {
					$data['menus'][$result['parent']]['children'][$result['code']] = $data['menus'][$result['code']];

					unset($data['menus'][$result['code']]);
				}
			}

			// Anti-Fraud
			/*
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
					'icon'     => 'fa-solid fa-share-alt',
					'name'     => $this->language->get('text_antifraud'),
					'href'     => '',
					'children' => $fraud
				];
			}
			*/


			/*
			if ($this->user->hasPermission('access', $result['route'])) {
				$catalog[] = [
					'name'     => $result['name'],
					'href'     => $this->url->link($result['route'], 'user_token=' . $this->session->data['user_token']),
					'children' => []
				];
			}

			// Attributes
			$attribute = [];

			if ($this->user->hasPermission('access', 'catalog/attribute')) {
				$attribute[] = [
					'name'     => $this->language->get('text_attribute'),
					'href'     => $this->url->link('catalog/attribute', 'user_token=' . $this->session->data['user_token']),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'catalog/attribute_group')) {
				$attribute[] = [
					'name'     => $this->language->get('text_attribute_group'),
					'href'     => $this->url->link('catalog/attribute_group', 'user_token=' . $this->session->data['user_token']),
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
					'href'     => $this->url->link('catalog/option', 'user_token=' . $this->session->data['user_token']),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'catalog/manufacturer')) {
				$catalog[] = [
					'name'     => $this->language->get('text_manufacturer'),
					'href'     => $this->url->link('catalog/manufacturer', 'user_token=' . $this->session->data['user_token']),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'catalog/download')) {
				$catalog[] = [
					'name'     => $this->language->get('text_download'),
					'href'     => $this->url->link('catalog/download', 'user_token=' . $this->session->data['user_token']),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'catalog/review')) {
				$catalog[] = [
					'name'     => $this->language->get('text_review'),
					'href'     => $this->url->link('catalog/review', 'user_token=' . $this->session->data['user_token']),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'catalog/information')) {
				$catalog[] = [
					'name'     => $this->language->get('text_information'),
					'href'     => $this->url->link('catalog/information', 'user_token=' . $this->session->data['user_token']),
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








			// System
			$system = [];

			if ($this->user->hasPermission('access', 'setting/setting')) {
				$system[] = [
					'name'     => $this->language->get('text_setting'),
					'href'     => $this->url->link('setting/store', 'user_token=' . $this->session->data['user_token']),
					'children' => []
				];
			}

			// Users


			// Localisation
			$localisation = [];

			if ($this->user->hasPermission('access', 'localisation/location')) {
				$localisation[] = [
					'name'     => $this->language->get('text_location'),
					'href'     => $this->url->link('localisation/location', 'user_token=' . $this->session->data['user_token']),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'localisation/language')) {
				$localisation[] = [
					'name'     => $this->language->get('text_language'),
					'href'     => $this->url->link('localisation/language', 'user_token=' . $this->session->data['user_token']),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'localisation/currency')) {
				$localisation[] = [
					'name'     => $this->language->get('text_currency'),
					'href'     => $this->url->link('localisation/currency', 'user_token=' . $this->session->data['user_token']),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'localisation/identifier')) {
				$localisation[] = [
					'name'     => $this->language->get('text_identifier'),
					'href'     => $this->url->link('localisation/identifier', 'user_token=' . $this->session->data['user_token']),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'localisation/stock_status')) {
				$localisation[] = [
					'name'     => $this->language->get('text_stock_status'),
					'href'     => $this->url->link('localisation/stock_status', 'user_token=' . $this->session->data['user_token']),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'localisation/order_status')) {
				$localisation[] = [
					'name'     => $this->language->get('text_order_status'),
					'href'     => $this->url->link('localisation/order_status', 'user_token=' . $this->session->data['user_token']),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'localisation/subscription_status')) {
				$localisation[] = [
					'name'     => $this->language->get('text_subscription_status'),
					'href'     => $this->url->link('localisation/subscription_status', 'user_token=' . $this->session->data['user_token']),
					'children' => []
				];
			}

			// Returns
			$returns = [];

			if ($this->user->hasPermission('access', 'localisation/return_status')) {
				$returns[] = [
					'name'     => $this->language->get('text_return_status'),
					'href'     => $this->url->link('localisation/return_status', 'user_token=' . $this->session->data['user_token']),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'localisation/return_action')) {
				$returns[] = [
					'name'     => $this->language->get('text_return_action'),
					'href'     => $this->url->link('localisation/return_action', 'user_token=' . $this->session->data['user_token']),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'localisation/return_reason')) {
				$returns[] = [
					'name'     => $this->language->get('text_return_reason'),
					'href'     => $this->url->link('localisation/return_reason', 'user_token=' . $this->session->data['user_token']),
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
					'href'     => $this->url->link('localisation/country', 'user_token=' . $this->session->data['user_token']),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'localisation/zone')) {
				$localisation[] = [
					'name'     => $this->language->get('text_zone'),
					'href'     => $this->url->link('localisation/zone', 'user_token=' . $this->session->data['user_token']),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'localisation/geo_zone')) {
				$localisation[] = [
					'name'     => $this->language->get('text_geo_zone'),
					'href'     => $this->url->link('localisation/geo_zone', 'user_token=' . $this->session->data['user_token']),
					'children' => []
				];
			}

			// Tax
			$tax = [];

			if ($this->user->hasPermission('access', 'localisation/tax_class')) {
				$tax[] = [
					'name'     => $this->language->get('text_tax_class'),
					'href'     => $this->url->link('localisation/tax_class', 'user_token=' . $this->session->data['user_token']),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'localisation/tax_rate')) {
				$tax[] = [
					'name'     => $this->language->get('text_tax_rate'),
					'href'     => $this->url->link('localisation/tax_rate', 'user_token=' . $this->session->data['user_token']),
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
					'href'     => $this->url->link('localisation/length_class', 'user_token=' . $this->session->data['user_token']),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'localisation/weight_class')) {
				$localisation[] = [
					'name'     => $this->language->get('text_weight_class'),
					'href'     => $this->url->link('localisation/weight_class', 'user_token=' . $this->session->data['user_token']),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'localisation/address_format')) {
				$localisation[] = [
					'name'     => $this->language->get('text_address_format'),
					'href'     => $this->url->link('localisation/address_format', 'user_token=' . $this->session->data['user_token']),
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

			if ($this->user->hasPermission('access', 'tool/menu')) {
				$maintenance[] = [
					'name'     => $this->language->get('text_menu'),
					'href'     => $this->url->link('tool/menu', 'user_token=' . $this->session->data['user_token']),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'tool/upgrade')) {
				$maintenance[] = [
					'name'     => $this->language->get('text_upgrade'),
					'href'     => $this->url->link('tool/upgrade', 'user_token=' . $this->session->data['user_token']),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'tool/backup')) {
				$maintenance[] = [
					'name'     => $this->language->get('text_backup'),
					'href'     => $this->url->link('tool/backup', 'user_token=' . $this->session->data['user_token']),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'tool/upload')) {
				$maintenance[] = [
					'name'     => $this->language->get('text_upload'),
					'href'     => $this->url->link('tool/upload', 'user_token=' . $this->session->data['user_token']),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'tool/log')) {
				$maintenance[] = [
					'name'     => $this->language->get('text_log'),
					'href'     => $this->url->link('tool/log', 'user_token=' . $this->session->data['user_token']),
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



			$report = [];

			if ($this->user->hasPermission('access', 'report/report')) {
				$report[] = [
					'name'     => $this->language->get('text_reports'),
					'href'     => $this->url->link('report/report', 'user_token=' . $this->session->data['user_token']),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'report/online')) {
				$report[] = [
					'name'     => $this->language->get('text_online'),
					'href'     => $this->url->link('report/online', 'user_token=' . $this->session->data['user_token']),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'report/statistics')) {
				$report[] = [
					'name'     => $this->language->get('text_statistics'),
					'href'     => $this->url->link('report/statistics', 'user_token=' . $this->session->data['user_token']),
					'children' => []
				];
			}

			if ($report) {
				$data['menus'][] = [
					'id'       => 'menu-report',
					'icon'     => 'fa-solid fa-chart-bar',
					'name'     => $this->language->get('text_reports'),
					'href'     => '',
					'children' => $report
				];
			}
	*/

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
