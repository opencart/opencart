<?php

/*
 * This file is part of the Doctum utility.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types = 1);

namespace Doctum\Renderer;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

class ThemeSet
{
    protected $themes;

    public function __construct(array $dirs)
    {
        $this->discover($dirs);
    }

    public function getTheme($name)
    {
        if (!isset($this->themes[$name])) {
            throw new \InvalidArgumentException(sprintf('Theme "%s" does not exist.', $name));
        }

        return $this->themes[$name];
    }

    protected function discover(array $dirs)
    {
        $this->themes = [];
        $parents      = [];
        $themes       = Finder::create()->name('manifest.yml')->files()->in($dirs)->getIterator();
        foreach ($themes as $manifest) {
            $manifest = $manifest->getPathname();
            if (! file_exists($manifest)) {
                // This should not exist
                throw new \InvalidArgumentException(sprintf('Theme manifest "%s" is not a file.', $manifest));
            }
            $text = file_get_contents($manifest);
            if ($text === false) {
                throw new \Exception(sprintf('Could not read "%s" file.', $manifest));
            }
            $config = Yaml::parse($text);
            if (!isset($config['name'])) {
                throw new \InvalidArgumentException(sprintf('Theme manifest in "%s" must have a "name" entry.', $manifest));
            }

            $theme                         = new Theme($config['name'], dirname($manifest));
            $this->themes[$config['name']] = $theme;

            if (isset($config['parent'])) {
                $parents[$config['name']] = $config['parent'];
            }

            foreach (['static', 'global', 'namespace', 'class'] as $type) {
                if (isset($config[$type])) {
                    $theme->setTemplates($type, $config[$type]);
                }
            }
        }

        // populate parent
        foreach ($parents as $name => $parent) {
            if (!isset($this->themes[$parent])) {
                throw new \LogicException(sprintf('Theme "%s" inherits from an unknown "%s" theme.', $name, $parent));
            }

            $this->themes[$name]->setParent($this->themes[$parent]);
        }
    }

}
