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

namespace Doctum\Store;

use Doctum\Project;
use Doctum\Reflection\ClassReflection;
use Doctum\Reflection\FunctionReflection;
use Exception;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class JsonStore implements StoreInterface
{
    private const JSON_PRETTY_PRINT = 128;

    /**
     * @return ClassReflection A ClassReflection instance
     *
     * @throws \InvalidArgumentException if the class does not exist in the store
     */
    public function readClass(Project $project, $name): ClassReflection
    {
        $fileName = $this->getFilename('class', $project, $name);

        if (!file_exists($fileName)) {
            throw new \InvalidArgumentException(sprintf('File "%s" for class "%s" does not exist.', $fileName, $name));
        }

        return ClassReflection::fromArray($project, $this->readJsonFile($fileName));
    }

    protected function readJsonFile(string $filePath): array
    {
        $contents = file_get_contents($filePath);
        if ($contents === false) {
            throw new Exception(
                sprintf('Unable to read the class: %s', $filePath)
            );
        }

        $contents = json_decode($contents, true);
        if ($contents === false) {
            throw new Exception(
                sprintf('Unable to JSON decode the class from: %s', $filePath)
            );
        }

        return $contents;
    }

    public function removeClass(Project $project, $name)
    {
        if (!file_exists($this->getFilename('class', $project, $name))) {
            throw new \RuntimeException(sprintf('Unable to remove the "%s" class.', $name));
        }

        unlink($this->getFilename('class', $project, $name));
    }

    public function writeClass(Project $project, ClassReflection $class)
    {
        file_put_contents($this->getFilename('class', $project, $class->getName()), json_encode($class->toArray(), self::JSON_PRETTY_PRINT));
    }

    /**
     * @return FunctionReflection A FunctionReflection instance
     *
     * @throws \InvalidArgumentException if the function does not exist in the store
     */
    public function readFunction(Project $project, string $name): FunctionReflection
    {
        $fileName = $this->getFilename('function', $project, $name);

        if (!file_exists($fileName)) {
            throw new \InvalidArgumentException(sprintf('File "%s" for function "%s" does not exist.', $fileName, $name));
        }

        return FunctionReflection::fromArray($project, $this->readJsonFile($fileName));
    }

    public function removeFunction(Project $project, string $name): void
    {
        if (!file_exists($this->getFilename('function', $project, $name))) {
            throw new \RuntimeException(sprintf('Unable to remove the "%s" function.', $name));
        }

        unlink($this->getFilename('function', $project, $name));
    }

    public function writeFunction(Project $project, FunctionReflection $function): void
    {
        file_put_contents($this->getFilename('function', $project, $function->getName()), json_encode($function->toArray(), self::JSON_PRETTY_PRINT));
    }

    public function readProject(Project $project)
    {
        $classesOrFunctions = [];
        $files              = Finder::create()->name('c_*.json')->files()->in($this->getStoreDir($project));
        foreach ($files as $file) {
            $contents = file_get_contents($file->getPathname());
            if ($contents === false) {
                continue;
            }
            $data = json_decode($contents, true);
            if ($data === false || $data === null) {
                continue;
            }
            $classesOrFunctions[] = ClassReflection::fromArray($project, $data);
        }
        $files = Finder::create()->name('f_*.json')->files()->in($this->getStoreDir($project));
        foreach ($files as $file) {
            $contents = file_get_contents($file->getPathname());
            if ($contents === false) {
                continue;
            }
            $data = json_decode($contents, true);
            if ($data === false || $data === null) {
                continue;
            }
            $classesOrFunctions[] = FunctionReflection::fromArray($project, $data);
        }

        return $classesOrFunctions;
    }

    public function flushProject(Project $project)
    {
        $filesystem = new Filesystem();
        $filesystem->remove($this->getStoreDir($project));
    }

    protected function getFilename(string $type, Project $project, string $name): string
    {
        $dir = $this->getStoreDir($project);

        return $dir . '/' . $type[0] . '_' . md5($name) . '.json';
    }

    protected function getStoreDir(Project $project): string
    {
        $dir = $project->getCacheDir() . '/store';

        if (!is_dir($dir)) {
            $filesystem = new Filesystem();
            $filesystem->mkdir($dir);
        }

        return $dir;
    }

}
