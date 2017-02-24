<?php
// *	@copyright	OPENCART.PRO 2011 - 2017.
// *	@forum	http://forum.opencart.pro
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

class ControllerStartupRouter extends Controller {
	public function index() {
		if (isset($this->request->get['route']) && $this->request->get['route'] != 'action/route') {
			return new Action($this->request->get['route']);
		} else {
			return new Action($this->config->get('action_default'));
		}
	}
}