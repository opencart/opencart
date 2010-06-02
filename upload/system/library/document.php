<?php
final class Document {
	public $title;
	public $description;
	public $keywords;
	public $base;	
	public $charset = 'utf-8';		
	public $language = 'en-gb';	
	public $direction = 'ltr';		
	public $links = array();		
	public $styles = array();
	public $scripts = array();
	public $breadcrumbs = array();
	
	public function setTitle($title) {
		$this->title = $title;
	}
	
	public function getTitle() {
		return $this->title;
	}
	
	public function setDescription($description) {
		$this->description = $description;
	}
	
	public function getDescription() {
		return $this->description;
	}
	
	public function setKeywords($keywords) {
		$this->keywords = $keywords;
	}
	
	public function getKeywords() {
		return $this->keywords;
	}
	
	public function setBase($base) {
		$this->base = $base;
	}
	
	public function getBase() {
		return $this->base;
	}		
	
	public function setCharset($charset) {
		$this->charset = $charset;
	}
	
	public function getCharset() {
		return $this->charset;
	}	
	
	public function setLanguage($language) {
		$this->language = $language;
	}
	
	public function getLanguage() {
		return $this->language;
	}	
	
	public function setDirection($direction) {
		$this->direction = $direction;
	}
	
	public function getDirection() {
		return $this->direction;
	}	
	
	public function addLink($href, $rel) {
		$this->links[] = array(
			'href' => $href,
			'rel'  => $rel
		);			
	}
	
	public function getLinks() {
		return $this->links;
	}	
	
	public function addStyle($href, $rel = 'stylesheet', $media = 'screen') {
		$this->styles[] = array(
			'href'  => $href,
			'rel'   => $rel,
			'media' => $media
		);			
	}
	
	public function getStyles() {
		return $this->styles;
	}	
	
	public function addScript($script) {
		$this->scripts[] = $script;			
	}
	
	public function getScripts() {
		return $this->scripts;
	}
	
	public function addBreadcrumb($text, $href, $separator = ' &gt; ') {
		$this->breadcrumbs[] = array(
			'text'      => $text,
			'href'      => $href,
			'separator' => $separator
		);			
	}
	
	public function getBreadcrumbs() {
		return $this->breadcrumbs;
	}	
}
?>