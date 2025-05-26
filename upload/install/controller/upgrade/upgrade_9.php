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

			/*
			$ssrs[] = [
				'code'   => 'article',
				'action' => 'ssr/catalog/article'
			];

			$ssrs[] = [
				'code'   => 'banner',
				'action' => 'ssr/catalog/banner'
			];


			$ssrs[] = [
				'code'   => 'category',
				'action' => 'ssr/catalog/category'
			];
			*/

			$ssrs[] = [
				'code'   => 'country',
				'action' => 'ssr/catalog/country'
			];

			$ssrs[] = [
				'code'   => 'country',
				'action' => 'ssr/admin/country'
			];


			$ssrs[] = [
				'code'   => 'currency',
				'action' => 'ssr/catalog/currency'
			];

			$ssrs[] = [
				'code'   => 'currency',
				'action' => 'ssr/admin/currency'
			];

			/*
			$ssrs[] = [
				'code'   => 'custom_field',
				'action' => 'ssr/catalog/custom_field'
			];

			$ssrs[] = [
				'code'   => 'custom_field',
				'action' => 'ssr/admin/custom_field'
			];

			$ssrs[] = [
				'code'   => 'customer_group',
				'action' => 'ssr/catalog/customer_group'
			];

			$ssrs[] = [
				'code'   => 'customer_group',
				'action' => 'ssr/admin/customer_group'
			];

			$ssrs[] = [
				'code'   => 'information',
				'action' => 'ssr/catalog/information'
			];
			*/

			$ssrs[] = [
				'code'   => 'language',
				'action' => 'ssr/catalog/language'
			];

			$ssrs[] = [
				'code'   => 'language',
				'action' => 'ssr/admin/language'
			];
			/*
			$ssrs[] = [
				'code'   => 'length_class',
				'action' => 'ssr/catalog/length_class'
			];

			$ssrs[] = [
				'code'   => 'length_class',
				'action' => 'ssr/admin/length_class'
			];

			$ssrs[] = [
				'code'   => 'manufacturer',
				'action' => 'ssr/catalog/manufacturer'
			];

			$ssrs[] = [
				'code'   => 'option',
				'action' => 'ssr/catalog/option'
			];

			$ssrs[] = [
				'code'   => 'order_status',
				'action' => 'ssr/admin/order_status'
			];

			$ssrs[] = [
				'code'   => 'product',
				'action' => 'ssr/catalog/product'
			];

			$ssrs[] = [
				'code'   => 'return_reason',
				'action' => 'ssr/catalog/return_reason'
			];

			$ssrs[] = [
				'code'   => 'return_reason',
				'action' => 'ssr/admin/return_reason'
			];

			$ssrs[] = [
				'code'   => 'store',
				'action' => 'ssr/catalog/store'
			];

			$ssrs[] = [
				'code'   => 'store',
				'action' => 'ssr/admin/store'
			];

			$ssrs[] = [
				'code'   => 'topic',
				'action' => 'ssr/catalog/topic'
			];

			$ssrs[] = [
				'code'   => 'translation',
				'action' => 'ssr/catalog/translation'
			];

			$ssrs[] = [
				'code'   => 'translation',
				'action' => 'ssr/admin/translation'
			];

			$ssrs[] = [
				'code'   => 'weight_class',
				'action' => 'ssr/catalog/weight_class'
			];

			$ssrs[] = [
				'code'   => 'weight_class',
				'action' => 'ssr/admin/weight_class'
			];
			*/

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
