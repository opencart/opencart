<?php
class ControllerActionStart extends Controller {
	public function index() {
		if (isset($request->get['route'])) {
			$route = $request->get['route'];
		} else {
			$route = 'common/home';
		}
		
		// Sanitize the call
		$route = str_replace('../', '', (string)$route);
		
		// Trigger the pre events
		$result = $this->registry->get('event')->trigger('controller/' . $route . '/before', array(&$route));
		
		if (!is_null($result)) {
			return $result;
		}
		
		$action = new Action($route);
		$output = $action->execute($this->registry);
			
		// Trigger the post events
		$result = $this->registry->get('event')->trigger('controller/' . $route . '/after', array(&$output));
		
		if (!is_null($result)) {
			return $result;
		}
		
		return $output;
	}
}
