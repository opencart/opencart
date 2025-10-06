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
	 * Edit Status
	 *
	 * Edit task status record in the database.
	 *
	 * @param int    $task_id primary key of the task record
	 * @param string $status
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('setting/task');
	 *
	 * $this->model_setting_task->editStatus($task_id, $status);
	 */
	public function editStatus(int $task_id, string $status, array $response = []): void {
		$allowed = [
			'pending',
			'processing',
			'paused',
			'complete',
			'failed'
		];

		if (!in_array($status, $allowed)) {
			$status = 'failed';
		}

		$this->db->query("UPDATE `" . DB_PREFIX . "task` SET `response` = '" . $this->db->escape(json_encode($response)) . "', `status` = '" . $this->db->escape($status) . "', `date_modified` = NOW() WHERE `task_id` = '" . (int)$task_id . "'");
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
	 * Clear Task
	 *
	 * @return void
	 */
	public function clear(): void {
		$this->db->query("TRUNCATE TABLE `" . DB_PREFIX . "task`");
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
	 *     'start' => 0,
	 *     'limit' => 10
	 * ];
	 *
	 * $this->load->model('setting/task');
	 *
	 * $results = $this->model_setting_task->getTasks($filter_data);
	 */
	public function getTasks(array $data = []): array {
		$task_data = [];

		$sql = "SELECT * FROM `" . DB_PREFIX . "task`";

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

		$sql .= " ORDER BY `task_id` ASC";

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

		foreach ($query->rows as $result) {
			$task_data[] = [
				'args'     => $result['args'] ? json_decode($result['args'], true) : [],
				'response' => $result['response'] ? json_decode($result['response'], true) : [],
			] + $result;
		}

		return $task_data;
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
