<?php

class ControllerEventTemplate extends Controller
{
    public function index(&$template)
    {
        $adaptor = $template->getAdaptor();

        if ($adaptor instanceof \Template\Twig) {

            // Twig Template Engine config
            $adaptor->getTwig()->setCache(false); //DIR_CACHE . 'twig/'

            //Load Twig Extensions
            $adaptor->getTwig()->addExtension(new \Twig_Extension_Debug());
        }
    }
}