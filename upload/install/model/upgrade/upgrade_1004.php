<?php
namespace Opencart\Install\Model\Upgrade;
class Upgrade1004 extends \Opencart\System\Engine\Model {
	public function upgrade() {


		// Convert 1.5.x core module format to 2.x (core modules only)
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE `serialized` = '1'");

		foreach ($query->rows as $result) {
			if ($result['serialized']) {
				$value = json_decode($result['value'], true);

				$module_data = [];

				if (in_array($result['code'], ['latest', 'bestseller', 'special', 'featured'])) {


					if ($value) {

						foreach ($value as $k => $v) {

							// Since 2.x doesn't look good with modules as side boxes, set to content bottom
							if ($v['position'] == 'column_left' || $v['position'] == 'column_right') {
								$v['position'] = 'content_bottom';
							}

							$module_data['name'] = ($result['key'] . '_' . $k);
							$module_data['status'] = $v['status'];

							if (isset($v['image_width'])) {
								$module_data['width'] = $v['image_width'];
							}

							if (isset($v['image_height'])) {
								$module_data['height'] = $v['image_height'];
							}

							if (isset($v['limit'])) {
								$module_data['limit'] = $v['limit'];
							} else {
								$module_data['limit'] = 4;
							}

							if ($result['code'] == 'featured') {
								foreach ($query->rows as $result2) {
									if ($result2['key'] == 'featured_product') {
										$module_data['product'] = explode(",", $result2['value']);
										$module_data['limit'] = 4;
										break;
									} else {
										$featured_product_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE `key` = 'featured_product'");

										if ($featured_product_query->num_rows) {
											$module_data['product'] = explode(",", $featured_product_query->row['value']);
											$module_data['limit'] = 4;
										}
									}
								}
							}

							$this->db->query("INSERT INTO `" . DB_PREFIX . "module` SET `name` = '" . $this->db->escape($result['key']) . '_' . $k . "', `code` = '" . $this->db->escape($result['code']) . "', `setting` = '" . $this->db->escape(json_encode($module_data)) . "'");

							$module_id = $this->db->getLastId();

							$this->db->query("INSERT INTO `" . DB_PREFIX . "layout_module` SET `layout_id` = '" . (int)$v['layout_id'] . "', `code` = '" . $this->db->escape($result['code'] . '.' . $module_id) . "', `position` = '" . $this->db->escape($v['position']) . "', `sort_order` = '" . (int)$v['sort_order'] . "'");

							$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE store_id = '" . (int)$result['store_id'] . "' AND `code` = '" . $this->db->escape($result['code']) . "'");
						}


					} else {
						$this->db->query("DELETE FROM `" . DB_PREFIX . "extension` WHERE `code` = '" . $this->db->escape($result['code']) . "'");
					}



				} elseif (in_array($result['code'], ['category', 'account', 'affiliate', 'filter'])) {


					foreach ($value as $k => $v) {
						$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `store_id` = '" . (int)$result['store_id'] . "' AND `code` = '" . $this->db->escape($result['code']) . "'");
						$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '" . (int)$result['store_id'] . "', `code` = '" . $this->db->escape($result['code']) . "', `key` = '" . ($result['code'] . '_status') . "', `value` = '1'");

						if ($v['status']) {
							$this->db->query("INSERT INTO `" . DB_PREFIX . "layout_module` SET `layout_id` = '" . (int)$v['layout_id'] . "', `code` = '" . $this->db->escape($result['code']) . "', `position` = '" . $this->db->escape($v['position']) . "', `sort_order` = '" . (int)$v['sort_order'] . "'");
						}
					}


				} elseif (in_array($result['code'], ['banner', 'carousel', 'slideshow'])) {


					if ($value) {
						foreach ($value as $k => $v) {
							$module_data['name'] = ($result['key'] . '_' . $k);
							$module_data['status'] = $v['status'];
							$module_data['banner_id'] = $v['banner_id'];

							if (isset($v['image_width'])) {
								$module_data['width'] = $v['image_width'];
							}

							if (isset($v['image_height'])) {
								$module_data['height'] = $v['image_height'];
							}

							if (isset($v['width'])) {
								$module_data['width'] = $v['width'];
							}

							if (isset($v['height'])) {
								$module_data['height'] = $v['height'];
							}

							$this->db->query("INSERT INTO `" . DB_PREFIX . "module` SET `name` = '" . $this->db->escape($result['key']) . '_' . $k . "', `code` = '" . $this->db->escape($result['code']) . "', `setting` = '" . $this->db->escape(json_encode($module_data)) . "'");

							$module_id = $this->db->getLastId();

							$this->db->query("INSERT INTO `" . DB_PREFIX . "layout_module` SET `layout_id` = '" . (int)$v['layout_id'] . "', `code` = '" . $this->db->escape($result['code'] . '.' . $module_id) . "', `position` = '" . $this->db->escape($v['position']) . "', `sort_order` = '" . (int)$v['sort_order'] . "'");

							$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `store_id` = '" . (int)$result['store_id'] . "' AND `code` = '" . $this->db->escape($result['code']) . "'");
						}
					} else {
						$this->db->query("DELETE FROM `" . DB_PREFIX . "extension` WHERE `code` = '" . $this->db->escape($result['code']) . "'");
					}


				} elseif (in_array($result['code'], ['welcome'])) {
					if ($value) {
						// Install HTML module if not already installed
						$html_query = $this->db->query("SELECT count(*) FROM `" . DB_PREFIX . "extension` WHERE `code` = 'html'");

						if ($html_query->row['count(*)'] == '0') {
							$this->db->query("INSERT INTO `" . DB_PREFIX . "extension` SET `type` = 'module', `code` = 'html'");
						}

						$result['code'] = 'html';

						foreach ($value as $k => $v) {
							$module_data['name'] = ($result['key'] . '_' . $k);
							$module_data['status'] = $v['status'];

							foreach ($v['description'] as $language_id => $description) {
								$module_data['module_description'][$language_id]['title'] = '';
								$module_data['module_description'][$language_id]['description'] = str_replace('image/data', 'image/catalo   g', $description);
							}

							$this->db->query("INSERT INTO `" . DB_PREFIX . "module` SET `name` = '" . $this->db->escape($result['key']) . '_' . $k . "', `code` = '" . $this->db->escape($result['code']) . "', `setting` = '" . $this->db->escape(json_encode($module_data)) . "'");

							$module_id = $this->db->getLastId();

							$this->db->query("INSERT INTO `" . DB_PREFIX . "layout_module` SET `layout_id` = '" . (int)$v['layout_id'] . "', `code` = '" . $this->db->escape($result['code'] . '.' . $module_id) . "', `position` = '" . $this->db->escape($v['position']) . "', `sort_order` = '" . (int)$v['sort_order'] . "'");

							$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `store_id` = '" . (int)$result['store_id'] . "' AND `code` = 'welcome'");
						}
					} else {
						$this->db->query("DELETE FROM `" . DB_PREFIX . "extension` WHERE `code` = 'welcome'");
					}
				} else {
					// Could add code for other types here
					// If module has position, but not a core module, then disable it because it likely isn't compatible
					if (!empty($value)) {
						foreach ($value as $k => $v) {
							if (isset($v['position'])) {
								$module_data = $v;

								$module_data['name'] = ($result['key'] . '_' . $k);
								$module_data['status'] = '0'; // Disable non-core modules

								$this->db->query("INSERT INTO `" . DB_PREFIX . "module` SET `name` = '" . $this->db->escape($result['key']) . '_' . $k . "', `code` = '" . $this->db->escape($result['code']) . "', `setting` = '" . $this->db->escape(json_encode($module_data)) . "'");
							}
						}
					} else {
						$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `value` = '" . $this->db->escape(json_encode($value)) . "' WHERE `setting_id` = '" . (int)$result['setting_id'] . "'");
					}
				}
			} else {
				// Something should go here?
			}
		}
	}
}
