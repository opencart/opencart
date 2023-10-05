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

namespace Doctum\Version;

use Symfony\Component\Finder\Glob;
use Symfony\Component\Process\Process;

class GitVersionCollection extends VersionCollection
{
    /**
     * @var \Closure
     */
    protected $sorter;

    /**
     * @var \Closure
     */
    protected $filter;

    /**
     * @var string
     */
    protected $repo;

    /**
     * @var string
     */
    protected $gitPath;

    public function __construct(string $repo)
    {
        $this->repo    = $repo;
        $this->filter  = static function (string $version): bool {
            foreach (['PR', 'RC', 'BETA', 'ALPHA'] as $str) {
                if (stripos($version, $str) !== false) {
                    return false;
                }
            }

            return true;
        };
        $this->sorter  = static function (string $a, string $b): int {
            return version_compare($a, $b, '>') === true ? 1 : 0;
        };
        $this->gitPath = 'git';
    }

    /**
     * @phpstan-return void
     */
    protected function switchVersion(Version $version)
    {
        $process = new Process(['git', 'status', '--porcelain', '--untracked-files=no'], $this->repo);
        $process->run();
        if (!$process->isSuccessful() || trim($process->getOutput())) {
            throw new \RuntimeException(sprintf('Unable to switch to version "%s" as the repository is not clean.', $version));
        }

        $this->execute(['checkout', '-qf', (string) $version]);
    }

    public function setGitPath(string $path): void
    {
        $this->gitPath = $path;
    }

    public function setFilter(\Closure $filter): void
    {
        $this->filter = $filter;
    }

    public function setSorter(\Closure $sorter): void
    {
        $this->sorter = $sorter;
    }

    /**
     * @param \Closure|null|string[] $filter An array_filter callback that will be used for filtering versions or an array of globs
     */
    public function addFromTags($filter = null): self
    {
        $tags = array_filter(explode("\n", $this->execute(['tag'])));

        $versions = array_filter($tags, $this->filter);
        if (null !== $filter) {
            if (!$filter instanceof \Closure) {
                $regexes = [];
                foreach ((array) $filter as $f) {
                    $regexes[] = Glob::toRegex($f);
                }
                $filter = static function ($version) use ($regexes) {
                    foreach ($regexes as $regex) {
                        if (preg_match($regex, $version)) {
                            return true;
                        }
                    }

                    return false;
                };
            }

            $versions = array_filter($versions, $filter);
        }
        usort($versions, $this->sorter);

        foreach ($versions as $version) {
            $version = new Version($version);
            $version->setFrozen(true);
            $this->add($version);
        }

        return $this;
    }

    /**
     * @param string[] $arguments
     */
    protected function execute(array $arguments): string
    {
        array_unshift($arguments, $this->gitPath);

        $process = new Process($arguments, $this->repo);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \RuntimeException(sprintf('Unable to run the command (%s).', $process->getErrorOutput()));
        }

        return $process->getOutput();
    }

}
