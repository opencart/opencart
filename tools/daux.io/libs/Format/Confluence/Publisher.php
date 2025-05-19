<?php namespace Todaymade\Daux\Format\Confluence;

use Symfony\Component\Console\Output\OutputInterface;
use Todaymade\Daux\Console\RunAction;

class Publisher
{
    use RunAction;

    protected int $width;
    protected OutputInterface $output;
    protected Api $client;
    protected Config $confluence;

    public function __construct(Config $confluence, Api $client, OutputInterface $output, int $width)
    {
        $this->confluence = $confluence;
        $this->client = $client;
        $this->output = $output;
        $this->width = $width;
    }

    public function run($title, $closure)
    {
        return $this->runAction($title, $this->width, $closure);
    }

    public function diff($local, $remote, $level)
    {
        if ($remote == null) {
            $this->output->writeLn("$level- " . $local['title'] . ' <fg=green>(create)</>');
        } elseif ($local == null) {
            $this->output->writeLn("$level- " . $remote['title'] . ' <fg=red>(delete)</>');
        } else {
            $this->output->writeLn("$level- " . $local['title'] . ' <fg=blue>(update)</>');
        }

        if ($local && array_key_exists('children', $local)) {
            $remoteChildren = $remote && array_key_exists('children', $remote) ? $remote['children'] : [];
            foreach ($local['children'] as $title => $content) {
                $this->diff(
                    $content,
                    array_key_exists($title, $remoteChildren) ? $remoteChildren[$title] : null,
                    "$level  "
                );
            }
        }

        if ($remote && array_key_exists('children', $remote)) {
            $localChildren = $local && array_key_exists('children', $local) ? $local['children'] : [];
            foreach ($remote['children'] as $title => $content) {
                if (!array_key_exists($title, $localChildren)) {
                    $this->diff(null, $content, "$level  ");
                }
            }
        }
    }

    public function publish(array $tree)
    {
        $this->output->writeLn('Finding Root Page...');
        $published = $this->getRootPage($tree);

        $ancestorId = $published['ancestor_id'];

        $this->run(
            'Getting already published pages...',
            function () use (&$published) {
                if ($published != null) {
                    $published['children'] = $this->client->getList($published['id'], true);
                }
            }
        );

        if ($this->confluence->shouldPrintDiff()) {
            $this->output->writeLn('The following changes will be applied');
            $this->diff($tree, $published, '');

            return;
        }

        $published = $this->run(
            'Create placeholder pages...',
            function () use ($ancestorId, $tree, $published) {
                return $this->createRecursive($ancestorId, $tree, $published);
            }
        );

        $this->output->writeLn('Publishing updates...');
        $published = $this->updateRecursive($ancestorId, $tree, $published);

        $delete = new PublisherDelete($this->output, $this->confluence->shouldAutoDeleteOrphanedPages(), $this->client);
        $delete->handle($published);
    }

    protected function rootNotFound(string $rootTitle, array $pages)
    {
        $pageNotFound = "Could not find a page named '$rootTitle'";
        $configRecommendation = "To create the page automatically, add '\"create_root_if_missing\": true'"
        . " in the 'confluence' section of your Daux configuration.";

        if (empty($pages)) {
            throw new ConfluenceConfigurationException(
                "$pageNotFound with the specified ancestor_id. $configRecommendation"
            );
        }

        $pageNames = implode(
            "', '",
            array_map(function ($page) { return $page['title']; }, $pages)
        );

        throw new ConfluenceConfigurationException("$pageNotFound but found ['$pageNames']. $configRecommendation");
    }

    protected function configureSpace($page)
    {
        // We infer the Space from the root page
        $this->client->setSpace($page['space_key']);
        $this->confluence->setSpaceId($page['space_key']);
    }

    protected function getRootPage($tree)
    {
        if ($this->confluence->hasRootId()) {
            $root = $this->client->getPage($this->confluence->getRootId());
            $this->configureSpace($root);

            return $root;
        }

        $ancestorId = $this->confluence->getAncestorId();
        $pages = $this->client->getList($ancestorId);
        $rootTitle = $tree['title'];

        foreach ($pages as $page) {
            if ($page['title'] == $rootTitle) {
                $this->configureSpace($page);

                return $page;
            }
        }

        if ($this->confluence->createRootIfMissing()) {
            // We need to configure the space before we're able to create a page
            if (empty($pages)) {
                $ancestorPage = $this->client->getPage($ancestorId);
                $this->configureSpace($ancestorPage);
            } else {
                $this->configureSpace(current($pages));
            }

            $id = $this->client->createPage($ancestorId, $rootTitle, 'The content will come very soon !');

            return $this->client->getPage($id);
        }

        $this->rootNotFound($rootTitle, $pages);
    }

    protected function createPage($parentId, $entry, $published)
    {
        $this->output->writeLn('- ' . PublisherUtilities::niceTitle($entry['file']->getUrl()));
        $published['version'] = 1;
        $published['title'] = $entry['title'];
        $published['id'] = $this->client->createPage($parentId, $entry['title'], 'The content will come very soon !');

        return $published;
    }

    protected function createPlaceholderPage($parentId, $entry, $published)
    {
        $this->output->writeLn('- ' . $entry['title']);
        $published['version'] = 1;
        $published['title'] = $entry['title'];
        $published['id'] = $this->client->createPage($parentId, $entry['title'], '');

        return $published;
    }

    protected function recursiveWithCallback($parentId, $entry, $published, $callback)
    {
        $published = $callback($parentId, $entry, $published);

        if (!array_key_exists('children', $entry)) {
            return $published;
        }

        foreach ($entry['children'] as $child) {
            $pub = [];
            if (isset($published['children']) && array_key_exists($child['title'], $published['children'])) {
                $pub = $published['children'][$child['title']];
            }

            $published['children'][$child['title']] = $this->recursiveWithCallback(
                $published['id'],
                $child,
                $pub,
                $callback
            );
        }

        return $published;
    }

    protected function createRecursive($parentId, $entry, $published)
    {
        $callback = function ($parentId, $entry, $published) {
            // nothing to do if the ID already exists
            if (array_key_exists('id', $published)) {
                return $published;
            }

            if (array_key_exists('page', $entry)) {
                return $this->createPage($parentId, $entry, $published);
            }

            // If we have no $entry['page'] it means the page
            // doesn't exist, but to respect the hierarchy,
            // we need a blank page there
            return $this->createPlaceholderPage($parentId, $entry, $published);
        };

        return $this->recursiveWithCallback($parentId, $entry, $published, $callback);
    }

    protected function updateRecursive($parentId, $entry, $published)
    {
        $callback = function ($parentId, $entry, $published) {
            if (array_key_exists('id', $published) && array_key_exists('page', $entry)) {
                $this->updatePage($parentId, $entry, $published);
            }
            $published['needed'] = true;

            return $published;
        };

        return $this->recursiveWithCallback($parentId, $entry, $published, $callback);
    }

    protected function updatePage($parentId, $entry, $published)
    {
        $updateThreshold = $this->confluence->getUpdateThreshold();

        $this->run(
            '- ' . PublisherUtilities::niceTitle($entry['file']->getUrl()),
            function () use ($entry, $published, $parentId, $updateThreshold) {
                $generatedContent = $entry['page']->getContent();
                if (PublisherUtilities::shouldUpdate($entry['page'], $generatedContent, $published, $updateThreshold)) {
                    $this->client->updatePage(
                        $parentId,
                        $published['id'],
                        $published['version'] + 1,
                        $entry['title'],
                        $generatedContent
                    );
                }
            }
        );

        if (count($entry['page']->attachments)) {
            foreach ($entry['page']->attachments as $attachment) {
                $this->run(
                    "  With attachment: $attachment[filename]",
                    function ($write) use ($published, $attachment) {
                        $this->client->uploadAttachment($published['id'], $attachment, $write);
                    }
                );
            }
        }
    }
}
