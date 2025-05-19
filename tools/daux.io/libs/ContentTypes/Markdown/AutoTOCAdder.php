<?php namespace Todaymade\Daux\ContentTypes\Markdown;

use League\CommonMark\Event\DocumentParsedEvent;
use League\CommonMark\Extension\TableOfContents\Node\TableOfContentsPlaceholder;
use League\CommonMark\Node\Block\Document;
use League\CommonMark\Node\NodeIterator;
use League\Config\ConfigurationAwareInterface;
use League\Config\ConfigurationInterface;
use Todaymade\Daux\Config;

class AutoTOCAdder implements ConfigurationAwareInterface
{
    /** @psalm-readonly-allow-private-mutation */
    private ConfigurationInterface $config;

    public function onDocumentParsed(DocumentParsedEvent $event): void
    {
        // Auto TOC is disabled, no need to check anything
        if (!$this->isAutoTOC()) {
            return;
        }

        $document = $event->getDocument();

        // We already have a TOC, we won't do anything
        if ($this->hasTOCPlaceholder($document)) {
            return;
        }

        // If no TOC is present, we add one at the beginning of the document
        $document->prependChild(new TableOfContentsPlaceholder());
    }

    public function isAutoTOC(): bool
    {
        /** @var Config $daux */
        $daux = $this->config->get('daux');

        return $daux->getHTML()->hasAutomaticTableOfContents();
    }

    public function hasTOCPlaceholder(Document $document): bool
    {
        foreach ($document->iterator(NodeIterator::FLAG_BLOCKS_ONLY) as $node) {
            if ($node instanceof TableOfContentsPlaceholder) {
                return true;
            }
        }

        return false;
    }

    public function setConfiguration(ConfigurationInterface $configuration): void
    {
        $this->config = $configuration;
    }
}
