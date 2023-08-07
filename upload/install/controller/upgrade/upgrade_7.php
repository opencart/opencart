<?php
namespace Opencart\Install\Controller\Upgrade;
/**
 * Class Upgrade7
 *
 * @package Opencart\Install\Controller\Upgrade
 */
class Upgrade7 extends \Opencart\System\Engine\Controller {
	/**
	 * @return void
	 */
	public function index(): void {
		$this->load->language('upgrade/upgrade');

		$json = [];

		try {
			// Set Product Meta Title default to product name if empty
			$this->db->query("UPDATE `" . DB_PREFIX . "product_description` SET `meta_title` = `name` WHERE `meta_title` = ''");
			$this->db->query("UPDATE `" . DB_PREFIX . "category_description` SET `meta_title` = `name` WHERE `meta_title` = ''");
			$this->db->query("UPDATE `" . DB_PREFIX . "information_description` SET `meta_title` = `title` WHERE `meta_title` = ''");

			//  Option
			$this->db->query("UPDATE `" . DB_PREFIX . "option` SET `type` = 'radio' WHERE `type` = 'image'");

			// product_option
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "product_option' AND COLUMN_NAME = 'option_value'");

			if ($query->num_rows) {
				$this->db->query("UPDATE `" . DB_PREFIX . "product_option` SET `option_value` = 'value'");
			}

			// tags
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "product_tag'");

			if ($query->num_rows) {
				$language_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "language`");

				foreach ($language_query->rows as $language) {
					// Get old tags
					$product_query = $this->db->query("SELECT p.`product_id`, GROUP_CONCAT(DISTINCT pt.`tag` order by pt.`tag` ASC SEPARATOR ',') as `tags` FROM `" . DB_PREFIX . "product` p LEFT JOIN `" . DB_PREFIX . "product_tag` pt ON (p.`product_id` = pt.`product_id`) WHERE pt.`language_id` = '" . (int)$language['language_id'] . "' GROUP BY p.`product_id`");

					if ($product_query->num_rows) {
						foreach ($product_query->rows as $product) {
							$this->db->query("UPDATE `" . DB_PREFIX . "product_description` SET `tag` = '" . $this->db->escape(strtolower($product['tags'])) . "' WHERE `product_id` = '" . (int)$product['product_id'] . "' AND `language_id` = '" . (int)$language['language_id'] . "'");
							$this->db->query("DELETE FROM `" . DB_PREFIX . "product_tag` WHERE `product_id` = '" . (int)$product['product_id'] . "' AND `language_id` = '" . (int)$language['language_id'] . "'");
						}
					}
				}
			}

			// Banner
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "banner_image_description'");

			if ($query->num_rows) {
				$banner_image_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "banner_image`");

				foreach ($banner_image_query->rows as $banner_image) {
					$this->db->query("DELETE FROM `" . DB_PREFIX . "banner_image` WHERE `banner_image_id` = '" . (int)$banner_image['banner_image_id'] . "'");

					$banner_image_description_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "banner_image_description` WHERE `banner_image_id` = '" . (int)$banner_image['banner_image_id'] . "'");

					foreach ($banner_image_description_query->rows as $banner_image_description) {
						$this->db->query("INSERT INTO `" . DB_PREFIX . "banner_image` SET `banner_id` = '" . (int)$banner_image['banner_id'] . "', `language_id` = '" . (int)$banner_image_description['language_id'] . "', `title` = '" . $this->db->escape($banner_image_description['title']) . "', `link` = '" . $this->db->escape($banner_image['link']) . "', `image` = '" . $this->db->escape($banner_image['image']) . "', `sort_order` = '" . (int)$banner_image['sort_order'] . "'");
					}
				}
			}

			// Drop Fields
			$remove = [];

			// product_option
			$remove[] = [
				'table' => 'product_option',
				'field' => 'option_value'
			];

			// custom_field
			$remove[] = [
				'table' => 'custom_field',
				'field' => 'required'
			];

			$remove[] = [
				'table' => 'custom_field',
				'field' => 'position'
			];

			// download
			$remove[] = [
				'table' => 'custom_field',
				'field' => 'required'
			];

			$remove[] = [
				'table' => 'download',
				'field' => 'remaining'
			];

			$remove[] = [
				'table' => 'custom_field',
				'field' => 'required'
			];

			$remove[] = [
				'table' => 'banner_image_description',
				'field' => 'title'
			];

			// Drop date_added field from extension_path
			$remove[] = [
				'table' => 'extension_path',
				'field' => 'date_added'
			];

			foreach ($remove as $result) {
				$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . $result['table'] . "' AND COLUMN_NAME = '" . $result['field'] . "'");

				if ($query->num_rows) {
					$this->db->query("ALTER TABLE `" . DB_PREFIX . $result['table'] . "` DROP `" . $result['field'] . "`");
				}
			}

			// Drop Tables
			$remove = [
				'product_tag',
				'banner_image_description'
			];

			foreach ($remove as $table) {
				$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . $table . "'");

				if ($query->num_rows) {
					$this->db->query("DROP TABLE `" . DB_PREFIX . $table . "`");
				}
			}

			// Sort the categories to take advantage of the nested set model
			$this->repairCategories(0);
		} catch (\ErrorException $exception) {
			$json['error'] = sprintf($this->language->get('error_exception'), $exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
		}

		if (!$json) {
			$json['text'] = sprintf($this->language->get('text_progress'), 7, 7, 9);

			$url = '';

			if (isset($this->request->get['version'])) {
				$url .= '&version=' . $this->request->get['version'];
			}

			if (isset($this->request->get['admin'])) {
				$url .= '&admin=' . $this->request->get['admin'];
			}

			$json['next'] = $this->url->link('upgrade/upgrade_8', $url, true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	// Function to repair any erroneous categories that are not in the category path table.

	/**
	 * @param int $parent_id
	 *
	 * @return void
	 */
	private function repairCategories(int $parent_id = 0): void {
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
