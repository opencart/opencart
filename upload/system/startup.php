<?php 
// Error Reporting
error_reporting(E_ALL);

// Check Version
if (version_compare(phpversion(), '5.1.0', '<') == TRUE) {
	exit('PHP5.1 Only');
}

// Register Globals Fix
if (ini_get('register_globals')) {
	@ini_set('session.use_cookies', '1');
	@ini_set('session.use_trans_sid', '0');
		
	@session_set_cookie_params(0, '/');
	@session_start();
	
	$globals = array($_REQUEST, $_SESSION, $_SERVER, $_FILES);

	foreach ($globals as $global) {
		foreach(array_keys($global) as $key) {
			unset($$key);
		}
	}
	
	ini_set('register_globals', 'Off');
}

// Magic Quotes Fix
if (ini_get('magic_quotes_gpc')) {
	function clean($data) {
   		if (is_array($data)) {
  			foreach ($data as $key => $value) {
    			$data[$key] = clean($value);
  			}
		} else {
  			$data = stripslashes($data);
		}
	
		return $data;
	}			
	
	$_GET = clean($_GET);
	$_POST = clean($_POST);
	$_COOKIE = clean($_COOKIE);
	
	ini_set('magic_quotes_gpc', 'Off');
}

// Engine
require_once(DIR_SYSTEM . 'engine/action.php');
require_once(DIR_SYSTEM . 'engine/controller.php');
require_once(DIR_SYSTEM . 'engine/front.php');
require_once(DIR_SYSTEM . 'engine/model.php');
require_once(DIR_SYSTEM . 'engine/registry.php');
require_once(DIR_SYSTEM . 'engine/router.php'); 

// Common
include(DIR_SYSTEM . 'library/cache.php');
include(DIR_SYSTEM . 'library/config.php');
include(DIR_SYSTEM . 'library/db.php');
include(DIR_SYSTEM . 'library/document.php');
include(DIR_SYSTEM . 'library/download.php');
include(DIR_SYSTEM . 'library/image.php');
include(DIR_SYSTEM . 'library/language.php');
include(DIR_SYSTEM . 'library/loader.php');
include(DIR_SYSTEM . 'library/mail.php');
include(DIR_SYSTEM . 'library/pagination.php');
include(DIR_SYSTEM . 'library/request.php');
include(DIR_SYSTEM . 'library/response.php');
include(DIR_SYSTEM . 'library/session.php');
include(DIR_SYSTEM . 'library/template.php');
include(DIR_SYSTEM . 'library/url.php');
?>