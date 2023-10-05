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

use Doctum\Message;
use Doctum\Project;
use Doctum\Reflection\ClassReflection;
use Doctum\Reflection\FunctionReflection;
use Doctum\Reflection\LazyClassReflection;
use Doctum\Store\StoreInterface;
use Symfony\Component\Finder\Finder;

class Parser
{
    /** @var StoreInterface */
    protected $store;
    /** @var Finder */
    protected $iterator;
    /** @var CodeParser */
    protected $parser;
    /** @var ProjectTraverser */
    protected $traverser;

    public function __construct($iterator, StoreInterface $store, CodeParser $parser, ProjectTraverser $traverser)
    {
        $this->iterator  = $this->createIterator($iterator);
        $this->store     = $store;
        $this->parser    = $parser;
        $this->traverser = $traverser;
    }

    public function parse(Project $project, $callback = null)
    {
        $step        = 0;
        $steps       = iterator_count($this->iterator);
        $context     = $this->parser->getContext();
        $transaction = new Transaction($project);
        $toStore     = new \SplObjectStorage();
        foreach ($this->iterator as $file) {
            $file = $file->getPathname();
            ++$step;

            $code = file_get_contents($file);
            if ($code === false) {
                continue;
            }

            $hash = sha1($code);
            if ($transaction->hasHash($hash)) {
                continue;
            }

            $context->enterFile((string) $file, $hash);

            $this->parser->parse($code);

            if (null !== $callback) {
                call_user_func($callback, Message::PARSE_ERROR, $context->getErrors());
            }

            foreach ($context->getFunctions() as $addr => $fun) {
                $project->addFunction($fun);
                $toStore->attach($fun);
            }

            foreach ($context->leaveFile() as $class) {
                if (null !== $callback) {
                    call_user_func($callback, Message::PARSE_CLASS, [$step, $steps, $class]);
                }

                $project->addClass($class);
                $transaction->addClass($class);
                $toStore->attach($class);
                $class->notFromCache();
            }
        }

        // cleanup
        foreach ($transaction->getRemovedClasses() as $class) {
            $project->removeClass(new LazyClassReflection($class));
            $this->store->removeClass($project, $class);
        }

        // visit each class for stuff that can only be done when all classes are parsed
        $toStore->addAll($this->traverser->traverse($project));

        foreach ($toStore as $classOrFun) {
            if ($classOrFun instanceof FunctionReflection) {
                $this->store->writeFunction($project, $classOrFun);
            } elseif ($classOrFun instanceof ClassReflection) {
                $this->store->writeClass($project, $classOrFun);
            }
        }

        return $transaction;
    }

    /**
     * @param string|Finder $iterator
     * @return Finder
     */
    private function createIterator($iterator)
    {
        if (is_string($iterator)) {
            $it = new Finder();
            $it->files()->name('*.php')->in($iterator);

            return $it;
        } elseif (!$iterator instanceof \Traversable) {
            throw new \InvalidArgumentException('The iterator must be a directory name or a Finder instance.');
        }

        return $iterator;
    }

}
