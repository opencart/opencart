<?php
class ModelSettingCron extends Model {
	public function getCronJobs() {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "cron` WHERE (cycle = 'hour' AND date_modified >= DATE_ADD(NOW(), INTERVAL 1 HOUR)) OR (cycle = 'day' AND date_modified >= DATE_ADD(NOW(), INTERVAL 1 DAY)) OR (cycle = 'month' AND date_modified >= DATE_ADD(NOW(), INTERVAL 1 MONTH)) ORDER BY `date_modified` ASC");

		foreach ($query->rows as $result) {
			$this->db->query("UPDATE `" . DB_PREFIX . "cron` SET `date_modified` = NOW() WHERE cron_id = '" . (int)$result['cron_id'] . "'");
		}

		return $query->rows;
	}
}