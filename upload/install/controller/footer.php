<?php
class ControllerFooter extends Controller {
	public function index() {
		return $this->load->view('footer.tpl');	
	}
}
