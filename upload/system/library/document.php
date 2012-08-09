<?php
class Document {
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
		$this->links[md5($href)] = array(
			'href' => $href,
			'rel'  => $rel
		);			
	}
	
	public function getLinks() {
		return $this->links;
	}	
	
	public function addStyle($href, $rel = 'stylesheet', $media = 'screen') {
		$this->styles[md5($href)] = array(
			'href'  => $href,
			'rel'   => $rel,
			'media' => $media
		);
	}
	
	public function inStyles($href) {
		foreach ($this->styles as $key => $style) {
			if ($style['href'] == $href) {
					return true;
				} 
		}
		return false;
	}
	
	public function removeStyle($href) {
		if ($this->inStyles($href)) {
			unset($this->styles[md5($href)]);
			return true;
		} else {
			return false;
		}
	}
	
	public function getStyles() {
		return $this->styles;
	}	
	
	public function addScript($script) {
		$this->scripts[md5($script)] = $script;			
	}
	
	public function inScripts($hrefscript) {
		foreach ($this->scripts as $key => $script) {
			if ($script == $hrefscript) {
					return true;
				} 
		}
		return false;
	}
	
	public function removeScript($hrefscript) {
		if ($this->inScripts($hrefscript)) {
			unset($this->scripts[md5($hrefscript)]);
			return true;
		} else {
			return false;
		}
	}
	
	public function getScripts() {
		return $this->scripts;
	}
}
?>