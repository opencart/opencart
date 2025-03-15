<?php
namespace Opencart\Install\Controller\Upgrade;
/**
 * Class Upgrade10
 *
 * @package Opencart\Install\Controller\Upgrade
 */
class Upgrade10 extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('upgrade/upgrade');

		$json = [];

		try {
			// Drop Fields
			$remove = [];

			$remove[] = [
				'table' => 'product',
				'field' => 'sku'
			];

			$remove[] = [
				'table' => 'product',
				'field' => 'upc'
			];

			$remove[] = [
				'table' => 'product',
				'field' => 'ean'
			];

			$remove[] = [
				'table' => 'product',
				'field' => 'jan'
			];

			$remove[] = [
				'table' => 'product',
				'field' => 'isbn'
			];

			foreach ($remove as $result) {
				$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . $result['table'] . "' AND COLUMN_NAME = '" . $result['field'] . "'");

				if ($query->num_rows) {
					$this->db->query("ALTER TABLE `" . DB_PREFIX . $result['table'] . "` DROP `" . $result['field'] . "`");
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
