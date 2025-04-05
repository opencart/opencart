<?php
namespace Opencart\Admin\Controller\Ssr;
/**
 * Class Manufacturer
 *
 * @package Opencart\Admin\Controller\Ssr
 */
class Manufacturer extends \Opencart\System\Engine\Controller {
	/**
	 * Generate
	 *
	 * @return void
	 */
	public function index() {
		$this->load->language('ssr/manufacturer');

		$json = [];

		if (!$this->user->hasPermission('modify', 'catalog/manufacturer')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$directory = DIR_CATALOG . 'view/data/catalog/';

		if (!is_dir($directory) && !mkdir($directory, 0777)) {
			$json['error'] = sprintf($this->language->get('error_directory'), $directory);
		}

		if (!$json) {
			$manufacturers = [];

			$this->load->model('catalog/manufacturer');

			$results = $this->model_catalog_manufacturer->getManufacturers();

			foreach ($results as $result) {
				if ($result['status']) {
					$manufacturers[] = $result;
				}
			}

			$file = $directory . 'manufacturer.json';

			if (!file_put_contents($file, json_encode($manufacturers))) {
				$json['error'] = sprintf($this->language->get('error_file'), $file);
			}
		}

		if (!$json) {
			$json['text'] = $this->language->get('text_manufacturer');

			$json['next'] = $this->url->link('ssr/manufacturer', 'user_token=' . $this->session->data['user_token'], true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function list() {
		$this->load->language('ssr/manufacturer');

		$json = [];

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		if (!$this->user->hasPermission('modify', 'ssr/manufacturer')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$directory = DIR_CATALOG . 'view/data/catalog/';

		if (!is_dir($directory) && !mkdir($directory, 0777)) {
			$json['error'] = $this->language->get('error_directory');
		}

		if (!$json) {
			$limit = 5;

			$this->load->model('catalog/manufacturer');

			$manufacturer_total = $this->model_catalog_manufacturer->getTotalManufacturers();

			$start = ($page - 1) * $limit;
			$end = $start > ($manufacturer_total - $limit) ? $manufacturer_total : ($start + $limit);

			$filter_data = [
				'start' => $start,
				'limit' => $limit
			];

			$topics = $this->model_catalog_manufacturer->getManufacturers($filter_data);

			foreach ($topics as $topic) {
				if ($topic['status']) {
					if (isset($languages[$description['language_id']])) {
						$code = preg_replace('/[^A-Z0-9_-]/i', '', $languages[$description['language_id']]['code']);

						$file = DIR_CATALOG . 'view/data/cms/topic.' . (int)$topic['topic_id'] . '.' . $code . '.json';

						if (!file_put_contents($file, json_encode($description + $topic))) {
							$json['error'] = $this->language->get('error_file');
						}
					}
				}
			}
		}

		if (!$json) {
			$json['text'] = sprintf($this->language->get('text_next'), $start, $end, $manufacturer_total);

			if ($end < $manufacturer_total) {
				$json['next'] = $this->url->link('ssr/topic', 'user_token=' . $this->session->data['user_token'] . '&page=' . ($page + 1), true);
			} else {
				$json['success'] = $this->language->get('text_success');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}