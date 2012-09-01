<?php
class ControllerCommonSSL extends Controller {
	// The purpurse of this function is to allow shared ssl on accross different domain names.
	public function index() {
		// Add rewrite to url class
		if ($this->config->get('config_ssl') && $this->config->get('config_ssl_shared')) {
			$this->url->addRewrite($this);
		}
	}
	
	public function rewrite($link) {
		return $link;			
	}	
}
?>