<?php
class ControllerFooter extends Controller {
	public function index() {
		$data['text_project'] = $this->language->get('text_project');
		$data['text_documentation'] = $this->language->get('text_documentation');
		$data['text_support'] = $this->language->get('text_support');
		$data['text_footer'] = $this->language->get('text_footer');
		$data['text_footer_desc'] = $this->language->get('text_footer_desc');
		
		return $this->load->view('footer.tpl', $data);	
	}
}
