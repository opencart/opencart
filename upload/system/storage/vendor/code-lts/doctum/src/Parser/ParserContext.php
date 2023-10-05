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

use Doctum\Parser\Filter\FilterInterface;
use Doctum\Reflection\ClassReflection;
use Doctum\Reflection\FunctionReflection;

class ParserContext
{
    protected $filter;
    protected $docBlockParser;
    /** @var \PhpParser\PrettyPrinter\Standard */
    protected $prettyPrinter;
    /** @var ParseError[] */
    protected $errors = [];
    /** @var string|null */
    protected $namespace;
    protected $aliases;
    protected $class;
    /** @var string|null */
    protected $file;
    /** @var string|null */
    protected $hash;
    protected $classes;

    /** @var array<string,FunctionReflection> */
    protected $functions;

    public function __construct(FilterInterface $filter, DocBlockParser $docBlockParser, $prettyPrinter)
    {
        $this->filter         = $filter;
        $this->docBlockParser = $docBlockParser;
        $this->prettyPrinter  = $prettyPrinter;
        $this->functions      = [];
    }

    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * @return DocBlockParser
     */
    public function getDocBlockParser()
    {
        return $this->docBlockParser;
    }

    /**
     * @return \PhpParser\PrettyPrinter\Standard
     */
    public function getPrettyPrinter()
    {
        return $this->prettyPrinter;
    }

    public function addAlias(?string $alias, string $name): void
    {
        $this->aliases[$alias] = $name;
    }

    public function getAliases()
    {
        return $this->aliases;
    }

    public function enterFile($file, $hash): void
    {
        $this->file    = $file;
        $this->hash    = $hash;
        $this->errors  = [];
        $this->classes = [];
    }

    public function leaveFile()
    {
        $this->hash   = null;
        $this->file   = null;
        $this->errors = [];

        return $this->classes;
    }

    /**
     * @return string|null
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @return string|null
     */
    public function getFile()
    {
        return $this->file;
    }

    public function addErrors(string $name, int $line, array $errors): void
    {
        foreach ($errors as $error) {
            $this->addError($name, $line, $error);
        }
    }

    public function addError(?string $name, int $line, string $error): void
    {
        $this->errors[] = new ParseError(
            sprintf('%s on "%s"', $error, $name),
            $this->file,
            $line
        );
    }

    /**
     * @return ParseError[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    public function addFunction(FunctionReflection $fun): void
    {
        $this->functions[$this->namespace . '\\' . $fun->getName()] = $fun;
    }

    /**
     * @return array<string,FunctionReflection>
     */
    public function getFunctions()
    {
        return $this->functions;
    }

    public function enterClass(ClassReflection $class): void
    {
        $this->class = $class;
    }

    public function leaveClass(): void
    {
        if (null === $this->class) {
            return;
        }

        $this->classes[] = $this->class;
        $this->class     = null;
    }

    public function getClass()
    {
        return $this->class;
    }

    public function enterNamespace(string $namespace): void
    {
        $this->namespace = $namespace;
        $this->aliases   = [];
    }

    public function leaveNamespace(): void
    {
        $this->namespace = null;
        $this->aliases   = [];
    }

    public function getNamespace(): ?string
    {
        return $this->namespace;
    }

}
