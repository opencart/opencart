<?php

namespace Opencart\Install\Controller\Upgrade;
class Upgrade4 extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('upgrade/upgrade');

		$json = [];

		try {
			// module
			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "module`");

			foreach ($query->rows as $result) {
				if (preg_match('/^(a:)/', $result['setting'])) {
					$this->db->query("UPDATE `" . DB_PREFIX . "module` SET `setting` = '" . $this->db->escape(json_encode(unserialize($result['setting']))) . "' WHERE `module_id` = '" . (int)$result['module_id'] . "'");
				}
			}

			// Convert 1.5.x core module format to 2.x (core modules only)
			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE `serialized` = '1'");

			foreach ($query->rows as $result) {
				if ($result['serialized']) {

					$values = json_decode($result['value'], true);

					$module_data = [];

					if (in_array($result['code'], ['latest', 'bestseller', 'special', 'featured'])) {


						if ($values) {

							foreach ($values as $key => $value) {

								// Since 2.x doesn't look good with modules as side boxes, set to content bottom
								if ($value['position'] == 'column_left' || $value['position'] == 'column_right') {
									$value['position'] = 'content_bottom';
								}

								$module_data['name'] = ($result['key'] . '_' . $key);
								$module_data['status'] = $value['status'];

								if (isset($value['image_width'])) {
									$module_data['width'] = $value['image_width'];
								}

								if (isset($value['image_height'])) {
									$module_data['height'] = $value['image_height'];
								}

								if (isset($value['limit'])) {
									$module_data['limit'] = $value['limit'];
								} else {
									$module_data['limit'] = 4;
								}

								if ($result['code'] == 'featured') {
									foreach ($query->rows as $result2) {

										if ($result2['key'] == 'featured_product') {

											$module_data['product'] = explode(',', $result2['value']);
											$module_data['limit'] = 4;

											break;
										} else {
											$featured_product_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE `key` = 'featured_product'");

											if ($featured_product_query->num_rows) {

												$module_data['product'] = explode(',', $featured_product_query->row['value']);

												$module_data['limit'] = 4;
											}
										}

									}
								}

								$this->db->query("INSERT INTO `" . DB_PREFIX . "module` SET `name` = '" . $this->db->escape($result['key']) . '_' . $key . "', `code` = '" . $this->db->escape($result['code']) . "', `setting` = '" . $this->db->escape(json_encode($module_data)) . "'");

								$module_id = $this->db->getLastId();

								$this->db->query("INSERT INTO `" . DB_PREFIX . "layout_module` SET `layout_id` = '" . (int)$value['layout_id'] . "', `code` = '" . $this->db->escape($result['code'] . '.' . $module_id) . "', `position` = '" . $this->db->escape($value['position']) . "', `sort_order` = '" . (int)$value['sort_order'] . "'");

								$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE store_id = '" . (int)$result['store_id'] . "' AND `code` = '" . $this->db->escape($result['code']) . "'");
							}


						} else {
							$this->db->query("DELETE FROM `" . DB_PREFIX . "extension` WHERE `code` = '" . $this->db->escape($result['code']) . "'");
						}


					} elseif (in_array($result['code'], ['category', 'account', 'affiliate', 'filter'])) {


						foreach ($value as $key => $value) {
							$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `store_id` = '" . (int)$result['store_id'] . "' AND `code` = '" . $this->db->escape($result['code']) . "'");
							$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '" . (int)$result['store_id'] . "', `code` = '" . $this->db->escape($result['code']) . "', `key` = '" . ($result['code'] . '_status') . "', `value` = '1'");

							if ($value['status']) {
								$this->db->query("INSERT INTO `" . DB_PREFIX . "layout_module` SET `layout_id` = '" . (int)$value['layout_id'] . "', `code` = '" . $this->db->escape($result['code']) . "', `position` = '" . $this->db->escape($value['position']) . "', `sort_order` = '" . (int)$value['sort_order'] . "'");
							}
						}


					} elseif (in_array($result['code'], ['banner', 'carousel', 'slideshow'])) {


						if ($values) {
							foreach ($values as $key => $value) {
								$module_data['name'] = ($result['key'] . '_' . $key);
								$module_data['status'] = $value['status'];
								$module_data['banner_id'] = $value['banner_id'];

								if (isset($value['image_width'])) {
									$module_data['width'] = $value['image_width'];
								}

								if (isset($value['image_height'])) {
									$module_data['height'] = $value['image_height'];
								}

								if (isset($value['width'])) {
									$module_data['width'] = $value['width'];
								}

								if (isset($value['height'])) {
									$module_data['height'] = $value['height'];
								}

								$this->db->query("INSERT INTO `" . DB_PREFIX . "module` SET `name` = '" . $this->db->escape($result['key']) . '_' . $key . "', `code` = '" . $this->db->escape($result['code']) . "', `setting` = '" . $this->db->escape(json_encode($module_data)) . "'");

								$module_id = $this->db->getLastId();

								$this->db->query("INSERT INTO `" . DB_PREFIX . "layout_module` SET `layout_id` = '" . (int)$value['layout_id'] . "', `code` = '" . $this->db->escape($result['code'] . '.' . $module_id) . "', `position` = '" . $this->db->escape($value['position']) . "', `sort_order` = '" . (int)$value['sort_order'] . "'");

								$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `store_id` = '" . (int)$result['store_id'] . "' AND `code` = '" . $this->db->escape($result['code']) . "'");
							}
						} else {
							$this->db->query("DELETE FROM `" . DB_PREFIX . "extension` WHERE `code` = '" . $this->db->escape($result['code']) . "'");
						}


					} elseif (in_array($result['code'], ['welcome'])) {
						if ($values) {
							// Install HTML module if not already installed
							$html_query = $this->db->query("SELECT count(*) AS total FROM `" . DB_PREFIX . "extension` WHERE `code` = 'html'");

							if (!$html_query->row['total']) {
								$this->db->query("INSERT INTO `" . DB_PREFIX . "extension` SET `type` = 'module', `code` = 'html'");
							}

							$result['code'] = 'html';

							foreach ($values as $key => $value) {
								$module_data['name'] = ($result['key'] . '_' . $key);
								$module_data['status'] = $value['status'];

								foreach ($value['description'] as $language_id => $description) {
									$module_data['module_description'][$language_id]['title'] = '';
									$module_data['module_description'][$language_id]['description'] = str_replace('image/data', 'image/catalog', $description);
								}

								$this->db->query("INSERT INTO `" . DB_PREFIX . "module` SET `name` = '" . $this->db->escape($result['key']) . '_' . $key . "', `code` = '" . $this->db->escape($result['code']) . "', `setting` = '" . $this->db->escape(json_encode($module_data)) . "'");

								$module_id = $this->db->getLastId();

								$this->db->query("INSERT INTO `" . DB_PREFIX . "layout_module` SET `layout_id` = '" . (int)$value['layout_id'] . "', `code` = '" . $this->db->escape($result['code'] . '.' . $module_id) . "', `position` = '" . $this->db->escape($value['position']) . "', `sort_order` = '" . (int)$value['sort_order'] . "'");

								$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `store_id` = '" . (int)$result['store_id'] . "' AND `code` = 'welcome'");
							}
						} else {
							$this->db->query("DELETE FROM `" . DB_PREFIX . "extension` WHERE `code` = 'welcome'");
						}
					} else {

						// Could add code for other types here
						// If module has position, but not a core module, then disable it because it likely isn't compatible
						if (!empty($values)) {

							foreach ($values as $key => $value) {
								if (isset($value['position'])) {
									$module_data = $value;

									$module_data['name'] = ($result['key'] . '_' . $key);
									$module_data['status'] = '0'; // Disable non-core modules

									$this->db->query("INSERT INTO `" . DB_PREFIX . "module` SET `name` = '" . $this->db->escape($result['key']) . '_' . $key . "', `code` = '" . $this->db->escape($result['code']) . "', `setting` = '" . $this->db->escape(json_encode($module_data)) . "'");
								}
							}

						} else {
							$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `value` = '" . $this->db->escape(json_encode($value)) . "' WHERE `setting_id` = '" . (int)$result['setting_id'] . "'");
						}
					}
				}
			}

			/*

INSERT INTO `oc_extension` (`extension_id`, `extension`, `type`, `code`) VALUES
(1, 'opencart', 'payment', 'cod'),
(2, 'opencart', 'total', 'shipping'),
(3, 'opencart', 'total', 'sub_total'),
(4, 'opencart', 'total', 'tax'),
(5, 'opencart', 'total', 'total'),
(6, 'opencart', 'module', 'banner'),
(8, 'opencart', 'total', 'credit'),
(9, 'opencart', 'shipping', 'flat'),
(10, 'opencart', 'total', 'handling'),
(11, 'opencart', 'total', 'low_order_fee'),
(12, 'opencart', 'total', 'coupon'),
(13, 'opencart', 'module', 'category'),
(14, 'opencart', 'module', 'account'),
(15, 'opencart', 'total', 'reward'),
(16, 'opencart', 'total', 'voucher'),
(17, 'opencart', 'payment', 'free_checkout'),
(18, 'opencart', 'module', 'featured'),
(20, 'opencart', 'theme', 'basic'),
(21, 'opencart', 'dashboard', 'activity'),
(22, 'opencart', 'dashboard', 'sale'),
(23, 'opencart', 'dashboard', 'recent'),
(24, 'opencart', 'dashboard', 'order'),
(25, 'opencart', 'dashboard', 'online'),
(26, 'opencart', 'dashboard', 'map'),
(27, 'opencart', 'dashboard', 'customer'),
(28, 'opencart', 'dashboard', 'chart'),
(29, 'opencart', 'report', 'sale_coupon'),
(31, 'opencart', 'report', 'customer_search'),
(32, 'opencart', 'report', 'customer_transaction'),
(33, 'opencart', 'report', 'product_purchased'),
(34, 'opencart', 'report', 'product_viewed'),
(35, 'opencart', 'report', 'sale_return'),
(36, 'opencart', 'report', 'sale_order'),
(37, 'opencart', 'report', 'sale_shipping'),
(38, 'opencart', 'report', 'sale_tax'),
(39, 'opencart', 'report', 'customer_activity'),
(40, 'opencart', 'report', 'customer_order'),
(41, 'opencart', 'report', 'customer_reward'),
(42, 'opencart', 'currency', 'ecb');
			 */


		} catch (\ErrorException $exception) {
			$json['error'] = sprintf($this->language->get('error_exception'), $exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
		}

		if (!$json) {
			$json['success'] = sprintf($this->language->get('text_progress'), 4, 4, 8);

			$json['next'] = $this->url->link('upgrade/upgrade_5', '', true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}