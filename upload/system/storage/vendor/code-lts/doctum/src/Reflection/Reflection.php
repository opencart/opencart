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

namespace Doctum\Reflection;

use Doctum\Project;

abstract class Reflection
{
    // Constants from: vendor/nikic/php-parser/lib/PhpParser/Node/Stmt/Class_.php
    public const MODIFIER_PUBLIC            = 1;
    public const MODIFIER_PROTECTED         = 2;
    public const MODIFIER_PRIVATE           = 4;
    public const MODIFIER_STATIC            = 8;
    public const MODIFIER_ABSTRACT          = 16;
    public const MODIFIER_FINAL             = 32;
    protected const VISIBILITY_MODIFER_MASK = 7; // 1 | 2 | 4

    /** @var string */
    protected $name;
    /** @var int */
    protected $line;
    /** @var string */
    protected $shortDesc;
    /** @var string */
    protected $longDesc;
    /** @var string|null|array<int,mixed> */
    protected $hint;
    /** @var string */
    protected $hintDesc;
    /** @var array<string,array> */
    protected $tags;
    /** @var string|null */
    protected $docComment;
    /** @var array<int,array<int,string|false>> */
    protected $see = [];
    /** @var string[] */
    protected $errors = [];
    /** @var bool */
    protected $isReadOnly = false;
    /** @var int */
    protected $modifiers;

    public function __construct(string $name, $line)
    {
        $this->name = $name;
        $this->line = $line;
        $this->tags = [];
    }

    /**
     * @return self|null
     */
    abstract public function getClass();

    /**
     * Set the modifier flags
     *
     * @phpstan-param self::MODIFIER_*|int $modifiers
     */
    public function setModifiers(int $modifiers): void
    {
        $this->modifiers = $modifiers;
    }

    /**
     * Set the modifier from phpdoc tags
     *
     */
    public function setModifiersFromTags(): void
    {
        $hasFinalTag     = count($this->getTags('final')) > 0;
        $hasProtectedTag = count($this->getTags('protected')) > 0;
        $hasPrivateTag   = count($this->getTags('private')) > 0;
        $hasPublicTag    = count($this->getTags('public')) > 0;
        $hasStaticTag    = count($this->getTags('static')) > 0;
        $accessTags      = $this->getTags('access');
        $hasAccessTag    = count($accessTags) > 0;
        $flags           = $this->modifiers ?? 0;

        if ($hasAccessTag) {
            $accessTag = strtolower(trim(implode('', $accessTags[0])));
            if ($accessTag === 'protected') {
                $hasProtectedTag = true;
            } elseif ($accessTag === 'private') {
                $hasPrivateTag = true;
            } elseif ($accessTag === 'public') {
                $hasPublicTag = true;
            }
        }

        if ($hasFinalTag) {
            $flags |= self::MODIFIER_FINAL;
        }
        if ($hasProtectedTag) {
            $flags |= self::MODIFIER_PROTECTED;
        }
        if ($hasPrivateTag) {
            $flags |= self::MODIFIER_PRIVATE;
        }
        if ($hasPublicTag) {
            $flags |= self::MODIFIER_PUBLIC;
        }
        if ($hasStaticTag) {
            $flags |= self::MODIFIER_STATIC;
        }

        $this->setModifiers($flags);
    }

    public function isPublic(): bool
    {
        return self::MODIFIER_PUBLIC === (self::MODIFIER_PUBLIC & $this->modifiers);
    }

    public function isProtected(): bool
    {
        return self::MODIFIER_PROTECTED === (self::MODIFIER_PROTECTED & $this->modifiers);
    }

    public function isPrivate(): bool
    {
        return self::MODIFIER_PRIVATE === (self::MODIFIER_PRIVATE & $this->modifiers);
    }

    public function isStatic(): bool
    {
        return self::MODIFIER_STATIC === (self::MODIFIER_STATIC & $this->modifiers);
    }

    public function isAbstract(): bool
    {
        return self::MODIFIER_ABSTRACT === (self::MODIFIER_ABSTRACT & $this->modifiers);
    }

    public function isFinal(): bool
    {
        return self::MODIFIER_FINAL === (self::MODIFIER_FINAL & $this->modifiers);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getLine()
    {
        return $this->line;
    }

    /**
     * @param int $line
     */
    public function setLine($line): void
    {
        $this->line = $line;
    }

    /**
     * @return string
     */
    public function getShortDesc()
    {
        return $this->shortDesc;
    }

    /**
     * @param string $shortDesc
     * @return void
     */
    public function setShortDesc($shortDesc)
    {
        $this->shortDesc = $shortDesc;
    }

    /**
     * @return string
     */
    public function getLongDesc()
    {
        return $this->longDesc;
    }

    /**
     * @param string $longDesc
     * @return void
     */
    public function setLongDesc($longDesc)
    {
        $this->longDesc = $longDesc;
    }

    /**
     * @return HintReflection[]
     */
    public function getHint()
    {
        if (! is_array($this->hint)) {
            return [];
        }

        $hints = [];
        /** @var FunctionReflection $class Not sure this is the right type */
        $class   = $this->getClass();
        $project = $class->getProject();
        foreach ($this->hint as $hint) {
            $hints[] = new HintReflection(Project::isPhpTypeHint($hint[0]) ? $hint[0] : $project->getClass($hint[0]), $hint[1]);
        }

        return $hints;
    }

    /**
     * @return string
     * @example string|int
     */
    public function getHintAsString()
    {
        $str = [];
        foreach ($this->getHint() as $hint) {
            $str[] = ($hint->isClass() ? $hint->getName()->getShortName() : $hint->getName()) . ($hint->isArray() ? '[]' : '');
        }

        return implode('|', $str);
    }

    public function hasHint(): bool
    {
        return $this->hint ? true : false;
    }

    /**
     * @param string|array|null $hint
     * @return void
     */
    public function setHint($hint)
    {
        $this->hint = $hint;
    }

    /**
     * @return string|array|null
     */
    public function getRawHint()
    {
        return $this->hint;
    }

    /**
     * @return void
     */
    public function setHintDesc($desc)
    {
        $this->hintDesc = $desc;
    }

    /**
     * @return string
     */
    public function getHintDesc()
    {
        return $this->hintDesc;
    }

    /**
     * @param array<string,array> $tags
     * @return void
     */
    public function setTags(array $tags): void
    {
        $this->tags = $tags;
    }

    /**
     * @return array<string,array>
     */
    public function getTags($name)
    {
        return $this->tags[$name] ?? [];
    }

    /**
     * @return array<string,array>
     */
    public function getDeprecated()
    {
        return $this->getTags('deprecated');
    }

    /**
     * @return array<string,array>
     */
    public function getTodo()
    {
        return $this->getTags('todo');
    }

    public function isDeprecated(): bool
    {
        return ! empty($this->getDeprecated());
    }

    public function isInternal(): bool
    {
        return ! empty($this->getTags('internal'));
    }

    public function hasExamples(): bool
    {
        return ! empty($this->getExamples());
    }

    /**
     * @return array[]
     */
    public function getExamples(): array
    {
        return $this->getTags('example');
    }

    public function hasSince(): bool
    {
        return $this->getSince() !== null;
    }

    public function getSince(): ?string
    {
        /** @var array[] $sinceTags */
        $sinceTags = $this->getTags('since');
        return count($sinceTags) > 0 ? implode(' ', $sinceTags[0]) : null;
    }

    /**
     * Get the internal tags
     *
     * @return array<string,array>
     */
    public function getInternal(): array
    {
        return $this->getTags('internal');
    }

    public function setReadOnly(bool $isReadOnly): void
    {
        $this->isReadOnly = $isReadOnly;
    }

    public function isReadOnly(): bool
    {
        return $this->isReadOnly;
    }

    /**
     * not serialized as it is only useful when parsing
     * @param string|null $comment
     * @return void
     */
    public function setDocComment($comment)
    {
        $this->docComment = $comment;
    }

    /**
     * @return string|null
     */
    public function getDocComment()
    {
        return $this->docComment;
    }

    /**
     * @return array[]
     */
    public function getSee()
    {
        $see = [];
        /** @var FunctionReflection $class Not sure this is the right type */
        $class   = $this->getClass();
        $project = $class->getProject();

        foreach ($this->see as $seeElem) {
            if ($seeElem[3]) {
                $seeElem = $this->prepareMethodSee($seeElem);
            } elseif ($seeElem[2]) {
                $seeElem[2] = Project::isPhpTypeHint($seeElem[2]) ? $seeElem[2] : $project->getClass($seeElem[2]);
            }

            $see[] = $seeElem;
        }
        return $see;
    }

    /**
     * @param array<int,array<int,string|false>> $see
     * @return void
     */
    public function setSee(array $see)
    {
        $this->see = $see;
    }

    /**
     * @param array<int,string|false> $seeElem
     * @return array<int,MethodReflection|ClassReflection|false>
     */
    private function prepareMethodSee(array $seeElem): array
    {
        /** @var FunctionReflection $class Not sure this is the right type */
        $class   = $this->getClass();
        $project = $class->getProject();

        $method = null;

        if ($seeElem[2] !== false) {
            $class  = $project->getClass($seeElem[2]);
            $method = $class->getMethod($seeElem[3]);
        }

        if ($method) {
            $seeElem[2] = false;
            $seeElem[3] = $method;
        } else {
            $seeElem[2] = false;
            $seeElem[3] = false;
        }

        return $seeElem;
    }

    /**
     * @return string[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param string[] $errors
     */
    public function setErrors(array $errors): void
    {
        $this->errors = $errors;
    }

    /**
     * @param array<string,mixed> $array
     * @return self
     */
    abstract public static function fromArray(Project $project, array $array);

}
