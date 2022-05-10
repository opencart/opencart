<?php
class ControllerCommonHeader extends Controller {
	public function index() {
		$this->load->language('common/header');
		define('VERSION','3.0.3.8');
		$data['title'] = $this->document->getTitle();
		$data['description'] = $this->document->getDescription();
		$data['links'] = $this->document->getLinks();
		$data['styles'] = $this->document->getStyles();
		$data['scripts'] = $this->document->getScripts();

		$data['base'] = HTTP_SERVER;

		return $this->load->view('common/header', $data);
	}
}
