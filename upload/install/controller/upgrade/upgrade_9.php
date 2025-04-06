<?php
namespace Opencart\Install\Controller\Upgrade;
/**
 * Class Upgrade9
 *
 * @package Opencart\Install\Controller\Upgrade
 */
class Upgrade9 extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('upgrade/upgrade');

		$json = [];

		try {
			$ssrs = [];

			//$ssrs[] = [
			//	'code'   => 'article',
			//	'action' => 'ssr/article'
			//];

			//$ssrs[] = [
			//	'code'   => 'category',
			//	'action' => 'ssr/category'
			//];

			$ssrs[] = [
				'code'   => 'country',
				'action' => 'ssr/country'
			];

			$ssrs[] = [
				'code'   => 'currency',
				'action' => 'ssr/currency'
			];

			//$ssrs[] = [
			//	'code'   => 'information',
			//	'action' => 'ssr/information'
			//];

			$ssrs[] = [
				'code'   => 'language',
				'action' => 'ssr/language'
			];

			//$ssrs[] = [
			//	'code'   => 'manufacturer',
			//	'action' => 'ssr/manufacturer'
			//];

			//$ssrs[] = [
			//	'code'   => 'product',
			//	'action' => 'ssr/product'
			//];

			//$ssrs[] = [
			//	'code'   => 'topic',
			//	'action' => 'ssr/topic'
			//];

			foreach ($ssrs as $ssr) {
				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ssr` WHERE `code` = '" . $this->db->escape($ssr['code']) . "'");

				if (!$query->num_rows) {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "ssr` SET `code` = '" . $this->db->escape($ssr['code']) . "', `action` = '" . $this->db->escape($ssr['action']) . "', `status` = '1', `sort_order` = '0', date_modified = NOW()");
				}
			}


		} catch (\ErrorException $exception) {
			$json['error'] = sprintf($this->language->get('error_exception'), $exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
		}

		if (!$json) {
			$json['text'] = sprintf($this->language->get('text_patch'), 9, count(glob(DIR_APPLICATION . 'controller/upgrade/upgrade_*.php')));

			$url = '';

			if (isset($this->request->get['version'])) {
				$url .= '&version=' . $this->request->get['version'];
			}

			if (isset($this->request->get['admin'])) {
				$url .= '&admin=' . $this->request->get['admin'];
			}

			$json['next'] = $this->url->link('upgrade/upgrade_10', $url, true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
