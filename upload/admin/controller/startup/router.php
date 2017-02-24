<?php
// *	@copyright	OPENCART.PRO 2011 - 2017.
// *	@forum	http://forum.opencart.pro
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

class ControllerStartupRouter extends Controller {
	public function index() {
		// Route
		if (isset($this->request->get['route']) && $this->request->get['route'] != 'startup/router') {
			$route = $this->request->get['route'];
		} else {
			$route = $this->config->get('action_default');
		}
		
		$data = array();
		
		// Sanitize the call
		$route = preg_replace('/[^a-zA-Z0-9_\/]/', '', (string)$route);
		
		// Trigger the pre events
		$result = $this->event->trigger('controller/' . $route . '/before', array(&$route, &$data));
		
		if (!is_null($result)) {
			return $result;
		}
		
		$action = new Action($route);
		
		// Any output needs to be another Action object. 
		$output = $action->execute($this->registry, $data);
		
		// Trigger the post events
		$result = $this->event->trigger('controller/' . $route . '/after', array(&$route, &$output));
		
		if (!is_null($result)) {
			return $result;
		}
		
		return $output;
	}
}
