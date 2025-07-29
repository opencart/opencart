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
}
