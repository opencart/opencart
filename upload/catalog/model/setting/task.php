<?php
namespace Opencart\Catalog\Model\Setting;
/**
 * Class Task
 *
 * Can be loaded using $this->load->model('setting/task');
 *
 * @package Opencart\Admin\Model\Setting
 */
class Task extends \Opencart\System\Engine\Model {
	/**
	 * Add Task
	 *
	 * Create a new task record in the database.
	 *
	 * @param array $data
	 *
	 * @return int
	 *
	 * @example
	 *
	 * $this->load->model('setting/task');
	 *
	 * $task_id = $this->model_setting_task->addTask($data);
	 */
	public function addTask(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "task` SET `code` = '" . $this->db->escape($data['code']) . "', `action` = '" . $this->db->escape($data['action']) . "', `args` = '" . $this->db->escape(!empty($data['args']) ? json_encode($data['args']) : '') . "', `status` = 'pending', `date_added` = NOW(), `date_modified` = NOW()");

		return $this->db->getLastId();
	}

	/**
	 * Get Total Task(s)
	 *
	 * Get the total number of total task records in the database.
	 *
	 * @return int total number of task records
	 *
	 * @example
	 *
	 * $filter_data = [
	 *     'sort'  => 'code',
	 *     'order' => 'DESC',
	 *     'start' => 0,
	 *     'limit' => 10
	 * ];
	 *
	 * $this->load->model('setting/task');
	 *
	 * $task_total = $this->model_setting_task->getTotalTasks($filter_data);
	 */
	public function getTotalTasks(array $data = []): int {
		$sql = "SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "task`";

		$implode = [];

		if (!empty($data['filter_code'])) {
			$implode[] = "LCASE(`code`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_code'])) . "'";
		}

		if (isset($data['filter_status']) && $data['filter_status'] !== '') {
			$implode[] = "`status` = '" . $this->db->escape($data['filter_status']) . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return (int)$query->row['total'];
	}
}
