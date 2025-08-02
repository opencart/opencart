<?php
namespace Opencart\Admin\Controller\Task\Admin;
/**
 * Class Restore
 *
 * @package Opencart\Admin\Controller\Ssr
 */
class Restore extends \Opencart\System\Engine\Controller {
	/**
	 * Restore
	 *
	 * @return void
	 */
	public function index(array $args = []): array {
		$this->load->language('task/admin/restore');

		$required = [
			'filename',
			'table',
			'start',
			'limit',
			'total'
		];

		foreach ($required as $value) {
			if (!array_key_exists($value, $args)) {
				return ['error' => sprintf($this->language->get('error_required'), $value)];
			}
		}

		$file = DIR_STORAGE . 'backup/' . $args['filename'];

		if (!is_file($file)) {
			return ['error' => $this->language->get('error_file')];
		}

		// 5MB Reads at a time;
		$limit = 5 * (1024 * 1024);

		$size = filesize($file);

		for ($i = 0; $i <= ceil($size / $limit); $i++) {
			$task_data = [
				'code'   => 'backup',
				'action' => 'admin/restore.read',
				'args'   => [
					'filename' => $args['filename'],
					'position' => $i
				]
			];

			$this->model_setting_task->addTask($task_data);
		}

		return ['success' => $this->language->get('text_success')];
	}

	public function read(array $args = []): array {
		$this->load->language('task/admin/restore');

		if (isset($args['position'])) {
			$position = $args['position'];
		} else {
			$position = 0;
		}

		$file = DIR_STORAGE . 'backup/' . $args['filename'];

		if (!is_file($file)) {
			return ['error' => $this->language->get('error_file')];
		}

		$required = [
			'filename',
			'table',
			'start',
			'limit',
			'total'
		];

		foreach ($required as $value) {
			if (!array_key_exists($value, $args)) {
				return ['error' => sprintf($this->language->get('error_required'), $value)];
			}
		}




		// We set $i so we can batch execute the queries rather than do them all at once.
		$i = 0;

		$handle = fopen($file, 'r');

		fseek($handle, $position, SEEK_SET);

		while (!feof($handle) && ($i < 100)) {
			$position = ftell($handle);

			$line = fgets($handle, 4096);

			if ($i > 0 && (str_starts_with($line, 'TRUNCATE TABLE `' . DB_PREFIX . 'user`') || str_starts_with($line, 'TRUNCATE TABLE `' . DB_PREFIX . 'user_group`'))) {
				fseek($handle, $position, SEEK_SET);

				break;
			}

			if ((substr($line, 0, 14) == 'TRUNCATE TABLE' || substr($line, 0, 11) == 'INSERT INTO') && substr($line, -2) == ";\n") {
				$this->db->query(substr($line, 0, strlen($line) - 2));
			}

			$i++;
		}

		$position = ftell($handle);

		$size = filesize($file);

		if ($position) {
			$json['progress'] = round(($position / $size) * 100);
		} else {
			$json['progress'] = 0;
		}

		if ($position && !feof($handle)) {
			$json['text'] = sprintf($this->language->get('text_restore'), $position, $size);

			$json['next'] = $this->url->link('tool/backup.restore', 'user_token=' . $this->session->data['user_token'] . '&filename=' . urlencode($filename) . '&position=' . $position, true);


		} else {
			$json['success'] = $this->language->get('text_success');
		}

		fclose($handle);

		return ['success' => sprintf($this->language->get('text_restore'), $args['table'], $start ?: 1, $end, $record_total)];
	}
}