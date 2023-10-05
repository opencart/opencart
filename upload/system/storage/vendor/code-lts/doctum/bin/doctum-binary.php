<?php

if (PHP_VERSION_ID < 70220) {// 70 2 20
    echo 'You need to use PHP 7.2.20 or above to run Doctum.' . PHP_EOL;
    echo 'Current detected version: (' . PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION . ') (' . PHP_VERSION_ID . ').' . PHP_EOL;
    exit(1);
}

// installed via composer?
$doctumComposerAutoLoadFile = __DIR__ . '/../../../autoload.php';

$doctumComposerAutoLoadFileEnv = getenv('DOCTUM_COMPOSER_AUTOLOAD_FILE');
if (is_string($doctumComposerAutoLoadFileEnv)) {
    $doctumComposerAutoLoadFile = $doctumComposerAutoLoadFileEnv;
}

if (file_exists($doctumComposerAutoLoadFile)) {
    require_once $doctumComposerAutoLoadFile;
} else {
    require_once __DIR__ . '/../vendor/autoload.php';
}

use Doctum\Console\Application;

$application = new Application();
$application->run();
