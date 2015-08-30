<?php
class ControllerAnalyticsGoogleAnalytics extends Controller {
    public function index() {
		return html_entity_decode($this->config->get('google_analytics_code'), ENT_QUOTES, 'UTF-8');
    }
}
