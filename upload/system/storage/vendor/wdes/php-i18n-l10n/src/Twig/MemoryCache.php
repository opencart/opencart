<?php

declare(strict_types = 1);

/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */
namespace Wdes\phpI18nL10n\Twig;

use Twig\Cache\CacheInterface;
use Twig\TemplateWrapper;

/**
 * Token parser for Twig
 * @license MPL-2.0
 */
class MemoryCache implements CacheInterface
{
    /**
     * Contains all templates
     *
     * @var array<string, string>
     */
    private $memory = [];

    /**
     * Generate a cache key
     *
     * @param string $name      The name
     * @param string $className The class name
     * @return string
     */
    public function generateKey($name, $className): string
    {
        return 'memcache_' . $name;
    }

    /**
     * Write to cache
     *
     * @param string $key     The cache key
     * @param string $content The content to write
     * @return void
     */
    public function write($key, $content): void
    {
        $this->memory[$key] = $content;
    }

    /**
     * Load from cache
     *
     * @param string $key The cache key
     * @return void
     */
    public function load(string $key): void
    {
        //load(string $key): void for twig 3.x
    }

    /**
     * Get the timestamp
     *
     * @param string $key The cache key
     * @return int
     */
    public function getTimestamp($key): int
    {
        return 0;
    }

    /**
     * Get the cached string for key
     *
     * @param TemplateWrapper $template The template
     * @return string
     */
    public function getFromCache(TemplateWrapper $template): string
    {
        return $this->memory['memcache_' . $template->getTemplateName()];
    }

    /**
     * Extract source code from memory cache
     *
     * @param TemplateWrapper $template The template
     * @return string
     */
    public function extractDoDisplayFromCache(TemplateWrapper $template): string
    {
        $content = self::getFromCache($template);
        // "/function ([a-z_]+)\("
        preg_match_all(
            '/protected function doDisplay\(([a-z\s,\$=\[\]]+)\)([\s]+){([=%\sa-z\/0-9-\>:\(\)\\\";.,\$\[\]?_-]+)/msi',
            $content,
            $output_array
        );
        return $output_array[3][0];
    }

}
