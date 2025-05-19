<?php namespace Todaymade\Daux\ContentTypes;

use Todaymade\Daux\Exception;
use Todaymade\Daux\Tree\Content;

class ContentTypeHandler
{
    /**
     * @var ContentType[]
     */
    protected $types;

    /**
     * @param ContentType[] $types
     */
    public function __construct($types)
    {
        $this->types = array_reverse($types);
    }

    /**
     * Get all valid content file extensions.
     *
     * @return string[]
     */
    public function getContentExtensions()
    {
        $extensions = [];
        foreach ($this->types as $type) {
            $extensions = array_merge($extensions, $type->getExtensions());
        }

        return array_unique($extensions);
    }

    /**
     * Get the ContentType able to handle this node.
     *
     * @return ContentType
     */
    public function getType(Content $node)
    {
        $path = $node->getPath() ?: $node->getName();
        $extension = pathinfo($path, PATHINFO_EXTENSION);

        foreach ($this->types as $type) {
            if (in_array($extension, $type->getExtensions())) {
                return $type;
            }
        }

        throw new Exception("no contentType found for $path");
    }
}
