<?php 
class ModelExtensionModuleNewsletter Extends Model {
    public function addNewsletter($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "newsletter SET email = '" . $this->db->escape(utf8_strtolower($data['email'])) . "', ip = '" . $this->db->escape((string)$data['ip']) . "', country = '" . $this->db->escape($data['country']) . "', date_added = NOW()");
        
        $newsletter_id = $this->db->getLastId();
        
        return $newsletter_id;
	}
    
    public function getTotalNewsletterByEmail($email) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "newsletter WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

		return $query->row['total'];
    }
}