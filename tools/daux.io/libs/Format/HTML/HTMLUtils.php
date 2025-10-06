<?php namespace Todaymade\Daux\Format\HTML;

use Todaymade\Daux\GeneratorHelper;

trait HTMLUtils
{
    public function ensureEmptyDestination($destination)
    {
        if (is_dir($destination)) {
            GeneratorHelper::rmdir($destination);
        } else {
            mkdir($destination, 0777, true);
        }
    }

    /**
     * Copy all files from $local to $destination.
     *
     * @param string $destination
     * @param string $localBase
     */
    public function copyThemes($destination, $localBase)
    {
        mkdir($destination . DIRECTORY_SEPARATOR . 'themes');
        GeneratorHelper::copyRecursive(
            $localBase,
            $destination . DIRECTORY_SEPARATOR . 'themes'
        );
    }
}
