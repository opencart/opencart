<?php
/*
 * CKFinder
 * ========
 * http://ckfinder.com
 * Copyright (C) 2007-2009, CKSource - Frederico Knabben. All rights reserved.
 *
 * The software, this file and its contents are subject to the CKFinder
 * License. Please read the license.txt file before using, installing, copying,
 * modifying or distribute this file or part of its contents. The contents of
 * this file is part of the Source Code of CKFinder.
 */

/**
 * Constants required by CKFinder
 *
 * @package  CKFinder
 * @subpackage Config
 * @copyright CKSource - Frederico Knabben
 */

/**
 * No errors
 */
define('CKFINDER_CONNECTOR_ERROR_NONE',0);
define('CKFINDER_CONNECTOR_ERROR_CUSTOM_ERROR',1);
define('CKFINDER_CONNECTOR_ERROR_INVALID_COMMAND',10);
define('CKFINDER_CONNECTOR_ERROR_TYPE_NOT_SPECIFIED',11);
define('CKFINDER_CONNECTOR_ERROR_INVALID_TYPE',12);
define('CKFINDER_CONNECTOR_ERROR_INVALID_NAME',102);
define('CKFINDER_CONNECTOR_ERROR_UNAUTHORIZED',103);
define('CKFINDER_CONNECTOR_ERROR_ACCESS_DENIED',104);
define('CKFINDER_CONNECTOR_ERROR_INVALID_EXTENSION',105);
define('CKFINDER_CONNECTOR_ERROR_INVALID_REQUEST',109);
define('CKFINDER_CONNECTOR_ERROR_UNKNOWN',110);
define('CKFINDER_CONNECTOR_ERROR_ALREADY_EXIST',115);
define('CKFINDER_CONNECTOR_ERROR_FOLDER_NOT_FOUND',116);
define('CKFINDER_CONNECTOR_ERROR_FILE_NOT_FOUND',117);
define('CKFINDER_CONNECTOR_ERROR_UPLOADED_FILE_RENAMED',201);
define('CKFINDER_CONNECTOR_ERROR_UPLOADED_INVALID',202);
define('CKFINDER_CONNECTOR_ERROR_UPLOADED_TOO_BIG',203);
define('CKFINDER_CONNECTOR_ERROR_UPLOADED_CORRUPT',204);
define('CKFINDER_CONNECTOR_ERROR_UPLOADED_NO_TMP_DIR',205);
define('CKFINDER_CONNECTOR_ERROR_UPLOADED_WRONG_HTML_FILE',206);
define('CKFINDER_CONNECTOR_ERROR_CONNECTOR_DISABLED',500);
define('CKFINDER_CONNECTOR_ERROR_THUMBNAILS_DISABLED',501);

define('CKFINDER_CONNECTOR_DEFAULT_USER_FILES_PATH',"/userfiles/");
define('CKFINDER_CONNECTOR_LANG_PATH',"./lang");
define('CKFINDER_CONNECTOR_CONFIG_FILE_PATH',"./../../../config.php");

if (version_compare(phpversion(), '6', '>=')) {
    define('CKFINDER_CONNECTOR_PHP_MODE', 6);
}
else if (version_compare(phpversion(), '5', '>=')) {
    define('CKFINDER_CONNECTOR_PHP_MODE', 5);
}
else {
    define('CKFINDER_CONNECTOR_PHP_MODE', 4);
}

if (CKFINDER_CONNECTOR_PHP_MODE == 4) {
    define('CKFINDER_CONNECTOR_LIB_DIR', "./php4");
} else {
    define('CKFINDER_CONNECTOR_LIB_DIR', "./php5");
}

define('CKFINDER_CHARS', '123456789ABCDEFGHJKLMNPQRSTUVWXYZ');
define('CKFINDER_REGEX_IMAGES_EXT', '/\.(jpg|gif|png|bmp|jpeg)$/i');
