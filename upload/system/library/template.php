<?php

class Template
{
    private $adaptor;

    public function __construct($class)
    {
        if (class_exists($class) == false) {
            throw new \Exception('Error: Could not load template class ' . $class . '!');
        }

        $this->adaptor = new $class();

        if (!$this->adaptor instanceof \Template\Interfaces\Template) {
            throw new \Exception('Error: The class is not an instance of \Template\Interfaces\Template!');
        }
    }

    /**
     * @return mixed
     */
    public function getAdaptor()
    {
        return $this->adaptor;
    }

    public function set($key, $value)
    {
        $this->adaptor->set($key, $value);
    }

    public function render($template)
    {
        return $this->adaptor->render($template);
    }
}
