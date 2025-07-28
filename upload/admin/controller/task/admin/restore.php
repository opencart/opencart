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
	public function index(): void {
		$this->load->language('tool/backup');

		$json = [];

		if (isset($this->request->get['filename'])) {
			$filename = basename(html_entity_decode($this->request->get['filename'], ENT_QUOTES, 'UTF-8'));
		} else {
			$filename = '';
		}

		if (isset($this->request->get['position'])) {
			$position = $this->request->get['position'];
		} else {
			$position = 0;
		}

		if (!$this->user->hasPermission('modify', 'tool/backup')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$file = DIR_STORAGE . 'backup/' . $filename;

		if (!is_file($file)) {
			$json['error'] = $this->language->get('error_file');
		}

		if (!$json) {
			// We set $i so we can batch execute the queries rather than do them all at once.
			$i = 0;

			$handle = fopen($file, 'r');

			fseek($handle, $position, SEEK_SET);

			while (!feof($handle) && ($i < 100)) {
				$position = ftell($handle);

				$line = fgets($handle, 1000000);

				if ($i > 0 && (substr($line, 0, strlen('TRUNCATE TABLE `' . DB_PREFIX . 'user`')) == 'TRUNCATE TABLE `' . DB_PREFIX . 'user`' || substr($line, 0, strlen('TRUNCATE TABLE `' . DB_PREFIX . 'user_group`')) == 'TRUNCATE TABLE `' . DB_PREFIX . 'user_group`')) {
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

				$this->cache->delete('*');
			}

			fclose($handle);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}