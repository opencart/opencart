<?php
class ControllerCommonFooter extends Controller {   
	protected function index() {
		$this->load->language('common/footer');
		
		$this->data['text_footer'] = $this->language->get('text_footer');
		
		$this->id       = 'footer';
		$this->template = 'common/footer.tpl';
	
    	$this->render();
  	}
}
?>