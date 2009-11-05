<?php
class ControllerHeader extends Controller {
	public function index() {
		$this->data['title'] = $this->document->title;
		$this->data['description'] = $this->document->description;
		$this->data['base'] = $this->document->base;
		$this->data['charset'] = $this->document->charset;
		$this->data['language'] = $this->document->language;
		$this->data['direction'] = $this->document->direction;
		$this->data['links'] = $this->document->links;	
		$this->data['styles'] = $this->document->styles;
		$this->data['scripts'] = $this->document->scripts;		
		$this->data['breadcrumbs'] = $this->document->breadcrumbs;
		
		$this->id       = 'header';
		$this->template = 'header.tpl';

		$this->render();
	}
}
?>