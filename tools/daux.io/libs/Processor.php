<?php namespace Todaymade\Daux;

use League\CommonMark\Environment\Environment;
use Symfony\Component\Console\Output\OutputInterface;
use Todaymade\Daux\Tree\Root;

class Processor
{
    protected Daux $daux;

    protected OutputInterface $output;

    protected int $width;

    public function __construct(Daux $daux, OutputInterface $output, int $width)
    {
        $this->daux = $daux;
        $this->output = $output;
        $this->width = $width;
    }

    /**
     * With this connection point, you can transform
     * the tree as you want, move pages, modify
     * pages and even add new ones.
     */
    public function manipulateTree(Root $root)
    {
    }

    /**
     * This connection point provides
     * a way to extend the Markdown
     * parser and renderer.
     */
    public function extendCommonMarkEnvironment(Environment $environment)
    {
    }

    /**
     * Provide new generators with this extension point. You
     * can simply return an array, the key is the format's
     * name, the value is a class name implementing the
     * `Todaymade\Daux\Format\Base\Generator` contract.
     * You can also replace base generators.
     *
     * @return string[]
     */
    public function addGenerators()
    {
        return [];
    }

    /**
     * Provide new content Types to be used during the generation
     * phase, with this you can change the markdown parser or add
     * a completely different file type.
     *
     * @return \Todaymade\Daux\ContentTypes\ContentType[]
     */
    public function addContentType()
    {
        return [];
    }
}
