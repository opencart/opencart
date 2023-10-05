<?php

declare(strict_types = 1);

/*
 * This file is part of the Doctum utility.
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Doctum\Reflection;

use Doctum\Project;

class FunctionReflection extends Reflection
{
    protected $namespace;
    /** @var array<string,ParameterReflection> */
    protected $parameters = [];
    protected $byRef;
    protected $project;
    /** @var string|null */
    protected $file = null;
    /** @var string|null */
    protected $relativeFilePath = null;
    protected $exceptions       = [];
    /** @var bool */
    protected $fromCache = false;

    public function __toString()
    {
        return $this->namespace . '\\' . $this->name;
    }

    public function setByRef($boolean)
    {
        $this->byRef = $boolean;
    }

    public function isByRef()
    {
        return $this->byRef;
    }

    /**
     * @return Project
     */
    public function getProject()
    {
        return $this->project;
    }

    public function setProject(Project $project)
    {
        $this->project = $project;
    }

    public function setNamespace($namespace)
    {
        $this->namespace = ltrim($namespace, '\\');
    }

    public function getNamespace()
    {
        return $this->namespace;
    }

    public function getClass()
    {
        return null;
    }

    public function setClass(ClassReflection $class)
    {
        return; // No op
    }

    /**
     * @overrides
     */
    public function getHint()
    {
        if (! is_array($this->hint)) {
            return [];
        }

        $hints   = [];
        $project = $this->getProject();
        foreach ($this->hint as $hint) {
            $hints[] = new HintReflection(Project::isPhpTypeHint($hint[0]) ? $hint[0] : $project->getClass($hint[0]), $hint[1]);
        }

        return $hints;
    }

    /**
     * @return void
     */
    public function addParameter(ParameterReflection $parameter)
    {
        $this->parameters[$parameter->getName()] = $parameter;
        $parameter->setFunction($this);
    }

    /**
     * @return array<string,ParameterReflection>
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    public function getParameter($name)
    {
        if (ctype_digit((string) $name)) {
            $tmp = array_values($this->parameters);

            return $tmp[$name] ?? null;
        }

        return $this->parameters[$name] ?? null;
    }

    /**
     * Can be any iterator (so that we can lazy-load the parameters)
     * @param array<string,ParameterReflection> $parameters
     * @return void
     */
    public function setParameters($parameters)
    {
        $this->parameters = $parameters;
    }

    public function setExceptions($exceptions)
    {
        $this->exceptions = $exceptions;
    }

    public function getExceptions()
    {
        $exceptions = [];
        foreach ($this->exceptions as $exception) {
            $exception[0] = $this->getProject()->getClass($exception[0]);
            $exceptions[] = $exception;
        }

        return $exceptions;
    }

    public function getRawExceptions()
    {
        return $this->exceptions;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(?string $file): void
    {
        $this->file = $file;
    }

    public function setRelativeFilePath(?string $relativeFilePath): void
    {
        $this->relativeFilePath = $relativeFilePath;
    }

    public function getRelativeFilePath(): ?string
    {
        return $this->relativeFilePath;
    }

    public function getSourcePath()
    {
        if (null === $this->relativeFilePath) {
            return '';
        }

        return $this->project->getViewSourceUrl($this->relativeFilePath, $this->line);
    }

    public function isFromCache(): bool
    {
        return $this->fromCache;
    }

    public function toArray()
    {
        return [
            'namespace' => $this->namespace,
            'name' => $this->name,
            'line' => $this->line,
            'file' => $this->file,
            'relative_file' => $this->relativeFilePath,
            'short_desc' => $this->shortDesc,
            'long_desc' => $this->longDesc,
            'hint' => $this->hint,
            'hint_desc' => $this->hintDesc,
            'tags' => $this->tags,
            'modifiers' => $this->modifiers,
            'is_by_ref' => $this->byRef,
            'exceptions' => $this->exceptions,
            'errors' => $this->errors,
            'parameters' => array_map(
                static function ($parameter) {
                    return $parameter->toArray();
                },
                $this->parameters
            ),
        ];
    }

    /**
     * @return self
     */
    public static function fromArray(Project $project, array $array)
    {
        $method                   = new self($array['name'], $array['line']);
        $method->shortDesc        = $array['short_desc'];
        $method->longDesc         = $array['long_desc'];
        $method->hint             = $array['hint'];
        $method->hintDesc         = $array['hint_desc'];
        $method->tags             = $array['tags'];
        $method->modifiers        = $array['modifiers'];
        $method->byRef            = $array['is_by_ref'];
        $method->exceptions       = $array['exceptions'];
        $method->errors           = $array['errors'];
        $method->namespace        = $array['namespace'] ?? '';// New in 5.4.0
        $method->file             = $array['file'] ?? '';// New in 5.5.0
        $method->relativeFilePath = $array['relative_file'] ?? '';// New in 5.5.0
        $method->fromCache        = true;

        foreach ($array['parameters'] as $parameter) {
            $method->addParameter(ParameterReflection::fromArray($project, $parameter));
        }

        return $method;
    }

}
