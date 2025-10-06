<?php

function loadApp()
{
    // Loaded as a dependency
    if (file_exists(__DIR__ . '/../../../autoload.php')) {
        return require_once __DIR__ . '/../../../autoload.php';
    }

    // Loaded in the project itself
    if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
        return require_once __DIR__ . '/../vendor/autoload.php';
    }

    throw new Exception('Impossible to load Daux, missing vendor/');
}

$loader = loadApp();

// This will define the path at which to
// find the daux processor extensions
if ($loader) {
    $ext = __DIR__ . '/../daux';
    if (is_dir(getcwd() . '/daux')) {
        $ext = getcwd() . '/daux';
    }

    $env = getenv('DAUX_EXTENSION');
    if ($env && is_dir($env)) {
        $ext = $env;
    }

    define('DAUX_EXTENSION', $ext);

    $loader->setPsr4('Todaymade\\Daux\\Extension\\', $ext);
}
