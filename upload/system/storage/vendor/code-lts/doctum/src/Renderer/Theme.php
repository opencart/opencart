<?php

declare(strict_types = 1);

/*
 * This file is part of the Doctum utility.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Doctum\Renderer;

class Theme
{
    protected $name;
    protected $dir;
    protected $parent;
    protected $templates;

    public function __construct($name, $dir)
    {
        $this->name = $name;
        $this->dir  = $dir;
    }

    public function getTemplateDirs()
    {
        $dirs = [];
        if ($this->parent) {
            $dirs = $this->parent->getTemplateDirs();
        }

        array_unshift($dirs, $this->dir);

        return $dirs;
    }

    public function setParent(Theme $parent)
    {
        $this->parent = $parent;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getTemplates($type)
    {
        $templates = [];
        if ($this->parent) {
            $templates = $this->parent->getTemplates($type);
        }

        if (!isset($this->templates[$type])) {
            return $templates;
        }

        return array_replace($templates, $this->templates[$type]);
    }

    public function setTemplates($type, $templates)
    {
        $this->templates[$type] = $templates;
    }

}
