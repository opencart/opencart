<?php
class ControllerCommonFooter extends Controller {   
	protected function index() {
		$this->load->language('common/footer');
		
		$this->id       = 'footer';
		$this->template = 'common/footer.tpl';
	
    	$this->render();
  	}
}
?>