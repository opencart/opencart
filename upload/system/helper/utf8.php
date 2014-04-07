<?php
if (extension_loaded('mbstring')) {
	mb_internal_encoding('UTF-8');
	
	function utf8_strlen($string) {
		return mb_strlen($string);
	}
	
	function utf8_strpos($string, $needle, $offset = 0) {
		return mb_strpos($string, $needle, $offset);	
	}
	
	function utf8_strrpos($string, $needle, $offset = 0) {
		return mb_strrpos($string, $needle, $offset);
	}
	
	function utf8_substr($string, $offset, $length = null) {
		return mb_substr($string, $offset, $length);
	}	
	
	function utf8_strtoupper($string) {	
		return mb_strtoupper($string);
	}
	
	function utf8_strtolower($string) {	
		return mb_strtolower($string);
	}
} elseif (function_exists('iconv')) {
	iconv_set_encoding('internal_encoding', 'UTF-8');
	
	function utf8_strlen($string) {
		return iconv_strlen($string);
	}
	
	function utf8_strpos($string, $needle, $offset = 0) {
		return iconv_strpos($string, $needle, $offset);	
	}
	
	function utf8_strrpos($string, $needle, $offset = 0) {
		return iconv_strrpos($string, $needle, $offset);
	}
	
	function utf8_substr($string, $offset, $length = null) {
		return iconv_substr($string, $offset,  is_null($length) ? iconv_strlen($string) : $length);
	}

	function utf8_strtoupper($string) {	
		$string = utf8_decode($string);
		$string = strtoupper($string);
		$string = utf8_encode($string);

		return $string;
	}
	
	function utf8_strtolower($string) {	
		$string = utf8_decode($string);
		$string = strtolower($string);
		$string = utf8_encode($string);
			
		return $string;
	}	
}
?>
