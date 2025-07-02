<?php
namespace Opencart\Catalog\Model\Account;
/**
 * Class Gdpr
 *
 * Can be called using $this->load->model('account/gdpr');
 *
 * @package Opencart\Catalog\Model\Account
 */
class Gdpr extends \Opencart\System\Engine\Model {
	/**
	 * Add Gdpr
	 *
	 * Create a new gdpr record in the database.
	 *
	 * @param string $code
	 * @param string $email
	 * @param string $action
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('account/gdpr');
	 *
	 * $this->model_account_gdpr->addGdpr($code, $email, $action);
	 */
	public function addGdpr(string $code, string $email, string $action): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "gdpr` SET `store_id` = '" . $this->db->escape($this->config->get('config_store_id')) . "', `language_id` = '" . $this->db->escape($this->config->get('config_language_id')) . "', `code` = '" . $this->db->escape($code) . "', `email` = '" . $this->db->escape($email) . "', `action` = '" . $this->db->escape($action) . "', `date_added` = NOW()");
	}

	/**
	 * Edit Status
	 *
	 * Edit gdpr status record in the database.
	 *
	 * @param int  $gdpr_id primary key of the gdpr record
	 * @param int  $status  status code (0=unverified, 1=pending, 2=processing, 3=complete/delete, -1=denied)
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('account/gdpr');
	 *
	 * $this->model_account_gdpr->editStatus($gdpr_id, $status);
	 */
	public function editStatus(int $gdpr_id, int $status): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "gdpr` SET `status` = '" . (int)$status . "' WHERE `gdpr_id` = '" . (int)$gdpr_id . "'");
	}

	/**
	 * Get Gdpr
	 *
	 * Get the record of the zone record in the database.
	 *
	 * @param int $gdpr_id primary key of the gdpr record
	 *
	 * @return array<string, mixed> gdpr record that has gdpr ID
	 *
	 * $this->load->model('account/gdpr');
	 *
	 * $gdpr_info = $this->model_account_gdpr->getGdpr($gdpr_id);
	 */
	public function getGdpr(int $gdpr_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "gdpr` WHERE `gdpr_id` = '" . (int)$gdpr_id . "'");

		return $query->row;
	}

	/**
	 * Get Gdpr By Code
	 *
	 * @param string $code
	 *
	 * @return array<string, mixed>
	 *
	 * @example
	 *
	 * $this->load->model('account/gdpr');
	 *
	 * $gdpr_info = $this->model_account_gdpr->getGdprByCode($code);
	 */
	public function getGdprByCode(string $code): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "gdpr` WHERE `code` = '" . $this->db->escape($code) . "'");

		return $query->row;
	}

	/**
	 * Get Gdpr(s) By Email
	 *
	 * @param string $email
	 *
	 * @return array<int, array<string, mixed>>
	 *
	 * @example
	 *
	 * $this->load->model('account/gdpr');
	 *
	 * $results = $this->model_account_customer->getGdprsByEmail($email);
	 */
	public function getGdprsByEmail(string $email): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "gdpr` WHERE `email` = '" . $this->db->escape($email) . "'");

		return $query->rows;
	}

	/**
	 * Get Expires
	 *
	 * @return array<int, array<string, mixed>> expire records
	 *
	 * @example
	 *
	 * $this->load->model('account/gdpr');
	 *
	 * $results = $this->model_account_customer->getExpires();
	 */
	public function getExpires(): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "gdpr` WHERE `status` = '2' AND DATE(`date_added`) <= DATE('" . $this->db->escape(date('Y-m-d', strtotime('+' . (int)$this->config->get('config_gdpr_limit') . ' days'))) . "') ORDER BY `date_added` DESC");

		return $query->rows;
	}
}
