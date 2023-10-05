<?php

declare(strict_types = 1);

/*
 * This file is part of the Doctum utility.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Doctum\RemoteRepository;

abstract class AbstractRemoteRepository
{
    /** @var string */
    protected $name;
    /** @var string */
    protected $localPath;

    public function __construct(string $name, string $localPath)
    {
        $this->name      = $name;
        $this->localPath = $localPath;
    }

    /**
     * Get an URL for a file
     *
     * @param string $projectVersion
     * @param string $relativePath
     * @param int $line
     *
     * @return string
     */
    abstract public function getFileUrl($projectVersion, $relativePath, $line);

    public function getRelativePath(string $file): string
    {
        $replacementCount = 0;
        $filePath         = str_replace($this->localPath, '', $file, $replacementCount);

        if (1 === $replacementCount) {
            return $filePath;
        }

        return '';
    }

    protected function buildProjectPath(string $projectVersion, string $relativePath): string
    {
        return str_replace('\\', '/', $projectVersion . '/' . ltrim($relativePath, '/'));
    }

}
