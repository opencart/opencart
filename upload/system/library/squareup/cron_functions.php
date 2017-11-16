<?php

function squareup_validate() {
    if (!getenv("SQUARE_CRON")) {
        die("Not in Command Line." . PHP_EOL);
    }
}

function squareup_chdir($current_dir) {
    $root_dir = dirname(dirname(dirname($current_dir)));

    chdir($root_dir);

    return $root_dir;
}

function squareup_define_route() {
    define('SQUAREUP_ROUTE', 'extension/recurring/squareup/recurring');

    $_GET['route'] = SQUAREUP_ROUTE;
}

function squareup_init($current_dir) {
    // Validate environment
    squareup_validate();

    // Set up default server vars
    $_SERVER["HTTP_HOST"] = getenv("CUSTOM_SERVER_NAME");
    $_SERVER["SERVER_NAME"] = getenv("CUSTOM_SERVER_NAME");
    $_SERVER["SERVER_PORT"] = getenv("CUSTOM_SERVER_PORT");

    putenv("SERVER_NAME=" . $_SERVER["SERVER_NAME"]);

    // Change root dir
    $root_dir = squareup_chdir($current_dir);

    squareup_define_route();

    if (file_exists($root_dir . '/index.php')) {
        return $root_dir . '/index.php';
    }
}