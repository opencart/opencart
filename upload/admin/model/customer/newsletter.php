<?php
class ModelCustomerNewsletter extends Model {
	public function addNewsletter($data) {				
		$this->db->query("INSERT INTO " . DB_PREFIX . "newsletter SET email = '" . $this->db->escape((string)$data['email']) . "', date_added = NOW()");

		$newsletter_id = $this->db->getLastId();

		return $newsletter_id;
	}

	public function editNewsletter($newsletter_id, $data) {		
		$this->db->query("UPDATE " . DB_PREFIX . "newsletter SET email = '" . $this->db->escape((string)$data['email']) . "' WHERE newsletter_id = '" . (int)$newsletter_id . "'");
	}

	public function deleteNewsletter($newsletter_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "newsletter WHERE newsletter_id = '" . (int)$newsletter_id . "'");
	}

	public function getNewsletter($newsletter_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "newsletter WHERE newsletter_id = '" . (int)$newsletter_id . "'");

		return $query->row;
	}

	public function getNewsletterByEmail($email) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "newsletter WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

		return $query->row;
	}
	
	public function getNewsletters($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "newsletter";

		$implode = array();

		if (!empty($data['filter_email'])) {
			$implode[] = " email LIKE '" . $this->db->escape((string)$data['filter_email']) . "%'";
		}

		if (!empty($data['filter_country'])) {
			$implode[] = " country = '" . $this->db->escape((string)$data['filter_country']) . "'";
		}

		if (!empty($data['filter_ip'])) {
			$implode[] = " ip = '" . $this->db->escape((string)$data['filter_ip']) . "'";
		}

		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(date_added) = DATE('" . $this->db->escape((string)$data['filter_date_added']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'email',
			'ip',
			'date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY email";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTotalNewsletters($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "newsletter";

		$implode = array();

		if (!empty($data['filter_email'])) {
			$implode[] = " email LIKE '" . $this->db->escape((string)$data['filter_email']) . "%'";
		}

		if (!empty($data['filter_country'])) {
			$implode[] = " country = '" . $this->db->escape((string)$data['filter_country']) . "'";
		}

		if (!empty($data['filter_ip'])) {
			$implode[] = " ip = '" . $this->db->escape((string)$data['filter_ip']) . "'";
		}

		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(date_added) = DATE('" . $this->db->escape((string)$data['filter_date_added']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
}
