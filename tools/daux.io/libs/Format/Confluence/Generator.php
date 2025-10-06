<?php namespace Todaymade\Daux\Format\Confluence;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Todaymade\Daux\Config as GlobalConfig;
use Todaymade\Daux\Console\RunAction;
use Todaymade\Daux\Daux;
use Todaymade\Daux\Tree\Content;
use Todaymade\Daux\Tree\Directory;

class Generator implements \Todaymade\Daux\Format\Base\Generator
{
    use RunAction;

    protected string $prefix;

    protected Daux $daux;

    protected Api $api;

    public function __construct(Daux $daux, Api $api = null)
    {
        $this->daux = $daux;

        $confluence = $this->checkConfiguration();

        if (!$api) {
            $api = new Api($confluence->getBaseUrl(), $confluence->getUser(), $confluence->getPassword());
        }

        $this->api = $api;
    }

    protected function checkConfiguration(): Config
    {
        $config = $this->daux->getConfig();
        $confluence = $config->getConfluenceConfiguration();

        $mandatory = ['base_url', 'user', 'pass', 'prefix'];
        $errors = [];
        foreach ($mandatory as $key) {
            if (!$confluence->hasValue($key)) {
                $errors[] = $key;
            }
        }

        if (count($errors)) {
            $message = "The following options are mandatory for confluence : '" . implode("', '", $errors) . "'";

            throw new ConfluenceConfigurationException($message);
        }

        if (!$confluence->hasAncestorId() && !$confluence->hasRootId()) {
            throw new ConfluenceConfigurationException(
                "You must specify an 'ancestor_id' or a 'root_id' for confluence."
            );
        }

        return $confluence;
    }

    /**
     * @return array
     */
    public function getContentTypes()
    {
        return [
            new ContentTypes\Markdown\ContentType($this->daux->getConfig()),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function generateAll(InputInterface $input, OutputInterface $output, $width)
    {
        $config = $this->daux->getConfig();

        $confluence = $config->getConfluenceConfiguration();
        $this->prefix = trim($confluence->getPrefix()) . ' ';
        if ($this->prefix == ' ') {
            $this->prefix = '';
        }

        $tree = $this->runAction(
            'Generating Tree ...',
            $width,
            function () use ($config) {
                $generatedTree = $this->generateRecursive($this->daux->tree, $config);
                $generatedTree['title'] = $this->prefix . $config->getTitle();

                return $generatedTree;
            }
        );

        $output->writeln('Start Publishing...');

        $publisher = new Publisher($confluence, $this->api, $output, $width);
        $publisher->publish($tree);
    }

    private function generateRecursive(Directory $tree, GlobalConfig $config, $baseUrl = '')
    {
        $final = ['title' => $this->prefix . $tree->getTitle()];
        $config['base_url'] = $baseUrl;

        if ($baseUrl !== '') {
            $config->setEntryPage($tree->getFirstPage());
        }
        foreach ($tree->getEntries() as $key => $node) {
            if ($node instanceof Directory) {
                $final['children'][$this->prefix . $node->getTitle()] = $this->generateRecursive(
                    $node,
                    $config,
                    '../' . $baseUrl
                );
            } elseif ($node instanceof Content) {
                $config->setRequest($node->getUrl());

                $contentType = $this->daux->getContentTypeHandler()->getType($node);

                $data = [
                    'title' => $this->prefix . $node->getTitle(),
                    'file' => $node,
                    'page' => ContentPage::fromFile($node, $config, $contentType),
                ];

                if ($key == 'index.html') {
                    $final['title'] = $this->prefix . $tree->getTitle();
                    $final['file'] = $node;
                    $final['page'] = $data['page'];
                } else {
                    $final['children'][$data['title']] = $data;
                }
            }
        }

        return $final;
    }
}
