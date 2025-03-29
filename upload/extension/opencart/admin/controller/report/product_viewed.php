<?php
namespace Opencart\Admin\Controller\Extension\Opencart\Report;
/**
 * Class Product Viewed
 *
 * @package Opencart\Admin\Controller\Extension\Opencart\Report
 */
class ProductViewed extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('extension/opencart/report/product_viewed');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=report')
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/opencart/report/product_viewed', 'user_token=' . $this->session->data['user_token'])
		];

		$data['save'] = $this->url->link('extension/opencart/report/product_viewed.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=report');

		$data['report_product_viewed_status'] = $this->config->get('report_product_viewed_status');
		$data['report_product_viewed_sort_order'] = $this->config->get('report_product_viewed_sort_order');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/opencart/report/product_viewed_form', $data));
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('extension/opencart/report/product_viewed');

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/opencart/report/product_viewed')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			// Setting
			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting('report_product_viewed', $this->request->post);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Install
	 *
	 * @return void
	 */
	public function install(): void {
		if ($this->user->hasPermission('modify', 'extension/report')) {
			// Extension
			$this->load->model('extension/opencart/report/product_viewed');

			$this->model_extension_opencart_report_product_viewed->install();
		}
	}

	/**
	 * Uninstall
	 *
	 * @return void
	 */
	public function uninstall(): void {
		if ($this->user->hasPermission('modify', 'extension/report')) {
			// Extension
			$this->load->model('extension/opencart/report/product_viewed');

			$this->model_extension_opencart_report_product_viewed->uninstall();
		}
	}

	/**
	 * Report
	 *
	 * @return void
	 */
	public function report(): void {
		$this->load->language('extension/opencart/report/product_viewed');

		$data['list'] = $this->getReport();

		$data['user_token'] = $this->session->data['user_token'];

		$this->response->setOutput($this->load->view('extension/opencart/report/product_viewed', $data));
	}

	/**
	 * List
	 *
	 * @return void
	 */
	public function list(): void {
		$this->load->language('extension/opencart/report/product_viewed');

		$this->response->setOutput($this->getReport());
	}

	/**
	 * Get Report
	 *
	 * @return string
	 */
	public function getReport(): string {
		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['products'] = [];

		// Product Viewed
		$this->load->model('extension/opencart/report/product_viewed');

		// Product
		$this->load->model('catalog/product');

		$total = $this->model_extension_opencart_report_product_viewed->getTotal();

		// Total Viewed
		$viewed_total = $this->model_extension_opencart_report_product_viewed->getTotalViewed();

		$results = $this->model_extension_opencart_report_product_viewed->getViewed(($page - 1) * $this->config->get('config_pagination'), $this->config->get('config_pagination'));

		foreach ($results as $result) {
			$product_info = $this->model_catalog_product->getProduct($result['product_id']);

			if ($product_info) {
				if ($result['viewed']) {
					$percent = round(($result['viewed'] / $total) * 100, 2);
				} else {
					$percent = 0;
				}

				$data['products'][] = [
					'name'    => $product_info['name'],
					'model'   => $product_info['model'],
					'viewed'  => $result['viewed'],
					'percent' => $percent . '%'
				];
			}
		}

		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		// Pagination
		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $viewed_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination'),
			'url'   => $this->url->link('extension/opencart/report/product_viewed.list', 'user_token=' . $this->session->data['user_token'] . '&code=product_viewed&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($viewed_total) ? (($page - 1) * $this->config->get('config_pagination')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination')) > ($viewed_total - $this->config->get('config_pagination'))) ? $viewed_total : ((($page - 1) * $this->config->get('config_pagination')) + $this->config->get('config_pagination')), $viewed_total, ceil($viewed_total / $this->config->get('config_pagination')));

		return $this->load->view('extension/opencart/report/product_viewed_list', $data);
	}

	/**
	 * Generate
	 *
	 * @return void
	 */
	public function generate(): void {
		$this->load->language('extension/opencart/report/product_viewed');

		$json = [];

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$limit = 10;

		if (!$this->user->hasPermission('modify', 'extension/opencart/report/product_viewed')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			// Extension
			$this->load->model('extension/opencart/report/product_viewed');

			if ($page == 1) {
				$this->model_extension_opencart_report_product_viewed->clear();
			}

			// Products
			$filter_data = [
				'start' => ($page - 1) * $limit,
				'limit' => $limit
			];

			$this->load->model('catalog/product');

			// Total Products
			$product_total = $this->model_catalog_product->getTotalProducts();

			$products = $this->model_catalog_product->getProducts($filter_data);

			foreach ($products as $product) {
				$this->model_extension_opencart_report_product_viewed->addReport($product['product_id'], $this->model_catalog_product->getTotalReports($product['product_id']));
			}

			if (($page * $limit) <= $product_total) {
				$json['text'] = sprintf($this->language->get('text_progress'), ($page - 1) * $limit, $product_total);

				$json['next'] = $this->url->link('extension/opencart/report/product_viewed.generate', 'user_token=' . $this->session->data['user_token'] . '&page=' . ($page + 1), true);
			} else {
				$json['success'] = $this->language->get('text_success');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
