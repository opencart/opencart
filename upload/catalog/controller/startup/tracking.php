<?php
class ControllerStartupTracking extends Controller {
	public function index() {
		// Tracking Code
		if (isset($this->request->get['tracking'])) {
			setcookie('tracking', $request->get['tracking'], time() + 3600 * 24 * 1000, '/');
		
			$this->db->query("UPDATE `" . DB_PREFIX . "marketing` SET clicks = (clicks + 1) WHERE code = '" . $this->db->escape($this->request->get['tracking']) . "'");
		}
	}
}