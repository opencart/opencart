<?php
namespace Opencart\Install\Controller\Upgrade;
class Upgrade7 extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('upgrade/upgrade');

		$json = [];

		try {
			// Set Product Meta Title default to product name if empty
			$this->db->query("UPDATE `" . DB_PREFIX . "product_description` SET `meta_title` = `name` WHERE meta_title = ''");
			$this->db->query("UPDATE `" . DB_PREFIX . "category_description` SET `meta_title` = `name` WHERE meta_title = ''");
			$this->db->query("UPDATE `" . DB_PREFIX . "information_description` SET `meta_title` = `title` WHERE meta_title = ''");

			//  Option
			$this->db->query("UPDATE `" . DB_PREFIX . "option` SET `type` = 'radio' WHERE `type` = 'image'");

			// product_option
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "product_option' AND COLUMN_NAME = 'option_value'");

			if ($query->num_rows) {
				$this->db->query("UPDATE `" . DB_PREFIX . "product_option` SET `option_value` = `value`");
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "product_option` DROP `option_value`");
			}

			// tags
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "product_tag'");

			if ($query->num_rows) {
				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "language`");

				foreach ($query->rows as $language) {
					// Get old tags
					$query = $this->db->query("SELECT p.`product_id`, GROUP_CONCAT(DISTINCT pt.`tag` order by pt.`tag` ASC SEPARATOR ',') as `tags` FROM `" . DB_PREFIX . "product` p LEFT JOIN `" . DB_PREFIX . "product_tag` pt ON (p.`product_id` = pt.`product_id`) WHERE pt.`language_id` = '" . (int)$language['language_id'] . "' GROUP BY p.`product_id`");

					if ($query->num_rows) {
						foreach ($query->rows as $row) {
							$this->db->query("UPDATE `" . DB_PREFIX . "product_description` SET `tag` = '" . $this->db->escape(strtolower($row['tags'])) . "' WHERE `product_id` = '" . (int)$row['product_id'] . "' AND `language_id` = '" . (int)$language['language_id'] . "'");
							$this->db->query("DELETE FROM `" . DB_PREFIX . "product_tag` WHERE `product_id` = '" . (int)$row['product_id'] . "' AND `language_id` = '" . (int)$language['language_id'] . "'");
						}
					}
				}

				$this->db->query("DROP TABLE `" . DB_PREFIX . "product_tag`");
			}

			// custom_field
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "custom_field' AND COLUMN_NAME = 'required'");

			if ($query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "custom_field` DROP `required`");
			}

			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "custom_field' AND COLUMN_NAME = 'position'");

			if ($query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "custom_field` DROP `position`");
			}

			// download
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "download' AND COLUMN_NAME = 'remaining'");

			if ($query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "download` DROP `remaining`");
			}

			// Banner
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "banner_image' AND COLUMN_NAME = 'language_id'");

			if (!$query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "banner_image` ADD `language_id` INT(11) NOT NULL AFTER `banner_id`");
			}

			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "banner_image' AND COLUMN_NAME = 'title'");

			if (!$query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "banner_image` ADD `title` VARCHAR(64) NOT NULL AFTER `language_id`");
			}

			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "banner_image_description'");

			if ($query->num_rows) {
				$banner_image_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "banner_image`");

				foreach ($banner_image_query->rows as $banner_image) {
					$this->db->query("DELETE FROM `" . DB_PREFIX . "banner_image` WHERE banner_image_id = '" . (int)$banner_image['banner_image_id'] . "'");

					$banner_image_description_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "banner_image_description` WHERE `banner_image_id` = '" . (int)$banner_image['banner_image_id'] . "'");

					foreach ($banner_image_description_query->rows as $banner_image_description) {
						$this->db->query("INSERT INTO `" . DB_PREFIX . "banner_image` SET `banner_id` = '" . (int)$banner_image['banner_id'] . "', `language_id` = '" . (int)$banner_image_description['language_id'] . "', `title` = '" . $this->db->escape($banner_image_description['title']) . "', `link` = '" . $this->db->escape($banner_image['link']) . "', `image` = '" . $this->db->escape($banner_image['image']) . "', `sort_order` = '" . (int)$banner_image['sort_order'] . "'");
					}
				}

				$this->db->query("DROP TABLE `" . DB_PREFIX . "banner_image_description`");
			}

			// Sort the categories to take advantage of the nested set model
			$this->repairCategories(0);
		} catch(\ErrorException $exception) {
			$json['error'] = sprintf($this->language->get('error_exception'), $exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
		}

		if (!$json) {
			$json['success'] = sprintf($this->language->get('text_progress'), 6, 6, 8);

			$json['next'] = $this->url->link('upgrade/upgrade_8', '', true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	// Function to repair any erroneous categories that are not in the category path table.
	public function repairCategories(int $parent_id = 0): void {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category` WHERE `parent_id` = '" . (int)$parent_id . "'");

		foreach ($query->rows as $category) {
			// Delete the path below the current one
			$this->db->query("DELETE FROM `" . DB_PREFIX . "category_path` WHERE `category_id` = '" . (int)$category['category_id'] . "'");

			// Fix for records with no paths
			$level = 0;

			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE `category_id` = '" . (int)$parent_id . "' ORDER BY `level` ASC");

			foreach ($query->rows as $result) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET `category_id` = '" . (int)$category['category_id'] . "', `path_id` = '" . (int)$result['path_id'] . "', `level` = '" . (int)$level . "'");

				$level++;
			}

			$this->db->query("REPLACE INTO `" . DB_PREFIX . "category_path` SET `category_id` = '" . (int)$category['category_id'] . "', `path_id` = '" . (int)$category['category_id'] . "', `level` = '" . (int)$level . "'");

			$this->repairCategories($category['category_id']);
		}
	}
}
