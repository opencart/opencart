<?php

$directory = chdir(__DIR__ . '/..');

passthru('php tools/ApiGen/bin/apigen --working-dir "upload/system/storage"');
