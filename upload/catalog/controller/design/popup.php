<?php
namespace Opencart\Catalog\Controller\Design;
/**
 * Class Popup
 *
 * @package Opencart\Catalog\Controller\Design
 */
class Popup extends \Opencart\System\Engine\Controller {
	/**
	 * @return string
	 */
	public function index(): string {
		$this->load->model('design/popup');
		$this->load->language('design/popup');
		$data = [];

		$data['active_popup'] = $this->model_design_popup->getActivePopup();
		if(!empty($data['active_popup'])){
			$data['active_popup']['content'] = html_entity_decode($data['active_popup']['content'], ENT_QUOTES, 'UTF-8');
		}

		return $this->load->view('design/popup', $data);
	}
}
