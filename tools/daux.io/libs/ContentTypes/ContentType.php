<?php namespace Todaymade\Daux\ContentTypes;

use Todaymade\Daux\Config;
use Todaymade\Daux\Tree\Content;

interface ContentType
{
    public function __construct(Config $config);

    /**
     * Get the file extensions supported by this Content Type.
     *
     * @return string[]
     */
    public function getExtensions();

    /**
     * @param string $raw The raw text to render
     * @param Content $node The original node we are converting
     *
     * @return string The generated output
     */
    public function convert($raw, Content $node);
}
