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

			$data['menus'] = [];

			$data['menus'][] = [
				'code'     => 'dashboard',
				'icon'     => 'fa-solid fa-home',
				'name'     => $this->language->get('text_dashboard'),
				'href'     => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token']),
				'children' => []
			];

			$paths = [];

			$paths[] = [
				'code'     => 'catalog',
				'icon'     => 'fa-solid fa-tag',
				'name'     => $this->language->get('text_catalog'),
				'type'     => 'dropdown',
				'children' => []
			];

			$paths[] = [
				'code'     => 'cms',
				'icon'     => 'fa-solid fa-newspaper',
				'name'     => $this->language->get('text_cms'),
				'type'     => 'dropdown',
				'children' => []
			];

			$paths[] = [
				'code'     => 'extension',
				'icon'     => 'fa-solid fa-puzzle-piece',
				'name'     => $this->language->get('text_extension'),
				'type'     => 'dropdown',
				'children' => []
			];

			$paths[] = [
				'code'     => 'design',
				'icon'     => 'fa-solid fa-tag',
				'name'     => $this->language->get('text_design'),
				'type'     => 'dropdown',
				'children' => []
			];

			$paths[] = [
				'code'     => 'sale',
				'icon'     => 'fa-solid fa-shopping-cart',
				'name'     => $this->language->get('text_sale'),
				'type'     => 'dropdown',
				'children' => []
			];

			$paths[] = [
				'code'     => 'customer',
				'icon'     => 'fa-solid fa-user',
				'name'     => $this->language->get('text_customer'),
				'type'     => 'dropdown',
				'children' => []
			];

			$paths[] = [
				'code'     => 'marketing',
				'icon'     => 'fa-solid fa-share-alt',
				'name'     => $this->language->get('text_marketing'),
				'type'     => 'dropdown',
				'children' => []
			];

			$paths[] = [
				'code'     => 'system',
				'icon'     => 'fa-solid fa-cog',
				'name'     => $this->language->get('text_system'),
				'type'     => 'dropdown',
				'children' => []
			];

			$paths[] = [
				'code'     => 'report',
				'icon'     => 'fa-solid fa-chart-bar',
				'name'     => $this->language->get('text_reports'),
				'type'     => 'dropdown',
				'children' => []
			];

			$menu = [];

			$this->load->model('tool/menu');

			$results = $this->model_tool_menu->getMenus();

			$stack = [];

			foreach ($results as $code => $result) {
				if ($result['type'] == 'link') {
					$result['href']  = $this->url->link($result['route'], 'user_token=' . $this->session->data['user_token']);
				}

				if (!array_key_exists($result['parent'], $stack)) {
					$menu[$result['parent']] = &$stack[$result['parent']];
				}

				if (!array_key_exists($code, $stack)) {
					$stack[$code] = ['children' => []] + $result;
				} elseif ($stack[$code]) {
					$stack[$code] = array_merge($result, $stack[$code]);
				}

				$stack[$result['parent']]['children'][$code] = &$stack[$code];

				unset($menu[$code]);
			}

			unset($stack);

			foreach ($paths as $path) {
				if (isset($menu[$path['code']])) {
					$children = $menu[$path['code']]['children'];

					if ($children) {
						$data['menus'][] = ['children' => $children] + $path;
					}
				}
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
					'code'     => 'menu-fraud',
					'icon'     => 'fa-solid fa-share-alt',
					'name'     => $this->language->get('text_antifraud'),
					'children' => $fraud
				];
			}

			// Stats
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
	}
}
