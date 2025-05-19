<?php
namespace Opencart\Catalog\Model\Cms;
/**
 * Class Anti-Spam
 *
 * Can be called using $this->load->model('cms/antispam');
 *
 * @package Opencart\Catalog\Model\Cms
 */
class Antispam extends \Opencart\System\Engine\Model {
	/**
	 * Get Spam
	 *
	 * Get the record of the antispam record in the database.
	 *
	 * @param string $comment
	 *
	 * @return int
	 *
	 * @example
	 *
	 * $this->load->model('cms/antispam');
	 *
	 * $spam_total = $this->model_cms_antispam->getSpam($comment);
	 */
	public function getSpam(string $comment): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "antispam` WHERE '" . $this->db->escape(str_replace(' ', '', $comment)) . "' LIKE CONCAT('%', `keyword`, '%')");

		return (int)$query->row['total'];
	}
}
