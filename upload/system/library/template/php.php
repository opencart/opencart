<?php
namespace Template;

use Template\Interfaces\Template;

final class PHP implements Template
{
    private $data = array();

    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function render($template)
    {
        $file = DIR_TEMPLATE . $template . '.tpl';

        if (is_file($file)) {
            extract($this->data);

            ob_start();

            require($file);

            return ob_get_clean();
        }

        trigger_error('Error: Could not load template ' . $file . '!');
        exit();
    }
}
