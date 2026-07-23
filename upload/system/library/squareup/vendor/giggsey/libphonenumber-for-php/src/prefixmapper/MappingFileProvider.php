<?php

declare(strict_types=1);

namespace libphonenumber\prefixmapper;

/**
 * A utility which knows the data files that are available for the phone prefix mappers to use.
 * The data files contain mappings from phone number prefixes to text descriptions, and are
 * organized by country calling code and language that the text descriptions are in.
 *
 * Class MappingFileProvider
 * @package libphonenumber\prefixmapper
 * @internal
 */
class MappingFileProvider
{
    /**
     * @var array<string,int[]>
     */
    protected array $map;

    /**
     * @param array<string,int[]> $map
     */
    public function __construct(array $map)
    {
        $this->map = $map;
    }

    public function getFileName(string $countryCallingCode, string $language, string $script, string $region): string
    {
        if ($language === '') {
            return '';
        }

        if ($language === 'zh' && ($region === 'TW' || $region === 'HK' || $region === 'MO')) {
            $language = 'zh_Hant';
        }

        // Loop through the $countryCallingCode and load the prefix
        $prefixLength = strlen($countryCallingCode);

        for ($i = $prefixLength; $i > 0; $i--) {
            $prefix = substr($countryCallingCode, 0, $i);
            if ($this->inMap($language, (int) $prefix)) {
                return $language . '\\' . ucfirst($language) . '_' . $prefix;
            }
        }

        return '';
    }

    protected function inMap(string $language, int $countryCallingCode): bool
    {
        return (isset($this->map[$language]) && in_array($countryCallingCode, $this->map[$language], true));
    }
}
