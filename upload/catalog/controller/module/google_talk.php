<?php  
class ControllerModuleGoogleTalk extends Controller {
	protected function index() {
		$this->language->load('module/google_talk');

      	$this->data['heading_title'] = $this->language->get('heading_title');
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$this->data['code'] = str_replace('http', 'https', html_entity_decode($this->config->get('google_talk_code')));
		} else {
			$this->data['code'] = html_entity_decode($this->config->get('google_talk_code'));
		}
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/google_talk.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/google_talk.tpl';
		} else {
			$this->template = 'default/template/module/google_talk.tpl';
		}
		
		$this->render();
	}
}
?>