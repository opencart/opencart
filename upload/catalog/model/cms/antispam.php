<?php
namespace Opencart\Catalog\Model\Cms;
/**
 * Class Anti-Spam
 *
 * @package Opencart\Catalog\Model\Cms
 */
class Antispam extends \Opencart\System\Engine\Model {
	/**
	 * @param string $comment
	 *
	 * @return int
	 */
	public function getSpam(string $comment): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "antispam` WHERE '" . $this->db->escape(str_replace(' ', '', $comment)) . "' LIKE CONCAT('%', `keyword`, '%')");

		return (int)$query->row['total'];
	}
}
