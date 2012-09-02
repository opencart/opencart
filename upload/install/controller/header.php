<?php
class ControllerHeader extends Controller {
	public function index() {
		$this->data['title'] = $this->document->getTitle();
		$this->data['description'] = $this->document->getDescription();
		$this->data['links'] = $this->document->getLinks();
		$this->data['styles'] = $this->document->getStyles();
		$this->data['scripts'] = $this->document->getScripts();		
		
		$this->data['base'] = HTTP_SERVER;
		
		$this->template = 'header.tpl';

		$this->render();
	}
}
?>