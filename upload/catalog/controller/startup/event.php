<?php
// *	@copyright	OPENCART.PRO 2011 - 2017.
// *	@forum	http://forum.opencart.pro
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

class ControllerStartupEvent extends Controller {
	public function index() {
		// Add events from the DB
		$this->load->model('extension/event');
		
		$results = $this->model_extension_event->getEvents();
		
		foreach ($results as $result) {
			$this->event->register(substr($result['trigger'], strpos($result['trigger'], '/') + 1), new Action($result['action']));
		}
	}
}