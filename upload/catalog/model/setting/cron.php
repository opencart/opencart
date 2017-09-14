<?php
class ModelSettingCron extends Model {
	public function getCrons() {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "cron` WHERE cycle = 'hour' AND date_modified < NOW() ORDER BY `date_added` ASC");

		return $query->rows;
	}
}