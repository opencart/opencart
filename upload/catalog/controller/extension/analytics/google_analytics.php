<?php
// *	@copyright	OPENCART.PRO 2011 - 2017.
// *	@forum	http://forum.opencart.pro
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

class ControllerExtensionAnalyticsGoogleAnalytics extends Controller {
    public function index() {
		return html_entity_decode($this->config->get('google_analytics_code'), ENT_QUOTES, 'UTF-8');
	}
}
