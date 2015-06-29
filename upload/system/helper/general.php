<?php
function token($length = 32) {
	// Create token to login with
	$string = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	
	$token = '';
	
	for ($i = 0; $i < $length; $i++) {
		$token .= $string[rand(0, strlen($string) - 1)];
	}	
	
	return $token;
}