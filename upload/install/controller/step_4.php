<?php
class ControllerStep4 extends Controller {
	public function index() {
		$data = array();
		
		$data['header'] = $this->load->controller('header');
		$data['footer'] = $this->load->controller('footer');

		$this->response->setOutput($this->load->view('step_4.tpl', $data));
	}
}
?>