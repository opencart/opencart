<?php
namespace Opencart\Install\Controller\Upgrade;
/**
 * Class Upgrade3
 *
 * @package Opencart\Install\Controller\Upgrade
 */
class Upgrade3 extends \Opencart\System\Engine\Controller {
	/**
	 * @return void
	 */
	public function index(): void {
		$this->load->language('upgrade/upgrade');

		$json = [];

		// It makes mass changes to the DB by creating tables that are not in the current db, changes the charset and DB engine to the SQL schema.
		try {
			// Structure
			$this->load->helper('db_schema');

			$tables = oc_db_schema();

			// Clear any old db foreign key constraints
			/*
			foreach ($tables as $table) {
				$foreign_query = $this->db->query("SELECT * FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . $table['name'] . "' AND CONSTRAINT_TYPE = 'FOREIGN KEY'");

				foreach ($foreign_query->rows as $foreign) {
					$this->db->query("ALTER TABLE `" . DB_PREFIX . $table['name'] . "` DROP FOREIGN KEY `" . $foreign['CONSTRAINT_NAME'] . "`");
				}
			}
			*/

			$this->load->model('install/install');
			$this->model_install_install->upgradeDatabaseSchema($this->db, $tables, DB_DATABASE, DB_PREFIX);

			/*
			// Setup foreign keys
			foreach ($tables as $table) {
				if (isset($table['foreign'])) {
					foreach ($table['foreign'] as $foreign) {
						//$this->db->query("ALTER TABLE `" . DB_PREFIX . $table['name'] . "` ADD FOREIGN KEY (`" . $foreign['key'] . "`) REFERENCES `" . DB_PREFIX . $foreign['table'] . "` (`" . $foreign['field'] . "`)");
					}
				}
			}
			*/
		} catch (\ErrorException $exception) {
			$json['error'] = sprintf($this->language->get('error_exception'), $exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
		}

		if (!$json) {
			$json['text'] = sprintf($this->language->get('text_progress'), 3, 3, 9);

			$url = '';

			if (isset($this->request->get['version'])) {
				$url .= '&version=' . $this->request->get['version'];
			}

			if (isset($this->request->get['admin'])) {
				$url .= '&admin=' . $this->request->get['admin'];
			}

			$json['next'] = $this->url->link('upgrade/upgrade_4', $url, true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
