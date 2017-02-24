<?php
// *	@copyright	OPENCART.PRO 2011 - 2017.
// *	@forum	http://forum.opencart.pro
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

class ControllerEventTranslation extends Controller {
	public function index($route) {
		$this->load->model('design/translation');
			
		$results = $this->model_design_translation->getTranslations($route);
		
		foreach ($results as $result) {
			$this->language->set($result['key'], $result['value']);
		}
	}
}
