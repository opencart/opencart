<?php
class ControllerEventTheme extends Controller {
	public function index(&$view, &$data) {
		// This is only here for compatibility with old templates
		if (substr($view, -4) == '.tpl') {
			$view = substr($view, 0, -4);
		}
	}
}
