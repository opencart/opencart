<?php
if (isset($_GET[session_name()])) {
	session_id($_GET[session_name()]);
	session_start();
	setcookie(session_name(), session_id(), 0, '/', ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false);	 
}
?>