<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Information
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class Information extends \Opencart\System\Engine\Controller {
	/**
	 * Generate
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('task/catalog/information');



		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		//if (!$this->user->hasPermission('modify', 'catalog/information')) {
			$json['error'] = $this->language->get('error_permission');
		//}


		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		$limit = 5;
		$information_total = $this->model_cms_information->getTotalInformations();

		$start = ($page - 1) * $limit;
		$end = $start > ($information_total - $limit) ? $information_total : ($start + $limit);

		$filter_data = [
			'start' => $start,
			'limit' => $limit
		];

		$this->load->model('catalog/information');

		$informations = $this->model_catalog_information->getInformations($filter_data);

		foreach ($informations as $information) {
			if ($information['status']) {
				$descriptions = $this->model_cms_information->getDescriptions($information['information_id']);

				foreach ($descriptions as $description) {
					if (isset($languages[$description['language_id']])) {
						$code = preg_replace('/[^A-Z0-9\._-]/i', '', $languages[$description['language_id']]['code']);

						$file = DIR_CATALOG . 'view/data/catalog/information.' . (int)$information['information_id'] . '.' . $code . '.json';

						if (!file_put_contents($file, json_encode($description + $information))) {
							$json['error'] = $this->language->get('error_file');
						}
					}
				}
			}
		}



		$json['text'] = sprintf($this->language->get('text_information'), $start, $end, $information_total);

		if ($end < $information_total) {
			$json['next'] = $this->url->link('task/catalog/information', 'user_token=' . $this->session->data['user_token'] . '&page=' . ($page + 1), true);
		} else {
			$json['success'] = $this->language->get('text_success');
		}


		return [];
	}
}
