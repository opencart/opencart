<?php

function advertise_google_validate() {
    if (!getenv("ADVERTISE_GOOGLE_CRON")) {
        die("Not in Command Line." . PHP_EOL);
    }
}

function advertise_google_chdir($current_dir) {
    $root_dir = dirname(dirname(dirname(dirname(dirname($current_dir)))));

    chdir($root_dir);

    return $root_dir;
}

function advertise_google_define_route() {
    define('ADVERTISE_GOOGLE_ROUTE', 'extension/advertise/google/cron');

    $_GET['route'] = ADVERTISE_GOOGLE_ROUTE;
}

function advertise_google_init($current_dir) {
    // Validate environment
    advertise_google_validate();

    // Set up default server vars
    $_SERVER["HTTP_HOST"] = getenv("CUSTOM_SERVER_NAME");
    $_SERVER["SERVER_NAME"] = getenv("CUSTOM_SERVER_NAME");
    $_SERVER["SERVER_PORT"] = getenv("CUSTOM_SERVER_PORT");

    putenv("SERVER_NAME=" . $_SERVER["SERVER_NAME"]);

    // Change root dir
    $root_dir = advertise_google_chdir($current_dir);

    advertise_google_define_route();

    if (file_exists($root_dir . '/index.php')) {
        return $root_dir . '/index.php';
    }
}