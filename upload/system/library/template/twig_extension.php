<?php
namespace Opencart\System\Library\Template;

class TwigExtension extends \Twig\Extension\AbstractExtension {
    public function getFilters(): array {
        return [
            new \Twig\TwigFilter('replace_first', [$this, 'replaceFirst']),
        ];
    }

    public function replaceFirst(string $haystack, string $search, $replace): string {
        $replace = (string)($replace ?? '');
        $pos = strpos($haystack, $search);
        if ($pos === false) {
            return $haystack;
        }
        return substr_replace($haystack, $replace, $pos, strlen($search));
    }
}
