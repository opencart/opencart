<?php  
class ControllerCommonFooter extends Controller {
	protected function index() {
		$this->load->language('common/footer');
		
		$this->data['text_powered_by'] = sprintf($this->language->get('text_powered_by'), $this->config->get('config_store'), date('Y'));
		
		$this->id       = 'footer';
		$this->template = $this->config->get('config_template') . 'common/footer.tpl';
		
		$this->render();
	}
}
?>