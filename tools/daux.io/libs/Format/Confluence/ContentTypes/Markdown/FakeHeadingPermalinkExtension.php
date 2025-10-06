<?php namespace Todaymade\Daux\Format\Confluence\ContentTypes\Markdown;

use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Extension\ConfigurableExtensionInterface;
use League\Config\ConfigurationBuilderInterface;
use Nette\Schema\Expect;

final class FakeHeadingPermalinkExtension implements ConfigurableExtensionInterface
{
    public function configureSchema(ConfigurationBuilderInterface $builder): void
    {
        $builder->addSchema('heading_permalink', Expect::structure([
            'fragment_prefix' => Expect::string()->default('content'),
        ]));
    }

    public function register(EnvironmentBuilderInterface $environment): void
    {
        // We have nothing to registry only schema
    }
}
