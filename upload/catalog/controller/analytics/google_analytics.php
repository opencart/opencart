<?php
class ControllerAnalyticsGoogleAnalytics extends Controller {
    public function index() {
		$settting = $this->config->get('google_analytics');
		
		if (isset($settting[$this->config->get('config_store_id')]) && $settting[$this->config->get('config_store_id')]['status']) {
			return html_entity_decode($settting[$this->config->get('config_store_id')]['code'], ENT_QUOTES, 'UTF-8');
		}
	}
}
