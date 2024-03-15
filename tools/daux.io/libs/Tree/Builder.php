<?php namespace Todaymade\Daux\Tree;

use Todaymade\Daux\DauxHelper;

class Builder
{
    protected static $ignoredPaths = [
        // Popular VCS Systems
        '.svn', '_svn', 'CVS', '_darcs', '.arch-params', '.monotone', '.bzr', '.git', '.hg',

        // Operating system files
        '.DS_Store', 'Thumbs.db',
    ];

    protected static function isIgnored(\SplFileInfo $file, $ignore)
    {
        $filename = $file->getFilename();

        if (in_array($filename, static::$ignoredPaths)) {
            return true;
        }

        if (array_key_exists('folders', $ignore) && $file->isDir() && in_array($filename, $ignore['folders'])) {
            return true;
        }

        if (array_key_exists('files', $ignore) && !$file->isDir() && in_array($filename, $ignore['files'])) {
            return true;
        }

        return false;
    }

    /**
     * Get name for a file.
     *
     * @param string $path
     *
     * @return string
     */
    protected static function getName($path)
    {
        return pathinfo($path, PATHINFO_FILENAME);
    }

    /**
     * Build the initial tree.
     *
     * @param Directory $node
     * @param array $ignore
     */
    public static function build($node, $ignore)
    {
        $it = new \FilesystemIterator($node->getPath());

        if ($node instanceof Root) {
            // Ignore config.json in the root directory
            $ignore['files'][] = 'config.json';
        }

        /** @var \SplFileInfo $file */
        foreach ($it as $file) {
            if (static::isIgnored($file, $ignore)) {
                continue;
            }

            if ($file->isDir()) {
                $title = DauxHelper::urlSlug(static::removeSortingInformations($file->getFilename()));
                $new = new Directory($node, $title, $file);
                $new->setName(static::getName($file->getPathName()));
                $new->setTitle(str_replace('_', ' ', static::removeSortingInformations($new->getName())));
                static::build($new, $ignore);

                $index = $new->getLocalIndexPage();
                if ($index && $index->getTitle() != $title) {
                    $new->setTitle($index->getTitle());
                }
            } else {
                static::createContent($node, $file);
            }
        }

        $node->sort();
    }

    /**
     * @return Content|Raw
     */
    public static function createContent(Directory $parent, \SplFileInfo $file)
    {
        $name = static::getName($file->getPathname());

        $config = $parent->getConfig();

        if (!in_array($file->getExtension(), $config->getValidContentExtensions())) {
            $uri = $file->getFilename();

            $entry = new Raw($parent, $uri, $file);
            $entry->setTitle(str_replace('_', ' ', static::removeSortingInformations($name)));
            $entry->setName($name);

            return $entry;
        }

        $uri = static::removeSortingInformations($name);
        $uri = DauxHelper::urlSlug($uri);
        if ($config->isStatic()) {
            $uri .= '.html';
        }

        $entry = new Content($parent, $uri, $file);

        if ($entry->getUri() == $config->getIndexKey()) {
            if ($parent instanceof Root) {
                $entry->setTitle($config->getTitle());
            } else {
                $entry->setTitle($parent->getTitle());
            }
        } else {
            $entry->setTitle(str_replace('_', ' ', static::removeSortingInformations($name)));
        }

        $entry->setName($name);

        return $entry;
    }

    /**
     * @param string $filename
     *
     * @return string
     */
    public static function removeSortingInformations($filename)
    {
        preg_match('/^[-+]?\d*_?(.*)/', $filename, $matches);

        // Remove the numeric part
        // of the filename, only if
        // there is something after
        return empty($matches[1]) ? $matches[0] : $matches[1];
    }

    /**
     * @param string $title
     *
     * @return Directory
     */
    public static function getOrCreateDir(Directory $parent, $title)
    {
        $slug = DauxHelper::urlSlug($title);

        if (array_key_exists($slug, $parent->getEntries())) {
            return $parent->getEntries()[$slug];
        }

        $dir = new Directory($parent, $slug);
        $dir->setTitle($title);

        return $dir;
    }

    /**
     * @param string $path
     *
     * @return ContentAbstract
     */
    public static function getOrCreatePage(Directory $parent, $path)
    {
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        // If the file doesn't have an extension, set .md as a default
        if ($extension == '') {
            $extension = 'md';
            $path .= '.md';
        }

        $raw = !in_array($extension, $parent->getConfig()->getValidContentExtensions());

        $title = $uri = $path;
        if (!$raw) {
            $title = static::getName($path);
            $uri = DauxHelper::urlSlug($title);
            if ($parent->getConfig()->isStatic()) {
                $uri .= '.html';
            }
        }

        if (array_key_exists($uri, $parent->getEntries())) {
            return $parent->getEntries()[$uri];
        }

        $page = $raw ? new ComputedRaw($parent, $uri) : new Content($parent, $uri);
        $page->setContent('-'); // set an almost empty content to avoid problems
        $page->setName($path);
        $page->setTitle($title);

        if ($title == 'index' || $title == '_index') {
            $page->setTitle($parent->getTitle());
        }

        return $page;
    }

    /**
     * Sort the tree recursively.
     */
    public static function sortTree(Directory $current)
    {
        $current->sort();
        foreach ($current->getEntries() as $entry) {
            if ($entry instanceof Directory) {
                Builder::sortTree($entry);
            }
        }
    }

    /**
     * Calculate next and previous for all pages.
     *
     * @param null|Content $prev
     *
     * @return null|Content
     */
    public static function finalizeTree(Directory $current, $prev = null)
    {
        foreach ($current->getEntries() as $entry) {
            if ($entry instanceof Directory) {
                $prev = Builder::finalizeTree($entry, $prev);
            } elseif ($entry instanceof Content) {
                if ($prev) {
                    $prev->setNext($entry);
                    $entry->setPrevious($prev);
                }

                $prev = $entry;
            }
        }

        return $prev;
    }
}
