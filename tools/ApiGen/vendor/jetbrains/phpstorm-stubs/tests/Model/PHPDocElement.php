<?php

namespace StubTests\Model;

use Exception;
use phpDocumentor\Reflection\DocBlock\Tags\Deprecated;
use phpDocumentor\Reflection\DocBlock\Tags\Generic;
use phpDocumentor\Reflection\DocBlock\Tags\Link;
use phpDocumentor\Reflection\DocBlock\Tags\Param;
use phpDocumentor\Reflection\DocBlock\Tags\Return_;
use phpDocumentor\Reflection\DocBlock\Tags\See;
use phpDocumentor\Reflection\DocBlock\Tags\Since;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use PhpParser\Node;
use StubTests\Model\Tags\RemovedTag;
use StubTests\Parsers\DocFactoryProvider;

trait PHPDocElement
{
    /**
     * @var Link[]
     */
    public $links = [];

    /**
     * @var string
     */
    public $phpdoc = '';

    /**
     * @var See[]
     */
    public $see = [];

    /**
     * @var Since[]
     */
    public $sinceTags = [];

    /**
     * @var Deprecated[]
     */
    public $deprecatedTags = [];

    /**
     * @var RemovedTag[]
     */
    public $removedTags = [];

    /**
     * @var Param[]
     */
    public $paramTags = [];

    /**
     * @var Return_[]
     */
    public $returnTags = [];

    /**
     * @var Var_[]
     */
    public $varTags = [];

    /**
     * @var string[]
     */
    public $tagNames = [];

    /**
     * @var bool
     */
    public $hasInheritDocTag = false;

    /**
     * @var bool
     */
    public $hasInternalMetaTag = false;
    public $templateTypes = null;

    protected function collectTags(Node $node)
    {
        if ($node->getDocComment() !== null) {
            try {
                $text = $node->getDocComment()->getText();
                $text = preg_replace("/int\<\w+,\s*\w+\>/", "int", $text);
                $this->phpdoc = $text;
                $phpDoc = DocFactoryProvider::getDocFactory()->create($text);
                $tags = $phpDoc->getTags();
                foreach ($tags as $tag) {
                    $this->tagNames[] = $tag->getName();
                }
                $this->paramTags = $phpDoc->getTagsByName('param');
                $this->returnTags = $phpDoc->getTagsByName('return');
                $this->varTags = $phpDoc->getTagsByName('var');
                $this->links = $phpDoc->getTagsByName('link');
                $this->see = $phpDoc->getTagsByName('see');
                $this->sinceTags = $phpDoc->getTagsByName('since');
                $this->deprecatedTags = $phpDoc->getTagsByName('deprecated');
                $this->removedTags = $phpDoc->getTagsByName('removed');
                $this->hasInternalMetaTag = $phpDoc->hasTag('meta');
                $this->hasInheritDocTag = $phpDoc->hasTag('inheritdoc') || $phpDoc->hasTag('inheritDoc') ||
                    stripos($phpDoc->getSummary(), 'inheritdoc') > 0;
                $this->templateTypes = array_map(
                    function (Generic $tag) {
                        return preg_split("/\W/", $tag->getDescription()->getBodyTemplate())[0];
                    },
                    $phpDoc->getTagsByName('template')
                );
            } catch (Exception $e) {
                $this->parseError = $e;
            }
        }
    }
}
