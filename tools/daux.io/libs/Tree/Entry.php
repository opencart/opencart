<?php namespace Todaymade\Daux\Tree;

abstract class Entry
{
    protected ?string $title;

    protected ?string $name;

    protected ?string $uri;

    protected ?Directory $parent;

    protected ?\SplFileInfo $info;

    protected ?string $path;

    /**
     * @param string $uri
     * @param \SplFileInfo $info
     */
    public function __construct(Directory $parent, $uri, \SplFileInfo $info = null)
    {
        $this->title = null;
        $this->name = null;
        $this->setUri($uri);
        $parent->addChild($this);
        $this->parent = $parent;

        if ($info !== null) {
            $this->info = $info;
            $this->path = $info->getPathname();
        } else {
            $this->info = null;
            $this->path = null;
        }
    }

    public function getName(): ?string
    {
        return $this->name ?? null;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    public function getUri(): ?string
    {
        return $this->uri;
    }

    public function setUri($uri): void
    {
        if (isset($this->parent)) {
            $this->parent->removeChild($this);
        }

        $this->uri = $uri;

        if (isset($this->parent)) {
            $this->parent->addChild($this);
        }
    }

    public function getTitle(): ?string
    {
        return $this->title ?? null;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getNameForSort(): ?string
    {
        // In case of generated pages, the name might be empty.
        // Thus we are falling back to other solutions, otherwise the page would disappear from the tree.
        $name = $this->getName();
        if ($name) {
            return $name;
        }

        $title = $this->getTitle();
        if ($title) {
            return $title;
        }

        $uri = $this->getUri();
        if ($uri) {
            return $uri;
        }

        return null;
    }

    public function getParent(): ?Directory
    {
        return $this->parent ?? null;
    }

    /**
     * Return all parents starting with the root.
     *
     * @return Directory[]
     */
    public function getParents()
    {
        $parents = [];
        if ($this->parent && !$this->parent instanceof Root) {
            $parents = $this->parent->getParents();
            $parents[] = $this->parent;
        }

        return $parents;
    }

    /**
     * @return string
     */
    public function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * Get the path to the file from the root of the documentation.
     */
    public function getRelativePath(): string
    {
        $root = $this;
        while ($root->getParent() != null) {
            $root = $root->getParent();
        }

        return substr($this->path, strlen($root->getPath()) + 1);
    }

    public function getUrl(): string
    {
        $url = '';

        if ($this->getParent() && !$this->getParent() instanceof Root) {
            $url = $this->getParent()->getUrl() . '/' . $url;
        }

        $url .= $this->getUri();

        return $url;
    }

    public function dump()
    {
        return [
            'title' => $this->getTitle(),
            'type' => get_class($this),
            'name' => $this->getName(),
            'uri' => $this->getUri(),
            'url' => $this->getUrl(),
            'path' => $this->path,
        ];
    }

    public function isHotPath(Entry $node = null)
    {
        return $this->parent->isHotPath($node ?: $this);
    }
}
