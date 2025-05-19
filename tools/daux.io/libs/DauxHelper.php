<?php namespace Todaymade\Daux;

use Todaymade\Daux\Tree\Builder;
use Todaymade\Daux\Tree\Directory;
use Todaymade\Daux\Tree\Entry;

class DauxHelper
{
    /**
     * Set a new base_url for the configuration.
     *
     * @param string $baseUrl
     */
    public static function rebaseConfiguration(Config $config, $baseUrl)
    {
        // Avoid changing the url if it is already correct
        if ($config->getBaseUrl() == $baseUrl && !empty($config->getTheme())) {
            return;
        }

        // Change base url for all links on the pages
        $config['base_url'] = $baseUrl;
        $config['theme'] = static::getTheme($config, $baseUrl);
        $config['image'] = str_replace('<base_url>', $baseUrl, $config->getImage());
    }

    /**
     * @return array
     */
    protected static function getTheme(Config $config, string $currentUrl)
    {
        static $cache = [];

        $htmlTheme = $config->getHTML()->getTheme();

        $themeFolder = $config->getThemesPath() . DIRECTORY_SEPARATOR . $htmlTheme;
        $themeUrl = $config->getBaseUrl() . 'themes/' . $htmlTheme . '/';

        $cacheKey = "$currentUrl-$htmlTheme";
        if (array_key_exists($cacheKey, $cache)) {
            return $cache[$cacheKey];
        }

        $theme = [];
        if (is_file($themeFolder . DIRECTORY_SEPARATOR . 'config.json')) {
            $theme = json_decode(file_get_contents($themeFolder . DIRECTORY_SEPARATOR . 'config.json'), true);
            if (!$theme) {
                $theme = [];
            }
        }

        // Default parameters for theme
        $theme += [
            'name' => $htmlTheme,
            'css' => [],
            'js' => [],
            'fonts' => [],
            'favicon' => '<base_url>themes/daux/img/favicon.png',
            'templates' => $themeFolder . DIRECTORY_SEPARATOR . 'templates',
            'variants' => [],
            'with_search' => $config->getHTML()->hasSearch(),
        ];

        if ($config->getHTML()->hasThemeVariant()) {
            $variant = $config->getHTML()->getThemeVariant();
            if (!array_key_exists($variant, $theme['variants'])) {
                throw new Exception("Variant '$variant' not found for theme '$theme[name]'");
            }

            // These will be replaced
            foreach (['templates', 'favicon'] as $element) {
                if (array_key_exists($element, $theme['variants'][$variant])) {
                    $theme[$element] = $theme['variants'][$variant][$element];
                }
            }

            // These will be merged
            foreach (['css', 'js', 'fonts'] as $element) {
                if (array_key_exists($element, $theme['variants'][$variant])) {
                    $theme[$element] = array_merge($theme[$element], $theme['variants'][$variant][$element]);
                }
            }
        }

        if ($theme['with_search']) {
            $theme['css'][] = '<base_url>daux_libraries/search.css';
        }

        if (is_file($config->getDocumentationDirectory() . DIRECTORY_SEPARATOR . 'style.css')) {
            $theme['css'][] = '<base_url>style.css';
        }

        $substitutions = [
            '<local_base>' => $config->getLocalBase(),
            '<base_url>' => $currentUrl,
            '<theme_url>' => $themeUrl,
        ];

        // Substitute some placeholders
        $theme['templates'] = strtr($theme['templates'], $substitutions);
        $theme['favicon'] = strtr($theme['favicon'], $substitutions);

        foreach (['css', 'js', 'fonts'] as $element) {
            foreach ($theme[$element] as $key => $value) {
                $theme[$element][$key] = strtr($value, $substitutions);
            }
        }

        if (!getenv('APP_ENV') || getenv('APP_ENV') != 'test') {
            $cache[$cacheKey] = $theme;
        }

        return $theme;
    }

    /**
     * Remove all '/./' and '/../' in a path, without actually checking the path.
     *
     * @param string $path
     *
     * @return string
     */
    public static function getCleanPath($path)
    {
        $path = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
        $parts = array_filter(explode(DIRECTORY_SEPARATOR, $path), 'strlen');
        $absolutes = [];
        foreach ($parts as $part) {
            if ('.' == $part) {
                continue;
            }
            if ('..' == $part) {
                array_pop($absolutes);
            } else {
                $absolutes[] = $part;
            }
        }

        return implode(DIRECTORY_SEPARATOR, $absolutes);
    }

    /**
     * Get the possible output file names for a source file.
     *
     * @param string $part
     *
     * @return string[]
     */
    public static function getFilenames(Config $config, $part)
    {
        $extensions = implode('|', array_map('preg_quote', $config->getValidContentExtensions())) . '|html';

        $raw = preg_replace('/(.*)?\\.(' . $extensions . ')$/', '$1', $part);
        $raw = Builder::removeSortingInformations($raw);

        return ["$raw.html", $raw];
    }

    /**
     * Locate a file in the tree. Returns the file if found or false.
     *
     * @param Directory $tree
     * @param string $request
     *
     * @return false|Tree\Content|Tree\Raw
     */
    public static function getFile($tree, $request)
    {
        $request = explode('/', $request);
        foreach ($request as $node) {
            // If the element we're in currently is not a
            // directory, we failed to find the requested file
            if (!$tree instanceof Directory) {
                return false;
            }

            // Some relative paths may start with ./
            if ($node == '.') {
                continue;
            }

            if ($node == '..') {
                $tree = $tree->getParent();

                continue;
            }

            // if the node exists in the current request tree,
            // change the $tree variable to reference the new
            // node and proceed to the next url part
            if (isset($tree->getEntries()[$node])) {
                $tree = $tree->getEntries()[$node];

                continue;
            }

            // We try a second time by decoding the url
            $node = DauxHelper::urlSlug(urldecode($node));
            if (isset($tree->getEntries()[$node])) {
                $tree = $tree->getEntries()[$node];

                continue;
            }

            // if the node doesn't exist, we can try
            // two variants of the requested file:
            // with and w/o the .html extension
            foreach (static::getFilenames($tree->getConfig(), $node) as $filename) {
                if (isset($tree->getEntries()[$filename])) {
                    $tree = $tree->getEntries()[$filename];

                    continue 2;
                }
            }

            // At this stage, we're in a directory, but no
            // sub-item matches, so the current node must
            // be an index page or we failed
            if ($node !== 'index' && $node !== 'index.html') {
                return false;
            }

            return $tree->getIndexPage();
        }

        // If the entry we found is not a directory, we're done
        if (!$tree instanceof Directory) {
            return $tree;
        }

        if ($index = $tree->getIndexPage()) {
            return $index;
        }

        return false;
    }

    private static function slugBase($slug)
    {
        // Convert to ASCII
        if (function_exists('transliterator_transliterate')) {
            $slug = transliterator_transliterate('Any-Latin; NFD; [:Nonspacing Mark:] Remove; NFC;', $slug);
        }

        if (function_exists('iconv')) {
            $slug = iconv('utf-8', 'ASCII//TRANSLIT//IGNORE', $slug);
        }

        // Remove unsupported characters
        return preg_replace('/[^\x20-\x7E]/u', '', $slug);
    }

    /**
     * Generate a URL friendly "slug" from a given string.
     *
     * Taken from Stringy
     *
     * @param  string $title
     * @param mixed $slug
     *
     * @return string
     */
    public static function urlSlug($slug)
    {
        $slug = static::slugBase($slug);

        $separator = '_';
        // Convert all dashes into underscores
        $slug = preg_replace('![' . preg_quote('-') . ']+!u', $separator, $slug);

        // Remove all characters that are not valid in a URL:
        // $-_.+!*'(), separator, letters, numbers, or whitespace.
        $slug = preg_replace('![^-' . preg_quote($separator) . '\!\'\(\),\.\+\*\$\pL\pN\s]+!u', '', $slug);

        // Replace all separator characters and whitespace by a single separator
        $slug = preg_replace('![_\s]+!u', $separator, $slug);

        return trim($slug, $separator);
    }

    public static function linkSlug($slug)
    {
        $slug = static::slugBase($slug);

        $separator = '-';

        // Replace characters other than letters, numbers, and marks with a separator.
        $slug = \preg_replace('/[^\p{L}\p{Nd}\p{Nl}\p{M}\s_-]+/u', $separator, $slug) ?? $slug;

        // Replace all separator characters and whitespace by a single separator
        $slug = preg_replace('/[-_\s]+/u', $separator, $slug);

        // Convert to lowercase
        $slug = \mb_strtolower($slug);

        return trim($slug, $separator);
    }

    /**
     * @param string $from
     * @param string $to
     *
     * @return string
     */
    public static function getRelativePath($from, $to)
    {
        // some compatibility fixes for Windows paths
        $from = is_dir($from) ? rtrim($from, '\/') . '/' : $from;
        $to = is_dir($to) ? rtrim($to, '\/') . '/' : $to;
        $from = str_replace('\\', '/', $from);
        $to = str_replace('\\', '/', $to);

        $from = explode('/', $from);
        $to = explode('/', $to);
        $relPath = $to;

        foreach ($from as $depth => $dir) {
            // find first non-matching dir
            if ($dir === $to[$depth]) {
                // ignore this directory
                array_shift($relPath);
            } else {
                // get number of remaining dirs to $from
                $remaining = count($from) - $depth;
                if ($remaining > 1) {
                    // add traversals up to first matching dir
                    $padLength = (count($relPath) + $remaining - 1) * -1;
                    $relPath = array_pad($relPath, $padLength, '..');

                    break;
                }
            }
        }

        return implode('/', $relPath);
    }

    public static function isAbsolutePath($path)
    {
        if (!is_string($path)) {
            $mess = sprintf('String expected but was given %s', gettype($path));

            throw new \InvalidArgumentException($mess);
        }

        if (!ctype_print($path)) {
            $mess = 'Path can NOT have non-printable characters or be empty';

            throw new \DomainException($mess);
        }

        // Optional wrapper(s).
        $regExp = '%^(?<wrappers>(?:[[:print:]]{2,}://)*)';

        // Optional root prefix.
        $regExp .= '(?<root>(?:[[:alpha:]]:/|/)?)';

        // Actual path.
        $regExp .= '(?<path>(?:[[:print:]]*))$%';

        $parts = [];
        if (!preg_match($regExp, $path, $parts)) {
            $mess = sprintf('Path is NOT valid, was given %s', $path);

            throw new \DomainException($mess);
        }

        return '' !== $parts['root'];
    }

    public static function getAbsolutePath($path)
    {
        if (DauxHelper::isAbsolutePath($path)) {
            return $path;
        }

        return getcwd() . '/' . $path;
    }

    public static function is($path, $type)
    {
        return ($type == 'dir') ? is_dir($path) : file_exists($path);
    }

    /**
     * @param Config $config
     * @param string $url
     *
     * @return Entry
     *
     * @throws LinkNotFoundException
     */
    public static function resolveInternalFile($config, $url)
    {
        $triedAbsolute = false;

        // Legacy absolute paths could start with
        // "!" In this case we will try to find
        // the file starting at the root
        if ($url[0] == '!' || $url[0] == '/') {
            $url = ltrim($url, '!/');

            if ($file = DauxHelper::getFile($config->getTree(), $url)) {
                return $file;
            }

            $triedAbsolute = true;
        }

        // Seems it's not an absolute path or not found,
        // so we'll continue with the current folder
        if ($file = DauxHelper::getFile($config->getCurrentPage()->getParent(), $url)) {
            return $file;
        }

        // If we didn't already try it, we'll
        // do a pass starting at the root
        if (!$triedAbsolute && $file = DauxHelper::getFile($config->getTree(), $url)) {
            return $file;
        }

        throw new LinkNotFoundException("Could not locate file '$url'");
    }

    public static function isValidUrl($url)
    {
        return !empty($url) && $url[0] != '#';
    }

    public static function isExternalUrl($url)
    {
        return preg_match('#^(?:[a-z]+:)?//|^mailto:#', $url);
    }

    public static function isDataUrl($url)
    {
        return preg_match('#^data:image/#', $url);
    }
}
