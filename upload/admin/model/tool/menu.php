<?php
namespace Opencart\Admin\Model\Tool;
/**
 * Class Menu
 *
 * Can be loaded using $this->load->model('tool/menu');
 *
 * @package Opencart\Admin\Model\Tool
 */
class Menu extends \Opencart\System\Engine\Model {
	/**
	 * Add Menu
	 *
	 * Create a new menu record in the database.
	 *
	 * @param string $code
	 * @param string $route
	 * @param bool   $status
	 *
	 * @return int
	 *
	 * @example
	 *
	 * $this->load->model('tool/menu');
	 *
	 * $menu_id = $this->model_tool_menu->addMenu($code, $description, $cycle, $action, $status);
	 */
	public function addMenu(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "menu` SET `code` = '" . $this->db->escape($data['code']) . "', `type` = '" . $this->db->escape($data['type']) . "', `route` = '" . $this->db->escape($data['route']) . "', `path` = '" . $this->db->escape($data['path']) . "', sort_order = '" . (int)$data['sort_order'] . "'");

		$menu_id = $this->db->getLastId();

		$this->db->query("UPDATE `" . DB_PREFIX . "menu` SET `path` = '" . $this->db->escape($data['path'] . '_' . $menu_id) . "' WHERE `menu_id` = '" . (int)$menu_id . "'");

		foreach ($data['menu_description'] as $language_id => $menu_description) {
			$this->model_tool_menu->addDescription($menu_id, $language_id, $menu_description);
		}

		return $menu_id;
	}

	public function editMenu(int $menu_id, array $data): void {
		$menu_info = $this->getMenu($menu_id);

		if ($menu_info) {
			$this->db->query("UPDATE `" . DB_PREFIX . "menu` SET `code` = '" . $this->db->escape($data['code']) . "', `type` = '" . $this->db->escape($data['type']) . "', `route` = '" . $this->db->escape($data['route']) . "', `path` = '" . $this->db->escape($data['path']) . "', sort_order = '" . (int)$data['sort_order'] . "' WHERE `menu_id` = '" . (int)$menu_id . "'");

			$this->db->query("UPDATE `" . DB_PREFIX . "menu` SET `path` = REPLACE(`path`, '" . $this->db->escape($menu_info['path']) . "_', '" . $this->db->escape($data['path']) . "_') WHERE `path` LIKE '" . $this->db->escape($menu_info['path']) . "_%'");

			$this->model_tool_menu->deleteDescriptions($menu_id);

			foreach ($data['menu_description'] as $language_id => $menu_description) {
				$this->model_tool_menu->addDescription($menu_id, $language_id, $menu_description);
			}
		}
	}

	/**
	 * Delete Menu By Code
	 *
	 * @param string $code
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('tool/menu');
	 *
	 * $this->model_tool_menu->deleteMenuByCode($code);
	 */
	public function deleteMenu(string $menu_id): void {
		$menu_info = $this->getMenu($menu_id);

		if ($menu_info) {
			$this->db->query("DELETE FROM `" . DB_PREFIX . "menu` WHERE `menu_id` = '" . (int)$menu_id . "'");

			$this->db->query("UPDATE `" . DB_PREFIX . "menu` SET `path` = REPLACE(`path`, '" . $this->db->escape($menu_info['path']) . "', '" . $this->db->escape(substr($menu_info['path'], 0, strrpos($menu_info['path'], '_'))) . "') WHERE `path` LIKE '" . $this->db->escape($menu_info['path']) . "_%'");

			$this->model_tool_menu->deleteDescriptions($menu_id);
		}
	}

	/**
	 * Delete Menu By Code
	 *
	 * @param string $code
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('tool/menu');
	 *
	 * $this->model_tool_menu->deleteMenuByCode($code);
	 */
	public function deleteMenuByCode(string $code): void {
		$results = $this->getMenuByCode($code);

		foreach ($results as $result) {
			$this->model_tool_menu->deleteMenu($result['menu_id']);
		}
	}

	/**
	 * Get Menu
	 *
	 * Get the record of the menu record in the database.
	 *
	 * @param int $menu_id primary key of the menu record
	 *
	 * @return array<string, mixed> menu record that has menu ID
	 *
	 * @example
	 *
	 * $this->load->model('tool/menu');
	 *
	 * $menu_info = $this->model_tool_menu->getMenu($menu_id);
	 */
	public function getMenu(int $menu_id): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "menu` `m` LEFT JOIN `" . DB_PREFIX . "menu_description` `md` ON (`m`.`menu_id` = `md`.`menu_id`) WHERE `m`.`menu_id` = '" . (int)$menu_id . "' AND `md`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	/**
	 * Get Menu(s)
	 *
	 * Get the record of the menu records in the database.
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>> menu records
	 *
	 * @example
	 *
	 * $this->load->model('tool/menu');
	 *
	 * $results = $this->model_tool_menu->getMenus();
	 */
	public function getMenus($path = ''): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "menu` `m` LEFT JOIN `" . DB_PREFIX . "menu_description` `md` ON (`m`.`menu_id` = `md`.`menu_id`)";

		if ($path) {
			$sql .= " WHERE `m`.`path` LIKE '" . $this->db->escape($path) . "'";
		}

		$sql .= " ORDER BY `m`.`path` ASC, `m`.`sort_order` ASC";

		$query = $this->db->query($sql);

		return $query->rows;
	}

	/**
	 * Get Total Menu(s)
	 *
	 * Get the total number of total menu records in the database.
	 *
	 * @return int total number of menu records
	 *
	 * @example
	 *
	 * $this->load->model('tool/menu');
	 *
	 * $menu_total = $this->model_tool_menu->getTotalMenus();
	 */
	public function getTotalMenus(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "menu`");

		return (int)$query->row['total'];
	}

	/**
	 * Add Description
	 *
	 * Create a new menu description record in the database.
	 *
	 * @param int                  $menu_id primary key of the menu record
	 * @param int                  $language_id  primary key of the language record
	 * @param array<string, mixed> $data         array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $menu_data['menu_description'] = [
	 *     'name' => 'Attribute'
	 * ];
	 *
	 * $this->load->model('tool/menu');
	 *
	 * $this->model_tool_menu->addDescription($menu_id, $language_id, $menu_data);
	 */
	public function addDescription(int $menu_id, int $language_id, array $data): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "menu_description` SET `menu_id` = '" . (int)$menu_id . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($data['name']) . "'");
	}

	/**
	 * Delete Descriptions
	 *
	 * Delete menu description records in the database.
	 *
	 * @param int $menu_id primary key of the menu record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('tool/menu');
	 *
	 * $this->model_tool_menu->deleteDescriptions($menu_id);
	 */
	public function deleteDescriptions(int $menu_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "menu_description` WHERE `menu_id` = '" . (int)$menu_id . "'");
	}

	/**
	 * Delete Descriptions By Language ID
	 *
	 * Delete menu description records in the database.
	 *
	 * @param int $language_id primary key of the language record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('tool/menu');
	 *
	 * $this->model_tool_menu->deleteDescriptionsByLanguageId($language_id);
	 */
	public function deleteDescriptionsByLanguageId(int $language_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "menu_description` WHERE `language_id` = '" . (int)$language_id . "'");
	}

	/**
	 * Get Descriptions
	 *
	 * Get the record of the menu record in the database.
	 *
	 * @param int $menu_id primary key of the menu record
	 *
	 * @return array<int, array<string, string>> description records that have menu ID
	 *
	 * @example
	 *
	 * $this->load->model('tool/menu');
	 *
	 * $menu_description = $this->model_tool_menu->getDescriptions($menu_id);
	 */
	public function getDescriptions(int $menu_id): array {
		$menu_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "menu_description` WHERE `menu_id` = '" . (int)$menu_id . "'");

		foreach ($query->rows as $result) {
			$menu_data[$result['language_id']] = $result;
		}

		return $menu_data;
	}

	/**
	 * Get Descriptions By Language ID
	 *
	 * Get the record of the menu descriptions by language records in the database.
	 *
	 * @param int $language_id primary key of the language record
	 *
	 * @return array<int, array<string, string>> description records that have language ID
	 *
	 * @example
	 *
	 * $this->load->model('tool/menu');
	 *
	 * $results = $this->model_tool_menu->getDescriptionsByLanguageId($language_id);
	 */
	public function getDescriptionsByLanguageId(int $language_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "menu_description` WHERE `language_id` = '" . (int)$language_id . "'");

		return $query->rows;
	}
}
