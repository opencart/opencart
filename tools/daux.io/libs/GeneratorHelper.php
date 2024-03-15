<?php namespace Todaymade\Daux;

class GeneratorHelper
{
    /**
     * Remove a directory recursively.
     *
     * @param string $dir
     */
    public static function rmdir($dir)
    {
        $it = new \RecursiveDirectoryIterator($dir);
        $files = new \RecursiveIteratorIterator($it, \RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($files as $file) {
            if ($file->getFilename() === '.' || $file->getFilename() === '..') {
                continue;
            }
            if ($file->isDir()) {
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }
    }

    /**
     * Copy files recursively.
     *
     * @param string $source
     * @param string $destination
     */
    public static function copyRecursive($source, $destination)
    {
        if (!is_dir($destination)) {
            mkdir($destination);
        }

        $dir = opendir($source);

        if ($dir === false) {
            throw new Exception("Cannot copy '$source' to '$destination'");
        }

        while (false !== ($file = readdir($dir))) {
            if ($file != '.' && $file != '..') {
                if (is_dir($source . DIRECTORY_SEPARATOR . $file)) {
                    static::copyRecursive(
                        $source . DIRECTORY_SEPARATOR . $file,
                        $destination . DIRECTORY_SEPARATOR . $file
                    );
                } else {
                    copy($source . DIRECTORY_SEPARATOR . $file, $destination . DIRECTORY_SEPARATOR . $file);
                }
            }
        }
        closedir($dir);
    }
}
