<?php

/**
 * SCSSPHP
 *
 * @copyright 2012-2020 Leaf Corcoran
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 * @link http://scssphp.github.io/scssphp
 */

namespace ScssPhp\ScssPhp;

use League\Uri\Contracts\UriInterface;
use League\Uri\Uri;
use ScssPhp\ScssPhp\Ast\Css\CssParentNode;
use ScssPhp\ScssPhp\Ast\Sass\Statement\Stylesheet;
use ScssPhp\ScssPhp\Collection\Map;
use ScssPhp\ScssPhp\Compiler\LegacyValueVisitor;
use ScssPhp\ScssPhp\Evaluation\EvaluateVisitor;
use ScssPhp\ScssPhp\Exception\SassException;
use ScssPhp\ScssPhp\Exception\SassScriptException;
use ScssPhp\ScssPhp\Function\FunctionRegistry;
use ScssPhp\ScssPhp\Importer\FilesystemImporter;
use ScssPhp\ScssPhp\Importer\ImportCache;
use ScssPhp\ScssPhp\Importer\Importer;
use ScssPhp\ScssPhp\Importer\LegacyCallbackImporter;
use ScssPhp\ScssPhp\Importer\NoOpImporter;
use ScssPhp\ScssPhp\Logger\DeprecationProcessingLogger;
use ScssPhp\ScssPhp\Logger\LoggerInterface;
use ScssPhp\ScssPhp\Logger\StreamLogger;
use ScssPhp\ScssPhp\Node\Number;
use ScssPhp\ScssPhp\SassCallable\BuiltInCallable;
use ScssPhp\ScssPhp\Serializer\Serializer;
use ScssPhp\ScssPhp\Util\Path;
use ScssPhp\ScssPhp\Value\ListSeparator;
use ScssPhp\ScssPhp\Value\SassArgumentList;
use ScssPhp\ScssPhp\Value\SassBoolean;
use ScssPhp\ScssPhp\Value\SassColor;
use ScssPhp\ScssPhp\Value\SassList;
use ScssPhp\ScssPhp\Value\SassMap;
use ScssPhp\ScssPhp\Value\SassNull;
use ScssPhp\ScssPhp\Value\SassNumber;
use ScssPhp\ScssPhp\Value\SassString;
use ScssPhp\ScssPhp\Value\Value;
use ScssPhp\ScssPhp\Visitor\CssVisitor;

final class Compiler
{
    const SOURCE_MAP_NONE   = 0;
    const SOURCE_MAP_INLINE = 1;
    const SOURCE_MAP_FILE   = 2;

    public static $true         = [Type::T_KEYWORD, 'true'];
    public static $false        = [Type::T_KEYWORD, 'false'];
    public static $null         = [Type::T_NULL];
    public static $emptyList    = [Type::T_LIST, '', []];
    public static $emptyMap     = [Type::T_MAP, [], []];
    public static $emptyString  = [Type::T_STRING, '"', []];

    /**
     * @var list<Importer>
     */
    private array $importers = [];

    /**
     * @var array<int, string|callable(string): (string|null)>
     */
    private array $importPaths = [];

    /**
     * @var array<string, array{0: callable, 1: string[]}>
     */
    private array $userFunctions = [];

    /**
     * @var array<string, Value>
     */
    private array $registeredVars = [];

    /**
     * @var self::SOURCE_MAP_*
     */
    private int $sourceMap = self::SOURCE_MAP_NONE;

    /**
     * @var array{sourceRoot?: string, sourceMapFilename?: string|null, sourceMapURL?: string|null, outputSourceFiles?: bool, sourceMapRootpath?: string, sourceMapBasepath?: string}
     */
    private array $sourceMapOptions = [];

    private bool $charset = true;

    private bool $quietDeps = false;

    /**
     * Deprecation warnings of these types will be ignored.
     *
     * @var Deprecation[]
     */
    private array $silenceDeprecations = [];

    /**
     * Deprecation warnings of one of these types will cause an error to be
     * thrown.
     *
     * Future deprecations in this list will still cause an error even if they
     * are not also in {@see $futureDeprecations}.
     *
     * @var Deprecation[]
     */
    private array $fatalDeprecations = [];

    /**
     * Future deprecations that the user has explicitly opted into.
     *
     * @var Deprecation[]
     */
    private array $futureDeprecations = [];

    private bool $verbose = false;

    private OutputStyle $outputStyle = OutputStyle::EXPANDED;

    private LoggerInterface $logger;

    public function __construct()
    {
        $this->logger = new StreamLogger(fopen('php://stderr', 'w'), true);
    }

    /**
     * Sets an alternative logger.
     *
     * Changing the logger in the middle of the compilation is not
     * supported and will result in an undefined behavior.
     */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    /**
     * Replaces variables.
     *
     * @param array<string, Value> $variables
     */
    public function replaceVariables(array $variables): void
    {
        $this->registeredVars = [];
        $this->addVariables($variables);
    }

    /**
     * Replaces variables.
     *
     * @param array<string, Value> $variables
     */
    public function addVariables(array $variables): void
    {
        foreach ($variables as $name => $value) {
            if (!$value instanceof Value) {
                throw new \InvalidArgumentException('Passing raw values to as custom variables to the Compiler is not supported anymore. Use "\ScssPhp\ScssPhp\ValueConverter::parseValue" or "\ScssPhp\ScssPhp\ValueConverter::fromPhp" to convert them instead.');
            }

            $this->registeredVars[$name] = $value;
        }
    }

    /**
     * Unset variable
     */
    public function unsetVariable(string $name): void
    {
        unset($this->registeredVars[$name]);
    }

    /**
     * Returns list of variables
     *
     * @return array<string, Value>
     */
    public function getVariables(): array
    {
        return $this->registeredVars;
    }

    public function addImporter(Importer $importer): void
    {
        $this->importers[] = $importer;
    }

    /**
     * Add import path
     *
     * @param string|callable(string): (string|null) $path
     */
    public function addImportPath(string|callable $path): void
    {
        if (! \in_array($path, $this->importPaths)) {
            $this->importPaths[] = $path;
        }
    }

    /**
     * Set import paths
     *
     * @param string|array<string|callable(string): (string|null)> $path
     */
    public function setImportPaths($path): void
    {
        $paths = (array) $path;
        $actualImportPaths = array_filter($paths, function ($path) {
            return $path !== '';
        });

        if (\count($actualImportPaths) !== \count($paths)) {
            throw new \InvalidArgumentException('Passing an empty string in the import paths to refer to the current working directory is not supported anymore. If that\'s the intended behavior, the value of "getcwd()" should be used directly instead. If this was used for resolving relative imports of the input alongside "chdir" with the source directory, the path of the input file should be passed to "compileString()" instead.');
        }

        $this->importPaths = $actualImportPaths;
    }

    /**
     * Sets the output style.
     */
    public function setOutputStyle(OutputStyle $style): void
    {
        $this->outputStyle = $style;
    }

    /**
     * Configures the handling of non-ASCII outputs.
     *
     * If $charset is `true`, this will include a `@charset` declaration or a
     * UTF-8 [byte-order mark][] if the stylesheet contains any non-ASCII
     * characters. Otherwise, it will never include a `@charset` declaration or a
     * byte-order mark.
     *
     * [byte-order mark]: https://en.wikipedia.org/wiki/Byte_order_mark#UTF-8
     */
    public function setCharset(bool $charset): void
    {
        $this->charset = $charset;
    }

    /**
     * If set to `true`, this will silence compiler warnings emitted for stylesheets loaded through {@see $importers} or {@see $importPaths}
     */
    public function setQuietDeps(bool $quietDeps): void
    {
        $this->quietDeps = $quietDeps;
    }

    /**
     * Configures the deprecation warning types that will be ignored.
     *
     * @param Deprecation[] $silenceDeprecations
     */
    public function setSilenceDeprecations(array $silenceDeprecations): void
    {
        $this->silenceDeprecations = $silenceDeprecations;
    }

    /**
     * Configures the deprecation warning types that will cause an error to be thrown.
     *
     * @param Deprecation[] $fatalDeprecations
     */
    public function setFatalDeprecations(array $fatalDeprecations): void
    {
        $this->fatalDeprecations = $fatalDeprecations;
    }

    /**
     * Configures the opt-in for future deprecation warning types.
     *
     * @param Deprecation[] $futureDeprecations
     */
    public function setFutureDeprecations(array $futureDeprecations): void
    {
        $this->futureDeprecations = $futureDeprecations;
    }

    /**
     * Configures the verbosity of deprecation warnings.
     *
     * In non-verbose mode, repeated deprecations are hidden once reaching the
     * threshold, with a summary at the end. In verbose mode, all deprecation
     * warnings are emitted to the logger.
     */
    public function setVerbose(bool $verbose): void
    {
        $this->verbose = $verbose;
    }

    /**
     * Enable/disable source maps
     *
     * @param self::SOURCE_MAP_* $sourceMap
     */
    public function setSourceMap(int $sourceMap): void
    {
        $this->sourceMap = $sourceMap;
    }

    /**
     * Set source map options
     *
     * @param array{sourceRoot?: string, sourceMapFilename?: string|null, sourceMapURL?: string|null, outputSourceFiles?: bool, sourceMapRootpath?: string, sourceMapBasepath?: string} $sourceMapOptions
     */
    public function setSourceMapOptions(array $sourceMapOptions): void
    {
        $this->sourceMapOptions = $sourceMapOptions;
    }

    /**
     * Registers a custom function
     *
     * @param (callable(list<Value>): Value)|(callable(list<array|Number>): (array|Number)) $callback
     * @param string[] $argumentDeclaration
     */
    public function registerFunction(string $name, callable $callback, array $argumentDeclaration): void
    {
        $normalizedName = $this->normalizeName($name);
        if (FunctionRegistry::isBuiltinFunction($normalizedName)) {
            throw new \InvalidArgumentException(sprintf('The "%s" function is a core sass function. Overriding it with a custom implementation through "%s" is not supported .', $name, __METHOD__));
        }

        $this->userFunctions[$normalizedName] = [$callback, $argumentDeclaration];
    }

    /**
     * Unregisters a custom function
     */
    public function unregisterFunction(string $name): void
    {
        unset($this->userFunctions[$this->normalizeName($name)]);
    }

    private function normalizeName(string $name): string
    {
        return str_replace('-', '_', $name);
    }

    /**
     * Compiles the provided scss file into CSS.
     *
     * Imports are resolved by trying, in order:
     *
     * * Loading a file relative to $path.
     *
     * * Each importer in {@see $importers}.
     *
     * * Each load path in {@see $importPaths}. Note that this is a shorthand for adding
     *   {@see FilesystemImporter}s to {@see $importers}.
     *
     * @throws SassException when the source fails to compile
     */
    public function compileFile(string $path): CompilationResult
    {
        // Force loading the CssParentNode and CssVisitor before using the AST classes because of a weird PHP behavior.
        class_exists(CssParentNode::class);
        class_exists(CssVisitor::class);

        $logger = new DeprecationProcessingLogger($this->logger, $this->silenceDeprecations, $this->fatalDeprecations, $this->futureDeprecations, !$this->verbose);
        $logger->validate();
        $importCache = $this->createImportCache($logger);

        $importer = new FilesystemImporter(null);

        $stylesheet = $importCache->importCanonical($importer, Path::toUri(Path::canonicalize($path)), Path::toUri($path));

        \assert($stylesheet !== null, 'The filesystem importer never returns null when loading a canonical URL. It either succeeds or throws an error.');

        $result = $this->compileStylesheet($stylesheet, $importCache, $logger, $importer);

        $logger->summarize();

        return $result;
    }

    /**
     * Compiles the provided scss source code into CSS.
     *
     * Imports are resolved by trying, in order:
     *
     * * The given $importer, with the imported URL resolved relative to $url.
     *
     * * Each importer in {@see $importers}.
     *
     * * Each load path in {@see $importPaths}. Note that this is a shorthand for adding
     *   {@see FilesystemImporter}s to {@see $importers}.
     *
     * The $url indicates the location from which $source was loaded. If $importer is
     * passed, $url must be passed as well and `$importer->load($url)` should
     * return `$source`.
     *
     * @throws SassException when the source fails to compile
     */
    public function compileString(string $source, UriInterface|string|null $url = null, ?Importer $importer = null, Syntax $syntax = Syntax::SCSS): CompilationResult
    {
        // Force loading the CssParentNode and CssVisitor before using the AST classes because of a weird PHP behavior.
        class_exists(CssParentNode::class);
        class_exists(CssVisitor::class);

        $logger = new DeprecationProcessingLogger($this->logger, $this->silenceDeprecations, $this->fatalDeprecations, $this->futureDeprecations, !$this->verbose);
        $logger->validate();

        if (\is_string($url)) {
            @trigger_error('Passing a path to "Compiler::compileString" is deprecated. Use `Compiler::compileFile" or pass a "UriInterface" instead.', E_USER_DEPRECATED);
            $url = Path::toUri($url);
            $importer ??= new FilesystemImporter(null);
        }

        $importCache = $this->createImportCache($logger);
        $stylesheet = Stylesheet::parse($source, $syntax, $logger, $url);

        $importer ??= $url === null ? new NoOpImporter() : new FilesystemImporter(null);

        $result = $this->compileStylesheet($stylesheet, $importCache, $logger, $importer);

        $logger->summarize();

        return $result;
    }

    private function createImportCache(LoggerInterface $logger): ImportCache
    {
        $importers = $this->importers;

        foreach ($this->importPaths as $importPath) {
            if (\is_string($importPath)) {
                $importers[] = new FilesystemImporter($importPath);
            } elseif (is_callable($importPath)) {
                $importers[] = new LegacyCallbackImporter($importPath(...));
                // TODO report deprecation
            }
        }

        return new ImportCache($importers, $logger);
    }

    /**
     * @throws SassException
     */
    private function compileStylesheet(Stylesheet $stylesheet, ImportCache $importCache, LoggerInterface $logger, Importer $importer): CompilationResult
    {
        $wantsSourceMap = $this->sourceMap !== self::SOURCE_MAP_NONE;

        $functions = [];
        foreach ($this->userFunctions as $name => $userFunction) {
            $ref = new \ReflectionFunction($userFunction[0](...));
            $signature = implode(', ', array_map(fn (string $arg) => '$' . $arg, $userFunction[1]));

            if ($ref->hasReturnType() && $ref->getReturnType() instanceof \ReflectionNamedType && $ref->getReturnType()->getName() === Value::class) {
                $callback = $userFunction[0];
            } else {
                $legacyCallback = $userFunction[0];
                $callback = function (array $arguments) use ($legacyCallback): Value {
                    $args = [];

                    foreach ($arguments as $argument) {
                        $args[] = $this->valueToLegacyValue($argument);
                    }

                    $result = $legacyCallback($args);

                    if ($result instanceof Value) {
                        return $result;
                    }

                    return $this->legacyValueToValue($result);
                };
            }
            $functions[] = BuiltInCallable::function($name, $signature, $callback);
        }

        $initialVariables = [];
        foreach ($this->registeredVars as $variableName => $variable) {
            if ($variableName[0] === '$') {
                $variableName = substr($variableName, 1);
            }
            $variableName = str_replace('_', '-', $variableName);
            $initialVariables[$variableName] = $variable;
        }

        $evaluateResult = (new EvaluateVisitor($importCache, $functions, $logger, $this->quietDeps, sourceMap: $wantsSourceMap))->run($importer, $stylesheet, $initialVariables);

        $serializeResult = Serializer::serialize($evaluateResult->getStylesheet(), style: $this->outputStyle, sourceMap: $wantsSourceMap, charset: $this->charset, logger: $logger);

        $css = $serializeResult->css;
        $sourceMap = null;

        if ($serializeResult->mapping !== null) {
            $mapping = $serializeResult->mapping;

            if (isset($this->sourceMapOptions['sourceMapBasepath']) || isset($this->sourceMapOptions['sourceMapRootpath'])) {
                $mapping = $mapping->mapUrls(function (string $url) {
                    $uri = Uri::new($url);

                    if ($uri->getScheme() !== null && $uri->getScheme() !== 'file') {
                        return $uri->toString();
                    }

                    $path = Path::fromUri($uri);

                    if (isset($this->sourceMapOptions['sourceMapBasepath']) && $this->sourceMapOptions['sourceMapBasepath'] !== '') {
                        $path = Path::relative($path, $this->sourceMapOptions['sourceMapBasepath']);
                    }

                    return Path::normalize(Path::join($this->sourceMapOptions['sourceMapRootpath'] ?? '', $path));
                });
            }

            if (isset($this->sourceMapOptions['sourceMapFilename'])) {
                $mapping->targetUrl = $this->sourceMapOptions['sourceMapFilename'];
            }

            if (isset($this->sourceMapOptions['sourceRoot'])) {
                $mapping->sourceRoot = $this->sourceMapOptions['sourceRoot'];
            }

            $sourceMap = json_encode($mapping->toJson($this->sourceMapOptions['outputSourceFiles'] ?? false), \JSON_THROW_ON_ERROR);

            $sourceMapUrl = null;

            switch ($this->sourceMap) {
                case self::SOURCE_MAP_INLINE:
                    $sourceMapUrl = 'data:application/json;charset=utf-8,' . Util::encodeURIComponent($sourceMap);
                    break;

                case self::SOURCE_MAP_FILE:
                    if (isset($this->sourceMapOptions['sourceMapURL'])) {
                        $sourceMapUrl = $this->sourceMapOptions['sourceMapURL'];
                    }
                    break;
            }

            if ($sourceMapUrl !== null) {
                $escapedUrl = str_replace('*/', '%2A/', $sourceMapUrl);

                $css .= ($this->outputStyle === OutputStyle::COMPRESSED ? '' : "\n\n") . "/*# sourceMappingURL=$escapedUrl */";
            }
        }

        return new CompilationResult($css, $sourceMap, $evaluateResult->getLoadedUrls());
    }

    /**
     * Converts a Sass value to its legacy representation.
     *
     * @return array|Number
     */
    private function valueToLegacyValue(Value $value)
    {
        $visitor = new LegacyValueVisitor();

        return $value->accept($visitor);
    }

    /**
     * Converts a legacy Sass value to its modern representation.
     *
     * @param array|Number $legacyValue
     */
    private function legacyValueToValue($legacyValue): Value
    {
        if ($legacyValue instanceof Number) {
            return SassNumber::withUnits($legacyValue->getDimension(), $legacyValue->getNumeratorUnits(), $legacyValue->getDenominatorUnits());
        }

        switch ($legacyValue[0]) {
            case Type::T_KEYWORD:
                if ($legacyValue === self::$true || $legacyValue === self::$false) {
                    return SassBoolean::create($legacyValue === self::$true);
                }

                throw new \UnexpectedValueException('Unsupported value using the "keyword" type. Only boolean values should use it as their representation.');

            case Type::T_COLOR:
                return SassColor::rgb($legacyValue[1], $legacyValue[2], $legacyValue[3], $legacyValue[4] ?? 1.0);

            case Type::T_STRING:
                return new SassString($this->getStringText($legacyValue), $legacyValue[1] !== '');

            case Type::T_LIST:
                $items = [];
                foreach ($legacyValue[2] as $item) {
                    $items[] = $this->legacyValueToValue($item);
                }
                $separator = match ($legacyValue[1]) {
                    ',' => ListSeparator::COMMA,
                    ' ' => ListSeparator::SPACE,
                    '/' => ListSeparator::SLASH,
                    '' => ListSeparator::UNDECIDED,
                    default => throw new \LogicException(\sprintf('Unsupported list separator "%s".', $legacyValue[1]))
                };

                if (isset($legacyValue[3]) && \is_array($legacyValue[3])) {
                    $keywords = [];
                    foreach ($legacyValue[3] as $name => $item) {
                        assert(\is_string($name));
                        $keywords[$name] = $this->legacyValueToValue($item);
                    }
                    return new SassArgumentList($items, $keywords, $separator);
                }

                $hasBrackets = ($legacyValue['enclosing'] ?? null) === 'bracket';

                return new SassList($items, $separator, $hasBrackets);

            case Type::T_MAP:
                $map = new Map();
                $keys = $legacyValue[1];
                $values = $legacyValue[2];

                for ($i = 0, $s = \count($keys); $i < $s; $i++) {
                    $map->put($this->legacyValueToValue($keys[$i]), $this->legacyValueToValue($values[$i]));
                }

                return SassMap::create($map);

            case Type::T_NULL:
                return SassNull::create();

            default:
                throw new \UnexpectedValueException(sprintf('"Unsupported type "%s" for the value conversion.', $legacyValue[0]));
        }
    }

    /**
     * Detects whether the import is a CSS import.
     */
    public static function isCssImport(string $url): bool
    {
        return 1 === preg_match('~\.css$|^https?://|^//~', $url);
    }

    /**
     * Is truthy?
     *
     * @param array|Number $value
     */
    public function isTruthy($value): bool
    {
        return $value !== self::$false && $value !== self::$null;
    }

    /**
     * Cast to Sass boolean
     */
    public function toBool(bool $thing): array
    {
        return $thing ? self::$true : self::$false;
    }

    /**
     * Gets the text of a Sass string
     *
     * Calling this method on anything else than a SassString is unsupported. Use {@see assertString} first
     * to ensure that the value is indeed a string.
     */
    public function getStringText(array $value): string
    {
        if ($value[0] !== Type::T_STRING) {
            throw new \InvalidArgumentException('The argument is not a sass string. Did you forgot to use "assertString"?');
        }

        return $this->compileStringContent($value);
    }

    /**
     * Compile string content
     */
    private function compileStringContent(array $string): string
    {
        $parts = [];

        foreach ($string[2] as $part) {
            if (\is_array($part) || $part instanceof Number) {
                $parts[] = $this->compileValue($part);
            } else {
                $parts[] = $part;
            }
        }

        return implode($parts);
    }

    /**
     * Assert value is a string
     *
     * This method deals with internal implementation details of the value
     * representation where unquoted strings can sometimes be stored under
     * other types.
     * The returned value is always using the T_STRING type.
     *
     * @param array|Number $value
     *
     * @throws SassScriptException
     */
    public function assertString($value, ?string $varName = null): array
    {
        if ($value[0] === Type::T_STRING) {
            assert(\is_array($value));

            return $value;
        }

        $value = $this->compileValue($value);
        throw SassScriptException::forArgument("$value is not a string.", $varName);
    }

    /**
     * Assert value is a map
     *
     * @param array|Number $value
     *
     * @throws SassScriptException
     */
    public function assertMap($value, ?string $varName = null): array
    {
        $map = $this->tryMap($value);

        if ($map === null) {
            $value = $this->compileValue($value);

            throw SassScriptException::forArgument("$value is not a map.", $varName);
        }

        return $map;
    }

    /**
     * Tries to convert an item to a Sass map
     *
     * @param Number|array $item
     */
    private function tryMap($item): ?array
    {
        if ($item instanceof Number) {
            return null;
        }

        if ($item[0] === Type::T_MAP) {
            return $item;
        }

        if (
            $item[0] === Type::T_LIST &&
            $item[2] === []
        ) {
            return self::$emptyMap;
        }

        return null;
    }

    /**
     * Gets the keywords of an argument list.
     *
     * Keys in the returned array are normalized names (underscores are replaced with dashes)
     * without the leading `$`.
     * Calling this helper with anything that an argument list received for a rest argument
     * of the function argument declaration is not supported.
     *
     * @param array|Number $value
     *
     * @return array<string, array|Number>
     */
    public function getArgumentListKeywords($value): array
    {
        if ($value[0] !== Type::T_LIST || !isset($value[3]) || !\is_array($value[3])) {
            throw new \InvalidArgumentException('The argument is not a sass argument list.');
        }

        return $value[3];
    }

    /**
     * Assert value is a color
     *
     * @param array|Number $value
     *
     * @throws SassScriptException
     */
    public function assertColor($value, ?string $varName = null): array
    {
        if ($value[0] === Type::T_COLOR) {
            assert(\is_array($value));

            return $value;
        }

        $value = $this->compileValue($value);

        throw SassScriptException::forArgument("$value is not a color.", $varName);
    }

    /**
     * Assert value is a number
     *
     * @param array|Number $value
     *
     * @throws SassScriptException
     */
    public function assertNumber($value, ?string $varName = null): Number
    {
        if (!$value instanceof Number) {
            $value = $this->compileValue($value);
            throw SassScriptException::forArgument("$value is not a number.", $varName);
        }

        return $value;
    }

    /**
     * Assert value is a integer
     *
     * @param array|Number $value
     *
     * @throws SassScriptException
     */
    public function assertInteger($value, ?string $varName = null): int
    {
        $value = $this->assertNumber($value, $varName)->getDimension();
        if (round($value - \intval($value), Number::PRECISION) > 0) {
            throw SassScriptException::forArgument("$value is not an integer.", $varName);
        }

        return intval($value);
    }

    /**
     * Compiles a primitive value into a string for debugging purposes.
     *
     * Values in scssphp are typed by being wrapped in arrays, their format is
     * typically:
     *
     *     array(type, contents [, additional_contents]*)
     *
     * @param array|Number $value
     */
    public function compileValue($value): string
    {
        return (string) $this->legacyValueToValue($value);
    }
}
