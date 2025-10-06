<?php namespace Todaymade\Daux\Format\HTMLFile;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Todaymade\Daux\Console\RunAction;
use Todaymade\Daux\Daux;
use Todaymade\Daux\DauxHelper;
use Todaymade\Daux\Format\HTML\HTMLUtils;
use Todaymade\Daux\Format\HTML\Template;
use Todaymade\Daux\Format\HTMLFile\ContentTypes\Markdown\ContentType;

class Generator implements \Todaymade\Daux\Format\Base\Generator
{
    use RunAction;
    use HTMLUtils;

    protected Daux $daux;

    protected Template $templateRenderer;

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

    /**
     * {@inheritdoc}
     */
    public function generateAll(InputInterface $input, OutputInterface $output, $width)
    {
        $destination = $input->getOption('destination');

        $config = $this->daux->getConfig();
        if (is_null($destination)) {
            $destination = $config->getLocalBase() . DIRECTORY_SEPARATOR . 'static';
        }

        $this->runAction(
            'Cleaning destination folder ...',
            $width,
            function () use ($destination) {
                $this->ensureEmptyDestination($destination);
            }
        );

        // TODO :: make it possible to customize the single page theme
        $config['html']['theme'] = 'daux_singlepage';
        $config['html']['theme-variant'] = null;

        DauxHelper::rebaseConfiguration($config, '');

        $book = new Book($this->daux->tree, $config);

        $current = $this->daux->tree->getIndexPage();
        while ($current) {
            $this->runAction(
                'Generating ' . $current->getTitle(),
                $width,
                function () use ($book, $current, $config) {
                    $contentType = $this->daux->getContentTypeHandler()->getType($current);
                    $content = ContentPage::fromFile($current, $config, $contentType);
                    $content->templateRenderer = $this->templateRenderer;
                    $content = $content->getContent();
                    $book->addPage($current, $content);
                }
            );

            $current = $current->getNext();
        }

        $content = $book->generate();
        file_put_contents($input->getOption('destination') . '/file.html', $content);
    }
}
