<?php
namespace Opencart\Admin\Model\Setting;
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
	 * @param string $code
	 * @param string $action
	 * @param bool   $status
	 *
	 * @return int
	 *
	 * @example
	 *
	 * $this->load->model('setting/task');
	 *
	 * $task_id = $this->model_setting_task->addTask($code, $description, $cycle, $action, $status);
	 */
	public function addTask(string $code, string $description, string $cycle, string $action, bool $status): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "task` SET `code` = '" . $this->db->escape($code) . "', `action` = '" . $this->db->escape($action) . "', `status` = '" . (int)$status . "', `date_added` = NOW()");

		return $this->db->getLastId();
	}

	/**
	 * Delete Task
	 *
	 * Delete task record in the database.
	 *
	 * @param int $task_id primary key of the task record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('setting/task');
	 *
	 * $this->model_setting_task->deleteTask($task_id);
	 */
	public function deleteTask(int $task_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "task` WHERE `task_id` = '" . (int)$task_id . "'");
	}

	/**
	 * Delete Task By Code
	 *
	 * @param string $code
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('setting/task');
	 *
	 * $this->model_setting_task->deleteTaskByCode($code);
	 */
	public function deleteTaskByCode(string $code): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "task` WHERE `code` = '" . $this->db->escape($code) . "'");
	}

	/**
	 * Edit Task
	 *
	 * Edit task record in the database.
	 *
	 * @param int $task_id primary key of the task record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('setting/task');
	 *
	 * $this->model_setting_task->editTask($task_id);
	 */
	public function editTask(int $task_id): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "task` SET `date_modified` = NOW() WHERE `task_id` = '" . (int)$task_id . "'");
	}

	/**
	 * Edit Status
	 *
	 * Edit task status record in the database.
	 *
	 * @param int  $task_id primary key of the task record
	 * @param bool $status
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('setting/task');
	 *
	 * $this->model_setting_task->editStatus($task_id, $status);
	 */
	public function editStatus(int $task_id, bool $status): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "task` SET `status` = '" . (bool)$status . "' WHERE `task_id` = '" . (int)$task_id . "'");
	}

	/**
	 * Get Task
	 *
	 * Get the record of the task record in the database.
	 *
	 * @param int $task_id primary key of the task record
	 *
	 * @return array<string, mixed> task record that has task ID
	 *
	 * @example
	 *
	 * $this->load->model('setting/task');
	 *
	 * $task_info = $this->model_setting_task->getTask($task_id);
	 */
	public function getTask(int $task_id): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "task` WHERE `task_id` = '" . (int)$task_id . "'");

		return $query->row;
	}

	/**
	 * Get Task By Code
	 *
	 * @param string $code
	 *
	 * @return array<string, mixed>
	 *
	 * @example
	 *
	 * $this->load->model('setting/task');
	 *
	 * $task_info = $this->model_setting_task->getTaskByCode($code);
	 */
	public function getTaskByCode(string $code): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "task` WHERE `code` = '" . $this->db->escape($code) . "' LIMIT 1");

		return $query->row;
	}

	/**
	 * Get Task(s)
	 *
	 * Get the record of the task records in the database.
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>> task records
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
	 * $results = $this->model_setting_task->getTasks($filter_data);
	 */
	public function getTasks(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "task`";

		$sort_data = [
			'code',
			'action',
			'status',
			'date_added'
		];

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY `date_added`";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

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
	 * Get Total Task(s)
	 *
	 * Get the total number of total task records in the database.
	 *
	 * @return int total number of task records
	 *
	 * @example
	 *
	 * $this->load->model('setting/task');
	 *
	 * $task_total = $this->model_setting_task->getTotalTasks();
	 */
	public function getTotalTasks(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "task`");

		return (int)$query->row['total'];
	}
}
