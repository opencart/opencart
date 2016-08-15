<?php

require __DIR__ . '/../vendor/twitter/autoload.php';

define('CONSUMER_KEY', getenv('TEST_CONSUMER_KEY'));
define('CONSUMER_SECRET', getenv('TEST_CONSUMER_SECRET'));
define('ACCESS_TOKEN', getenv('TEST_ACCESS_TOKEN'));
define('ACCESS_TOKEN_SECRET', getenv('TEST_ACCESS_TOKEN_SECRET'));
define('OAUTH_CALLBACK', getenv('TEST_OAUTH_CALLBACK'));
define('PROXY', getenv('TEST_CURLOPT_PROXY'));
define('PROXYUSERPWD', getenv('TEST_CURLOPT_PROXYUSERPWD'));
define('PROXYPORT', getenv('TEST_CURLOPT_PROXYPORT'));
