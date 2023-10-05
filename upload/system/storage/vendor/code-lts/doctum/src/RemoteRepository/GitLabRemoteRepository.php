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

class GitLabRemoteRepository extends AbstractRemoteRepository
{
    /** @var string */
    protected $url = 'https://gitlab.com/';
    /** @var string */
    protected $separator = '/-/blob/';

    public function __construct(
        string $name,
        string $localPath,
        ?string $url = null,
        ?string $separator = null
    ) {
        if ($url !== null) {
            $this->url = $url;
        }

        if ($separator !== null) {
            $this->separator = $separator;
        }

        parent::__construct($name, $localPath);
    }

    public function getFileUrl($projectVersion, $relativePath, $line)
    {
        $url = $this->url . $this->name . $this->separator . $this->buildProjectPath($projectVersion, $relativePath);

        if (null !== $line) {
            $url .= '#L' . (int) $line;
        }

        return $url;
    }

}
