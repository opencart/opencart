<?php

namespace Template\Interfaces;


interface Template
{
    public function set($key, $value);

    public function render($template);
}