<?php namespace Todaymade\Daux\ContentTypes\Markdown;

use League\CommonMark\Normalizer\TextNormalizerInterface;
use League\Config\ConfigurationAwareInterface;
use League\Config\ConfigurationInterface;
use Todaymade\Daux\DauxHelper;

class TextNormalization implements TextNormalizerInterface, ConfigurationAwareInterface
{
    private int $defaultMaxLength = 255;

    public function setConfiguration(ConfigurationInterface $configuration): void
    {
        $this->defaultMaxLength = $configuration->get('slug_normalizer/max_length');
    }

    public function normalize(string $text, $context = null): string
    {
        // Add any requested prefix
        $slug = ($context['prefix'] ?? '') . $text;

        // Normalize
        $slug = DauxHelper::linkSlug($slug);

        // Trim to requested length if given
        if ($length = $context['length'] ?? $this->defaultMaxLength) {
            $slug = \mb_substr($slug, 0, $length);
        }

        return $slug;
    }
}
