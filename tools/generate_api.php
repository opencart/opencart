<?php

$directory = chdir(__DIR__ . '/..');

passthru('php tools/apigen.phar --working-dir "upload/system/storage"');
