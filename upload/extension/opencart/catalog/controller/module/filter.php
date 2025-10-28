<?php
namespace Opencart\Catalog\Controller\Extension\Opencart\Module;
/**
 * Class Filter
 *
 * @package Opencart\Catalog\Controller\Extension\Opencart\Module
 */
class Filter extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return string
	 */
	public function index(): string {
		// Category
		if (isset($this->request->get['path'])) {
			$parts = explode('_', (string)$this->request->get['path']);
		} else {
			$parts = [];
		}

		$category_id = (int)end($parts);

		$this->load->model('catalog/category');

		$category_info = $this->model_catalog_category->getCategory($category_id);

		if ($category_info) {
			$this->load->language('extension/opencart/module/filter');

			$remove = [
				'route',
				'user_token',
				'code',
				'page'
			];

			$url = http_build_query(array_diff_key($this->request->get, array_flip($remove)));

			$data['action'] = str_replace('&amp;', '&', $this->url->link('product/category', 'language=' . $this->config->get('config_language') . '&path=' . $this->request->get['path'] . $url));

			if (isset($this->request->get['filter'])) {
				$data['filter_category'] = explode(',', $this->request->get['filter']);
			} else {
				$data['filter_category'] = [];
			}

			$this->load->model('catalog/product');

			$data['filter_groups'] = [];

			$filter_groups = $this->model_catalog_category->getFilters($category_id);

			if ($filter_groups) {
				foreach ($filter_groups as $filter_group) {
					$children_data = [];

					foreach ($filter_group['filter'] as $filter) {
						$filter_data = [
							'filter_category_id' => $category_id,
							'filter_filter'      => $filter['filter_id']
						];

						$children_data[] = [
							'filter_id' => $filter['filter_id'],
							'name'      => $filter['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : '')
						];
					}

					$data['filter_groups'][] = [
						'filter_group_id' => $filter_group['filter_group_id'],
						'name'            => $filter_group['name'],
						'filter'          => $children_data
					];
				}

				return $this->load->view('extension/opencart/module/filter', $data);
			}
		}

		return '';
	}
}
