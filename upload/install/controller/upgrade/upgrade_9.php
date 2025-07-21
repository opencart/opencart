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

			$ssrs[] = [
				'code'   => 'article',
				'action' => 'catalog/data/article'
			];

			$ssrs[] = [
				'code'   => 'banner',
				'action' => 'catalog/data/banner'
			];

			$ssrs[] = [
				'code'   => 'category',
				'action' => 'catalog/data/category'
			];

			$ssrs[] = [
				'code'   => 'country',
				'action' => 'catalog/data/country'
			];

			$ssrs[] = [
				'code'   => 'country',
				'action' => 'admin/data/country'
			];

			$ssrs[] = [
				'code'   => 'currency',
				'action' => 'ssr/catalog/currency'
			];

			$ssrs[] = [
				'code'   => 'currency',
				'action' => 'ssr/admin/currency'
			];

			$ssrs[] = [
				'code'   => 'custom_field',
				'action' => 'catalog/data/custom_field'
			];

			$ssrs[] = [
				'code'   => 'custom_field',
				'action' => 'admin/data/custom_field'
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
				'action' => 'catalog/data/information'
			];

			$ssrs[] = [
				'code'   => 'language',
				'action' => 'catalog/data/language'
			];

			$ssrs[] = [
				'code'   => 'language',
				'action' => 'admin/data/language'
			];

			$ssrs[] = [
				'code'   => 'language',
				'action' => 'catalog/data/language'
			];

			$ssrs[] = [
				'code'   => 'language',
				'action' => 'admin/data/language'
			];

			$ssrs[] = [
				'code'   => 'length_class',
				'action' => 'catalog/data/length_class'
			];

			$ssrs[] = [
				'code'   => 'length_class',
				'action' => 'admin/data/length_class'
			];

			$ssrs[] = [
				'code'   => 'manufacturer',
				'action' => 'catalog/data/manufacturer'
			];

			$ssrs[] = [
				'code'   => 'option',
				'action' => 'catalog/data/option'
			];

			$ssrs[] = [
				'code'   => 'option',
				'action' => 'admin/data/option'
			];

			$ssrs[] = [
				'code'   => 'order_status',
				'action' => 'admin/order_status'
			];

			$ssrs[] = [
				'code'   => 'product',
				'action' => 'catalog/data/product'
			];

			$ssrs[] = [
				'code'   => 'return_reason',
				'action' => 'catalog/data/return_reason'
			];

			$ssrs[] = [
				'code'   => 'return_reason',
				'action' => 'admin/data/return_reason'
			];

			$ssrs[] = [
				'code'   => 'store',
				'action' => 'catalog/data/store'
			];

			$ssrs[] = [
				'code'   => 'store',
				'action' => 'admin/data/store'
			];

			$ssrs[] = [
				'code'   => 'theme',
				'action' => 'catalog/data/theme'
			];

			$ssrs[] = [
				'code'   => 'topic',
				'action' => 'catalog/data/topic'
			];

			$ssrs[] = [
				'code'   => 'translation',
				'action' => 'catalog/ata/translation'
			];

			$ssrs[] = [
				'code'   => 'weight_class',
				'action' => 'catalog/data/weight_class'
			];

			$ssrs[] = [
				'code'   => 'weight_class',
				'action' => 'admin/data/weight_class'
			];

			foreach ($ssrs as $ssr) {
				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ssr` WHERE `code` = '" . $this->db->escape($ssr['code']) . "'");

				if (!$query->num_rows) {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "ssr` SET `code` = '" . $this->db->escape($ssr['code']) . "', `action` = '" . $this->db->escape($ssr['action']) . "', `status` = '1', date_modified = NOW()");
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
