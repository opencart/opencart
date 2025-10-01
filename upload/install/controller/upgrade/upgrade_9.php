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
				'code'   => 'banner',
				'action' => 'task/catalog/banner'
			];

			$ssrs[] = [
				'code'   => 'country',
				'action' => 'task/catalog/country'
			];

			$ssrs[] = [
				'code'   => 'country',
				'action' => 'task/admin/country'
			];

			$ssrs[] = [
				'code'   => 'currency',
				'action' => 'task/catalog/currency'
			];

			$ssrs[] = [
				'code'   => 'currency',
				'action' => 'task/admin/currency'
			];

			$ssrs[] = [
				'code'   => 'customer_group',
				'action' => 'task/catalog/customer_group'
			];

			$ssrs[] = [
				'code'   => 'customer_group',
				'action' => 'task/admin/customer_group'
			];

			$ssrs[] = [
				'code'   => 'information',
				'action' => 'task/admin/information'
			];

			$ssrs[] = [
				'code'   => 'language',
				'action' => 'task/catalog/language'
			];

			$ssrs[] = [
				'code'   => 'language',
				'action' => 'task/admin/language'
			];

			$ssrs[] = [
				'code'   => 'length_class',
				'action' => 'task/catalog/length_class'
			];

			$ssrs[] = [
				'code'   => 'length_class',
				'action' => 'task/admin/length_class'
			];

			$ssrs[] = [
				'code'   => 'report_order',
				'action' => 'task/report/order'
			];

			$ssrs[] = [
				'code'   => 'report_return',
				'action' => 'task/report/returns'
			];

			$ssrs[] = [
				'code'   => 'report_review',
				'action' => 'task/report/review'
			];

			$ssrs[] = [
				'code'   => 'report_sale',
				'action' => 'task/report/sale'
			];

			$ssrs[] = [
				'code'   => 'report_stock',
				'action' => 'task/report/stock'
			];

			$ssrs[] = [
				'code'   => 'return_action',
				'action' => 'task/admin/return_action'
			];

			$ssrs[] = [
				'code'   => 'return_reason',
				'action' => 'task/catalog/return_reasonn'
			];

			$ssrs[] = [
				'code'   => 'return_reason',
				'action' => 'task/admin/return_reason'
			];

			$ssrs[] = [
				'code'   => 'return_status',
				'action' => 'task/admin/return_status'
			];

			$ssrs[] = [
				'code'   => 'review',
				'action' => 'task/catalog/review'
			];

			$ssrs[] = [
				'code'   => 'sass',
				'action' => 'task/catalog/stock_status'
			];

			$ssrs[] = [
				'code'   => 'stock_status',
				'action' => 'task/admin/stock_status'
			];

			$ssrs[] = [
				'code'   => 'store',
				'action' => 'task/admin/store'
			];

			$ssrs[] = [
				'code'   => 'subscription_status',
				'action' => 'task/admin/subscription_status'
			];

			$ssrs[] = [
				'code'   => 'theme',
				'action' => 'task/catalog/theme'
			];

			$ssrs[] = [
				'code'   => 'translation',
				'action' => 'task/catalog/translation'
			];

			$ssrs[] = [
				'code'   => 'weight_class',
				'action' => 'task/catalog/weight_class'
			];

			$ssrs[] = [
				'code'   => 'weight_class',
				'action' => 'task/admin/weight_class'
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
