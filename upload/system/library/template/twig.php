<?php
namespace Template;

final class Twig
{
    private $data = array();

    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function render($template, $registry)
    {
        try {

            $paths = array(
                DIR_TEMPLATE,
            );

            $fileSystem = new \Twig_Loader_Filesystem($paths);

            //Set configs
            $twig = new \Twig_Environment($fileSystem, array(
                'autoescape' => false
            ));

            $registry->get('event')->trigger('library/template/twig/before', array(&$twig));

            if ($fileSystem->exists($template)) {
                $output = $twig->render($template, $this->data);
            } else {
                $template = str_replace('.tpl', '', $template) . '.twig';
                $output = $twig->render($template, $this->data);
            }

            return $output;

        } catch (\Twig_Error_Syntax $e) {
            trigger_error('Error: ' . $e->getMessage());
        }
    }
}
