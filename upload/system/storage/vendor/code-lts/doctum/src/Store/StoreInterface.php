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

interface StoreInterface
{

    public function readClass(Project $project, $name);

    public function writeClass(Project $project, ClassReflection $class);

    public function removeClass(Project $project, $name);

    public function readFunction(Project $project, string $name): FunctionReflection;

    public function writeFunction(Project $project, FunctionReflection $Function): void;

    public function removeFunction(Project $project, string $name): void;

    /**
     * Read the storage and return it
     *
     * @return array<int|string,ClassReflection|FunctionReflection>
     */
    public function readProject(Project $project);

    /**
     * Empty the storage
     *
     * @return void
     */
    public function flushProject(Project $project);

}
