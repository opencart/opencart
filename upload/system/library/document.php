<?php
final class Document {
	private $title;
	private $description;
	private $keywords;	
	private $links = array();		
	private $styles = array();
	private $scripts = array();
	
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
	
	public function addLink($href, $rel) {
		$this->links[] = array(
			'href' => $href,
			'rel'  => $rel
		);			
	}
	
	public function getLinks() {
		return array_unique($this->links);
	}	
	
	public function addStyle($href, $rel = 'stylesheet', $media = 'screen') {
		$this->styles[] = array(
			'href'  => $href,
			'rel'   => $rel,
			'media' => $media
		);
	}
	
	public function getStyles() {
		return array_unique($this->styles);
	}	
	
	public function addScript($script) {
		$this->scripts[] = $script;			
	}
	
	public function getScripts() {
		return array_unique($this->scripts);
	}
}
?>