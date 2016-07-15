<?php
namespace Template;

use Template\Interfaces\Template;

final class Twig implements Template
{
    /**
     * @var \Twig_Loader_Filesystem
     */
    private $fileSystem;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var array
     */
    private $data = [];


    public function __construct()
    {
        $paths = [
            DIR_TEMPLATE,
        ];

        $this->setFileSystem(new \Twig_Loader_Filesystem($paths));

        //Set configs
        $this->setTwig(new \Twig_Environment($this->fileSystem, [
            'autoescape' => false
        ]));
    }

    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * @return \Twig_Loader_Filesystem
     */
    public function getFileSystem()
    {
        return $this->fileSystem;
    }

    /**
     * @param \Twig_Loader_Filesystem $fileSystem
     */
    public function setFileSystem($fileSystem)
    {
        $this->fileSystem = $fileSystem;
    }

    /**
     * @return \Twig_Environment
     */
    public function getTwig()
    {
        return $this->twig;
    }

    /**
     * @param \Twig_Environment $twig
     */
    public function setTwig($twig)
    {
        $this->twig = $twig;
    }

    /**
     * Render template
     * @param $template
     * @return string
     */
    public function render($template)
    {
        try {

            if ($this->getFileSystem()->exists($template)) {
                $output = $this->getTwig()->render($template, $this->data);
            } else {
                $template = str_replace('.tpl', '', $template) . '.twig';
                $output = $this->getTwig()->render($template, $this->data);
            }

            return $output;

        } catch (\Twig_Error_Syntax $e) {
            trigger_error('Error: ' . $e->getMessage());
        }
    }
}
