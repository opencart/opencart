<?php
// *	@copyright	OPENCART.PRO 2011 - 2017.
// *	@forum	http://forum.opencart.pro
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

class ControllerEventTheme extends Controller {
	public function index(&$view, &$data) {
		// This is only here for compatibility with old templates
		if (substr($view, -3) == 'tpl') {
			$view = substr($view, 0, -3);
		}
	}
}
