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









       ('location', 'Generates location data.', 'task/catalog/location', 1, NOW()),
       ('manufacturer', 'Generates article data.', 'task/catalog/manufacturer', 1, NOW()),
       ('product', 'Generates product data.', 'task/catalog/product', 1, NOW()),
       ('return_reason', 'Generates return_reason data.', 'task/catalog/return_reason', 1, NOW()),
       ('review', 'Generates review data.', 'task/catalog/review', 1, NOW()),
       ('sass', 'Generates sass data.', 'task/catalog/sass', 1, NOW()),
       ('setting', 'Generates setting data.', 'task/catalog/setting', 1, NOW()),
       ('tag', 'Generates tag data.', 'task/catalog/tag', 1, NOW()),
       ('tax_rate', 'Generates tax rate data.', 'task/catalog/tax_rate', 1, NOW()),
       ('template', 'Generates template data.', 'task/catalog/template', 1, NOW()),
       ('topic', 'Generates topic data.', 'task/catalog/topic', 1, NOW()),
       ('translation', 'Generates translation data.', 'task/catalog/translation', 1, NOW());


			$ssrs[] = [
				'code'   => 'article',
				'action' => 'task/catalog/article'
			];

			$ssrs[] = [
				'code'   => 'banner',
				'action' => 'task/catalog/banner'
			];

			$ssrs[] = [
				'code'   => 'category',
				'action' => 'task/catalog/category'
			];

			$ssrs[] = [
				'code'   => 'comment',
				'action' => 'task/catalog/comment'
			];

			$ssrs[] = [
				'code'   => 'country',
				'action' => 'task/catalog/country'
			];

			$ssrs[] = [
				'code'   => 'currency',
				'action' => 'task/catalog/currency'
			];

			$ssrs[] = [
				'code'   => 'customer_group',
				'action' => 'task/catalog/customer_group'
			];

			$ssrs[] = [
				'code'   => 'filter',
				'action' => 'task/catalog/filter'
			];

			$ssrs[] = [
				'code'   => 'information',
				'action' => 'task/catalog/information'
			];

			$ssrs[] = [
				'code'   => 'language',
				'action' => 'task/catalog/language'
			];

			$ssrs[] = [
				'code'   => 'location',
				'action' => 'task/catalog/location'
			];

			$ssrs[] = [
				'code'   => 'manufacturer',
				'action' => 'task/catalog/manufacturer'
			];

			$ssrs[] = [
				'code'   => 'product',
				'action' => 'task/catalog/product'
			];

			$ssrs[] = [
				'code'   => 'return_reason',
				'action' => 'task/catalog/return_reasonn'
			];

			$ssrs[] = [
				'code'   => 'review',
				'action' => 'task/catalog/review'
			];

			$ssrs[] = [
				'code'   => 'sass',
				'action' => 'task/catalog/sass'
			];

			$ssrs[] = [
				'code'   => 'setting',
				'action' => 'task/catalog/setting'
			];

			$ssrs[] = [
				'code'   => 'store',
				'action' => 'task/catalog/store'
			];

			$ssrs[] = [
				'code'   => 'tag',
				'action' => 'task/catalog/tag'
			];

			$ssrs[] = [
				'code'   => 'tax_rate',
				'action' => 'task/catalog/tax_rate'
			];

			$ssrs[] = [
				'code'   => 'template',
				'action' => 'task/catalog/template'
			];

			$ssrs[] = [
				'code'   => 'topic',
				'action' => 'task/catalog/topic'
			];

			$ssrs[] = [
				'code'   => 'translation',
				'action' => 'task/catalog/translation'
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
