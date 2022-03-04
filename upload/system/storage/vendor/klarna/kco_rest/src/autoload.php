<?php
/**
 * Copyright 2019 Klarna AB
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * File containing the abstract base resource class.
 */

spl_autoload_register(function ($class_path) {
    $baseDir = __DIR__;

    $parts = explode('\\', $class_path);
    $class_name = array_pop($parts) . '.php';
    $path = implode(DIRECTORY_SEPARATOR, $parts);
    $path .= DIRECTORY_SEPARATOR . $class_name;
    if (file_exists($baseDir . DIRECTORY_SEPARATOR . $path)) {
        require_once $baseDir . DIRECTORY_SEPARATOR . $path;
    }
});
