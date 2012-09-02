<?php
class ControllerCommonShared extends Controller {
	// The purpurse of this class is to allow shared ssl to run accross multiple domains.
	public function index() {
		// Add rewrite to url class
		if ($this->config->get('config_shared')) {
			$this->url->addRewrite($this);
		}
	}
	
	public function rewrite($link) {
		$link = str_replace('&amp;', '&', $link);
		
		$url_info = parse_url($link);
		
		if (isset($this->request->server['HTTP_USER_AGENT']) && $this->request->server['HTTP_HOST'] != $url_info['host']) {
			$status = true;
			
			$robots = explode("\n", trim($this->config->get('config_robots')));
			
			foreach ($robots as $robot) {
				if (strpos($this->request->server['HTTP_USER_AGENT'], trim($robot)) !== false) {
					$status = false;
					
					break;
				}
			}
			
			if ($status) {
				if ($url_info['query']) {
					$link .= '&session_id=' . $this->session->getId();
				} else {
					$link .= '?session_id=' . $this->session->getId();
				}
			}
		}
		
		return str_replace('&', '&amp;', $link);		
	}
}
?>