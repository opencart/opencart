<?php
class ModelUpgrade1009 extends Model {
	public function upgrade() {
		// Affiliate customer merge code
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "affiliate'");
		
		if ($query->num_rows) {
			// Removing affiliate and moving to the customer account.
			$config = new Config();
			
			$setting_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE store_id = '0'");
			
			foreach ($setting_query->rows as $setting) {
				$config->set($setting['key'], $setting['value']);
			}
			
			$affiliate_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "affiliate`");
			
			foreach ($affiliate_query->rows as $affiliate) {
				$customer_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer` WHERE `email` = '" . $this->db->escape($affiliate['email']) . "'");
				
				if (!$customer_query->num_rows) {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "customer` SET `customer_group_id` = '" . (int)$config->get('config_customer_group_id') . "', `language_id` = '" . (int)$config->get('config_customer_group_id') . "', `firstname` = '" . $this->db->escape($affiliate['firstname']) . "', `lastname` = '" . $this->db->escape($affiliate['lastname']) . "', `email` = '" . $this->db->escape($affiliate['email']) . "', `telephone` = '" . $this->db->escape($affiliate['telephone']) . "', `fax` = '" . $this->db->escape($affiliate['fax']) . "', `password` = '" . $this->db->escape($affiliate['password']) . "', `salt` = '" . $this->db->escape($affiliate['salt']) . "', `cart` = '" . $this->db->escape(json_encode(array())) . "', `wishlist` = '" . $this->db->escape(json_encode(array())) . "', `newsletter` = '0', `custom_field` = '" . $this->db->escape(json_encode(array())) . "', `ip` = '" . $this->db->escape($affiliate['ip']) . "', `status` = '" . $this->db->escape($affiliate['status']) . "', `approved` = '" . (int)$affiliatee['approved'] . "', `date_added` = '" . $this->db->escape($affiliate['date_added']) . "'");
					
					$customer_id = $this->db->getLastId();
					
					$this->db->query("INSERT INTO " . DB_PREFIX . "address SET customer_id = '" . (int)$customer_id . "', firstname = '" . $this->db->escape($affiliate['firstname']) . "', lastname = '" . $this->db->escape($affiliate['lastname']) . "', company = '" . $this->db->escape($affiliate['company']) . "', address_1 = '" . $this->db->escape($affiliate['address_1']) . "', address_2 = '" . $this->db->escape($affiliate['address_2']) . "', city = '" . $this->db->escape($affiliate['city']) . "', postcode = '" . $this->db->escape($affiliate['postcode']) . "', zone_id = '" . (int)$affiliate['zone_id'] . "', country_id = '" . (int)$affiliate['country_id'] . "', custom_field = '" . $this->db->escape(json_encode(array())) . "'");
			
					$address_id = $this->db->getLastId();
			
					$this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$customer_id . "'");
				} else {
					$customer_id = $customer_query->row['customer_id'];
				}
				
				$customer_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_affiliate` WHERE `customer_id` = '" . (int)$customer_id . "'");
				
				if (!$customer_query->num_rows) {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_affiliate` SET `customer_id` = '" . (int)$customer_id . "', `company` = '" . $this->db->escape($affiliate['company']) . "', `tracking` = '" . $this->db->escape($affiliate['code']) . "', `commission` = '" . (float)$affiliate['commission'] . "', `tax` = '" . $this->db->escape($affiliate['tax']) . "', `payment` = '" . $this->db->escape($affiliate['payment']) . "', `cheque` = '" . $this->db->escape($affiliate['cheque']) . "', `paypal` = '" . $this->db->escape($affiliate['paypal']) . "', `bank_name` = '" . $this->db->escape($affiliate['bank_name']) . "', `bank_branch_number` = '" . $this->db->escape($affiliate['bank_branch_number']) . "', `bank_account_name` = '" . $this->db->escape($affiliate['bank_account_name']) . "', `bank_account_number` = '" . $this->db->escape($affiliate['bank_account_number']) . "', `status` = '" . (int)$affiliate['status'] . "', `date_added` = '" . $this->db->escape($affiliate['date_added']) . "'");
				}
				
				$affiliate_transaction_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "affiliate_transaction` WHERE `affiliate_id` = '" . (int)$affiliate['affiliate_id'] . "'");
			
				foreach ($affiliate_transaction_query->rows as $affiliate_transaction) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "customer_transaction SET customer_id = '" . (int)$customer_id . "', order_id = '" . (int)$affiliate_transaction['order_id'] . "', description = '" . $this->db->escape($affiliate_transaction['description']) . "', amount = '" . (float)$affiliate_transaction['amount'] . "', `date_added` = '" . $this->db->escape($affiliate_transaction['date_added']) . "'");
					
					$this->db->query("DELETE FROM " . DB_PREFIX . "affiliate_transaction WHERE affiliate_transaction_id = '" . (int)$affiliate_transaction['affiliate_transaction_id'] . "'");
				}
			}
			
			$this->db->query("DROP TABLE `" . DB_PREFIX . "affiliate`");
			$this->db->query("DROP TABLE `" . DB_PREFIX . "affiliate_activity`");
			$this->db->query("DROP TABLE `" . DB_PREFIX . "affiliate_login`");
			$this->db->query("DROP TABLE `" . DB_PREFIX . "affiliate_transaction`");
		}
	}
}
