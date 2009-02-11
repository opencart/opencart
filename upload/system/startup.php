<?php 
// Error Reporting
error_reporting(E_ALL);

// Check Version
if (version_compare(phpversion(), '5.1.0', '<') == TRUE) {
	exit('PHP5.1 Only');
}

// Maximum Execution Time;
set_time_limit(10);

// Security
ini_set('register_globals', 'Off');

if (ini_get('register_globals')) {
	exit('Error: register_globals is enabled!');
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