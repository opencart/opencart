<?php
class ControllerModuleFooter extends Controller {   
	protected function index() {
		$this->load->language('module/footer');
		
		$this->id       = 'footer';
		$this->template = 'module/footer.tpl';
	
    	$this->render();
  	}
}
?>