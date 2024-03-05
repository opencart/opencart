<?php namespace Todaymade\Daux\Format\HTML;

use Todaymade\Daux\BaseConfig;

class Theme extends BaseConfig
{
    public function getFonts()
    {
        return $this->getValue('fonts');
    }

    public function getCSS()
    {
        return $this->getValue('css');
    }

    public function getJS()
    {
        return $this->getValue('js');
    }

    public function getFavicon()
    {
        return $this->getValue('favicon');
    }

    public function getTemplates()
    {
        return $this->getValue('templates');
    }
}
