<?php

class ControllerEventTwig extends Controller {

    public function index(&$twig)
    {
        // Twig Template Engine config
        $twig->setCache(false); //DIR_CACHE . 'twig/'

        //Load Twig Extensions
        $twig->addExtension(new \Twig_Extension_Debug());
    }

}