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

namespace Doctum;

use ArrayAccess;
use ReturnTypeWillChange;// phpcs:ignore SlevomatCodingStandard.Namespaces.UnusedUses.UnusedUse
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter\Standard as PrettyPrinter;
use PhpParser\Parser as PhpParser;
use Doctum\Parser\ProjectTraverser;
use Doctum\Parser\ClassVisitor;
use Doctum\Parser\FunctionVisitor;
use Doctum\Parser\CodeParser;
use Doctum\Parser\DocBlockParser;
use Doctum\Parser\Filter\DefaultFilter;
use Doctum\Parser\Filter\FilterInterface;
use Doctum\Parser\NodeVisitor;
use Doctum\Parser\Parser;
use Doctum\Parser\ParserContext;
use Doctum\RemoteRepository\AbstractRemoteRepository;
use Doctum\Renderer\Renderer;
use Doctum\Renderer\ThemeSet;
use Doctum\Renderer\TwigExtension;
use Doctum\Store\JsonStore;
use Doctum\Store\StoreInterface;
use Doctum\Version\SingleVersionCollection;
use Doctum\Version\Version;
use Doctum\Version\VersionCollection;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Wdes\phpI18nL10n\Twig\Extension\I18n as I18nExtension;
use Wdes\phpI18nL10n\plugins\MoReader;
use Wdes\phpI18nL10n\Launcher;

/**
 * @implements \ArrayAccess<string,mixed>
 */
class Doctum implements ArrayAccess
{
    public const VERSION_MAJOR = 5;
    public const VERSION_MINOR = 5;
    public const VERSION_PATCH = 2;
    public const IS_DEV        = false;

    //@phpstan-ignore-next-line
    public const VERSION = self::VERSION_MAJOR . '.' . self::VERSION_MINOR . '.' . self::VERSION_PATCH . (self::IS_DEV ? '-dev' : '');

    public static $defaultVersionName = 'main';

    /**
     * @var \Symfony\Component\Finder\Finder
     */
    private $files;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var ThemeSet
     */
    private $themes;

    /**
     * @var Project
     */
    private $project;

    /**
     * @var Parser
     */
    private $parser;

    /**
     * @var Indexer
     */
    private $indexer;

    /**
     * @var Tree
     */
    private $tree;

    /**
     * @var ProjectTraverser
     */
    private $traverser;

    /**
     * @var Renderer
     */
    private $renderer;

    /**
     * @var StoreInterface
     */
    private $store;

    /**
     * @var \Doctum\Parser\Filter\FilterInterface
     */
    private $filter;

    /**
     * @var PrettyPrinter
     */
    private $pretty_printer;

    /**
     * @var CodeParser
     */
    private $code_parser;

    /**
     * @var NodeTraverser
     */
    private $php_traverser;

    /**
     * @var PhpParser
     */
    private $php_parser;

    /**
     * @var DocBlockParser
     */
    private $docblock_parser;

    /**
     * @var ParserContext
     */
    private $parser_context;

    /**
     * @var VersionCollection
     */
    private $_versions = null;// phpcs:ignore PSR2.Classes.PropertyDeclaration.Underscore

    /** @var array<string,bool|int|string|array|object> */
    private $config = null;

    /** @var string */
    private $theme = 'default';
    /** @var string */
    private $title = 'API';
    /** @var string */
    private $version;
    /** @var VersionCollection|string|null */
    private $versions = null;
    /** @var string[] */
    private $template_dirs = [];
    /** @var string */
    private $build_dir;
    /** @var string */
    private $cache_dir;
    /** @var string */
    private $project_dir;
    /** @var AbstractRemoteRepository|null */
    private $remoteRepository = null;
    /** @var string */
    private $source_url = '';
    /** @var string */
    private $source_dir = '';
    /** @var int */
    private $default_opened_level = 2;
    /** @var bool */
    private $insert_todos = false;
    /** @var bool */
    private $sort_class_properties = false;
    /** @var bool */
    private $sort_class_methods = false;
    /** @var bool */
    private $sort_class_constants = false;
    /** @var bool */
    private $sort_class_traits = false;
    /** @var bool */
    private $sort_class_interfaces = false;
    /** @var AbstractRemoteRepository|null */
    private $remote_repository = null;
    /** @var string|null */
    private $base_url = null;
    /** @var string|null */
    private $favicon = null;

    /**
     * @var array<string,string>|null
     */
    private $footer_link = null;

    /**
     * include parent properties and methods on class pages
     *
     * @var bool
     */
    private $include_parent_data = true;

    public function __construct($iterator = null, array $config = [])
    {
        $this->config = $config;

        if (null !== $iterator) {
            $this->files = $iterator;
        }

        $this->version    = self::$defaultVersionName;
        $this->build_dir  = getcwd() . DIRECTORY_SEPARATOR . 'build';
        $this->cache_dir  = getcwd() . DIRECTORY_SEPARATOR . 'cache';
        $this->source_dir = getcwd() . DIRECTORY_SEPARATOR;

        foreach ($config as $key => $value) {
            $this->{$key} = $value;
        }

        // I explain what I understood about the code:
        // This file is used in each Doctum config
        // The console commands boots the app and requires this config file
        // If it returns a Doctum instance, then okay it can go to the next step
        // -- Next step
        // The console does things and calls getProject() on this class and does the render
        // The console can call setVersion and make only one version render, using the --only-version name provided
        // End
        // -- Code explained
        // The ArrayAccess extends is because before the project was using pimple
        // I decided it was overkill and replaced it by pure PHP code, we all love pure PHP code.
        // But I still have to be backwards compatible with "hackers" that used this system "badly"
        // By replacing the things they did want, bug or feature nobody cares.
        // So allow the non standard configs to do this for example
        // $doctum['store'] = function () { return new ApiJsonStore(); };
        // Yes, it feels strange but that can not be removed silently now.
        // So allow array access get/set on this class
        // Without array access it still could be possible using the config because we do not whitelist configs params
    }

    public function setVersion(string $version): void
    {
        $this->versions = $version;
    }

    public function getProject(): Project
    {
        $project = $this->offsetGet('project');
        $project->setSourceDir($this->source_dir);
        return $project;
    }

    /**
     * @param string $offset
     * @param mixed  $value
     * @return void
     */
    #[ReturnTypeWillChange]
    public function offsetSet($offset, $value)
    {
        $this->{$offset} = $value;
    }

    /**
     * @param string $offset
     * @return bool
     */
    #[ReturnTypeWillChange]
    public function offsetExists($offset)
    {
        return isset($this->{$offset});
    }

    /**
     * @param string $offset
     * @return void
     */
    #[ReturnTypeWillChange]
    public function offsetUnset($offset)
    {
        unset($this->{$offset});
    }

    /**
     * @param string $offset
     * @return mixed
     */
    #[ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        // TODO: maybe drop this in the next major release
        if (! isset($this->{$offset}) && ! is_callable($this->{$offset})) {
            switch ($offset) {
                case 'project':
                    $this->project = $this->getBuiltProject();
                    break;
                case 'twig':
                    $this->twig = $this->getTwig();
                    break;
                case '_versions':
                    $this->_versions = $this->getVersions();
                    break;
                case 'traverser':
                    $this->traverser = $this->getTraverser();
                    break;
                case 'themes':
                    $this->themes = $this->getThemes();
                    break;
                case 'renderer':
                    $this->renderer = $this->getRenderer();
                    break;
                case 'parser':
                    $this->parser = $this->getParser();
                    break;
                case 'tree':
                    $this->tree = $this->getTree();
                    break;
                case 'indexer':
                    $this->indexer = $this->getIndexer();
                    break;
                case 'store':
                    $this->store = $this->getStore();
                    break;
                case 'filter':
                    $this->filter = $this->getFilter();
                    break;
                case 'parser_context':
                    $this->parser_context = $this->getParserContext();
                    break;
                case 'pretty_printer':
                    $this->pretty_printer = $this->getPrettyPrinter();
                    break;
                case 'code_parser':
                    $this->code_parser = $this->getCodeParser();
                    break;
                case 'docblock_parser':
                    $this->docblock_parser = $this->getDocblockParser();
                    break;
                case 'php_parser':
                    $this->php_parser = $this->getPhpParser();
                    break;
                case 'php_traverser':
                    $this->php_traverser = $this->getPhpTraverser();
                    break;
            }
        }
        // This is for users to be able to override our properties like they want..
        // TODO: maybe drop this in the next major release
        if (isset($this->{$offset}) && is_callable($this->{$offset})) {
            $this->{$offset} = ($this->{$offset})($this);
        }
        return isset($this->{$offset}) ? $this->{$offset} : null;
    }

    private function getLanguageFromConfig(): string
    {
        /** @var string $language */
        $language = $this->config['language'] ?? 'en';
        return $language;
    }

    private function getTwig(): Environment
    {
        $dataDir  = __DIR__ . '/../locale/';
        $moReader = new MoReader(
            ['localeDir' => $dataDir]
        );
        $moReader->readFile($dataDir . $this->getLanguageFromConfig() . '.mo');
        Launcher::setPlugin($moReader);
        $twig = new Environment(
            new FilesystemLoader(['/']),
            [
            'strict_variables' => true,
            'debug' => true,
            'auto_reload' => true,
            'cache' => false,
            ]
        );
        Doctum::addTwigExtensions($twig);

        return $twig;
    }

    /**
     * @internal Do not use outside of the project
     */
    public static function addTwigExtensions(Environment $twig): void
    {
        $twig->addExtension(new TwigExtension());
        $twig->addExtension(new I18nExtension());
    }

    private function getBuiltProject(): Project
    {
        $project = new Project(
            $this['store'],
            $this['_versions'],
            [
            'build_dir' => $this->build_dir,
            'cache_dir' => $this->cache_dir,
            'remote_repository' => $this->remote_repository,
            'include_parent_data' => $this->include_parent_data,
            'default_opened_level' => $this->default_opened_level,
            'theme' => $this->theme,
            'title' => $this->title,
            'source_url' => $this->source_url,
            'base_url' => $this->base_url,
            'favicon' => $this->favicon,
            'insert_todos' => $this->insert_todos,
            'footer_link' => $this->footer_link,
            'sort_class_properties' => $this->sort_class_properties,
            'sort_class_methods' => $this->sort_class_methods,
            'sort_class_constants' => $this->sort_class_constants,
            'sort_class_traits' => $this->sort_class_traits,
            'sort_class_interfaces' => $this->sort_class_interfaces,
            'language' => $this->getLanguageFromConfig(),
            ]
        );
        $project->setRenderer($this['renderer']);
        $project->setParser($this['parser']);

        return $project;
    }

    private function getVersions(): VersionCollection
    {
        $versions = $this->versions ?? $this->version;

        if (is_string($versions)) {
            $versions = new Version($versions);
        }

        if ($versions instanceof Version) {
            $versions = new SingleVersionCollection($versions);
        }

        return $versions;
    }

    private function getTraverser(): ProjectTraverser
    {
        $visitors = [
            new ClassVisitor\InheritdocClassVisitor(),
            new ClassVisitor\MethodClassVisitor(),
            new ClassVisitor\PropertyClassVisitor($this['parser_context']),
        ];

        if ($this['remote_repository'] instanceof AbstractRemoteRepository) {
            $visitors[] = new ClassVisitor\ViewSourceClassVisitor($this['remote_repository']);
            $visitors[] = new FunctionVisitor\ViewSourceFunctionVisitor($this['remote_repository']);
        }

        return new ProjectTraverser($visitors);
    }

    private function getThemes(): ThemeSet
    {
        /** @var string[] $templates */
        $templates   = $this->config['template_dirs'] ?? [];
        $templates[] = __DIR__ . '/Resources/themes';

        return new ThemeSet($templates);
    }

    private function getRenderer(): Renderer
    {
        return new Renderer($this['twig'], $this['themes'], $this['tree'], $this['indexer']);
    }

    private function getParser(): Parser
    {
        return new Parser($this->files, $this['store'], $this['code_parser'], $this['traverser']);
    }

    private function getTree(): Tree
    {
        return new Tree();
    }

    private function getIndexer(): Indexer
    {
        return new Indexer();
    }

    private function getStore(): StoreInterface
    {
        return new JsonStore();
    }

    private function getFilter(): FilterInterface
    {
        return new DefaultFilter();
    }

    private function getParserContext(): ParserContext
    {
        return new ParserContext($this['filter'], $this['docblock_parser'], $this['pretty_printer']);
    }

    private function getPrettyPrinter(): PrettyPrinter
    {
        return new PrettyPrinter();
    }

    private function getCodeParser(): CodeParser
    {
        return new CodeParser($this['parser_context'], $this['php_parser'], $this['php_traverser']);
    }

    private function getDocblockParser(): DocBlockParser
    {
        return new DocBlockParser();
    }

    private function getPhpParser(): PhpParser
    {
        return (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
    }

    private function getPhpTraverser(): NodeTraverser
    {
        $traverser = new NodeTraverser();
        $traverser->addVisitor(new NameResolver());
        $traverser->addVisitor(new NodeVisitor($this['parser_context']));

        return $traverser;
    }

}
