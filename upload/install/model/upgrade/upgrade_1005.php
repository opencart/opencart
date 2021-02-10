<?php
namespace Opencart\Application\Model\Upgrade;
class Upgrade1005 extends \Opencart\System\Engine\Model {
	public function upgrade() {
		// customer
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "customer` CHANGE `token` `token` text NOT NULL");

		// custom_field
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "custom_field' AND COLUMN_NAME = 'validation'");

		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "custom_field` ADD `validation` varchar(255) NOT NULL AFTER `value`");
		}

		// custom_field
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "custom_field' AND COLUMN_NAME = 'required'");

		if ($query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "custom_field` DROP `required`");
		}

		// custom_field
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "custom_field' AND COLUMN_NAME = 'position'");

		if ($query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "custom_field` DROP `position`");
		}



		// order_custom_field
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "order_field'");

		if ($query->num_rows) {
			$order_field_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_field`");

			foreach ($order_field_query->rows as $result) {
				$order_custom_field_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_custom_field` WHERE `order_id` = '" . (int)$result['order_id'] . "' AND `custom_field_id` = '" . (int)$result['custom_field_id'] . "' AND `custom_field_value_id` = '" . (int)$result['custom_field_value_id'] . "' AND `name` = '" . $this->db->escape($result['name']) . "' AND `value` = '" . $this->db->escape($result['value']) . "'");

				if (!$order_custom_field_query->num_rows) {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "order_custom_field` SET `order_id` = '" . (int)$result['order_id'] . "', `custom_field_id` = '" . (int)$result['custom_field_id'] . "', `custom_field_value_id` = '" . (int)$result['custom_field_value_id'] . "', `name` = '" . $this->db->escape($result['name']) . "', `value` = '" . $this->db->escape($result['value']) . "'");
				}
			}

			$this->db->query("DROP TABLE `" . DB_PREFIX . "order_field`");
		}






		// order_recurring
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "order_recurring' AND COLUMN_NAME = 'created'");

		if ($query->num_rows) {
			$this->db->query("UPDATE `" . DB_PREFIX . "order_recurring` SET `date_added` = `created` WHERE `date_added` IS NULL or `date_added` = ''");
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "order_recurring` DROP `created`");
		}

		// order_recurring
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "order_recurring' AND COLUMN_NAME = 'profile_id'");

		if ($query->num_rows) {
			$this->db->query("UPDATE `" . DB_PREFIX . "order_recurring` SET `recurring_id` = `profile_id` WHERE `recurring_id` IS NULL OR `recurring_id` = ''");
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "order_recurring` DROP `profile_id`");
		}

		// order_recurring
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "order_recurring' AND COLUMN_NAME = 'profile_name'");

		if ($query->num_rows) {
			$this->db->query("UPDATE `" . DB_PREFIX . "order_recurring` SET `recurring_name` = `profile_name` WHERE `recurring_name` IS NULL or `recurring_name` = ''");
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "order_recurring` DROP `profile_name`");
		}

		// order_recurring
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "order_recurring' AND COLUMN_NAME = 'profile_description'");

		if ($query->num_rows) {
			$this->db->query("UPDATE `" . DB_PREFIX . "order_recurring` SET `recurring_description` = `profile_description` WHERE `recurring_description` IS NULL OR `recurring_description` = ''");
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "order_recurring` DROP `profile_description`");
		}


		// order_recurring_transaction
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "order_recurring_transaction' AND COLUMN_NAME = 'created'");

		if ($query->num_rows) {
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "order_recurring_transaction' AND COLUMN_NAME = 'date_added'");

			if ($query->num_rows) {
				$this->db->query("UPDATE `" . DB_PREFIX . "order_recurring_transaction` SET `date_added` = `created` WHERE `date_added` IS NULL or `date_added` = ''");
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "order_recurring_transaction` DROP `created`");
			} else {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "order_recurring_transaction` CHANGE `created` `date_added` datetime NOT NULL AFTER `amount`");
			}
		}

		// order_recurring_transaction
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "order_recurring_transaction' AND COLUMN_NAME = 'reference'");

		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "order_recurring_transaction` ADD `reference` varchar(255) NOT NULL AFTER `order_recurring_id`");
		}

		// user
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "user' AND COLUMN_NAME = 'image'");

		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "user` ADD `image` varchar(255) NOT NULL AFTER `email`");
		}
	}
}
