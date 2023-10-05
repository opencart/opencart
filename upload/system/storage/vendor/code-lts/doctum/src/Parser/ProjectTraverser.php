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

namespace Doctum\Parser;

use Doctum\Project;
use SplObjectStorage;

class ProjectTraverser
{
    /**
     * The class visitors
     *
     * @var ClassVisitorInterface[]
     */
    protected $classVisitors = [];

    /**
     * The function visitors
     *
     * @var FunctionVisitorInterface[]
     */
    protected $functionVisitors = [];

    /**
     * @param (ClassVisitorInterface|FunctionVisitorInterface)[] $visitors
     */
    public function __construct(array $visitors = [])
    {
        foreach ($visitors as $visitor) {
            if ($visitor instanceof ClassVisitorInterface) {
                $this->addClassVisitor($visitor);
            }
            if ($visitor instanceof FunctionVisitorInterface) {
                $this->addFunctionVisitor($visitor);
            }
        }
    }

    public function addClassVisitor(ClassVisitorInterface $visitor): void
    {
        $this->classVisitors[] = $visitor;
    }

    public function addFunctionVisitor(FunctionVisitorInterface $visitor): void
    {
        $this->functionVisitors[] = $visitor;
    }

    public function traverse(Project $project): SplObjectStorage
    {
        // parent classes/interfaces are visited before their "children"
        $classes  = $project->getProjectClasses();
        $modified = new SplObjectStorage();
        while ($class = array_shift($classes)) {
            // re-push the class at the end if parent class/interfaces have not been visited yet
            if (($parent = $class->getParent()) && isset($classes[$parent->getName()])) {
                $classes[$class->getName()] = $class;

                continue;
            }

            $interfaces = $class->getInterfaces();
            foreach ($interfaces as $interface) {
                if (isset($classes[$interface->getName()])) {
                    $classes[$class->getName()] = $class;

                    continue 2;
                }
            }

            // only visits classes not coming from the cache
            // and for which parent/interfaces also come from the cache
            $visit = !$class->isFromCache() || ($parent && !$parent->isFromCache());
            foreach ($interfaces as $interface) {
                if (!$interface->isFromCache()) {
                    $visit = true;

                    break;
                }
            }

            if (!$visit) {
                continue;
            }

            $isModified = false;
            foreach ($this->classVisitors as $visitor) {
                $isModified = $visitor->visit($class) || $isModified;
            }

            if ($isModified) {
                $modified->attach($class);
            }
        }

        $functions = $project->getProjectFunctions();
        foreach ($functions as $function) {
            if ($function->isFromCache()) {
                continue;
            }

            $isModifiedFunction = false;
            foreach ($this->functionVisitors as $visitor) {
                $isModifiedFunction = $visitor->visit($function) || $isModifiedFunction;
            }

            if ($isModifiedFunction) {
                $modified->attach($function);
            }
        }

        return $modified;
    }

}
