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
			// Set Product Meta Title default to product name if empty
			$this->db->query("UPDATE `" . DB_PREFIX . "category_description` SET `meta_title` = `name` WHERE `meta_title` = ''");
			$this->db->query("UPDATE `" . DB_PREFIX . "product_description` SET `meta_title` = `name` WHERE `meta_title` = ''");
			$this->db->query("UPDATE `" . DB_PREFIX . "information_description` SET `meta_title` = `title` WHERE `meta_title` = ''");

			// Convert image/data to image/catalog
			$this->db->query("UPDATE `" . DB_PREFIX . "banner_image` SET `image` = REPLACE(image, 'data/', 'catalog/')");
			$this->db->query("UPDATE `" . DB_PREFIX . "category` SET `image` = REPLACE(image, 'data/', 'catalog/')");
			$this->db->query("UPDATE `" . DB_PREFIX . "manufacturer` SET `image` = REPLACE(image, 'data/', 'catalog/')");
			$this->db->query("UPDATE `" . DB_PREFIX . "product` SET `image` = REPLACE(image, 'data/', 'catalog/')");
			$this->db->query("UPDATE `" . DB_PREFIX . "product_image` SET `image` = REPLACE(image, 'data/', 'catalog/')");
			$this->db->query("UPDATE `" . DB_PREFIX . "option_value` SET `image` = REPLACE(image, 'data/', 'catalog/')");
			$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `value` = REPLACE(value, 'data/', 'catalog/')");
			$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `value` = REPLACE(value, 'data/', 'catalog/')");
			$this->db->query("UPDATE `" . DB_PREFIX . "product_description` SET `description` = REPLACE(description, 'data/', 'catalog/')");
			$this->db->query("UPDATE `" . DB_PREFIX . "category_description` SET `description` = REPLACE(description, 'data/', 'catalog/')");
			$this->db->query("UPDATE `" . DB_PREFIX . "information_description` SET `description` = REPLACE(description, 'data/', 'catalog/')");

			// Fix https://github.com/opencart/opencart/issues/11594
			$this->db->query("UPDATE `" . DB_PREFIX . "layout_route` SET `route` = REPLACE(`route`, '|', '.')");
			$this->db->query("UPDATE `" . DB_PREFIX . "seo_url` SET `value` = REPLACE(`value`, '|', '.') WHERE `key` = 'route'");
			$this->db->query("UPDATE `" . DB_PREFIX . "event` SET `trigger` = REPLACE(`trigger`, '|', '.'), `action` = REPLACE(`action`, '|', '.')");
			$this->db->query("UPDATE `" . DB_PREFIX . "banner_image` SET `link` = REPLACE(`link`, '|', '.')");

			//  Option
			$this->db->query("UPDATE `" . DB_PREFIX . "option` SET `type` = 'radio' WHERE `type` = 'image'");

			// product_option
			$this->load->model('upgrade/upgrade');

			if ($this->model_upgrade_upgrade->hasField('product_option', 'option_value')) {
				$this->model_upgrade_upgrade->dropField('product_option', 'option_value');
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

			// banner_image_description
			$remove[] = [
				'table' => 'banner_image_description',
				'field' => 'title'
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

			$remove[] = [
				'table' => 'custom_field',
				'field' => 'required'
			];

			$remove[] = [
				'table' => 'custom_field',
				'field' => 'required'
			];

			// download
			$remove[] = [
				'table' => 'download',
				'field' => 'remaining'
			];

			// extension_path
			$remove[] = [
				'table' => 'extension_path',
				'field' => 'date_added'
			];

			// geo_zone
			$remove[] = [
				'table' => 'geo_zone',
				'field' => 'date_added'
			];

			$remove[] = [
				'table' => 'geo_zone',
				'field' => 'date_modified'
			];

			// product_option
			$remove[] = [
				'table' => 'product_option',
				'field' => 'option_value'
			];

			// tax_class
			$remove[] = [
				'table' => 'tax_class',
				'field' => 'date_added'
			];

			$remove[] = [
				'table' => 'tax_class',
				'field' => 'date_modified'
			];

			// tax_rate
			$remove[] = [
				'table' => 'tax_rate',
				'field' => 'date_added'
			];

			$remove[] = [
				'table' => 'tax_rate',
				'field' => 'date_modified'
			];

			$this->load->model('upgrade/upgrade');

			foreach ($remove as $result) {
				$this->model_upgrade_upgrade->dropField($result['table'], $result['field']);
			}

			// Drop Tables
			$remove = [
				'product_tag',
				'banner_image_description'
			];

			foreach ($remove as $table) {
				$this->model_upgrade_upgrade->dropTable($table);
			}

			// Sort the categories to take advantage of the nested set model
			$this->repairCategories(0);
		} catch (\ErrorException $exception) {
			$json['error'] = sprintf($this->language->get('error_exception'), $exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
		}

		if (!$json) {
			$json['text'] = sprintf($this->language->get('text_patch'), 11, count(glob(DIR_APPLICATION . 'controller/upgrade/upgrade_*.php')));

			$url = '';

			if (isset($this->request->get['version'])) {
				$url .= '&version=' . $this->request->get['version'];
			}

			if (isset($this->request->get['admin'])) {
				$url .= '&admin=' . $this->request->get['admin'];
			}

			$json['next'] = $this->url->link('upgrade/upgrade_11', $url, true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Repair Categories
	 *
	 * Repair any erroneous categories that are not in the category path table.
	 *
	 * @param int $parent_id primary key of the parent category record
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
