<?php

$squareup_dir = dirname(__FILE__);

require_once $squareup_dir . DIRECTORY_SEPARATOR . 'cron_functions.php';

if ($index = squareup_init($squareup_dir)) {
    require_once $index;
}