<?php
namespace Todaymade\Daux\Format\HTML;

use League\Plates\Engine;
use Symfony\Component\Console\Output\OutputInterface;
use Todaymade\Daux\Config as GlobalConfig;
use Todaymade\Daux\Daux;
use Todaymade\Daux\Tree\Content;
use Todaymade\Daux\Tree\Directory;

class Template
{
    protected $engine;

    protected $config;

    /**
     * @param string $base
     * @param string $theme
     */
    public function __construct(GlobalConfig $config)
    {
        $this->config = $config;
    }

    public function getEngine(GlobalConfig $config)
    {
        if ($this->engine) {
            return $this->engine;
        }

        $base = $config->getTemplates();
        $theme = $config->getTheme()->getTemplates();

        // Use internal templates if no templates
        // dir exists in the working directory
        if (!is_dir($base)) {
            $base = __DIR__ . '/../../../templates';
        }

        // Create new Plates instance
        $this->engine = new Engine($base);
        if (!is_dir($theme)) {
            $theme = $base;
        }
        $this->engine->addFolder('theme', $theme, true);

        Daux::writeln("Starting Template engine with basedir '$base' and theme folder '$theme'.", OutputInterface::VERBOSITY_VERBOSE);

        $this->registerFunctions($this->engine);

        return $this->engine;
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public function render($name, array $data = [])
    {
        $engineInstance = $this->getEngine($data['config']);

        $engineInstance->addData([
            'base_url' => $data['config']->getBaseUrl(),
            'base_page' => $data['config']->getBasePage(),
            'page' => $data['page'],
            'params' => $data['config'], // legacy name for config
            'config' => $data['config'],
            'tree' => $data['config']['tree'],
        ]);

        Daux::writeln("Rendering template '$name'", OutputInterface::VERBOSITY_VERBOSE);

        return $engineInstance->render($name, $data);
    }

    protected function registerFunctions($engine)
    {
        $engine->registerFunction('get_navigation', function ($tree, $path, $currentUrl, $basePage, $mode) {
            $nav = $this->buildNavigation($tree, $path, $currentUrl, $basePage, $mode);

            return $this->renderNavigation($nav);
        });

        $engine->registerFunction('translate', function ($key) {
            $language = $this->config->getLanguage();

            if (isset($this->engine->getData('page')['page'])) {
                $page = $this->engine->getData('page');
                if (!empty($page['page']['language'])) {
                    $language = $page['page']['language'];
                }
            }

            if ($this->config->hasTranslationKey($language, $key)) {
                return $this->config->getTranslationKey($language, $key);
            }

            if ($this->config->hasTranslationKey('en', $key)) {
                return $this->config->getTranslationKey('en', $key);
            }

            return "Unknown key $key";
        });

        $engine->registerFunction('get_breadcrumb_title', function ($page, $basePage) {
            $title = '';
            $breadcrumbTrail = $page['breadcrumb_trail'];
            $separator = $this->getSeparator($page['breadcrumb_separator']);
            foreach ($breadcrumbTrail as $value) {
                $title .= '<a href="' . $basePage . $value['url'] . '">' . $value['title'] . '</a>' . $separator;
            }
            if ($page['filename'] === 'index' || $page['filename'] === '_index') {
                if ($page['title'] != '') {
                    $title = substr($title, 0, -1 * strlen($separator));
                }
            } else {
                $title .= '<a href="' . $basePage . $page['request'] . '">' . $page['title'] . '</a>';
            }

            return $title;
        });
    }

    private function renderNavigation($entries)
    {
        $nav = '';
        foreach ($entries as $entry) {
            if (array_key_exists('children', $entry)) {
                $icon = '<i class="Nav__arrow">&nbsp;</i>';

                if (array_key_exists('href', $entry)) {
                    $link = '<a href="' . $entry['href'] . '" class="Nav__item__link">' . $icon . $entry['title'] . '</a>';
                } else {
                    $link = '<a href="#" class="Nav__item__link Nav__item__link--nopage">' . $icon . $entry['title'] . '</a>';
                }

                $link .= $this->renderNavigation($entry['children']);
            } else {
                $link = '<a href="' . $entry['href'] . '">' . $entry['title'] . '</a>';
            }

            $nav .= "\n<li class='Nav__item $entry[class]'>$link</li>";
        }

        return "<ul class='Nav'>$nav</ul>";
    }

    private function buildNavigation(Directory $tree, $path, $currentUrl, $basePage, $mode)
    {
        $nav = [];
        foreach ($tree->getEntries() as $node) {
            $url = $node->getUri();
            if ($node instanceof Content) {
                if ($node->isIndex()) {
                    continue;
                }

                $link = ($path === '') ? $url : $path . '/' . $url;

                $nav[] = [
                    'title' => $node->getTitle(),
                    'href' => $basePage . $link,
                    'class' => $node->isHotPath() ? 'Nav__item--active' : '',
                ];
            } elseif ($node instanceof Directory) {
                if (!$node->hasContent()) {
                    continue;
                }

                $folder = [
                    'title' => $node->getTitle(),
                    'class' => $node->isHotPath() ? 'Nav__item--open' : '',
                ];

                if ($index = $node->getIndexPage()) {
                    $folder['href'] = $basePage . $index->getUrl();
                }

                // Child pages
                $newPath = ($path === '') ? $url : $path . '/' . $url;
                $folder['children'] = $this->buildNavigation($node, $newPath, $currentUrl, $basePage, $mode);

                if (!empty($folder['children'])) {
                    $folder['class'] .= ' has-children';
                }

                $nav[] = $folder;
            }
        }

        return $nav;
    }

    /**
     * @param string $separator
     *
     * @return string
     */
    private function getSeparator($separator)
    {
        if ($separator === 'Chevrons') {
            return ' <svg class="Page__header--separator" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 477.175 477.175"><path d="M360.73 229.075l-225.1-225.1c-5.3-5.3-13.8-5.3-19.1 0s-5.3 13.8 0 19.1l215.5 215.5-215.5 215.5c-5.3 5.3-5.3 13.8 0 19.1 2.6 2.6 6.1 4 9.5 4 3.4 0 6.9-1.3 9.5-4l225.1-225.1c5.3-5.2 5.3-13.8.1-19z"/></svg> ';
        }

        return $separator;
    }
}
