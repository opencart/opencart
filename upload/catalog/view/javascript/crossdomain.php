<?php 
header('P3P: CP="CAO COR CURa ADMa DEVa OUR IND ONL COM DEM PRE"');

if (isset($_GET['session_id'])) {
	setcookie(session_name(), $_GET['session_id'], 0, getenv('HTTP_HOST'));
}
?>