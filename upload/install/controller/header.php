<?php
class ControllerHeader extends Controller {
	public function index() {
		$data['title'] = $this->document->getTitle();
		$data['description'] = $this->document->getDescription();
		$data['links'] = $this->document->getLinks();
		$data['styles'] = $this->document->getStyles();
		$data['scripts'] = $this->document->getScripts();		
		
		$data['error_javascript'] = $this->language->get('error_javascript');
		$data['text_upgrading'] = $this->language->get('text_upgrading');
		$data['text_installing'] = $this->language->get('text_installing');
		$data['text_install_progress'] = $this->language->get('text_install_progress');
		$data['text_upgrade_progress'] = $this->language->get('text_upgrade_progress');
		
		$data['base'] = HTTP_SERVER;
		
		return $this->load->view('header.tpl', $data);	
	}
}