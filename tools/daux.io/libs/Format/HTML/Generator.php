<?php namespace Todaymade\Daux\Format\HTML;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Todaymade\Daux\Config as GlobalConfig;
use Todaymade\Daux\Console\RunAction;
use Todaymade\Daux\Daux;
use Todaymade\Daux\DauxHelper;
use Todaymade\Daux\Format\Base\LiveGenerator;
use Todaymade\Daux\Format\HTML\ContentTypes\Markdown\ContentType;
use Todaymade\Daux\GeneratorHelper;
use Todaymade\Daux\Tree\ComputedRaw;
use Todaymade\Daux\Tree\Directory;
use Todaymade\Daux\Tree\Entry;
use Todaymade\Daux\Tree\Raw;

class Generator implements \Todaymade\Daux\Format\Base\Generator, LiveGenerator
{
    use RunAction;
    use HTMLUtils;

    protected Daux $daux;

    protected Template $templateRenderer;

    protected $indexedPages = [];

    public function __construct(Daux $daux)
    {
        $config = $daux->getConfig();

        $this->daux = $daux;
        $this->templateRenderer = new Template($config);
        $config->setTemplateRenderer($this->templateRenderer);
    }

    /**
     * @return array
     */
    public function getContentTypes()
    {
        return [
            'markdown' => new ContentType($this->daux->getConfig()),
        ];
    }

    public function generateAll(InputInterface $input, OutputInterface $output, $width)
    {
        $destination = $input->getOption('destination');

        $config = $this->daux->getConfig();
        if (is_null($destination)) {
            $destination = $config->getLocalBase() . DIRECTORY_SEPARATOR . 'static';
        }

        $this->runAction(
            'Copying Static assets ...',
            $width,
            function () use ($destination, $config) {
                $this->ensureEmptyDestination($destination);

                $this->copyThemes($destination, $config->getThemesPath());
            }
        );

        $output->writeLn('Generating ...');

        $this->generateRecursive($this->daux->tree, $destination, $config, $output, $width, $config->getHTML()->hasSearch());

        GeneratorHelper::copyRecursive(
            $config->getLocalBase() . DIRECTORY_SEPARATOR . 'daux_libraries' . DIRECTORY_SEPARATOR,
            $destination . DIRECTORY_SEPARATOR . 'daux_libraries'
        );

        if ($config->getHTML()->hasSearch()) {
            file_put_contents(
                $destination . DIRECTORY_SEPARATOR . 'daux_search_index.js',
                'load_search_index(' . json_encode(['pages' => $this->indexedPages]) . ');'
            );

            if (json_last_error()) {
                echo "Could not write search index: \n" . json_last_error_msg() . "\n";
            }
        }
    }

    /**
     * Remove HTML tags, including invisible text such as style and
     * script code, and embedded objects.  Add line breaks around
     * block-level tags to prevent word joining after tag removal.
     * Also collapse whitespace to single space and trim result.
     * modified from: http://nadeausoftware.com/articles/2007/09/php_tip_how_strip_html_tags_web_page.
     *
     * @param string $text
     *
     * @return string
     */
    private function sanitize($text)
    {
        $space = ' ';
        $addNewline = "\n\$0";

        $text = preg_replace(
            [
                // Remove invisible content
                '@<head[^>]*?>.*?</head>@siu',
                '@<style[^>]*?>.*?</style>@siu',
                '@<script[^>]*?.*?</script>@siu',
                '@<object[^>]*?.*?</object>@siu',
                '@<embed[^>]*?.*?</embed>@siu',
                '@<applet[^>]*?.*?</applet>@siu',
                '@<noframes[^>]*?.*?</noframes>@siu',
                '@<noscript[^>]*?.*?</noscript>@siu',
                '@<noembed[^>]*?.*?</noembed>@siu',
                // Add line breaks before and after blocks
                '@</?((address)|(blockquote)|(center)|(del))@iu',
                '@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
                '@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
                '@</?((table)|(th)|(td)|(caption))@iu',
                '@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
                '@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
                '@</?((frameset)|(frame)|(iframe))@iu',
            ],
            [
                $space, $space, $space, $space, $space, $space, $space, $space, $space,
                $addNewline, $addNewline, $addNewline, $addNewline, $addNewline,
                $addNewline, $addNewline, $addNewline,
            ],
            $text
        );

        $text = trim(preg_replace('/\s+/', ' ', strip_tags($text)));

        // Sometimes strings are detected as invalid UTF-8 and json_encode can't treat them
        // iconv can fix those strings
        return iconv('UTF-8', 'UTF-8//IGNORE', $text);
    }

    /**
     * Recursively generate the documentation.
     *
     * @param string $outputDir
     * @param OutputInterface $output
     * @param int $width
     * @param bool $indexPages
     * @param string $baseUrl
     *
     * @throws \Exception
     */
    private function generateRecursive(Directory $tree, $outputDir, GlobalConfig $config, $output, $width, $indexPages, $baseUrl = '')
    {
        DauxHelper::rebaseConfiguration($config, $baseUrl);

        if ($baseUrl !== '' && !$config->hasEntryPage()) {
            $config->setEntryPage($tree->getFirstPage());
        }

        foreach ($tree->getEntries() as $key => $node) {
            if ($node instanceof Directory) {
                $newOutputDir = $outputDir . DIRECTORY_SEPARATOR . $key;
                mkdir($newOutputDir);
                $this->generateRecursive($node, $newOutputDir, $config, $output, $width, $indexPages, '../' . $baseUrl);

                // Rebase configuration again as $config is a shared object
                DauxHelper::rebaseConfiguration($config, $baseUrl);
            } else {
                $this->runAction(
                    '- ' . $node->getUrl(),
                    $width,
                    function () use ($node, $outputDir, $key, $config, $indexPages) {
                        if ($node instanceof Raw) {
                            copy($node->getPath(), $outputDir . DIRECTORY_SEPARATOR . $key);

                            return;
                        }

                        $this->daux->tree->setActiveNode($node);

                        $generated = $this->generateOne($node, $config);
                        file_put_contents($outputDir . DIRECTORY_SEPARATOR . $key, $generated->getContent());
                        if ($indexPages) {
                            $this->indexedPages[] = [
                                'title' => $node->getTitle(),
                                'text' => $this->sanitize($generated->getPureContent()),
                                'tags' => '',
                                'url' => $node->getUrl(),
                            ];
                        }
                    }
                );
            }
        }
    }

    /**
     * @return \Todaymade\Daux\Format\Base\Page
     */
    public function generateOne(Entry $node, GlobalConfig $config)
    {
        if ($node instanceof Raw) {
            return new RawPage($node->getPath());
        }

        if ($node instanceof ComputedRaw) {
            return new ComputedRawPage($node);
        }

        $config->setRequest($node->getUrl());

        $contentPage = ContentPage::fromFile($node, $config, $this->daux->getContentTypeHandler()->getType($node));
        $contentPage->templateRenderer = $this->templateRenderer;

        return $contentPage;
    }
}
