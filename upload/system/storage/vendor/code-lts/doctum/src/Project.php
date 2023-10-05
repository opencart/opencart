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

use Doctum\Parser\Parser;
use Doctum\Reflection\ClassReflection;
use Doctum\Reflection\FunctionReflection;
use Doctum\Reflection\LazyClassReflection;
use Doctum\Renderer\Renderer;
use Doctum\RemoteRepository\AbstractRemoteRepository;
use Doctum\Store\StoreInterface;
use Doctum\Version\SingleVersionCollection;
use Doctum\Version\Version;
use Doctum\Version\VersionCollection;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Project represents an API project.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class Project
{
    protected $versions;
    protected $store;

    /** @var Parser */
    protected $parser;

    /** @var Renderer */
    protected $renderer;

    /** @var ClassReflection[] */
    protected $classes;

    /** @var array<string,array<string,FunctionReflection>> */
    protected $functions = [];

    protected $namespaceClasses;
    protected $namespaceInterfaces;
    protected $namespaceExceptions;
    /** @var array<string,string> */
    protected $namespaces;
    protected $config;
    /** @var string|null */
    protected $version;
    protected $filesystem;
    protected $interfaces;
    /** @var string */
    protected $sourceDir;

    public function __construct(StoreInterface $store, ?VersionCollection $versions = null, array $config = [])
    {
        if (null === $versions) {
            $versions = new SingleVersionCollection(new Version(Doctum::$defaultVersionName));
        }
        $this->versions   = $versions;
        $this->store      = $store;
        $this->config     = array_merge(
            [
                'build_dir' => sys_get_temp_dir() . 'doctum/build',
                'cache_dir' => sys_get_temp_dir() . 'doctum/cache',
                'include_parent_data' => true,
                'theme' => 'default',
            ],
            $config
        );
        $this->filesystem = new Filesystem();

        if (count($this->versions) > 1) {
            foreach (['build_dir', 'cache_dir'] as $dir) {
                if (false === strpos($this->config[$dir], '%version%')) {
                    throw new \LogicException(
                        sprintf(
                            'The "%s" setting must have the "%%version%%" placeholder'
                            . ' as the project has more than one version.',
                            $dir
                        )
                    );
                }
            }
        }

        $this->initialize();
    }

    public function setRenderer(Renderer $renderer): void
    {
        $this->renderer = $renderer;
    }

    public function setParser(Parser $parser): void
    {
        $this->parser = $parser;
    }

    public function setSourceDir(string $sourceDir): void
    {
        $this->sourceDir = $sourceDir;
    }

    public function getSourceDir(): string
    {
        return $this->replaceVars($this->sourceDir);
    }

    public function getConfig($name, $default = null)
    {
        return $this->config[$name] ?? $default;
    }

    public function getVersion(): ?string
    {
        return $this->version;
    }

    public function getVersions(): array
    {
        return $this->versions->getVersions();
    }

    public function update($callback = null, $force = false): void
    {
        foreach ($this->versions as $version) {
            $this->switchVersion($version, $callback, $force);
            $this->parseVersion($version, null, $callback, $force);
            $this->renderVersion($version, null, $callback, $force);
        }
    }

    public function parse($callback = null, $force = false): void
    {
        $previous = null;
        foreach ($this->versions as $version) {
            $this->switchVersion($version, $callback, $force);

            $this->parseVersion($version, $previous, $callback, $force);

            $previous = $this->getCacheDir();
        }
    }

    public function render($callback = null, $force = false): void
    {
        $previous = null;
        foreach ($this->versions as $version) {
            // here, we don't want to flush the parse cache, as we are rendering
            $this->switchVersion($version, $callback, false);

            $this->renderVersion($version, $previous, $callback, $force);

            $previous = $this->getBuildDir();
        }
    }

    public function switchVersion(Version $version, $callback = null, $force = false): void
    {
        if (null !== $callback) {
            call_user_func($callback, Message::SWITCH_VERSION, $version);
        }

        $this->version = $version->getName();

        $this->initialize();

        if (!$force) {
            $this->read();
        }
    }

    public function hasNamespaces(): bool
    {
        // if there is only one namespace and this is the global one, it means that there is no namespace in the project
        return [''] != array_keys($this->namespaces);
    }

    public function hasNamespace(string $namespace): bool
    {
        return array_key_exists($namespace, $this->namespaces);
    }

    /**
     * @return string[]
     */
    public function getNamespaces(): array
    {
        ksort($this->namespaces);

        return array_keys($this->namespaces);
    }

    public function addNamespace(string $namespace): void
    {
        $this->namespaces[$namespace] = $namespace;
        // add sub-namespaces

        while ($namespace = substr($namespace, 0, (int) strrpos($namespace, '\\'))) {
            $this->namespaces[$namespace] = $namespace;
        }
    }

    /**
      * @return array<string,FunctionReflection>
      */
    public function getNamespaceFunctions($namespace): array
    {
        if (! isset($this->functions[$namespace])) {
            return [];
        }

        ksort($this->functions[$namespace]);

        return $this->functions[$namespace];
    }

    public function getNamespaceAllClasses($namespace)
    {
        $classes = array_merge(
            $this->getNamespaceExceptions($namespace),
            $this->getNamespaceInterfaces($namespace),
            $this->getNamespaceClasses($namespace)
        );

        ksort($classes);

        return $classes;
    }

    public function getNamespaceExceptions($namespace)
    {
        if (!isset($this->namespaceExceptions[$namespace])) {
            return [];
        }

        ksort($this->namespaceExceptions[$namespace]);

        return $this->namespaceExceptions[$namespace];
    }

    public function getNamespaceClasses($namespace)
    {
        if (!isset($this->namespaceClasses[$namespace])) {
            return [];
        }

        ksort($this->namespaceClasses[$namespace]);

        return $this->namespaceClasses[$namespace];
    }

    public function getNamespaceInterfaces($namespace)
    {
        if (!isset($this->namespaceInterfaces[$namespace])) {
            return [];
        }

        ksort($this->namespaceInterfaces[$namespace]);

        return $this->namespaceInterfaces[$namespace];
    }

    public function getNamespaceSubNamespaces($parent): array
    {
        $prefix     = strlen($parent) ? ($parent . '\\') : '';
        $len        = strlen($prefix);
        $namespaces = [];

        foreach ($this->namespaces as $sub) {
            $prefixMatch = substr($sub, 0, $len) === $prefix;
            if ($prefixMatch && strpos(substr($sub, $len), '\\') === false) {
                $namespaces[] = $sub;
            }
        }

        return $namespaces;
    }

    public function addFunction(FunctionReflection $fun): void
    {
        $this->functions[$fun->getNamespace()][$fun->getName()] = $fun;
        $fun->setProject($this);
        $this->addNamespace($fun->getNamespace() ?? '');
    }

    public function addClass(ClassReflection $class): void
    {
        $this->classes[$class->getName()] = $class;
        $class->setProject($this);

        if ($class->isProjectClass()) {
            $this->updateCache($class);
        }
    }

    public function removeClass(ClassReflection $class): void
    {
        unset($this->classes[$class->getName()]);
        unset($this->interfaces[$class->getName()]);
        unset($this->namespaceClasses[$class->getNamespace()][$class->getName()]);
        unset($this->namespaceInterfaces[$class->getNamespace()][$class->getName()]);
        unset($this->namespaceExceptions[$class->getNamespace()][$class->getName()]);
    }

    public function getProjectInterfaces(): array
    {
        $interfaces = [];
        foreach ($this->interfaces as $interface) {
            if ($interface->isProjectClass()) {
                $interfaces[$interface->getName()] = $interface;
            }
        }
        ksort($interfaces);

        return $interfaces;
    }

    public function getProjectClasses(): array
    {
        $classes = [];
        foreach ($this->classes as $name => $class) {
            if ($class->isProjectClass()) {
                $classes[$name] = $class;
            }
        }
        ksort($classes);

        return $classes;
    }

    /**
     * @return FunctionReflection[]
     */
    public function getProjectFunctions(): array
    {
        $functions = [];

        foreach ($this->functions as $allFunctionsOfNamespace) {
            foreach ($allFunctionsOfNamespace as $functionInNamespace) {
                $functions[] = $functionInNamespace;
            }
        }

        usort(
            $functions,
            static function (FunctionReflection $a, FunctionReflection $b): int {
                return strcmp($a->__toString(), $b->__toString());
            }
        );

        return $functions;
    }

    public function getClass(string $name): ClassReflection
    {
        $name = ltrim($name, '\\');

        if (isset($this->classes[$name])) {
            return $this->classes[$name];
        }

        $class = new LazyClassReflection($name);
        $this->addClass($class);

        return $class;
    }

    /**
     * this must only be used in LazyClassReflection to get the right values
     */
    public function loadClass(string $name): ?ClassReflection
    {
        $name = ltrim($name, '\\');

        if ($this->getClass($name) instanceof LazyClassReflection) {
            try {
                $this->addClass($this->store->readClass($this, $name));
            } catch (\InvalidArgumentException $e) {
                // probably a PHP built-in class
                return null;
            }
        }

        return $this->classes[$name];
    }

    public function initialize()
    {
        $this->namespaces          = [];
        $this->interfaces          = [];
        $this->classes             = [];
        $this->namespaceClasses    = [];
        $this->namespaceInterfaces = [];
        $this->namespaceExceptions = [];
    }

    public function read(): void
    {
        $this->initialize();

        foreach ($this->store->readProject($this) as $classOrFun) {
            if ($classOrFun instanceof FunctionReflection) {
                $this->addFunction($classOrFun);
            } elseif ($classOrFun instanceof ClassReflection) {
                $this->addClass($classOrFun);
            }
        }
    }

    public function getBuildDir()
    {
        return $this->prepareDir($this->config['build_dir']);
    }

    public function getCacheDir()
    {
        return $this->prepareDir($this->config['cache_dir']);
    }

    public function flushDir(string $dir): void
    {
        $this->filesystem->remove($dir);
        $this->filesystem->mkdir($dir);
        file_put_contents($dir . '/DOCTUM_VERSION', Doctum::VERSION);
        file_put_contents($dir . '/PROJECT_VERSION', $this->version);
    }

    public function seedCache(string $previous, string $current): void
    {
        $this->filesystem->remove($current);
        $this->filesystem->mirror($previous, $current);
        $this->read();
    }

    /**
     * @internal Will be removed without notice someday
     */
    public static function isPhpTypeHint(string $hint): bool
    {
        // TODO: improve
        return in_array(
            strtolower($hint),
            [
                '',
                'scalar',
                'object',
                'boolean',
                'bool',
                'true',
                'false',
                'int',
                'integer',
                'array',
                'string',
                'mixed',
                'void',
                'null',
                'resource',
                'double',
                'float',
                'callable',
                '$this',
            ]
        );
    }

    /**
     * @var array<string,true>
     * @internal Will be removed without notice someday
     */
    public static $phpInternalClasses = [
        'stdclass' => true,
        'exception' => true,
        'errorexception' => true,
        'error' => true,
        'parseerror' => true,
        'typeerror' => true,
        'arithmeticerror' => true,
        'divisionbyzeroerror' => true,
        'closure' => true,
        'generator' => true,
        'closedgeneratorexception' => true,
        'datetime' => true,
        'datetimeimmutable' => true,
        'datetimezone' => true,
        'dateinterval' => true,
        'dateperiod' => true,
        'libxmlerror' => true,
        'sqlite3' => true,
        'sqlite3stmt' => true,
        'sqlite3result' => true,
        'domexception' => true,
        'domstringlist' => true,
        'domnamelist' => true,
        'domimplementationlist' => true,
        'domimplementationsource' => true,
        'domimplementation' => true,
        'domnode' => true,
        'domnamespacenode' => true,
        'domdocumentfragment' => true,
        'domdocument' => true,
        'domnodelist' => true,
        'domnamednodemap' => true,
        'domcharacterdata' => true,
        'domattr' => true,
        'domelement' => true,
        'domtext' => true,
        'domcomment' => true,
        'domtypeinfo' => true,
        'domuserdatahandler' => true,
        'domdomerror' => true,
        'domerrorhandler' => true,
        'domlocator' => true,
        'domconfiguration' => true,
        'domcdatasection' => true,
        'domdocumenttype' => true,
        'domnotation' => true,
        'domentity' => true,
        'domentityreference' => true,
        'domprocessinginstruction' => true,
        'domstringextend' => true,
        'domxpath' => true,
        'finfo' => true,
        'logicexception' => true,
        'badfunctioncallexception' => true,
        'badmethodcallexception' => true,
        'domainexception' => true,
        'invalidargumentexception' => true,
        'lengthexception' => true,
        'outofrangeexception' => true,
        'runtimeexception' => true,
        'outofboundsexception' => true,
        'overflowexception' => true,
        'rangeexception' => true,
        'underflowexception' => true,
        'unexpectedvalueexception' => true,
        'recursiveiteratoriterator' => true,
        'iteratoriterator' => true,
        'filteriterator' => true,
        'recursivefilteriterator' => true,
        'callbackfilteriterator' => true,
        'recursivecallbackfilteriterator' => true,
        'parentiterator' => true,
        'limititerator' => true,
        'cachingiterator' => true,
        'recursivecachingiterator' => true,
        'norewinditerator' => true,
        'appenditerator' => true,
        'infiniteiterator' => true,
        'regexiterator' => true,
        'recursiveregexiterator' => true,
        'emptyiterator' => true,
        'recursivetreeiterator' => true,
        'arrayobject' => true,
        'arrayiterator' => true,
        'recursivearrayiterator' => true,
        'splfileinfo' => true,
        'directoryiterator' => true,
        'filesystemiterator' => true,
        'recursivedirectoryiterator' => true,
        'globiterator' => true,
        'splfileobject' => true,
        'spltempfileobject' => true,
        'spldoublylinkedlist' => true,
        'splqueue' => true,
        'splstack' => true,
        'splheap' => true,
        'splminheap' => true,
        'splmaxheap' => true,
        'splpriorityqueue' => true,
        'splfixedarray' => true,
        'splobjectstorage' => true,
        'multipleiterator' => true,
        'pdoexception' => true,
        'pdo' => true,
        'pdostatement' => true,
        'pdorow' => true,
        'sessionhandler' => true,
        'reflectionexception' => true,
        'reflection' => true,
        'reflectionfunctionabstract' => true,
        'reflectionfunction' => true,
        'reflectiongenerator' => true,
        'reflectionparameter' => true,
        'reflectiontype' => true,
        'reflectionmethod' => true,
        'reflectionclass' => true,
        'reflectionobject' => true,
        'reflectionproperty' => true,
        'reflectionextension' => true,
        'reflectionzendextension' => true,
        '__php_incomplete_class' => true,
        'php_user_filter' => true,
        'directory' => true,
        'assertionerror' => true,
        'simplexmlelement' => true,
        'simplexmliterator' => true,
        'pharexception' => true,
        'phar' => true,
        'phardata' => true,
        'pharfileinfo' => true,
        'xmlreader' => true,
        'xmlwriter' => true,
        'collator' => true,
        'numberformatter' => true,
        'normalizer' => true,
        'locale' => true,
        'messageformatter' => true,
        'intldateformatter' => true,
        'resourcebundle' => true,
        'transliterator' => true,
        'intltimezone' => true,
        'intlcalendar' => true,
        'intlgregoriancalendar' => true,
        'spoofchecker' => true,
        'intlexception' => true,
        'intliterator' => true,
        'intlbreakiterator' => true,
        'intlrulebasedbreakiterator' => true,
        'intlcodepointbreakiterator' => true,
        'intlpartsiterator' => true,
        'uconverter' => true,
        'intlchar' => true,
        'traversable' => true,
        'iteratoraggregate' => true,
        'iterator' => true,
        'arrayaccess' => true,
        'serializable' => true,
        'throwable' => true,
        'datetimeinterface' => true,
        'jsonserializable' => true,
        'recursiveiterator' => true,
        'outeriterator' => true,
        'countable' => true,
        'seekableiterator' => true,
        'splobserver' => true,
        'splsubject' => true,
        'sessionhandlerinterface' => true,
        'sessionidinterface' => true,
        'sessionupdatetimestamphandlerinterface' => true,
        'reflector' => true,
        'stringable' => true,// PHP >= 8.0
        'weakreference' => true,// PHP >= 7.4.0
    ];

    protected function updateCache(ClassReflection $class)
    {
        $name = $class->getName();

        $this->addNamespace($class->getNamespace() ?? '');

        if ($class->isException()) {
            $this->namespaceExceptions[$class->getNamespace() ?? ''][$name] = $class;
        } elseif ($class->isInterface()) {
            $this->namespaceInterfaces[$class->getNamespace() ?? ''][$name] = $class;
            $this->interfaces[$name]                                        = $class;
        } else {
            $this->namespaceClasses[$class->getNamespace() ?? ''][$name] = $class;
        }
    }

    protected function prepareDir($dir)
    {
        static $prepared = [];

        $dir = $this->replaceVars($dir);

        if (isset($prepared[$dir])) {
            return $dir;
        }

        $prepared[$dir] = true;

        if (!is_dir($dir)) {
            $this->flushDir($dir);

            return $dir;
        }

        $doctumVersion = null;
        if (file_exists($dir . '/DOCTUM_VERSION')) {
            $doctumVersion = file_get_contents($dir . '/DOCTUM_VERSION');
        }

        if (Doctum::VERSION !== $doctumVersion) {
            $this->flushDir($dir);
        }

        return $dir;
    }

    protected function replaceVars(string $pattern): string
    {
        return str_replace('%version%', (string) $this->version, $pattern);
    }

    protected function parseVersion(Version $version, $previous, $callback = null, $force = false): void
    {
        if (null === $this->parser) {
            throw new \LogicException('You must set a parser.');
        }

        if ($version->isFrozen() && count($this->classes) > 0) {
            return;
        }

        if ($force) {
            $this->store->flushProject($this);
        }

        if ($previous && 0 === count($this->classes)) {
            $this->seedCache($previous, $this->getCacheDir());
        }

        $transaction = $this->parser->parse($this, $callback);

        if (null !== $callback) {
            call_user_func($callback, Message::PARSE_VERSION_FINISHED, $transaction);
        }
    }

    protected function renderVersion(Version $version, $previous, $callback = null, $force = false): void
    {
        if (null === $this->renderer) {
            throw new \LogicException('You must set a renderer.');
        }

        $frozen = $version->isFrozen() && $this->renderer->isRendered($this) && $this->version === file_get_contents($this->getBuildDir() . '/PROJECT_VERSION');

        if ($force && !$frozen) {
            $this->flushDir($this->getBuildDir());
        }

        if ($previous && !$this->renderer->isRendered($this)) {
            $this->seedCache($previous, $this->getBuildDir());
        }

        $diff = $this->renderer->render($this, $callback, $force);

        if (null !== $callback) {
            call_user_func($callback, Message::RENDER_VERSION_FINISHED, $diff);
        }
    }

    public function getSourceRoot()
    {
        $root = $this->getConfig('source_url');
        if (! $root) {
            return;
        }

        if (strpos($root, 'github') !== false) {
            return $root . '/tree/' . $this->version;
        }
    }

    public function getViewSourceUrl($relativePath, $line)
    {
        $remoteRepository = $this->getConfig('remote_repository');

        if ($remoteRepository instanceof AbstractRemoteRepository) {
            return $remoteRepository->getFileUrl($this->version ?? '', $relativePath, $line);
        }

        return '';
    }

    public function getBaseUrl(): ?string
    {
        $url = $this->getConfig('base_url');
        return $url === null ? null : rtrim($url, '/');
    }

    public function hasFooterLink(): bool
    {
        return $this->getConfig('footer_link') !== null && is_array($this->getConfig('footer_link'));
    }

    /**
     * @return array<string,string>
     * @phpstan-return array{href: string, rel: string, target: string, before_text: string, link_text: string, after_text: string}
     */
    public function getFooterLink(): array
    {
        $link = $this->getConfig('footer_link');
        return [
            'href' => $link['href'] ?? '',
            'target' => $link['target'] ?? '',
            'rel' => $link['rel'] ?? '',
            'before_text' => $link['before_text'] ?? '',
            'link_text' => $link['link_text'] ?? '',
            'after_text' => $link['after_text'] ?? '',
        ];
    }

}
