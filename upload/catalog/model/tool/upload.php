<?php
class ModelToolUpload extends Model {	
	public function addUpload($name, $filename) {
		$code = sha1(uniqid(mt_rand(), true));
		
		$this->db->query("INSERT INTO `" . DB_PREFIX . "upload` SET `name` = '" . $this->db->escape($name) . "', `filename` = '" . $this->db->escape($filename) . "', `code` = '" . $this->db->escape($code) . "', `date_added` = NOW()");
	
		return $code;
	}
<<<<<<< HEAD
=======
	
	public function getUploadById($upload_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "upload` WHERE upload_id = '" . (int)$upload_id . "'");
		
		return $query->row;
	}
>>>>>>> f08f21e6f15d396efa99777e6e011549fe247403
		
	public function getUploadByCode($code) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "upload` WHERE code = '" . $this->db->escape($code) . "'");
		
		return $query->row;
	}
}