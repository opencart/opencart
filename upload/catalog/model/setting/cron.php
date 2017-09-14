<?php
class ModelSettingCron extends Model {
	public function getCrons() {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "cron` WHERE (cycle = 'hour' AND date_modified >= DATE_ADD(NOW(), INTERVAL 1 HOUR) OR (cycle = 'day' AND date_modified >= DATE_ADD(NOW(), INTERVAL 1 DAY) OR (cycle = 'month' AND date_modified >= DATE_ADD(NOW(), INTERVAL 1 MONTH)) ORDER BY `date_modified` ASC");

		return $query->rows;
	}

	public function editDateModified($cron_id, ) {
		$this->db->query("UPDATE * FROM `" . DB_PREFIX . "cron` WHERE (cycle = 'hour' AND date_modified >= DATE_ADD(NOW(), INTERVAL 1 HOUR) OR (cycle = 'day' AND date_modified >= DATE_ADD(NOW(), INTERVAL 1 DAY) OR (cycle = 'month' AND date_modified >= DATE_ADD(NOW(), INTERVAL 1 MONTH)) ORDER BY `date_modified` ASC");
	}
}