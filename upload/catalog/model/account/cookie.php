<?php
namespace Opencart\Application\Model\Account;
class Cookie extends \Opencart\System\Engine\Model {
	public function addCookie($ip, $language, $status) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "cookie` SET `ip` = '" . $this->db->escape($ip) . "', `language` = '" . $this->db->escape($language) . "', `status` = '" . (int)$status . "', `date_added` = NOW()");
	}
}
