<?php
namespace Opencart\Install\Controller\Upgrade;
/**
 * Class Upgrade18
 *
 * @package Opencart\Install\Controller\Upgrade
 */
class Upgrade18 extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('upgrade/upgrade');

		$json = [];

		try {
			$menu_query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "startup`");

			if (!$menu_query->row['total']) {
				$results = [];

				// Catalog


				$this->load->model('upgrade/upgrade');

				foreach ($results as $result) {
					$menu_description = $result['menu_description'];

					unset($result['menu_description']);

					$menu_id = $this->model_upgrade_upgrade->addRecord('menu', $result);

					foreach ($menu_description as $key => $value) {
						$menu_description_data = [
							'menu_id'     => $menu_id,
							'language_id' => $key,
							'name'        => $value['name']
						];

						$this->model_upgrade_upgrade->addRecord('menu_description', $menu_description_data);
					}
				}
			}
		} catch (\ErrorException $exception) {
			$json['error'] = sprintf($this->language->get('error_exception'), $exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
		}

		if (!$json) {
			$json['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['admin'])) {
				$url .= '&admin=' . $this->request->get['admin'];
			}

			$json['redirect'] = $this->url->link('install/step_4', $url, true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
