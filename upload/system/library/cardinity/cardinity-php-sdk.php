<?php

//only use sdk if the version suits
if (version_compare(phpversion(), '7.2.5', '>=')) {
    require_once __DIR__ . '/vendor/autoload.php';
}
