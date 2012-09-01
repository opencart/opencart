<?php
class ControllerCommonShared extends Controller {
	// The purpurse of this function is to allow shared ssl to run accross multiple domains.
	public function index() {
		// Add rewrite to url class
		if ($this->config->get('config_use_shared')) {
			$this->url->addRewrite($this);
		}
	}
	
	public function rewrite($link) {
		$url_info = parse_url(str_replace('&amp;', '&', $link));
		
		if ($this->request->server['HTTP_HOST'] != $url_info['host']) {
			if ($url_info['query']) {
				$link .= '&session_id=' . $this->session->getId();
			} else {
				$link .= '?session_id=' . $this->session->getId();
			}
		}
		
		return str_replace('&', '&amp;', $link);		
	}
}
?>