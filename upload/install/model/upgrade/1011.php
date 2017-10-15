<?php
class ModelUpgrade1011 extends Model {
	public function upgrade() {
		$file = DIR_APPLICATION . '../install/db_schema.php';

		include($file);


	}
}