<?php namespace Todaymade\Daux\Tree;

use Todaymade\Daux\Config;

class Root extends Directory
{
    protected Config $config;

    protected ?Entry $activeNode;

    /**
     * The root doesn't have a parent.
     */
    public function __construct(Config $config)
    {
        $this->setConfig($config);

        $this->setUri($config->getDocumentationDirectory());
        $this->path = $config->getDocumentationDirectory();

        $this->activeNode = null;
    }

    public function getConfig(): Config
    {
        return $this->config;
    }

    public function setConfig(Config $config)
    {
        $this->config = $config;
    }

    public function isHotPath(Entry $node = null): bool
    {
        if ($node == null) {
            return true;
        }

        if ($this->activeNode == null) {
            return false;
        }

        if ($node == $this->activeNode) {
            return true;
        }

        foreach ($this->activeNode->getParents() as $parent) {
            if ($node === $parent) {
                return true;
            }
        }

        return false;
    }

    public function setActiveNode(Entry $node)
    {
        $this->activeNode = $node;
    }
}
