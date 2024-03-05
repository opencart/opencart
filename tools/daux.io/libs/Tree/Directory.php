<?php namespace Todaymade\Daux\Tree;

use Todaymade\Daux\Config;

class Directory extends Entry implements \ArrayAccess, \IteratorAggregate
{
    /** @var Entry[] */
    protected $children = [];

    protected Content $firstPage;

    public function sort()
    {
        // Separate the values into buckets to sort them separately
        $buckets = [
            'up_numeric' => [],
            'up' => [],
            'index' => [],
            'numeric' => [],
            'normal' => [],
            'down_numeric' => [],
            'down' => [],
        ];

        foreach ($this->children as $key => $entry) {
            $name = $entry->getNameForSort();

            if (!$name) {
                continue;
            }

            if ($name == 'index' || $name == '_index') {
                $buckets['index'][$key] = $entry;

                continue;
            }

            if ($name[0] == '-') {
                if (is_numeric($name[1])) {
                    $exploded = explode('_', $name);
                    $buckets['down_numeric'][abs((int) substr($exploded[0], 1))][$key] = $entry;

                    continue;
                }

                $buckets['down'][$key] = $entry;

                continue;
            }

            if ($name[0] == '+') {
                if (is_numeric($name[1])) {
                    $exploded = explode('_', $name);
                    $buckets['up_numeric'][abs((int) substr($exploded[0], 1))][$key] = $entry;

                    continue;
                }

                $buckets['up'][$key] = $entry;

                continue;
            }

            if (is_numeric($name[0])) {
                $exploded = explode('_', $name);
                $buckets['numeric'][abs((int) $exploded[0])][$key] = $entry;

                continue;
            }

            $buckets['normal'][$key] = $entry;
        }

        $final = [];
        foreach ($buckets as $name => $bucket) {
            if (substr($name, -7) == 'numeric') {
                ksort($bucket);
                foreach ($bucket as $subBucket) {
                    $final = $this->sortBucket($subBucket, $final);
                }
            } else {
                $final = $this->sortBucket($bucket, $final);
            }
        }

        $this->children = $final;
    }

    private function sortBucket($bucket, $final)
    {
        uasort($bucket, function (Entry $a, Entry $b) {
            return strcasecmp($a->getNameForSort(), $b->getNameForSort());
        });

        foreach ($bucket as $key => $value) {
            $final[$key] = $value;
        }

        return $final;
    }

    /**
     * @return Entry[]
     */
    public function getEntries()
    {
        return $this->children;
    }

    public function addChild(Entry $entry): void
    {
        $this->children[$entry->getUri()] = $entry;

        // TODO :: should we somehow change the child's parent when adding it ?
        // TODO :: should we remove child from previous parent ?
    }

    public function removeChild(Entry $entry): void
    {
        unset($this->children[$entry->getUri()]);
    }

    public function getConfig(): Config
    {
        if (!$this->parent) {
            throw new TreeException('Could not retrieve configuration. Are you sure that your tree has a Root ?');
        }

        return $this->parent->getConfig();
    }

    public function getLocalIndexPage()
    {
        $indexKey = $this->getConfig()->getIndexKey();

        if (isset($this->children[$indexKey])) {
            return $this->children[$indexKey];
        }

        return false;
    }

    public function getIndexPage(): ?Content
    {
        $indexPage = $this->getLocalIndexPage();

        if ($indexPage instanceof Content) {
            return $indexPage;
        }

        if ($this->getConfig()->shouldInheritIndex() && $firstPage = $this->seekFirstPage()) {
            return $firstPage;
        }

        return null;
    }

    /**
     * Seek the first available page from descendants.
     */
    public function seekFirstPage(): ?Content
    {
        if ($this instanceof self) {
            $indexKey = $this->getConfig()->getIndexKey();
            if (isset($this->children[$indexKey]) && $this->children[$indexKey] instanceof Content) {
                return $this->children[$indexKey];
            }
            foreach ($this->children as $node) {
                if ($node instanceof Content) {
                    return $node;
                }
                if ($node instanceof self
                && strpos($node->getUri(), '.') !== 0
                && $childNode = $node->seekFirstPage()) {
                    return $childNode;
                }
            }
        }

        return null;
    }

    public function getFirstPage(): ?Content
    {
        if (isset($this->firstPage)) {
            return $this->firstPage;
        }

        // First we try to find a real page
        foreach ($this->getEntries() as $node) {
            if ($node instanceof Content) {
                if ($this instanceof Root && $this->getIndexPage() == $node) {
                    // The homepage should not count as first page
                    continue;
                }

                $this->setFirstPage($node);

                return $node;
            }
        }

        // If we can't find one we check in the sub-directories
        foreach ($this->getEntries() as $node) {
            if ($node instanceof self && $page = $node->getFirstPage()) {
                $this->setFirstPage($page);

                return $page;
            }
        }

        return null;
    }

    public function setFirstPage(Content $firstPage)
    {
        $this->firstPage = $firstPage;
    }

    /**
     * Used when creating the navigation.
     * Hides folders without showable content.
     */
    public function hasContent(): bool
    {
        foreach ($this->getEntries() as $node) {
            if ($node instanceof Content) {
                return true;
            }
            if ($node instanceof self && $node->hasContent()) {
                return true;
            }
        }

        return false;
    }

    public function dump()
    {
        $dump = parent::dump();

        $dump['index'] = $this->getIndexPage() ? $this->getIndexPage()->getUrl() : '';
        $dump['first'] = $this->getFirstPage() ? $this->getFirstPage()->getUrl() : '';

        foreach ($this->getEntries() as $entry) {
            $dump['children'][] = $entry->dump();
        }

        return $dump;
    }

    /**
     * Whether a offset exists.
     *
     * @param mixed $offset an offset to check for
     *
     * @return bool true on success or false on failure
     */
    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->children);
    }

    /**
     * Offset to retrieve.
     *
     * @param mixed $offset the offset to retrieve
     *
     * @return Entry can return all value types
     */
    public function offsetGet($offset): mixed
    {
        return $this->children[$offset];
    }

    /**
     * Offset to set.
     *
     * @param mixed $offset the offset to assign the value to
     * @param Entry $value the value to set
     */
    public function offsetSet($offset, $value): void
    {
        if (!$value instanceof Entry) {
            throw new TreeException('The value is not of type Entry');
        }

        $this->addChild($value);
    }

    /**
     * Offset to unset.
     *
     * @param string $offset the offset to unset
     */
    public function offsetUnset($offset): void
    {
        unset($this->children[$offset]);
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->children);
    }
}
