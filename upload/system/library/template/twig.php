<?php

namespace Template;

use \Twig_Loader_Filesystem;
use \Twig_Environment;
use \Twig_Extension_Debug;

final class TWIG {

    private $data = array();

    /**
     * 
     * @param type $key
     * @param type $value
     */
    public function set($key, $value) {
        $this->data[$key] = $value;
    }

    /**
     * 
     * @param type $template
     */
    public function render($template) {

        $loader = new Twig_Loader_Filesystem(DIR_TEMPLATE);
        $twig = new Twig_Environment($loader, array(
            'cache' => DIR_CACHE,
            'debug' => true
        ));

        $twig->addExtension(new Twig_Extension_Debug());

        return $twig->render($template . ".html.twig", $this->data);
    }

}
