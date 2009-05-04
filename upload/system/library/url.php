<?php 
final class Url { 
  	public function http($route) {
		return HTTP_SERVER . 'index.php?route=' . str_replace('&', '&amp;', $route);
  	}

  	public function https($route) {
		if (HTTPS_SERVER != '') {
	  		$link = HTTPS_SERVER . 'index.php?route=' . str_replace('&', '&amp;', $route);
		} else {
	  		$link = HTTP_SERVER . 'index.php?route=' . str_replace('&', '&amp;', $route);
		}
				
		return $link;
  	}
}
?>