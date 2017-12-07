<?php
class ControllerExtensionAnalyticsGoogle extends Controller {
    public function index() {
		return html_entity_decode($this->config->get('analytics_google_code'), ENT_QUOTES, 'UTF-8');
	}
}
