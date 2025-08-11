<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Banner
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class Banner extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generates banner task list.
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/catalog/banner');

		$this->load->model('setting/task');

		$this->load->model('setting/store');

		$stores = $this->model_setting_store->getStores();

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($stores as $store) {
			foreach ($languages as $language) {
				$task_data = [
					'code'   => 'banner',
					'action' => 'task/catalog/banner.list',
					'args'   => [
						'store_id'    => $store['store_id'],
						'language_id' => $language['language_id']
					]
				];

				$this->model_setting_task->addTask($task_data);
			}
		}

		return ['success' => $this->language->get('text_success')];
	}

	/**
	 * List
	 *
	 * Generates banner list file.
	 *
	 * @return array
	 */
	public function list(array $args = []): array {
		$this->load->language('task/catalog/banner');

		$this->load->model('setting/task');

		// Store
		$this->load->model('setting/store');

		$store_info = $this->model_setting_store->getStore((int)$args['store_id']);

		if (!$store_info) {
			return ['error' => $this->language->get('error_store')];
		}

		// Language
		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguage((int)$args['language_id']);

		if (!$language_info) {
			return ['error' => $this->language->get('error_language')];
		}

		$this->load->model('design/banner');

		$banners = $this->model_design_banner->getBanners();

		foreach ($banners as $banner) {
			if ($banner['status']) {
				$task_data = [
					'code'   => 'banner',
					'action' => 'task/catalog/banner.list',
					'args'   => [
						'banner_id'   => $banner['banner_id'],
						'store_id'    => $store_info['store_id'],
						'language_id' => $language_info['language_id']
					]
				];

				$this->model_setting_task->addTask($task_data);
			}
		}

		return ['success' => sprintf($this->language->get('text_list'), $language_info['name'])];
	}

	/**
	 * Info
	 *
	 * Generates banner information.
	 *
	 * @return array
	 */
	public function info(array $args = []): array {
		$this->load->language('task/catalog/banner');

		$this->load->model('setting/store');

		$store_info = $this->model_setting_store->getStore((int)$args['store_id']);

		if (!$store_info) {
			return ['error' => $this->language->get('error_store')];
		}

		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguage((int)$args['language_id']);

		if (!$language_info) {
			return ['error' => $this->language->get('error_language')];
		}

		$this->load->model('design/banner');

		$banner_info = $this->model_design_banner->getBanner((int)$args['banner_id']);

		if (!$banner_info) {
			return ['error' => $this->language->get('error_banner')];
		}

		if (!$banner_info) {
			return [];
		}

		$banner_images = $this->model_design_banner->getImages($banner_info['banner_id'], $language_info['language_id']);

		foreach ($images as $image) {
			$banner_data[$banner['banner_id']] = $image + $banner;
		}




		$zone_data = [];

		$this->load->model('localisation/zone');

		$zones = $this->model_localisation_zone->getZonesByCountryId((int)$country_info['country_id']);

		foreach ($zones as $zone) {
			if ($zone['status']) {
				$zone_description_info = $this->model_localisation_zone->getDescription((int)$zone['zone_id'], (int)$language_info['language_id']);

				if ($zone_description_info) {
					$zone_data[] = $zone_description_info + $zone;
				}
			}
		}

		$base = DIR_CATALOG . 'view/data/';
		$directory = parse_url($store_info['url'], PHP_URL_HOST) . '/' . $language_info['code'] . '/localisation/';
		$filename = 'country-' . $args['country_id'] . '.json';

		if (!oc_directory_create($base . $directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($base . $directory . $filename, json_encode($country_info + $description_info + ['zone' => $zone_data]))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_info'), $store_info['name'], $language_info['name'], $country_info['name'])];
	}

	/**
	 * Clear
	 *
	 * Clears generated banners.
	 *
	 * @return array
	 */
	public function clear(array $args = []): array {
		$this->load->language('task/catalog/language');

		$this->load->model('setting/store');

		$stores = $this->model_setting_store->getStores();

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($stores as $store) {
			foreach ($languages as $language) {
				$base = DIR_CATALOG . 'view/data/';
				$directory = parse_url($store['url'], PHP_URL_HOST) . '/' . $language['code'] . '/design/';

				$files = oc_directory_read($base . $directory, false, '/country\-.+\.json$/');

				foreach ($files as $file) {
					unlink($file);
				}
			}
		}

		return ['success' => $this->language->get('text_clear')];
	}
}
