<?php  
class ControllerModuleFooter extends Controller {
	protected function index() {
		$this->load->language('module/footer');
		
		$this->data['text_powered_by'] = sprintf($this->language->get('text_powered_by'), $this->config->get('config_store'), date('Y'));
		
		$this->id       = 'footer';
		$this->template = $this->config->get('config_template') . 'module/footer.tpl';
		
		$this->render();
	}
}
?>