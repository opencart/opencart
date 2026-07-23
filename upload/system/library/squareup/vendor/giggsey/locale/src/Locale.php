<?php

declare(strict_types=1);

namespace Giggsey\Locale;

use RuntimeException;

class Locale
{
    protected static $dataDir = '../data/';

    /**
     * Gets the primary language for the input locale
     *
     * @param string $locale Input locale (e.g. en-GB)
     * @return string Primary Language (e.g. en)
     */
    public static function getPrimaryLanguage(string $locale): string
    {
        $parts = explode('-', str_replace('_', '-', $locale));

        return strtolower($parts[0]);
    }

    /**
     * Get the region for the input locale
     *
     * @param string $locale Input locale (e.g. de-CH-1991)
     * @return string Region (e.g. CH)
     */
    public static function getRegion(string $locale): string
    {
        $parts = explode('-', str_replace('_', '-', $locale));

        if (count($parts) === 1) {
            return '';
        }

        $region = end($parts);

        if (strlen($region) === 4) {
            return '';
        }

        if ($region === 'POSIX') {
            $region = 'US';
        }

        return strtoupper($region);
    }

    /**
     * Get the localised display name for the region of the input locale
     *
     * @param string $locale The locale to return a display region for
     * @param string $inLocale Format locale to display the region name
     * @return string Display name for the region, or an empty string if no result could be found
     */
    public static function getDisplayRegion(string $locale, string $inLocale): string
    {
        $dataDir = __DIR__ . DIRECTORY_SEPARATOR . static::$dataDir;

        // Convert $locale into a region
        $region = static::getRegion($locale);

        $regionList = require $dataDir . '_list.php';

        /*
         * Loop through each part of the $inLocale, and see if we have data for that locale
         *
         * E.g zh-Hans-HK will look for zh-Hanks-HK, zh-Hanks, then finally zh
         */
        $fallbackParts = explode('-', str_replace('_', '-', $inLocale));
        $filesToSearch = [];

        $i = count($fallbackParts);
        while ($i > 0) {
            $searchLocale = strtolower(implode('-', $fallbackParts));

            if (isset($regionList[$searchLocale])) {
                $filesToSearch[] = $searchLocale;
            }

            array_pop($fallbackParts);
            $i--;
        }

        /*
         * Load data files, and load the region (if it exists) from it
         */

        foreach ($filesToSearch as $fileToSearch) {
            // Load data file
            $data = require $dataDir . $fileToSearch . '.php';

            if (isset($data[$region])) {
                return $data[$region];
            }
        }

        return '';
    }

    public static function getVersion()
    {
        $file = __DIR__ . DIRECTORY_SEPARATOR . static::$dataDir . '_version.php';

        return require $file;
    }

    /**
     * Return a list of all the supported locales
     *
     * @return string[]
     */
    public static function getSupportedLocales(): array
    {
        $dataDir = __DIR__ . DIRECTORY_SEPARATOR . static::$dataDir;
        $regionList = require $dataDir . '_list.php';

        return array_keys($regionList);
    }

    /**
     * Load a list of all countries supported by a particular Locale
     *
     * @return string[] Associative array of Country Code => Country Name
     * @throws RuntimeException On an invalid region
     */
    public static function getAllCountriesForLocale(string $locale): array
    {
        $dataDir = __DIR__ . DIRECTORY_SEPARATOR . static::$dataDir;
        $regionList = require $dataDir . '_list.php';

        if (!isset($regionList[$locale])) {
            throw new RuntimeException('Locale is not supported');
        }

        /*
         * Loop through each part of the $locale, and load data for that locale
         *
         * E.g zh-Hans-HK will look for zh-Hanks-HK, zh-Hanks, then finally zh
         */
        $fallbackParts = explode('-', str_replace('_', '-', $locale));
        $filesToSearch = [];

        $i = count($fallbackParts);
        while ($i > 0) {
            $searchLocale = strtolower(implode('-', $fallbackParts));

            if (isset($regionList[$searchLocale])) {
                $filesToSearch[] = $searchLocale;
            }

            array_pop($fallbackParts);
            $i--;
        }

        /*
         * Load data files, and load the region (if it exists) from it
         */

        $returnData = [];

        foreach ($filesToSearch as $fileToSearch) {
            // Load data file
            $data = require $dataDir . $fileToSearch . '.php';

            $returnData += $data;
        }

        ksort($returnData);

        return $returnData;
    }
}
