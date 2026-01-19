<?php
namespace Opencart\Admin\Model\Design;
/**
 * Class Template
 *
 * Can be loaded using $this->load->model('design/template');
 *
 * @package Opencart\Admin\Model\Design
 */
class Template extends \Opencart\System\Engine\Model {
	/**
	 * Add Template
	 *
	 * Create a new template record in the database.
	 *
	 * @param array<string, mixed> $data array of data
	 *
	 * @return int
	 *
	 * @example
	 *
	 * $template_data = [
	 *     'route'  => '',
	 *     'code'   => '',
	 *     'status' => 0
	 * ];
	 *
	 * $this->load->model('design/template');
	 *
	 * $template_id = $this->model_design_template->addTemplate($template_data);
	 */
	public function addTemplate(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "template` SET `store_id` = '" . (int)$data['store_id'] . "', `route` = '" . $this->db->escape($data['route']) . "', `code` = '" . $this->db->escape($data['code']) . "', `status` = '" . (bool)$data['status'] . "', `date_added` = NOW()");

		return $this->db->getLastId();
	}

	/**
	 * Edit Template
	 *
	 * Edit template record in the database.
	 *
	 * @param int                  $template_id primary key of the template record
	 * @param array<string, mixed> $data     array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $template_data = [
	 *     'route'  => '',
	 *     'code'   => '',
	 *     'status' => 1
	 * ];
	 *
	 * $this->load->model('design/template');
	 *
	 * $this->model_design_template->editTemplate($template_id, $template_data);
	 */
	public function editTemplate(int $template_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "template` SET `store_id` = '" . (int)$data['store_id'] . "', `route` = '" . $this->db->escape($data['route']) . "', `code` = '" . $this->db->escape($data['code']) . "', `status` = '" . (bool)$data['status'] . "', `date_added` = NOW() WHERE `template_id` = '" . (int)$template_id . "'");
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
	public function editStatus(int $template_id, bool $status): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "template` SET `status` = '" . (bool)$status . "' WHERE `template_id` = '" . (int)$template_id . "'");
	}

	/**
	 * Delete Template
	 *
	 * Delete template record in the database.
	 *
	 * @param int $template_id primary key of the template record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('design/template');
	 *
	 * $this->model_design_template->deleteTemplate($template_id);
	 */
	public function deleteTemplate(int $template_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "template` WHERE `template_id` = '" . (int)$template_id . "'");
	}

	/**
	 * Delete Templates By Store ID
	 *
	 * Delete templates by store record in the database.
	 *
	 * @param int $store_id primary key of the store record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('design/template');
	 *
	 * $this->model_design_template->deleteTemplatesByStoreId($store_id);
	 */
	public function deleteTemplatesByStoreId(int $store_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "template` WHERE `store_id` = '" . (int)$store_id . "'");
	}

	/**
	 * Get Template
	 *
	 * Get the record of the template record in the database.
	 *
	 * @param int $template_id primary key of the template record
	 *
	 * @return array<string, mixed> template record that has template ID
	 *
	 * @example
	 *
	 * $this->load->model('design/template');
	 *
	 * $template_info = $this->model_design_template->getTemplate($template_id);
	 */
	public function getTemplate(int $template_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "template` WHERE `template_id` = '" . (int)$template_id . "'");

		return $query->row;
	}

	/**
	 * Get Templates
	 *
	 * Get the record of the template records in the database.
	 *
	 * @param int $start
	 * @param int $limit
	 *
	 * @return array<int, array<string, mixed>> template records
	 *
	 * @example
	 *
	 * $this->load->model('design/template');
	 *
	 * $results = $this->model_design_template->getTemplates();
	 */
	public function getTemplates(array $data = []): array {
		$sql = "SELECT *, (SELECT `name` FROM `" . DB_PREFIX . "store` `s` WHERE `s`.`store_id` = `t`.`store_id`) AS `store` FROM `" . DB_PREFIX . "template` `t` ORDER BY `t`.`date_added`";
		
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
	 * Get Total Templates
	 *
	 * Get the total number of template records in the database.
	 *
	 * @return int total number of template records
	 *
	 * @example
	 *
	 * $this->load->model('design/template');
	 *
	 * $template_total = $this->model_design_template->getTotalTemplates();
	 */
	public function getTotalTemplates(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "template`");

		return (int)$query->row['total'];
	}
}
