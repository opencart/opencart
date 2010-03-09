<?php  
class ControllerCommonFooter extends Controller {
	protected function index() {
		$this->language->load('common/footer');
		
		$this->data['text_powered_by'] = sprintf($this->language->get('text_powered_by'), $this->config->get('config_store'), date('Y', time()));
		
		$this->id = 'footer';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/footer.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/footer.tpl';
		} else {
			$this->template = 'default/template/common/footer.tpl';
		}
		
		if ($this->config->get('google_analytics_status')) {
			$this->data['google_analytics'] = $this->config->get('google_analytics_code');
		} else {
			$this->data['google_analytics'] = '';
		}
		
		$this->render();
	}
}
?>