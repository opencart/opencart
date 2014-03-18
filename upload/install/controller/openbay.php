<?php
class ControllerOpenbay extends Controller {
	private $error = array();

	public function index() {
		$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

		$this->document->setTitle($this->language->get('heading_openbay'));

		$data['heading_openbay'] = $this->language->get('heading_openbay');
		$data['heading_openbay_small'] = $this->language->get('heading_openbay_small');

		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_back'] = $this->language->get('button_back');


		$data['back'] = $this->url->link('step_4');

		$data['footer'] = $this->load->controller('footer');
		$data['header'] = $this->load->controller('header');

		$this->response->setOutput($this->load->view('openbay.tpl', $data));
	}
}