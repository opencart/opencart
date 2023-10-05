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

namespace Doctum\Renderer;

use Doctum\Reflection\Reflection;
use Doctum\Reflection\ClassReflection;
use Doctum\Reflection\FunctionReflection;
use Doctum\Reflection\MethodReflection;
use Doctum\Reflection\PropertyReflection;
use Doctum\Tree;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Twig\TwigFilter;
use Parsedown;

class TwigExtension extends AbstractExtension
{
    /** @var Parsedown */
    protected $markdown;
    protected $project;
    /** @var int|null */
    protected $currentDepth = null;

    /**
     * Returns a list of filters to add to the existing list.
     *
     * @return TwigFilter[] An array of filters
     */
    public function getFilters()
    {
        return [
            new TwigFilter('desc', [$this, 'parseDesc'], ['needs_context' => false, 'is_safe' => ['html']]),
            new TwigFilter('md_to_html', [$this, 'markdownToHtml'], ['needs_context' => false, 'is_safe' => ['html']]),
            new TwigFilter('snippet', [$this, 'getSnippet']),
        ];
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return TwigFunction[] An array of functions
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('global_namespace_name', [Tree::class, 'getGlobalNamespaceName']),
            new TwigFunction('global_namespace_page_name', [Tree::class, 'getGlobalNamespacePageName']),
            new TwigFunction('function_path', [$this, 'pathForFunction'], ['needs_context' => true, 'is_safe' => ['html']]),
            new TwigFunction('namespace_path', [$this, 'pathForNamespace'], ['needs_context' => true, 'is_safe' => ['html']]),
            new TwigFunction('class_path', [$this, 'pathForClass'], ['needs_context' => true, 'is_safe' => ['html']]),
            new TwigFunction('method_path', [$this, 'pathForMethod'], ['needs_context' => true, 'is_safe' => ['html']]),
            new TwigFunction('property_path', [$this, 'pathForProperty'], ['needs_context' => true, 'is_safe' => ['html']]),
            new TwigFunction('path', [$this, 'pathForStaticFile'], ['needs_context' => true]),
            new TwigFunction(
                'abbr_class',
                static function ($class, bool $absolute = false) {
                    return self::abbrClass($class, $absolute);
                },
                ['is_safe' => ['html']]
            ),
        ];
    }

    public function setCurrentDepth(int $depth): void
    {
        $this->currentDepth = $depth;
    }

    public function pathForFunction(array $context, FunctionReflection $function): string
    {
        $namespace = $this->pathForNamespace($context, $function->getNamespace());
        return $this->relativeUri($this->currentDepth) . $namespace . '#function_' . str_replace('\\', '', $function->getName());
    }

    public function pathForClass(array $context, string $class): string
    {
        return $this->relativeUri($this->currentDepth) . str_replace('\\', '/', $class) . '.html';
    }

    public function pathForNamespace(array $context, string $namespace): string
    {
        if ($namespace === '') {
            $namespace = Tree::getGlobalNamespacePageName();
        }
        return $this->relativeUri($this->currentDepth) . str_replace('\\', '/', $namespace) . '.html';
    }

    public function pathForMethod(array $context, MethodReflection $method)
    {
        /** @var Reflection $class */
        $class = $method->getClass();

        return $this->relativeUri($this->currentDepth) . str_replace('\\', '/', $class->getName()) . '.html#method_' . $method->getName();
    }

    public function pathForProperty(array $context, PropertyReflection $property)
    {
        /** @var Reflection $class */
        $class = $property->getClass();

        return $this->relativeUri($this->currentDepth) . str_replace('\\', '/', $class->getName()) . '.html#property_' . $property->getName();
    }

    public function pathForStaticFile(array $context, string $file): string
    {
        return $this->relativeUri($this->currentDepth) . $file;
    }

    /**
     * Generate the abbreviation of a class
     *
     * @param ClassReflection|string $class The class
     */
    public static function abbrClass($class, bool $absolute = false): string
    {
        if ($class instanceof ClassReflection) {
            $short = $class->getShortName();
            $class = $class->getName();

            if ($short === $class && !$absolute) {
                return htmlspecialchars($class, ENT_QUOTES);
            }
        } else {
            $parts = explode('\\', $class, ENT_QUOTES);

            if (count($parts) === 1 && !$absolute) {
                return htmlspecialchars($class);
            }

            $short = array_pop($parts);
        }

        return sprintf('<abbr title="%s">%s</abbr>', htmlentities($class, ENT_QUOTES), htmlspecialchars($short));
    }

    public function parseDesc(?string $desc, Reflection $classOrFunctionRefl): ?string
    {
        if ($desc === null || $desc === '') {
            return $desc;
        }

        $desc = (string) preg_replace_callback(
            '/@see ([^ ]+)/', // Match until a whitespace is found
            function ($match) use (&$classOrFunctionRefl): string {
                return $this->transformContentsIntoLinks($match[1], $classOrFunctionRefl);
            },
            $desc
        );

        $desc = (string) preg_replace_callback(
            '/\{@link (?!\})(?<contents>[^\r\n\t\f]+)\}/',
            function (array $match) use (&$classOrFunctionRefl): string {
                $data = rtrim($match['contents'], '}');
                return $this->transformContentsIntoLinks($data, $classOrFunctionRefl);
            },
            $desc
        );

        return $desc;
    }

    public function markdownToHtml(?string $desc): ?string
    {
        if ($desc === null || $desc === '') {
            return $desc;
        }

        if (null === $this->markdown) {
            $this->markdown = new Parsedown();
        }

        $desc           = str_replace(['<code>', '</code>'], ['```', '```'], $desc);
        $outputMarkdown = $this->markdown->text($desc);

        $matches = [];
        // Values without a space do not need to be forced into a <p> tag
        if (preg_match('#^<p>(\S+)</p>$#', $outputMarkdown, $matches) === 1) {
            return $matches[1] ?? $outputMarkdown;
        }

        return $outputMarkdown;
    }

    public function transformContentsIntoLinks(string $data, Reflection $classOrFunctionRefl): string
    {
            $isClassReflection    = $classOrFunctionRefl instanceof ClassReflection;
            $isFunctionReflection = $classOrFunctionRefl instanceof FunctionReflection;
        if (! $isClassReflection && ! $isFunctionReflection) {
            return $data;
        }

            /** @var ClassReflection|FunctionReflection $class */
            $class = $classOrFunctionRefl;

            // Example: Foo::bar_function_on_foo_class
            $classMethod = explode('::', trim($data, " \t\n\r"), 2);

            // Found "bar_function_on_foo_class", from example: bar_function_on_foo_class
        if (count($classMethod) === 1 && $class instanceof ClassReflection) {
            // In this case we resolve a link to a method name in the current class
            $method = $class->getMethod($classMethod[0]);
            if ($method !== false) {
                $short = $this->pathForMethod([], $method);
                return '[' . $data . '](' . $short . ')';
            }
        }

            /** @var \Doctum\Project|null $project Original one is not realistic */
            $project = $class->getProject();
        if ($project === null) {
            // This should never happen
            return $data;
        }

            $cr = $project->getClass($classMethod[0]);
        if ($cr->isPhpClass()) {
            $className = $cr->getName();
            return '[' . $className . '](https://www.php.net/' . $className . ')';
        }

        if (! $cr->isProjectClass()) {
            return $data;
        }

            // Found "bar_function_on_foo_class", from example: Foo::bar_function_on_foo_class
        if (count($classMethod) === 2) {
            // In this case we have a function name to resolve on the previously found class
            $method = $cr->getMethod($classMethod[1]);
            if ($method !== false) {
                $short = $this->pathForMethod([], $method);
                return '[' . $data . '](' . $short . ')';
            }
        }

            // Final case, we link the found class
            $short = $this->pathForClass([], $cr->getName());
            return '[' . $data . '](' . $short . ')';
    }

    /**
     * Seems not to be used
     *
     * @return string
     */
    public function getSnippet(string $string)
    {
        if (preg_match('/^(.{50,}?)\s.*/m', $string, $matches)) {
            $string = $matches[1];
        }

        return str_replace(["\n", "\r"], '', strip_tags($string));
    }

    protected function relativeUri(?int $value): string
    {
        if (!$value) {
            return '';
        }

        return rtrim(str_repeat('../', $value), '/') . '/';
    }

}
