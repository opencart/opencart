<?php
class ControllerStep1 extends Controller {
	public function index() {
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$this->response->redirect($this->url->link('step_2'));
		}	
		
		$data['action'] = $this->url->link('step_1');
		
		$data['header'] = $this->load->controller('header');
		$data['footer'] = $this->load->controller('footer');
		
		$this->response->setOutput($this->load->view('step_1.tpl', $data));
	}
}