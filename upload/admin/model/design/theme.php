<?php
namespace Opencart\Admin\Model\Design;
/**
 * Class Theme
 *
 * Can be loaded using $this->load->model('design/theme');
 *
 * @package Opencart\Admin\Model\Design
 */
class Theme extends \Opencart\System\Engine\Model {
	/**
	 * Add Theme
	 *
	 * Create a new theme record in the database.
	 *
	 * @param array<string, mixed> $data array of data
	 *
	 * @return int
	 *
	 * @example
	 *
	 * $theme_data = [
	 *     'route'  => '',
	 *     'code'   => '',
	 *     'status' => 0
	 * ];
	 *
	 * $this->load->model('design/theme');
	 *
	 * $theme_id = $this->model_design_theme->addTheme($theme_data);
	 */
	public function addTheme(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "theme` SET `store_id` = '" . (int)$data['store_id'] . "', `route` = '" . $this->db->escape($data['route']) . "', `code` = '" . $this->db->escape($data['code']) . "', `status` = '" . (bool)$data['status'] . "', `date_added` = NOW()");

		return $this->db->getLastId();
	}

	/**
	 * Edit Theme
	 *
	 * Edit theme record in the database.
	 *
	 * @param int                  $theme_id primary key of the theme record
	 * @param array<string, mixed> $data     array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $theme_data = [
	 *     'route'  => '',
	 *     'code'   => '',
	 *     'status' => 1
	 * ];
	 *
	 * $this->load->model('design/theme');
	 *
	 * $this->model_design_theme->editTheme($theme_id, $theme_data);
	 */
	public function editTheme(int $theme_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "theme` SET `store_id` = '" . (int)$data['store_id'] . "', `route` = '" . $this->db->escape($data['route']) . "', `code` = '" . $this->db->escape($data['code']) . "', `status` = '" . (bool)$data['status'] . "', `date_added` = NOW() WHERE `theme_id` = '" . (int)$theme_id . "'");
	}

	/**
	 * Edit Status
	 *
	 * Edit category status record in the database.
	 *
	 * @param int  $category_id primary key of the category record
	 * @param bool $status
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('catalog/category');
	 *
	 * $this->model_catalog_category->editStatus($category_id, $status);
	 */
	public function editStatus(int $theme_id, bool $status): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "theme` SET `status` = '" . (bool)$status . "' WHERE `theme_id` = '" . (int)$theme_id . "'");
	}

	/**
	 * Delete Theme
	 *
	 * Delete theme record in the database.
	 *
	 * @param int $theme_id primary key of the theme record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('design/theme');
	 *
	 * $this->model_design_theme->deleteTheme($theme_id);
	 */
	public function deleteTheme(int $theme_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "theme` WHERE `theme_id` = '" . (int)$theme_id . "'");
	}

	/**
	 * Delete Themes By Store ID
	 *
	 * Delete themes by store record in the database.
	 *
	 * @param int $store_id primary key of the store record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('design/theme');
	 *
	 * $this->model_design_theme->deleteThemesByStoreId($store_id);
	 */
	public function deleteThemesByStoreId(int $store_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "theme` WHERE `store_id` = '" . (int)$store_id . "'");
	}

	/**
	 * Get Theme
	 *
	 * Get the record of the theme record in the database.
	 *
	 * @param int $theme_id primary key of the theme record
	 *
	 * @return array<string, mixed> theme record that has theme ID
	 *
	 * @example
	 *
	 * $this->load->model('design/theme');
	 *
	 * $theme_info = $this->model_design_theme->getTheme($theme_id);
	 */
	public function getTheme(int $theme_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "theme` WHERE `theme_id` = '" . (int)$theme_id . "'");

		return $query->row;
	}

	/**
	 * Get Themes
	 *
	 * Get the record of the theme records in the database.
	 *
	 * @param int $start
	 * @param int $limit
	 *
	 * @return array<int, array<string, mixed>> theme records
	 *
	 * @example
	 *
	 * $this->load->model('design/theme');
	 *
	 * $results = $this->model_design_theme->getThemes();
	 */
	public function getThemes(array $data = []): array {
		$sql = "SELECT *, (SELECT `name` FROM `" . DB_PREFIX . "store` `s` WHERE `s`.`store_id` = `t`.`store_id`) AS `store` FROM `" . DB_PREFIX . "theme` `t` ORDER BY `t`.`date_added`";
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	/**
	 * Get Total Themes
	 *
	 * Get the total number of theme records in the database.
	 *
	 * @return int total number of theme records
	 *
	 * @example
	 *
	 * $this->load->model('design/theme');
	 *
	 * $theme_total = $this->model_design_theme->getTotalThemes();
	 */
	public function getTotalThemes(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "theme`");

		return (int)$query->row['total'];
	}
}
