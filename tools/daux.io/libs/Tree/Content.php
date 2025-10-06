<?php namespace Todaymade\Daux\Tree;

use League\CommonMark\Extension\FrontMatter\Data\SymfonyYamlFrontMatterParser;
use League\CommonMark\Extension\FrontMatter\Exception\InvalidFrontMatterException;
use League\CommonMark\Extension\FrontMatter\FrontMatterParser;
use Todaymade\Daux\Exception;

class Content extends ContentAbstract
{
    protected string $content;

    protected ?Content $previous;

    protected ?Content $next;

    protected array $attributes;

    protected bool $manuallySetContent = false;

    public function __construct(Directory $parent, $uri, \SplFileInfo $info = null)
    {
        parent::__construct($parent, $uri, $info);
        $this->previous = null;
        $this->next = null;
    }

    protected function getFrontMatter()
    {
        if ($this->manuallySetContent) {
            $content = $this->content;
        } elseif (!$this->getPath()) {
            throw new Exception('Empty content');
        } else {
            $content = file_get_contents($this->getPath());
        }

        // Remove BOM if it's present
        if (substr($content, 0, 3) == "\xef\xbb\xbf") {
            $content = substr($content, 3);
        }

        $frontMatterParser = new FrontMatterParser(new SymfonyYamlFrontMatterParser());

        return $frontMatterParser->parse($content);
    }

    public function getContent(): string
    {
        if (!isset($this->attributes)) {
            $this->parseAttributes();
        }

        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->manuallySetContent = true;
        $this->content = $content;
    }

    /**
     * @return Content
     */
    public function getPrevious(): ?Content
    {
        return $this->previous;
    }

    public function setPrevious(Content $previous)
    {
        $this->previous = $previous;
    }

    /**
     * @return Content
     */
    public function getNext(): ?Content
    {
        return $this->next;
    }

    public function setNext(Content $next)
    {
        $this->next = $next;
    }

    public function isIndex()
    {
        $indexKey = $this->parent->getConfig()->getIndexKey();

        return $this->uri == $indexKey;
    }

    public function getTitle(): string
    {
        if ($title = $this->getAttribute('title')) {
            return $title;
        }

        return parent::getTitle();
    }

    protected function parseAttributes()
    {
        // We set an empty array first to
        // avoid a loop when "parseAttributes"
        // is called in "getContent"
        $this->attributes = [];

        try {
            $document = $this->getFrontMatter();
            $frontMatter = $document->getFrontMatter();
            $this->attributes = array_replace_recursive($this->attributes, $frontMatter ?: []);
            $this->content = $document->getContent();
        } catch (InvalidFrontMatterException $e) {
            $file = $this->getPath();
            if (!$file) {
                $file = $this->getUrl();
            }

            throw new Exception('Could not parse front matter in "' . $file . '"', 0, $e);
        }
    }

    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * Get one or all attributes for the content.
     *
     * @param null|string $key
     *
     * @return null|array|mixed
     */
    public function getAttribute($key = null)
    {
        if (!isset($this->attributes)) {
            $this->parseAttributes();
        }

        if (is_null($key)) {
            return $this->attributes;
        }

        if (!array_key_exists($key, $this->attributes)) {
            return null;
        }

        return $this->attributes[$key];
    }

    public function dump()
    {
        $dump = parent::dump();

        $dump['prev'] = $this->getPrevious() ? $this->getPrevious()->getUrl() : '';
        $dump['next'] = $this->getNext() ? $this->getNext()->getUrl() : '';
        $dump['attributes'] = $this->getAttribute();

        return $dump;
    }
}
