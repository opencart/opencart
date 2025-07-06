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

namespace ScssPhp\ScssPhp\Evaluation;

use League\Uri\Uri;
use ScssPhp\ScssPhp\Ast\AstNode;
use ScssPhp\ScssPhp\Ast\Css\CssAtRule;
use ScssPhp\ScssPhp\Ast\Css\CssComment;
use ScssPhp\ScssPhp\Ast\Css\CssKeyframeBlock;
use ScssPhp\ScssPhp\Ast\Css\CssMediaQuery;
use ScssPhp\ScssPhp\Ast\Css\CssMediaRule;
use ScssPhp\ScssPhp\Ast\Css\CssNode;
use ScssPhp\ScssPhp\Ast\Css\CssStyleRule;
use ScssPhp\ScssPhp\Ast\Css\CssStylesheet;
use ScssPhp\ScssPhp\Ast\Css\CssValue;
use ScssPhp\ScssPhp\Ast\Css\MediaQuerySingletonMergeResult;
use ScssPhp\ScssPhp\Ast\Css\ModifiableCssAtRule;
use ScssPhp\ScssPhp\Ast\Css\ModifiableCssComment;
use ScssPhp\ScssPhp\Ast\Css\ModifiableCssDeclaration;
use ScssPhp\ScssPhp\Ast\Css\ModifiableCssImport;
use ScssPhp\ScssPhp\Ast\Css\ModifiableCssKeyframeBlock;
use ScssPhp\ScssPhp\Ast\Css\ModifiableCssMediaRule;
use ScssPhp\ScssPhp\Ast\Css\ModifiableCssNode;
use ScssPhp\ScssPhp\Ast\Css\ModifiableCssParentNode;
use ScssPhp\ScssPhp\Ast\Css\ModifiableCssStyleRule;
use ScssPhp\ScssPhp\Ast\Css\ModifiableCssStylesheet;
use ScssPhp\ScssPhp\Ast\Css\ModifiableCssSupportsRule;
use ScssPhp\ScssPhp\Ast\FakeAstNode;
use ScssPhp\ScssPhp\Ast\Sass\ArgumentDeclaration;
use ScssPhp\ScssPhp\Ast\Sass\ArgumentInvocation;
use ScssPhp\ScssPhp\Ast\Sass\AtRootQuery;
use ScssPhp\ScssPhp\Ast\Sass\CallableInvocation;
use ScssPhp\ScssPhp\Ast\Sass\Expression;
use ScssPhp\ScssPhp\Ast\Sass\Expression\BinaryOperationExpression;
use ScssPhp\ScssPhp\Ast\Sass\Expression\BinaryOperator;
use ScssPhp\ScssPhp\Ast\Sass\Expression\BooleanExpression;
use ScssPhp\ScssPhp\Ast\Sass\Expression\ColorExpression;
use ScssPhp\ScssPhp\Ast\Sass\Expression\FunctionExpression;
use ScssPhp\ScssPhp\Ast\Sass\Expression\IfExpression;
use ScssPhp\ScssPhp\Ast\Sass\Expression\InterpolatedFunctionExpression;
use ScssPhp\ScssPhp\Ast\Sass\Expression\IsCalculationSafeVisitor;
use ScssPhp\ScssPhp\Ast\Sass\Expression\ListExpression;
use ScssPhp\ScssPhp\Ast\Sass\Expression\MapExpression;
use ScssPhp\ScssPhp\Ast\Sass\Expression\NullExpression;
use ScssPhp\ScssPhp\Ast\Sass\Expression\NumberExpression;
use ScssPhp\ScssPhp\Ast\Sass\Expression\ParenthesizedExpression;
use ScssPhp\ScssPhp\Ast\Sass\Expression\SelectorExpression;
use ScssPhp\ScssPhp\Ast\Sass\Expression\StringExpression;
use ScssPhp\ScssPhp\Ast\Sass\Expression\SupportsExpression;
use ScssPhp\ScssPhp\Ast\Sass\Expression\UnaryOperationExpression;
use ScssPhp\ScssPhp\Ast\Sass\Expression\UnaryOperator;
use ScssPhp\ScssPhp\Ast\Sass\Expression\ValueExpression;
use ScssPhp\ScssPhp\Ast\Sass\Expression\VariableExpression;
use ScssPhp\ScssPhp\Ast\Sass\Import\DynamicImport;
use ScssPhp\ScssPhp\Ast\Sass\Import\StaticImport;
use ScssPhp\ScssPhp\Ast\Sass\Interpolation;
use ScssPhp\ScssPhp\Ast\Sass\Statement;
use ScssPhp\ScssPhp\Ast\Sass\Statement\AtRootRule;
use ScssPhp\ScssPhp\Ast\Sass\Statement\AtRule;
use ScssPhp\ScssPhp\Ast\Sass\Statement\ContentBlock;
use ScssPhp\ScssPhp\Ast\Sass\Statement\ContentRule;
use ScssPhp\ScssPhp\Ast\Sass\Statement\DebugRule;
use ScssPhp\ScssPhp\Ast\Sass\Statement\Declaration;
use ScssPhp\ScssPhp\Ast\Sass\Statement\EachRule;
use ScssPhp\ScssPhp\Ast\Sass\Statement\ErrorRule;
use ScssPhp\ScssPhp\Ast\Sass\Statement\ExtendRule;
use ScssPhp\ScssPhp\Ast\Sass\Statement\ForRule;
use ScssPhp\ScssPhp\Ast\Sass\Statement\FunctionRule;
use ScssPhp\ScssPhp\Ast\Sass\Statement\IfRule;
use ScssPhp\ScssPhp\Ast\Sass\Statement\ImportRule;
use ScssPhp\ScssPhp\Ast\Sass\Statement\IncludeRule;
use ScssPhp\ScssPhp\Ast\Sass\Statement\LoudComment;
use ScssPhp\ScssPhp\Ast\Sass\Statement\MediaRule;
use ScssPhp\ScssPhp\Ast\Sass\Statement\MixinRule;
use ScssPhp\ScssPhp\Ast\Sass\Statement\ReturnRule;
use ScssPhp\ScssPhp\Ast\Sass\Statement\SilentComment;
use ScssPhp\ScssPhp\Ast\Sass\Statement\StyleRule;
use ScssPhp\ScssPhp\Ast\Sass\Statement\Stylesheet;
use ScssPhp\ScssPhp\Ast\Sass\Statement\SupportsRule;
use ScssPhp\ScssPhp\Ast\Sass\Statement\VariableDeclaration;
use ScssPhp\ScssPhp\Ast\Sass\Statement\WarnRule;
use ScssPhp\ScssPhp\Ast\Sass\Statement\WhileRule;
use ScssPhp\ScssPhp\Ast\Sass\SupportsCondition;
use ScssPhp\ScssPhp\Ast\Sass\SupportsCondition\SupportsAnything;
use ScssPhp\ScssPhp\Ast\Sass\SupportsCondition\SupportsDeclaration;
use ScssPhp\ScssPhp\Ast\Sass\SupportsCondition\SupportsFunction;
use ScssPhp\ScssPhp\Ast\Sass\SupportsCondition\SupportsInterpolation;
use ScssPhp\ScssPhp\Ast\Sass\SupportsCondition\SupportsNegation;
use ScssPhp\ScssPhp\Ast\Sass\SupportsCondition\SupportsOperation;
use ScssPhp\ScssPhp\Ast\Selector\SelectorList;
use ScssPhp\ScssPhp\Ast\Selector\SimpleSelector;
use ScssPhp\ScssPhp\Collection\Map;
use ScssPhp\ScssPhp\Colors;
use ScssPhp\ScssPhp\Deprecation;
use ScssPhp\ScssPhp\Exception\MultiSpanSassRuntimeException;
use ScssPhp\ScssPhp\Exception\SassException;
use ScssPhp\ScssPhp\Exception\SassRuntimeException;
use ScssPhp\ScssPhp\Exception\SassScriptException;
use ScssPhp\ScssPhp\Exception\SimpleSassException;
use ScssPhp\ScssPhp\Exception\SimpleSassFormatException;
use ScssPhp\ScssPhp\Exception\SimpleSassRuntimeException;
use ScssPhp\ScssPhp\Extend\ConcreteExtensionStore;
use ScssPhp\ScssPhp\Extend\Extension;
use ScssPhp\ScssPhp\Extend\ExtensionStore;
use ScssPhp\ScssPhp\Function\FunctionRegistry;
use ScssPhp\ScssPhp\Importer\ImportCache;
use ScssPhp\ScssPhp\Importer\Importer;
use ScssPhp\ScssPhp\Logger\LoggerInterface;
use ScssPhp\ScssPhp\Parser\InterpolationMap;
use ScssPhp\ScssPhp\Parser\KeyframeSelectorParser;
use ScssPhp\ScssPhp\SassCallable\BuiltInCallable;
use ScssPhp\ScssPhp\SassCallable\PlainCssCallable;
use ScssPhp\ScssPhp\SassCallable\SassCallable;
use ScssPhp\ScssPhp\SassCallable\UserDefinedCallable;
use ScssPhp\ScssPhp\SourceSpan\MultiSpan;
use ScssPhp\ScssPhp\StackTrace\Frame;
use ScssPhp\ScssPhp\StackTrace\Trace;
use ScssPhp\ScssPhp\Util;
use ScssPhp\ScssPhp\Util\AstUtil;
use ScssPhp\ScssPhp\Util\Character;
use ScssPhp\ScssPhp\Util\EquatableUtil;
use ScssPhp\ScssPhp\Util\IterableUtil;
use ScssPhp\ScssPhp\Util\ListUtil;
use ScssPhp\ScssPhp\Util\LoggerUtil;
use ScssPhp\ScssPhp\Util\SpanUtil;
use ScssPhp\ScssPhp\Util\StringUtil;
use ScssPhp\ScssPhp\Value\CalculationOperation;
use ScssPhp\ScssPhp\Value\CalculationOperator;
use ScssPhp\ScssPhp\Value\ListSeparator;
use ScssPhp\ScssPhp\Value\SassArgumentList;
use ScssPhp\ScssPhp\Value\SassBoolean;
use ScssPhp\ScssPhp\Value\SassCalculation;
use ScssPhp\ScssPhp\Value\SassColor;
use ScssPhp\ScssPhp\Value\SassFunction;
use ScssPhp\ScssPhp\Value\SassList;
use ScssPhp\ScssPhp\Value\SassMap;
use ScssPhp\ScssPhp\Value\SassMixin;
use ScssPhp\ScssPhp\Value\SassNull;
use ScssPhp\ScssPhp\Value\SassNumber;
use ScssPhp\ScssPhp\Value\SassString;
use ScssPhp\ScssPhp\Value\Value;
use ScssPhp\ScssPhp\Visitor\ExpressionVisitor;
use ScssPhp\ScssPhp\Visitor\StatementVisitor;
use ScssPhp\ScssPhp\Warn;
use SourceSpan\FileSpan;
use SourceSpan\SourceFile;
use SourceSpan\SimpleSourceLocation;

/**
 * A visitor that executes Sass code to produce a CSS tree.
 *
 * @template-implements StatementVisitor<Value|null>
 * @template-implements ExpressionVisitor<Value>
 *
 * @internal
 */
class EvaluateVisitor implements StatementVisitor, ExpressionVisitor
{
    /**
     * The import cache used to import other stylesheets.
     */
    private readonly ImportCache $importCache;

    /**
     * @var array<string, SassCallable>
     */
    private array $builtInFunctions = [];

    private readonly LoggerInterface $logger;

    /**
     * A set of message/location pairs for warnings that have been emitted via
     * {@see warn}.
     *
     * We only want to emit one warning per location, to avoid blowing up users'
     * consoles with redundant warnings.
     *
     * @var array<string, array<string, true>>
     */
    private array $warningsEmitted = [];

    /**
     * Whether to avoid emitting warnings for files loaded from dependencies.
     */
    private readonly bool $quietDeps;

    /**
     * Whether to track source map information.
     */
    private readonly bool $sourceMap;

    /**
     * The current lexical environment.
     */
    private Environment $environment;

    /**
     * The style rule that defines the current parent selector, if any.
     *
     * This doesn't take into consideration any intermediate `@at-root` rules. In
     * the common case where those rules are relevant, use {@see getStyleRule} instead.
     */
    private ?ModifiableCssStyleRule $styleRuleIgnoringAtRoot = null;

    /**
     * The current media queries, if any.
     *
     * @var list<CssMediaQuery>|null
     */
    private ?array $mediaQueries = null;

    /**
     * The set of media queries that were merged together to create
     * {@see $mediaQueries}.
     *
     * This will be non-null if and only if {@see $mediaQueries} is non-null, but it
     * will be empty if {@see $mediaQueries} isn't the result of a merge.
     *
     * @var CssMediaQuery[]|null
     */
    private ?array $mediaQuerySources = null;

    private ?ModifiableCssParentNode $parent = null;

    /**
     * The name of the current declaration parent.
     */
    private ?string $declarationName = null;

    /**
     * The human-readable name of the current stack frame.
     */
    private string $member = "root stylesheet";

    /**
     * The innermost user-defined callable that's being invoked.
     */
    private ?UserDefinedCallable $currentCallable = null;

    /**
     * The node for the innermost callable that's being invoked.
     *
     * This is used to produce warnings for function calls. It's stored as an
     * {@see AstNode} rather than a {@see FileSpan} so we can avoid calling {@see AstNode::getSpan}
     * if the span isn't required, since some nodes need to do real work to
     * manufacture a source span.
     */
    private ?AstNode $callableNode = null;

    /**
     * The span for the current import that's being resolved.
     *
     * This is used to produce warnings for importers.
     */
    private ?FileSpan $importSpan = null;

    /**
     * Whether we're currently executing a function.
     */
    private bool $inFunction = false;

    /**
     * Whether we're currently building the output of an unknown at rule.
     */
    private bool $inUnknownAtRule = false;

    /**
     * Whether we're directly within an `@at-root` rule that excludes style rules.
     */
    private bool $atRootExcludingStyleRule = false;

    /**
     * Whether we're currently building the output of a `@keyframes` rule.
     */
    private bool $inKeyFrames = false;

    /**
     * Whether we're currently evaluating a {@see SupportsDeclaration}.
     *
     * When this is true, calculations will not be simplified.
     */
    private bool $inSupportsDeclaration = false;

    /**
     * The canonical URLs of all stylesheets loaded during compilation.
     *
     * @var array<string, true>
     */
    private array $loadedUrls = [];

    /**
     * A map from canonical URLs for modules (or imported files) that are
     * currently being evaluated to AST nodes whose spans indicate the original
     * loads for those modules.
     *
     * Map values may be `null`, which indicates an active module that doesn't
     * have a source span associated with its original load (such as the
     * entrypoint module).
     *
     * This is used to ensure that we don't get into an infinite load loop.
     *
     * @var array<string, AstNode|null>
     */
    private array $activeModules = [];

    /**
     * The dynamic call stack representing function invocations, mixin
     * invocations, and imports surrounding the current context.
     *
     * Each member is a tuple of the span where the stack trace starts and the
     * name of the member being invoked.
     *
     * This stores {@see AstNode}s rather than {@see FileSpan}s so it can avoid calling
     * {@see AstNode::getSpan} if the span isn't required, since some nodes need to do
     * real work to manufacture a source span.
     *
     * @var list<array{string, AstNode}>
     */
    private array $stack = [];

    /**
     * The importer that's currently being used to resolve relative imports.
     *
     * If this is `null`, relative imports aren't supported in the current
     * stylesheet.
     */
    private ?Importer $importer = null;

    /**
     * Whether we're in a dependency.
     *
     * A dependency is defined as a stylesheet imported by an importer other than
     * the original.
     */
    private bool $inDependency = false;

    private ?Stylesheet $stylesheet = null;

    private ?ModifiableCssStylesheet $root = null;

    private ?int $endOfImports = null;

    /**
     * Plain-CSS imports that didn't appear in the initial block of CSS imports.
     *
     * These are added to the initial CSS import block by {@see visitStylesheet} after
     * the stylesheet has been fully performed.
     *
     * This is `null` unless there are any out-of-order imports in the current
     * stylesheet.
     *
     * @var list<ModifiableCssImport>|null
     */
    private ?array $outOfOrderImports = null;

    private ?ExtensionStore $extensionStore = null;

    /**
     * @param SassCallable[] $functions
     */
    public function __construct(ImportCache $importCache, array $functions, LoggerInterface $logger, bool $quietDeps = false, bool $sourceMap = false)
    {
        $this->importCache = $importCache;
        $this->logger = $logger;
        $this->quietDeps = $quietDeps;
        $this->sourceMap = $sourceMap;
        $this->environment = Environment::create();

        $sassMetaUri = Uri::new('sass:meta');
        // These functions are defined in the context of the evaluator because
        // they need access to the environment or other local state.
        // When adding a new function here, its name must also be added in {@see FunctionRegistry::SPECIAL_META_GLOBAL_FUNCTIONS}.
        $metaFunctions = [
            BuiltInCallable::function('global-variable-exists', '$name, $module: null', function ($arguments) {
                $variable = $arguments[0]->assertString('name');
                $module = $arguments[1]->realNull()?->assertString('module');

                if ($module !== null) {
                    // TODO remove this when implementing modules
                    throw new SassScriptException('Sass modules are not implemented yet.');
                }

                return SassBoolean::create($this->environment->globalVariableExists(str_replace('_', '-', $variable->getText())));
            }, $sassMetaUri),
            BuiltInCallable::function('variable-exists', '$name', function ($arguments) {
                $variable = $arguments[0]->assertString('name');

                return SassBoolean::create($this->environment->variableExists(str_replace('_', '-', $variable->getText())));
            }, $sassMetaUri),
            BuiltInCallable::function('function-exists', '$name, $module: null', function ($arguments) {
                $variable = $arguments[0]->assertString('name');
                $module = $arguments[1]->realNull()?->assertString('module');

                if ($module !== null) {
                    // TODO remove this when implementing modules
                    throw new SassScriptException('Sass modules are not implemented yet.');
                }

                return SassBoolean::create($this->environment->functionExists(str_replace('_', '-', $variable->getText())) || isset($this->builtInFunctions[$variable->getText()]) || FunctionRegistry::has($variable->getText()));
            }, $sassMetaUri),
            BuiltInCallable::function('mixin-exists', '$name, $module: null', function ($arguments) {
                $variable = $arguments[0]->assertString('name');
                $module = $arguments[1]->realNull()?->assertString('module');

                if ($module !== null) {
                    // TODO remove this when implementing modules
                    throw new SassScriptException('Sass modules are not implemented yet.');
                }

                return SassBoolean::create($this->environment->mixinExists(str_replace('_', '-', $variable->getText())));
            }, $sassMetaUri),
            BuiltInCallable::function('content-exists', '', function ($arguments) {
                if (! $this->environment->isInMixin()) {
                    throw new SassScriptException('content-exists() may only be called within a mixin.');
                }

                return SassBoolean::create($this->environment->getContent() !== null);
            }, $sassMetaUri),
            BuiltInCallable::function('get-function', '$name, $css: false, $module: null', function ($arguments) {
                $name = $arguments[0]->assertString('name');
                $css = $arguments[1]->isTruthy();
                $module = $arguments[2]->realNull()?->assertString('module');

                if ($css) {
                    if ($module !== null) {
                        throw new SassScriptException('$css and $module may not both be passed at once.');
                    }

                    return new SassFunction(new PlainCssCallable($name->getText()));
                }

                \assert($this->callableNode !== null);
                $callable = $this->addExceptionSpan($this->callableNode, function () use ($name, $module) {
                    $normalizedName = str_replace('_', '-', $name->getText());
                    $namespace = $module?->getText();

                    if ($namespace !== null) {
                        // TODO remove this when implementing modules
                        throw new SassScriptException('Sass modules are not implemented yet.');
                    }

                    $local = $this->environment->getFunction($normalizedName);

                    if ($local !== null) {
                        return $local;
                    }

                    return $this->getBuiltinFunction($normalizedName);
                });

                if ($callable === null) {
                    throw new SassScriptException("Function not found: $name");
                }

                return new SassFunction($callable);
            }, $sassMetaUri),
            BuiltInCallable::function('get-mixin', '$name, $module: null', function ($arguments) {
                $name = $arguments[0]->assertString('name');
                $module = $arguments[1]->realNull()?->assertString('module');

                \assert($this->callableNode !== null);
                $callable = $this->addExceptionSpan($this->callableNode, function () use ($name, $module) {
                    if ($module !== null) {
                        // TODO remove this when implementing modules
                        throw new SassScriptException('Sass modules are not implemented yet.');
                    }

                    return $this->environment->getMixin(str_replace('_', '-', $name->getText()));
                });

                if ($callable === null) {
                    throw new SassScriptException("Mixin not found: $name");
                }

                return new SassMixin($callable);
            }, $sassMetaUri),
            BuiltInCallable::function('call', '$function, $args...', function ($arguments) {
                $function = $arguments[0];
                $args = $arguments[1];
                \assert($args instanceof SassArgumentList);

                $callableNode = $this->callableNode;
                \assert($callableNode !== null);

                if (\count($args->getKeywords()) === 0) {
                    $keywordRest = null;
                } else {
                    $keywordArgs = new Map();
                    foreach ($args->getKeywords() as $name => $value) {
                        $keywordArgs->put(new SassString($name, false), $value);
                    }

                    $keywordRest = new ValueExpression(SassMap::create($keywordArgs), $callableNode->getSpan());
                }

                $invocation = new ArgumentInvocation([], [], $callableNode->getSpan(), new ValueExpression($args, $callableNode->getSpan()), $keywordRest);

                if ($function instanceof SassString) {
                    Warn::forDeprecation("Passing a string to call() is deprecated and will be illegal in Dart Sass 2.0.0.\n\nRecommendation: call(get-function($function))", Deprecation::callString);
                    $expression = new FunctionExpression($function->getText(), $invocation, $callableNode->getSpan());

                    return $expression->accept($this);
                }

                $callable = $function->assertFunction('function')->getCallable();

                return $this->runFunctionCallable($invocation, $callable, $callableNode);
            }, $sassMetaUri),
        ];

        foreach ($functions as $function) {
            $this->builtInFunctions[str_replace('_', '-', $function->getName())] = $function;
        }
        foreach ($metaFunctions as $function) {
            $this->builtInFunctions[$function->getName()] = $function;
        }
    }

    public function getCallableNode(): ?AstNode
    {
        return $this->callableNode;
    }

    public function getImportSpan(): ?FileSpan
    {
        return $this->importSpan;
    }

    /**
     * The current parent node in the output CSS tree.
     */
    private function getParent(): ModifiableCssParentNode
    {
        if ($this->parent === null) {
            throw new \LogicException('Cannot access "getParent" outside of a module.');
        }

        return $this->parent;
    }

    private function getStyleRule(): ?ModifiableCssStyleRule
    {
        return $this->atRootExcludingStyleRule ? null : $this->styleRuleIgnoringAtRoot;
    }

    /**
     * The stylesheet that's currently being evaluated.
     */
    private function getStylesheet(): Stylesheet
    {
        if ($this->stylesheet === null) {
            throw new \LogicException('Cannot access "getStylesheet" outside of a module.');
        }

        return $this->stylesheet;
    }

    /**
     * The root stylesheet node.
     */
    private function getRoot(): ModifiableCssStylesheet
    {
        if ($this->root === null) {
            throw new \LogicException('Cannot access "getRoot" outside of a module.');
        }

        return $this->root;
    }

    /**
     * The first index in `$this->getRoot()->getChildren()` after the initial block of CSS imports.
     */
    private function getEndOfImports(): int
    {
        if ($this->endOfImports === null) {
            throw new \LogicException('Cannot access "getEndOfImports" outside of a module.');
        }

        return $this->endOfImports;
    }

    /**
     * The extension store that tracks extensions and style rules for the current
     * module.
     */
    private function getExtensionStore(): ExtensionStore
    {
        if ($this->extensionStore === null) {
            throw new \LogicException('Cannot access "getExtensionStore" outside of a module.');
        }

        return $this->extensionStore;
    }

    /**
     * @param array<string, Value> $initialVariables
     */
    public function run(?Importer $importer, Stylesheet $node, array $initialVariables = []): EvaluateResult
    {
        return EvaluationContext::withEvaluationContext(new VisitorEvaluationContext($this, $node), function () use ($importer, $node, $initialVariables) {
            $url = $node->getSpan()->getSourceUrl();

            if ($url !== null) {
                $urlString = (string) $url;
                $this->activeModules[$urlString] = null;
                // TODO check how to handle stdin
                $this->loadedUrls[$urlString] = true;
            }

            /** @var ExtensionStore $extensionStore */
            [$css, $extensionStore] = $this->addExceptionTrace(fn() => $this->execute($importer, $node, $initialVariables));
            $selectors = $extensionStore->getSimpleSelectors();
            $unsatisfiedExtension = IterableUtil::firstOrNull($extensionStore->extensionsWhereTarget(fn (SimpleSelector $target) => !EquatableUtil::iterableContains($selectors, $target)));
            if ($unsatisfiedExtension !== null) {
                $this->throwForUnsatisfiedExtension($unsatisfiedExtension);
            }

            return new EvaluateResult($css, array_keys($this->loadedUrls));
        });
    }

    /**
     * @param array<string, Value> $initialVariables
     *
     * @return array{CssStylesheet, ExtensionStore}
     */
    private function execute(?Importer $importer, Stylesheet $stylesheet, array $initialVariables = []): array
    {
        $environment = Environment::create();
        foreach ($initialVariables as $variableName => $initialVariable) {
            $environment->setVariable($variableName, $initialVariable, new FakeAstNode(fn () => SourceFile::fromString('')->span(0)));
        }

        $css = null;

        $extensionStore = ConcreteExtensionStore::create();

        $this->withEnvironment($environment, function () use ($importer, $stylesheet, $extensionStore, &$css) {
            $oldImporter = $this->importer;
            $oldStylesheet = $this->stylesheet;
            $oldRoot = $this->root;
            $oldParent = $this->parent;
            $oldEndOfImports = $this->endOfImports;
            $oldOutOfOrderImports = $this->outOfOrderImports;
            $oldExtensionStore = $this->extensionStore;
            $oldStyleRule = $this->getStyleRule();
            $oldMediaQueries = $this->mediaQueries;
            $oldDeclarationName = $this->declarationName;
            $oldInUnknownAtRule = $this->inUnknownAtRule;
            $oldAtRootExcludingStyleRule = $this->atRootExcludingStyleRule;
            $oldInKeyframes = $this->inKeyFrames;

            $this->importer = $importer;
            $this->stylesheet = $stylesheet;
            $this->root = $root = new ModifiableCssStylesheet($stylesheet->getSpan());
            $this->parent = $root;
            $this->endOfImports = 0;
            $this->outOfOrderImports = null;
            $this->extensionStore = $extensionStore;
            $this->styleRuleIgnoringAtRoot = null;
            $this->mediaQueries = null;
            $this->declarationName = null;
            $this->inUnknownAtRule = false;
            $this->atRootExcludingStyleRule = false;
            $this->inKeyFrames = false;

            $this->visitStylesheet($stylesheet);
            $css = $this->outOfOrderImports === null ? $root : new ModifiableCssStylesheet($stylesheet->getSpan(), $this->addOutOfOrderImports());

            $this->importer = $oldImporter;
            $this->stylesheet = $oldStylesheet;
            $this->root = $oldRoot;
            $this->parent = $oldParent;
            $this->endOfImports = $oldEndOfImports;
            $this->outOfOrderImports = $oldOutOfOrderImports;
            $this->extensionStore = $oldExtensionStore;
            $this->styleRuleIgnoringAtRoot = $oldStyleRule;
            $this->mediaQueries = $oldMediaQueries;
            $this->declarationName = $oldDeclarationName;
            $this->inUnknownAtRule = $oldInUnknownAtRule;
            $this->atRootExcludingStyleRule = $oldAtRootExcludingStyleRule;
            $this->inKeyFrames = $oldInKeyframes;
        });

        assert($css instanceof CssStylesheet);

        return [$css, $extensionStore];
    }

    /**
     * Returns a copy of `$this->getRoot()->getChildren` with {@see outOfOrderImports} inserted
     * after {@see endOfImports}, if necessary.
     *
     * @return list<ModifiableCssNode>
     */
    private function addOutOfOrderImports(): array
    {
        if ($this->outOfOrderImports === null) {
            return $this->getRoot()->getChildren();
        }

        $children = $this->getRoot()->getChildren();

        array_splice($children, $this->getEndOfImports(), 0, $this->outOfOrderImports);

        return array_values($children);
    }

    /**
     * Throws an exception indicating that $extension is unsatisfied.
     */
    private function throwForUnsatisfiedExtension(Extension $extension): never
    {
        throw new SimpleSassException(
            "The target selector was not found.\nUse \"@extend $extension->target !optional\" to avoid this error.",
            $extension->span,
        );
    }

    /**
     * @phpstan-impure
     */
    public function visitStylesheet(Stylesheet $node): ?Value
    {
        foreach ($node->getChildren() as $child) {
            $child->accept($this);
        }

        return null;
    }

    public function visitAtRootRule(AtRootRule $node): ?Value
    {
        $unparsedQuery = $node->getQuery();

        if ($unparsedQuery !== null) {
            [$resolved, $map] = $this->performInterpolationWithMap($unparsedQuery, true);
            $query = AtRootQuery::parse($resolved, $this->logger, null, $map);
        } else {
            $query = AtRootQuery::getDefault();
        }

        $parent = $this->getParent();
        /** @var ModifiableCssParentNode[] $included */
        $included = [];

        while (!$parent instanceof CssStylesheet) {
            if (!$query->excludes($parent)) {
                $included[] = $parent;
            }

            $grandParent = $parent->getParent();
            if ($grandParent === null) {
                throw new \LogicException('CssNodes must have a CssStylesheet transitive parent node.');
            }

            $parent = $grandParent;
        }

        $root = $this->trimIncluded($included);

        // If we didn't exclude any rules, we don't need to use the copies we might
        // have created.
        if ($root === $this->getParent()) {
            $this->environment->scope(function () use ($node) {
                foreach ($node->getChildren() as $child) {
                    $child->accept($this);
                }
            }, $node->hasDeclarations());

            return null;
        }

        $innerCopy = $root;
        if (!empty($included)) {
            $innerCopy = $included[0]->copyWithoutChildren();
            $outerCopy = $innerCopy;

            foreach (array_slice($included, 1) as $includedNode) {
                $copy = $includedNode->copyWithoutChildren();
                $copy->addChild($outerCopy);
                $outerCopy = $copy;
            }

            $root->addChild($outerCopy);
        }

        $scope = $this->scopeForAtRoot($node, $innerCopy, $query, $included);
        $scope(function () use ($node) {
            foreach ($node->getChildren() as $child) {
                $child->accept($this);
            }
        });

        return null;
    }

    /**
     * Returns a scope callback for $query.
     *
     * This returns a callback that adjusts various instance variables for its
     * duration, based on which rules are excluded by $query. It always assigns
     * {@see parent} to $newParent.
     *
     * @param ModifiableCssParentNode[] $included
     *
     * @return callable((callable(): void)): void
     */
    private function scopeForAtRoot(AtRootRule $node, ModifiableCssParentNode $newParent, AtRootQuery $query, array $included): callable
    {
        $scope = function (callable $callback) use ($newParent, $node) {
            // We can't use  *rent here because it'll add the node to the tree
            // in the wrong place.
            $oldParent = $this->parent;
            $this->parent = $newParent;
            $this->environment->scope($callback, $node->hasDeclarations());
            $this->parent = $oldParent;
        };

        if ($query->excludesStyleRules()) {
            $innerScope = $scope;
            $scope = function (callable $callback) use ($innerScope) {
                $oldAtRootExcludingStyleRule = $this->atRootExcludingStyleRule;
                $this->atRootExcludingStyleRule = true;
                $innerScope($callback);
                $this->atRootExcludingStyleRule = $oldAtRootExcludingStyleRule;
            };
        }

        if ($this->mediaQueries !== null && $query->excludesName('media')) {
            $innerScope = $scope;
            $scope = function (callable $callback) use ($innerScope) {
                $this->withMediaQueries(null, null, function () use ($innerScope, $callback) {
                    $innerScope($callback);
                });
            };
        }

        if ($this->inKeyFrames && $query->excludesName('keyframes')) {
            $innerScope = $scope;
            $scope = function (callable $callback) use ($innerScope) {
                $wasInKeyframes = $this->inKeyFrames;
                $this->inKeyFrames = false;
                $innerScope($callback);
                $this->inKeyFrames = $wasInKeyframes;
            };
        }

        if ($this->inUnknownAtRule && !IterableUtil::any($included, fn($parent) => $parent instanceof CssAtRule)) {
            $innerScope = $scope;
            $scope = function (callable $callback) use ($innerScope) {
                $wasInUnknownAtRule = $this->inUnknownAtRule;
                $this->inUnknownAtRule = false;
                $innerScope($callback);
                $this->inUnknownAtRule = $wasInUnknownAtRule;
            };
        }

        return $scope;
    }

    /**
     * Destructively trims a trailing sublist from $nodes that matches the
     * current list of parents.
     *
     * $nodes should be a list of parents included by an `@at-root` rule, from
     * innermost to outermost. If it contains a trailing sublist that's
     * contiguous—meaning that each node is a direct parent of the node before
     * it—and whose final node is a direct child of {@see getRoot}, this removes that
     * sublist and returns the innermost removed parent.
     *
     * Otherwise, this leaves $nodes as-is and returns {@see getRoot}.
     *
     * @param ModifiableCssParentNode[] $nodes
     */
    private function trimIncluded(array &$nodes): ModifiableCssParentNode
    {
        if (empty($nodes)) {
            return $this->getRoot();
        }

        $parent = $this->getParent();
        $innermostContiguous = null;

        foreach ($nodes as $i => $node) {
            while ($parent !== $node) {
                $innermostContiguous = null;

                $grandParent = $parent->getParent();
                if ($grandParent === null) {
                    throw new \LogicException('Expected the node to be an ancestor.');
                }

                $parent = $grandParent;
            }

            $innermostContiguous = $innermostContiguous ?? $i;

            $grandParent = $parent->getParent();
            if ($grandParent === null) {
                throw new \LogicException('Expected the node to be an ancestor.');
            }

            $parent = $grandParent;
        }

        if ($parent !== $this->getRoot()) {
            return $this->getRoot();
        }

        $root = $nodes[$innermostContiguous];
        array_splice($nodes, $innermostContiguous);

        return $root;
    }

    public function visitContentBlock(ContentBlock $node): ?Value
    {
        throw new \BadMethodCallException('Evaluation handles @include and its content block together.');
    }

    public function visitContentRule(ContentRule $node): ?Value
    {
        $content = $this->environment->getContent();

        if ($content === null) {
            return null;
        }

        $this->runUserDefinedCallable($node->getArguments(), $content, $node, function () use ($content) {
            foreach ($content->getDeclaration()->getChildren() as $statement) {
                $statement->accept($this);
            }

            return null;
        });

        return null;
    }

    public function visitDebugRule(DebugRule $node): ?Value
    {
        $value = $node->getExpression()->accept($this);
        $this->logger->debug($value instanceof SassString ? $value->getText() : (string) $value, $node->getSpan());

        return null;
    }

    public function visitDeclaration(Declaration $node): ?Value
    {
        if ($this->getStyleRule() === null && !$this->inUnknownAtRule && !$this->inKeyFrames) {
            throw $this->exception('Declarations may only be used within style rules.', $node->getSpan());
        }

        if ($this->declarationName !== null && $node->isCustomProperty()) {
            throw $this->exception('Declarations whose names begin with "--" may not be nested.', $node->getSpan());
        }

        \assert($this->getParent()->getParent() !== null);
        $siblings = $this->getParent()->getParent()->getChildren();
        $interleavedRules = [];

        if (
            ListUtil::last($siblings) !== $this->getParent()
            // Reproduce this condition from {@see warn} so that we don't add anything to
            // $interleavedRules for declarations in dependencies.
            && !($this->quietDeps && ($this->inDependency || ($this->currentCallable?->isInDependency() ?? false)))
        ) {
            $parentOffset = array_search($this->getParent(), $siblings, true);
            if ($parentOffset === false) {
                $parentOffset = -1;
            }
            foreach (array_slice($siblings, $parentOffset + 1) as $sibling) {
                if ($sibling instanceof CssComment) {
                    continue;
                }

                if ($sibling instanceof CssStyleRule) {
                    $interleavedRules[] = $sibling;
                    continue;
                }

                // Always warn for siblings that aren't style rules, because they
                // add no specificity and they're nested in the same parent as this
                // declaration.
                $this->warn(
                    <<<'MESSAGE'
                    Sass's behavior for declarations that appear after nested
                    rules will be changing to match the behavior specified by CSS in an upcoming
                    version. To keep the existing behavior, move the declaration above the nested
                    rule. To opt into the new behavior, wrap the declaration in `& {}`.

                    More info: https://sass-lang.com/d/mixed-decls
                    MESSAGE,
                    new MultiSpan($node->getSpan(), 'declaration', [
                        'nested rule' => $sibling->getSpan(),
                    ]),
                    Deprecation::mixedDecls
                );
                $interleavedRules = [];
            }
        }

        $name = $this->interpolationToValue($node->getName(), true);

        if ($this->declarationName !== null) {
            $name = new CssValue($this->declarationName . '-' . $name->getValue(), $name->getSpan());
        }

        $expression = $node->getValue();
        if ($expression !== null) {
            $value = $expression->accept($this);

            // If the value is an empty list, preserve it, because converting it to CSS
            // will throw an error that we want the user to see.

            if (!$value->isBlank() || empty($value->asList())) {
                $valueSpanForMap = null;
                if ($this->sourceMap && $node->getValue() !== null) {
                    $valueSpanForMap = $this->expressionNode($node->getValue())->getSpan();
                }

                $this->getParent()->addChild(new ModifiableCssDeclaration(
                    $name,
                    new CssValue($value, $expression->getSpan()),
                    $node->getSpan(),
                    $node->isCustomProperty(),
                    $interleavedRules,
                    $interleavedRules === [] ? null : $this->stackTrace($node->getSpan()),
                    $valueSpanForMap,
                ));
            } elseif (str_starts_with($name->getValue(), '--')) {
                throw $this->exception('Custom property values may not be empty.', $expression->getSpan());
            }
        }

        $children = $node->getChildren();
        if ($children !== null) {
            $oldDeclarationName = $this->declarationName;
            $this->declarationName = $name->getValue();
            $this->environment->scope(function () use ($children) {
                foreach ($children as $child) {
                    $child->accept($this);
                }
            }, $node->hasDeclarations());
            $this->declarationName = $oldDeclarationName;
        }

        return null;
    }

    public function visitEachRule(EachRule $node): ?Value
    {
        $list = $node->getList()->accept($this);
        $nodeWithSpan = $this->expressionNode($node->getList());

        if (\count($node->getVariables()) === 1) {
            $variableName = $node->getVariables()[0];
            $setVariables = function (Value $value) use ($variableName, $nodeWithSpan) {
                $this->environment->setLocalVariable($variableName, $this->withoutSlash($value, $nodeWithSpan), $nodeWithSpan);
            };
        } else {
            $variables = $node->getVariables();
            $setVariables = function (Value $value) use ($variables, $nodeWithSpan) {
                $this->setMultipleVariables($variables, $value, $nodeWithSpan);
            };
        }

        return $this->environment->scope(function () use ($list, $setVariables, $node) {
            return $this->handleReturn($list->asList(), function ($element) use ($setVariables, $node) {
                $setVariables($element);

                return $this->handleReturn($node->getChildren(), fn(Statement $child) => $child->accept($this));
            });
        }, true, true);
    }

    /**
     * Destructures $value and assigns it to $variables, as in an `@each`
     * statement.
     *
     * @param list<string> $variables
     */
    private function setMultipleVariables(array $variables, Value $value, AstNode $nodeWithSpan): void
    {
        $list = $value->asList();
        $minLength = min(\count($variables), \count($list));

        for ($i = 0; $i < $minLength; $i++) {
            $this->environment->setLocalVariable($variables[$i], $this->withoutSlash($list[$i], $nodeWithSpan), $nodeWithSpan);
        }

        for ($i = $minLength; $i < \count($variables); $i++) {
            $this->environment->setLocalVariable($variables[$i], SassNull::create(), $nodeWithSpan);
        }
    }

    public function visitErrorRule(ErrorRule $node): ?Value
    {
        throw $this->exception((string) $node->getExpression()->accept($this), $node->getSpan());
    }

    public function visitExtendRule(ExtendRule $node): ?Value
    {
        $styleRule = $this->getStyleRule();
        if ($styleRule === null || $this->declarationName !== null) {
            throw $this->exception('@extend may only be used within style rules.', $node->getSpan());
        }

        foreach ($styleRule->getOriginalSelector()->getComponents() as $complex) {
            if (!$complex->isBogus()) {
                continue;
            }

            $selectorString = trim($complex);
            $verb = $complex->isUseless() ? "can't" : "shouldn't";

            $this->warn(
                "The selector \"$selectorString\" is invalid CSS and $verb be an extender.\nThis will be an error in Dart Sass 2.0.0.\n\nMore info: https://sass-lang.com/d/bogus-combinators",
                new MultiSpan(SpanUtil::trimRight($complex->getSpan()), 'invalid selector', [
                    '@extend rule' => $node->getSpan(),
                ]),
                Deprecation::bogusCombinators
            );
        }

        [$targetText, $targetMap] = $this->performInterpolationWithMap($node->getSelector(), true);
        $list = SelectorList::parse(StringUtil::trimAscii($targetText, true), $this->logger, $targetMap, null, false);

        foreach ($list->getComponents() as $complex) {
            $compound = $complex->getSingleCompound();
            if ($compound === null) {
                // If the selector was a compound selector but not a simple
                // selector, emit a more explicit error.
                throw new SimpleSassFormatException('complex selectors may not be extended.', $complex->getSpan());
            }

            $simple = $compound->getSingleSimple();
            if ($simple === null) {
                $alternativeString = implode(', ', $compound->getComponents());
                throw new SimpleSassFormatException("compound selectors may no longer be extended.\nConsider `@extend $alternativeString` instead.\nSee https://sass-lang.com/d/extend-compound for details.\n", $compound->getSpan());
            }

            $this->getExtensionStore()->addExtension($styleRule->getSelector(), $simple, $node, $this->mediaQueries);
        }

        return null;
    }

    public function visitAtRule(AtRule $node): ?Value
    {
        if ($this->declarationName !== null) {
            throw $this->exception('At-rules may not be used within nested declarations.', $node->getSpan());
        }

        $name = $this->interpolationToValue($node->getName());
        $value = $node->getValue() !== null ? $this->interpolationToValue($node->getValue(), true, true) : null;
        $children = $node->getChildren();

        if ($children === null) {
            $this->getParent()->addChild(new ModifiableCssAtRule($name, $node->getSpan(), true, $value));

            return null;
        }

        $wasInKeyframes = $this->inKeyFrames;
        $wasInUnknownAtRule = $this->inUnknownAtRule;

        if (Util::unvendor($name->getValue()) === 'keyframes') {
            $this->inKeyFrames = true;
        } else {
            $this->inUnknownAtRule = true;
        }

        $this->withParent(
            new ModifiableCssAtRule($name, $node->getSpan(), false, $value),
            function () use ($children, $name) {
                $styleRule = $this->getStyleRule();

                if ($styleRule === null || $this->inKeyFrames || $name->getValue() === 'font-face') {
                    // Special-cased at-rules within style blocks are pulled out to the
                    // root. Equivalent to prepending "@at-root" on them.
                    foreach ($children as $child) {
                        $child->accept($this);
                    }
                } else {
                    // If we're in a style rule, copy it into the at-rule so that
                    // declarations immediately inside it have somewhere to go.
                    //
                    // For example, "a {@foo {b: c}}" should produce "@foo {a {b: c}}".
                    $this->withParent($styleRule->copyWithoutChildren(), function () use ($children) {
                        foreach ($children as $child) {
                            $child->accept($this);
                        }
                    }, null, false);
                }
            },
            function ($node) {
                return $node instanceof CssStyleRule;
            },
            $node->hasDeclarations()
        );

        $this->inUnknownAtRule = $wasInUnknownAtRule;
        $this->inKeyFrames = $wasInKeyframes;

        return null;
    }

    public function visitForRule(ForRule $node): ?Value
    {
        /** @var SassNumber $fromNumber */
        $fromNumber = $this->addExceptionSpan($node->getFrom(), function () use ($node) {
            return $node->getFrom()->accept($this)->assertNumber();
        });
        /** @var SassNumber $toNumber */
        $toNumber = $this->addExceptionSpan($node->getTo(), function () use ($node) {
            return $node->getTo()->accept($this)->assertNumber();
        });

        $from = $this->addExceptionSpan($node->getFrom(), function () use ($fromNumber) {
            return $fromNumber->assertInt();
        });
        $to = $this->addExceptionSpan($node->getTo(), function () use ($toNumber, $fromNumber) {
            return $toNumber->coerce($fromNumber->getNumeratorUnits(), $fromNumber->getDenominatorUnits())->assertInt();
        });

        $direction = $from > $to ? -1 : 1;
        if (!$node->isExclusive()) {
            $to += $direction;
        }

        if ($from === $to) {
            return null;
        }

        return $this->environment->scope(function () use ($node, $from, $to, $direction, $fromNumber) {
            $nodeWithSpan = $this->expressionNode($node->getFrom());

            for ($i = $from; $i !== $to; $i += $direction) {
                $this->environment->setLocalVariable($node->getVariable(), SassNumber::withUnits($i, $fromNumber->getNumeratorUnits(), $fromNumber->getDenominatorUnits()), $nodeWithSpan);
                $result = $this->handleReturn($node->getChildren(), function (Statement $child) {
                    return $child->accept($this);
                });

                if ($result !== null) {
                    return $result;
                }
            }

            return null;
        }, true, true);
    }

    public function visitFunctionRule(FunctionRule $node): ?Value
    {
        $this->environment->setFunction(new UserDefinedCallable($node, $this->environment->closure(), $this->inDependency));

        return null;
    }

    public function visitIfRule(IfRule $node): ?Value
    {
        $clause = $node->getLastClause();

        foreach ($node->getClauses() as $clauseToCheck) {
            if ($clauseToCheck->getExpression()->accept($this)->isTruthy()) {
                $clause = $clauseToCheck;
                break;
            }
        }

        if ($clause === null) {
            return null;
        }

        return $this->environment->scope(function () use ($clause) {
            return $this->handleReturn($clause->getChildren(), function (Statement $child) {
                return $child->accept($this);
            });
        }, $clause->hasDeclarations(), true);
    }

    public function visitImportRule(ImportRule $node): ?Value
    {
        foreach ($node->getImports() as $import) {
            if ($import instanceof DynamicImport) {
                $this->visitDynamicImport($import);
            } else {
                assert($import instanceof StaticImport);
                $this->visitStaticImport($import);
            }
        }

        return null;
    }

    /**
     * Adds the stylesheet imported by $import to the current document.
     */
    private function visitDynamicImport(DynamicImport $import): void
    {
        $this->withStackFrame('@import', $import, function () use ($import) {
            $result = $this->loadStylesheet($import->getUrlString(), $import->getSpan(), true);
            $stylesheet = $result->getStylesheet();

            $url = $stylesheet->getSpan()->getSourceUrl();

            if ($url !== null) {
                $urlString = (string) $url;
                if (array_key_exists($urlString, $this->activeModules)) {
                    $previousLoad = $this->activeModules[$urlString];
                    if ($previousLoad !== null) {
                        throw $this->multiSpanException('This file is already being loaded.', 'new load', ['original load' => $previousLoad->getSpan()]);
                    }

                    throw $this->exception('This file is already being loaded.');
                }

                $this->activeModules[$urlString] = $import;
            }

            $oldImporter = $this->importer;
            $oldStylesheet = $this->stylesheet;
            $oldInDependency = $this->inDependency;
            $this->importer = $result->getImporter();
            $this->stylesheet = $stylesheet;
            $this->inDependency = $result->isDependency();
            $this->visitStylesheet($stylesheet);
            $this->importer = $oldImporter;
            $this->stylesheet = $oldStylesheet;
            $this->inDependency = $oldInDependency;

            if ($url !== null) {
                unset($this->activeModules[(string) $url]);
            }
        });
    }

    private function loadStylesheet(string $url, FileSpan $span, bool $forImport = false): LoadedStylesheet
    {
        try {
            assert($this->importSpan === null);
            $this->importSpan = $span;

            $baseUrlString = $this->getStylesheet()->getSpan()->getSourceUrl();
            $baseUrl = $baseUrlString === null ? null : Uri::new($baseUrlString);

            $result = $this->importCache->canonicalize(Uri::new($url), $this->importer, $baseUrl, $forImport);
            if ($result !== null) {
                $canonicalUrl = $result->canonicalUrl;
                $importer = $result->importer;
                $originalUrl = $result->originalUrl;

                // Make sure we record the canonical URL as "loaded" even if the
                // actual load fails, because watchers should watch it to see if it
                // changes in a way that allows the load to succeed.
                $this->loadedUrls[$canonicalUrl->toString()] = true;

                $isDependency = $this->inDependency || $importer !== $this->importer;

                $stylesheet = $this->importCache->importCanonical($importer, $canonicalUrl, $originalUrl, $this->quietDeps && $isDependency);

                if ($stylesheet !== null) {
                    return new LoadedStylesheet($stylesheet, $importer, $isDependency);
                }
            }

            throw new \Exception("Can't find stylesheet to import.");
        } catch (SassException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw $this->exception($e->getMessage(), null, $e);
        } finally {
            $this->importSpan = null;
        }
    }

    /**
     * Adds a CSS import for $import.
     */
    private function visitStaticImport(StaticImport $import): void
    {
        $url = $this->interpolationToValue($import->getUrl());
        $modifiers = $import->getModifiers() !== null ? $this->interpolationToValue($import->getModifiers()) : null;

        $node = new ModifiableCssImport($url, $import->getSpan(), $modifiers);

        if ($this->getParent() !== $this->getRoot()) {
            $this->getParent()->addChild($node);
        } elseif ($this->getEndOfImports() === \count($this->getRoot()->getChildren())) {
            $this->getRoot()->addChild($node);
            $this->endOfImports++;
        } else {
            $this->outOfOrderImports[] = $node;
        }
    }

    /**
     * Evaluate a given $mixin with $arguments and $contentCallable
     */
    private function applyMixin(?SassCallable $mixin, ?UserDefinedCallable $contentCallable, ArgumentInvocation $arguments, AstNode $nodeWithSpan, AstNode $nodeWithSpanWithoutContent): void
    {
        if ($mixin === null) {
            throw $this->exception('Undefined mixin.', $nodeWithSpan->getSpan());
        }

        if ($mixin instanceof BuiltInCallable && !$mixin->acceptsContent() && $contentCallable !== null) {
            $evaluated = $this->evaluateArguments($arguments);
            /** @var ArgumentDeclaration $overload */
            [$overload,] = $mixin->callbackFor(\count($evaluated->getPositional()), $evaluated->getNamed());
            throw new MultiSpanSassRuntimeException(
                "Mixin doesn't accept a content block.",
                $nodeWithSpanWithoutContent->getSpan(),
                'invocation',
                ['declaration' => $overload->getSpanWithName()],
                $this->stackTrace($nodeWithSpanWithoutContent->getSpan())
            );
        }

        if ($mixin instanceof BuiltInCallable) {
            $this->environment->withContent($contentCallable, fn() => $this->environment->asMixin(function () use ($arguments, $mixin, $nodeWithSpanWithoutContent) {
                $this->runBuiltInCallable($arguments, $mixin, $nodeWithSpanWithoutContent);
            }));
        } elseif ($mixin instanceof UserDefinedCallable) {
            $declaration = $mixin->getDeclaration();
            assert($declaration instanceof MixinRule);

            if ($contentCallable !== null && !$declaration->hasContent()) {
                throw new MultiSpanSassRuntimeException(
                    "Mixin doesn't accept a content block.",
                    $nodeWithSpanWithoutContent->getSpan(),
                    'invocation',
                    ['declaration' => $mixin->getDeclaration()->getArguments()->getSpanWithName()],
                    $this->stackTrace($nodeWithSpanWithoutContent->getSpan())
                );
            }

            $this->runUserDefinedCallable($arguments, $mixin, $nodeWithSpanWithoutContent, function () use ($contentCallable, $declaration, $nodeWithSpanWithoutContent) {
                $this->environment->withContent($contentCallable, fn() => $this->environment->asMixin(function () use ($declaration, $nodeWithSpanWithoutContent) {
                    foreach ($declaration->getChildren() as $statement) {
                        $this->addErrorSpan($nodeWithSpanWithoutContent, fn() => $statement->accept($this));
                    }
                }));

                return null;
            });
        } else {
            throw new \LogicException('Unknown callable type ' . get_class($mixin));
        }
    }

    public function visitIncludeRule(IncludeRule $node): ?Value
    {
        $mixin = $this->addExceptionSpan($node, function () use ($node) {
            return $this->environment->getMixin($node->getName());
        });

        if (str_starts_with($node->getOriginalName(), '--') && $mixin instanceof UserDefinedCallable && !str_starts_with($mixin->getDeclaration()->getOriginalName(), '--')) {
            $this->warn("Sass @mixin names beginning with -- are deprecated for forward-compatibility with plain CSS mixins.\n\nFor details, see https://sass-lang.com/d/css-function-mixin", $node->getNameSpan(), Deprecation::cssFunctionMixin);
        }

        $contentCallable = null;
        if ($node->getContent() !== null) {
            $contentCallable = new UserDefinedCallable($node->getContent(), $this->environment->closure(), $this->inDependency);
        }

        $nodeWithSpanWithoutContent = new FakeAstNode(function () use ($node) {
            return $node->getSpanWithoutContent();
        });

        $this->applyMixin($mixin, $contentCallable, $node->getArguments(), $node, $nodeWithSpanWithoutContent);

        return null;
    }

    public function visitMixinRule(MixinRule $node): ?Value
    {
        $this->environment->setMixin(new UserDefinedCallable($node, $this->environment->closure(), $this->inDependency));

        return null;
    }

    public function visitLoudComment(LoudComment $node): ?Value
    {
        if ($this->inFunction) {
            return null;
        }

        // Comments are allowed to appear between CSS imports.
        if ($this->getParent() === $this->getRoot() && $this->getEndOfImports() === \count($this->getRoot()->getChildren())) {
            $this->endOfImports++;
        }

        $text = $this->performInterpolation($node->getText());
        // Indented syntax doesn't require */
        if (!str_ends_with($text, '*/')) {
            $text .= ' */';
        }

        $this->getParent()->addChild(new ModifiableCssComment($text, $node->getSpan()));

        return null;
    }

    public function visitMediaRule(MediaRule $node): ?Value
    {
        if ($this->declarationName !== null) {
            throw $this->exception('Media rules may not be used within nested declarations.', $node->getSpan());
        }

        $queries = $this->visitMediaQueries($node->getQuery());
        $mergedQueries = $this->mediaQueries !== null ? $this->mergeMediaQueries($this->mediaQueries, $queries) : null;

        if ($mergedQueries === []) {
            return null;
        }

        if ($mergedQueries === null) {
            $mergedSources = [];
        } else {
            assert($this->mediaQuerySources !== null);
            assert($this->mediaQueries !== null);

            $mergedSources = array_merge($this->mediaQuerySources, $this->mediaQueries, $queries);
        }

        $this->withParent(
            new ModifiableCssMediaRule($mergedQueries ?? $queries, $node->getSpan()),
            function () use ($mergedQueries, $mergedSources, $queries, $node) {
                $this->withMediaQueries($mergedQueries ?? $queries, $mergedSources, function () use ($node) {
                    $styleRule = $this->getStyleRule();

                    if ($styleRule !== null) {
                        // If we're in a style rule, copy it into the media query so that
                        // declarations immediately inside @media have somewhere to go.
                        //
                        // For example, "a {@media screen {b: c}}" should produce
                        // "@media screen {a {b: c}}".
                        $this->withParent($styleRule->copyWithoutChildren(), function () use ($node) {
                            foreach ($node->getChildren() as $child) {
                                $child->accept($this);
                            }
                        }, null, false);
                    } else {
                        foreach ($node->getChildren() as $child) {
                            $child->accept($this);
                        }
                    }
                });
            },
            function ($node) use ($mergedSources) {
                if ($node instanceof CssStyleRule) {
                    return true;
                }

                if ($mergedSources !== [] && $node instanceof CssMediaRule) {
                    return IterableUtil::every($node->getQueries(), function (CssMediaQuery $query) use ($mergedSources) {
                        return \in_array($query, $mergedSources, true);
                    });
                }

                return false;
            },
            $node->hasDeclarations()
        );

        return null;
    }

    /**
     * @param Interpolation $interpolation
     *
     * @return list<CssMediaQuery>
     */
    private function visitMediaQueries(Interpolation $interpolation): array
    {
        [$resolved, $map] = $this->performInterpolationWithMap($interpolation, true);

        return CssMediaQuery::parseList($resolved, $this->logger, null, $map);
    }

    /**
     * Returns a list of queries that selects for contexts that match both
     * $queries1 and $queries2.
     *
     * Returns the empty list if there are no contexts that match both $queries1
     * and $queries2, or `null` if there are contexts that can't be represented
     * by media queries.
     *
     * @param CssMediaQuery[] $queries1
     * @param CssMediaQuery[] $queries2
     *
     * @return list<CssMediaQuery>|null
     */
    private function mergeMediaQueries(array $queries1, array $queries2): ?array
    {
        $queries = [];

        foreach ($queries1 as $query1) {
            foreach ($queries2 as $query2) {
                $result = $query1->merge($query2);

                if ($result === MediaQuerySingletonMergeResult::empty) {
                    continue;
                }

                if ($result === MediaQuerySingletonMergeResult::unrepresentable) {
                    return null;
                }

                // Always true but not detected due to https://github.com/jiripudil/phpstan-sealed-classes/issues/2
                \assert($result instanceof CssMediaQuery);

                $queries[] = $result;
            }
        }

        return $queries;
    }

    public function visitReturnRule(ReturnRule $node): ?Value
    {
        return $this->withoutSlash($node->getExpression()->accept($this), $node->getExpression());
    }

    public function visitSilentComment(SilentComment $node): ?Value
    {
        return null;
    }

    public function visitStyleRule(StyleRule $node): ?Value
    {
        if ($this->declarationName !== null) {
            throw $this->exception('Style rules may not be used within nested declarations.', $node->getSpan());
        }

        if ($this->inKeyFrames && $this->getParent() instanceof CssKeyframeBlock) {
            throw $this->exception('Style rules may not be used within keyframe blocks.', $node->getSpan());
        }

        [$selectorText, $selectorMap] = $this->performInterpolationWithMap($node->getSelector(), true);

        if ($this->inKeyFrames) {
            $parsedSelector = (new KeyframeSelectorParser($selectorText, $this->logger, null, $selectorMap))->parse();
            $rule = new ModifiableCssKeyframeBlock(new CssValue($parsedSelector, $node->getSelector()->getSpan()), $node->getSpan());

            $this->withParent(
                $rule,
                function () use ($node) {
                    foreach ($node->getChildren() as $child) {
                        $child->accept($this);
                    }
                },
                function ($node) {
                    return $node instanceof CssStyleRule;
                },
                $node->hasDeclarations()
            );

            return null;
        }

        $parsedSelector = SelectorList::parse($selectorText, $this->logger, $selectorMap, plainCss: $this->getStylesheet()->isPlainCss());
        $nest = !($this->getStyleRule()?->isFromPlainCss() ?? false);
        if ($nest) {
            if ($this->getStylesheet()->isPlainCss()) {
                foreach ($parsedSelector->getComponents() as $complex) {
                    if (\count($complex->getLeadingCombinators()) > 0) {
                        throw $this->exception("Top-level leading combinators aren't allowed in plain CSS.", $complex->getLeadingCombinators()[0]->getSpan());
                    }
                }
            }

            $parsedSelector = $parsedSelector->nestWithin(
                $this->styleRuleIgnoringAtRoot?->getOriginalSelector(),
                !$this->atRootExcludingStyleRule,
                $this->getStylesheet()->isPlainCss()
            );
        }

        $selector = $this->getExtensionStore()->addSelector($parsedSelector, $this->mediaQueries);
        $rule = new ModifiableCssStyleRule($selector, $node->getSpan(), $parsedSelector, $this->getStylesheet()->isPlainCss());
        $oldAtRootExcludingStyleRule = $this->atRootExcludingStyleRule;
        $this->atRootExcludingStyleRule = false;
        $this->withParent(
            $rule,
            function () use ($rule, $node) {
                $this->withStyleRule($rule, function () use ($node) {
                    foreach ($node->getChildren() as $child) {
                        $child->accept($this);
                    }
                });
            },
            $nest ? fn($node) => $node instanceof CssStyleRule : null,
            $node->hasDeclarations()
        );
        $this->atRootExcludingStyleRule = $oldAtRootExcludingStyleRule;

        $this->warnForBogusCombinators($rule);

        if ($this->getStyleRule() === null && \count($this->getParent()->getChildren()) > 0) {
            $lastChild = ListUtil::last($this->getParent()->getChildren());
            $lastChild->setGroupEnd(true);
        }

        return null;
    }

    private function warnForBogusCombinators(CssStyleRule $rule): void
    {
        if (!$rule->isInvisibleOtherThanBogusCombinators()) {
            foreach ($rule->getSelector()->getComponents() as $complex) {
                if (!$complex->isBogus()) {
                    continue;
                }

                $selectorString = trim($complex);

                if ($complex->isUseless()) {
                    $this->warn(
                        "The selector \"$selectorString\" is invalid CSS. It will be omitted from the generated CSS.\nThis will be an error in Dart Sass 2.0.0.\n\nMore info: https://sass-lang.com/d/bogus-combinators",
                        SpanUtil::trimRight($complex->getSpan()),
                        Deprecation::bogusCombinators
                    );
                } elseif (\count($complex->getLeadingCombinators()) > 0) {
                    if (!$this->getStylesheet()->isPlainCss()) {
                        $this->warn(
                            "The selector \"$selectorString\" is invalid CSS.\nThis will be an error in Dart Sass 2.0.0.\n\nMore info: https://sass-lang.com/d/bogus-combinators",
                            SpanUtil::trimRight($complex->getSpan()),
                            Deprecation::bogusCombinators
                        );
                    }
                } else {
                    $omittedMessage = $complex->isBogusOtherThanLeadingCombinator() ? ' It will be omitted from the generated CSS.' : '';
                    $suffix = IterableUtil::every($rule->getChildren(), fn (CssNode $child) => $child instanceof CssComment) ? "\n(try converting to a //-style comment)" : '';
                    $this->warn(
                        "The selector \"$selectorString\" is only valid for nesting and shouldn't\nhave children other than style rules.$omittedMessage\nThis will be an error in Dart Sass 2.0.0.\n\nMore info: https://sass-lang.com/d/bogus-combinators",
                        new MultiSpan(SpanUtil::trimRight($complex->getSpan()), 'invalid selector', [
                            'this is not a style rule' . $suffix => $rule->getChildren()[0]->getSpan(),
                        ]),
                        Deprecation::bogusCombinators
                    );
                }
            }
        }
    }

    public function visitSupportsRule(SupportsRule $node): ?Value
    {
        if ($this->declarationName !== null) {
            throw $this->exception('Supports rules may not be used within nested declarations.', $node->getSpan());
        }

        $condition = new CssValue($this->visitSupportsCondition($node->getCondition()), $node->getCondition()->getSpan());

        $this->withParent(
            new ModifiableCssSupportsRule($condition, $node->getSpan()),
            function () use ($node) {
                $styleRule = $this->getStyleRule();

                if ($styleRule !== null) {
                    // If we're in a style rule, copy it into the supports rule so that
                    // declarations immediately inside @supports have somewhere to go.
                    //
                    // For example, "a {@supports (a: b) {b: c}}" should produce "@supports
                    // (a: b) {a {b: c}}".
                    $this->withParent($styleRule->copyWithoutChildren(), function () use ($node) {
                        foreach ($node->getChildren() as $child) {
                            $child->accept($this);
                        }
                    });
                } else {
                    foreach ($node->getChildren() as $child) {
                        $child->accept($this);
                    }
                }
            },
            function ($node) {
                return $node instanceof CssStyleRule;
            },
            $node->hasDeclarations()
        );

        return null;
    }

    private function visitSupportsCondition(SupportsCondition $condition): string
    {
        if ($condition instanceof SupportsOperation) {
            return sprintf('%s %s %s', $this->parenthesize($condition->getLeft(), $condition->getOperator()), $condition->getOperator(), $this->parenthesize($condition->getRight(), $condition->getOperator()));
        }

        if ($condition instanceof SupportsNegation) {
            return 'not ' . $this->parenthesize($condition->getCondition());
        }

        if ($condition instanceof SupportsInterpolation) {
            return $this->evaluateToCss($condition->getExpression(), false);
        }

        if ($condition instanceof SupportsDeclaration) {
            return $this->withSupportsDeclaration(function () use ($condition) {
                return sprintf('(%s:%s%s)', $this->evaluateToCss($condition->getName()), $condition->isCustomProperty() ? '' : ' ', $this->evaluateToCss($condition->getValue()));
            });
        }

        if ($condition instanceof SupportsFunction) {
            return sprintf('%s(%s)', $this->performInterpolation($condition->getName()), $this->performInterpolation($condition->getArguments()));
        }

        if ($condition instanceof SupportsAnything) {
            return '(' . $this->performInterpolation($condition->getContents()) . ')';
        }

        throw new \InvalidArgumentException('Unknown supports condition type ' . get_class($condition));
    }

    /**
     * Runs $callback in a context where {@see $inSupportsDeclaration} is true.
     *
     * @template T
     *
     * @param callable(): T $callback
     * @return T
     *
     * @param-immediately-invoked-callable $callback
     */
    private function withSupportsDeclaration(callable $callback)
    {
        $oldInSupportsDeclaration = $this->inSupportsDeclaration;
        $this->inSupportsDeclaration = true;
        try {
            return $callback();
        } finally {
            $this->inSupportsDeclaration = $oldInSupportsDeclaration;
        }
    }

    private function parenthesize(SupportsCondition $condition, ?string $operator = null): string
    {
        if ($condition instanceof SupportsNegation || $condition instanceof SupportsOperation && $operator !== $condition->getOperator()) {
            return '(' . $this->visitSupportsCondition($condition) . ')';
        }

        return $this->visitSupportsCondition($condition);
    }

    public function visitVariableDeclaration(VariableDeclaration $node): ?Value
    {
        if ($node->isGuarded()) {
            $value = $this->addExceptionSpan($node, function () use ($node) {
                return $this->environment->getVariable($node->getName());
            });

            if ($value !== null && $value !== SassNull::create()) {
                return null;
            }
        }

        if ($node->isGlobal() && !$this->environment->globalVariableExists($node->getName())) {
            $this->warn(
                $this->environment->atRoot()
                    ? "As of Dart Sass 2.0.0, !global assignments won't be able to declare new variables.\n\nSince this assignment is at the root of the stylesheet, the !global flag is\nunnecessary and can safely be removed."
                    : "As of Dart Sass 2.0.0, !global assignments won't be able to declare new variables.\n\nRecommendation: add `{$node->getOriginalName()}: null` at the stylesheet root.",
                $node->getSpan(),
                Deprecation::newGlobal
            );
        }

        $value = $this->withoutSlash($node->getExpression()->accept($this), $node->getExpression());
        $this->addExceptionSpan($node, function () use ($value, $node) {
            $this->environment->setVariable($node->getName(), $value, $this->expressionNode($node->getExpression()), $node->isGlobal());
        });

        return null;
    }

    public function visitWarnRule(WarnRule $node): ?Value
    {
        $value = $this->addExceptionSpan($node, function () use ($node) {
            return $node->getExpression()->accept($this);
        });
        $this->logger->warn($value instanceof SassString ? $value->getText() : $this->serialize($value, $node->getExpression()), null, null, $this->stackTrace($node->getSpan()));

        return null;
    }

    public function visitWhileRule(WhileRule $node): ?Value
    {
        return $this->environment->scope(function () use ($node) {
            while ($node->getCondition()->accept($this)->isTruthy()) {
                $result = $this->handleReturn($node->getChildren(), function (Statement $child) {
                    return $child->accept($this);
                });

                if ($result !== null) {
                    return $result;
                }
            }

            return null;
        }, $node->hasDeclarations(), true);
    }

    // ## Expressions

    public function visitBinaryOperationExpression(BinaryOperationExpression $node): Value
    {
        if ($this->getStylesheet()->isPlainCss() && $node->getOperator() !== BinaryOperator::SINGLE_EQUALS  && $node->getOperator() !== BinaryOperator::DIVIDED_BY) {
            throw $this->exception("Operators aren't allowed in plain CSS.", $node->getOperatorSpan());
        }

        return $this->addExceptionSpan($node, function () use ($node) {
            $left = $node->getLeft()->accept($this);

            return match ($node->getOperator()) {
                BinaryOperator::SINGLE_EQUALS => $left->singleEquals($node->getRight()->accept($this)),
                BinaryOperator::OR => $left->isTruthy() ? $left : $node->getRight()->accept($this),
                BinaryOperator::AND => $left->isTruthy() ? $node->getRight()->accept($this) : $left,
                BinaryOperator::EQUALS => SassBoolean::create($left->equals($node->getRight()->accept($this))),
                BinaryOperator::NOT_EQUALS => SassBoolean::create(!$left->equals($node->getRight()->accept($this))),
                BinaryOperator::GREATER_THAN => $left->greaterThan($node->getRight()->accept($this)),
                BinaryOperator::GREATER_THAN_OR_EQUALS => $left->greaterThanOrEquals($node->getRight()->accept($this)),
                BinaryOperator::LESS_THAN => $left->lessThan($node->getRight()->accept($this)),
                BinaryOperator::LESS_THAN_OR_EQUALS => $left->lessThanOrEquals($node->getRight()->accept($this)),
                BinaryOperator::PLUS => $left->plus($node->getRight()->accept($this)),
                BinaryOperator::MINUS => $left->minus($node->getRight()->accept($this)),
                BinaryOperator::TIMES => $left->times($node->getRight()->accept($this)),
                BinaryOperator::DIVIDED_BY => $this->slash($left, $node->getRight()->accept($this), $node),
                BinaryOperator::MODULO => $left->modulo($node->getRight()->accept($this)),
            };
        });
    }

    /**
     * Returns the result of the SassScript `/` operation between $left and
     * $right in $node.
     */
    private function slash(Value $left, Value $right, BinaryOperationExpression $node): Value
    {
        $result = $left->dividedBy($right);

        if ($left instanceof SassNumber && $right instanceof SassNumber && $node->allowsSlash() && $this->operandAllowsSlash($node->getLeft()) && $this->operandAllowsSlash($node->getRight())) {
            assert($result instanceof SassNumber);

            return $result->withSlash($left, $right);
        }

        if ($left instanceof SassNumber && $right instanceof SassNumber) {
            $recommendation = function (Expression $expression) use (&$recommendation): string {
                if ($expression instanceof BinaryOperationExpression && $expression->getOperator() === BinaryOperator::DIVIDED_BY) {
                    $leftRecommendation = $recommendation($expression->getLeft());
                    $rightRecommendation = $recommendation($expression->getRight());

                    return "math.div($leftRecommendation, $rightRecommendation)";
                }

                if ($expression instanceof ParenthesizedExpression) {
                    return (string) $expression->getExpression();
                }

                return (string) $expression;
            };

            $calcRecommendation = AstUtil::expressionToCalc($node);

            $message = <<<WARNING
Using / for division outside of calc() is deprecated and will be removed in Dart Sass 2.0.0.

Recommendation: {$recommendation($node)} or $calcRecommendation

More info and automated migrator: https://sass-lang.com/d/slash-div
WARNING;

            $this->warn($message, $node->getSpan(), Deprecation::slashDiv);

            return $result;
        }

        return $result;
    }

    /**
     * Returns whether $node can be used as a component of a slash-separated
     * number.
     *
     * Although this logic is mostly resolved at parse-time, we can't tell
     * whether operands will be evaluated as calculations until evaluation-time.
     */
    private function operandAllowsSlash(Expression $node): bool
    {
        if (!$node instanceof FunctionExpression) {
            return true;
        }

        if ($node->getNamespace() !== null) {
            return false;
        }

        return \in_array(strtolower($node->getName()), ['calc', 'clamp', 'hypot', 'sin', 'cos', 'tan', 'asin', 'acos', 'atan', 'sqrt', 'exp', 'sign', 'mod', 'rem', 'atan2', 'pow', 'log'], true) && $this->environment->getFunction($node->getName()) === null;
    }

    public function visitValueExpression(ValueExpression $node): Value
    {
        return $node->getValue();
    }

    public function visitVariableExpression(VariableExpression $node): Value
    {
        $result = $this->addExceptionSpan($node, function () use ($node) {
            return $this->environment->getVariable($node->getName());
        });

        if ($result !== null) {
            return $result;
        }

        throw $this->exception('Undefined variable.', $node->getSpan());
    }

    public function visitUnaryOperationExpression(UnaryOperationExpression $node): Value
    {
        $operand = $node->getOperand()->accept($this);

        return $this->addExceptionSpan($node, fn() => match ($node->getOperator()) {
            UnaryOperator::PLUS => $operand->unaryPlus(),
            UnaryOperator::MINUS => $operand->unaryMinus(),
            UnaryOperator::DIVIDE => $operand->unaryDivide(),
            UnaryOperator::NOT => $operand->unaryNot(),
        });
    }

    public function visitBooleanExpression(BooleanExpression $node): Value
    {
        return SassBoolean::create($node->getValue());
    }

    public function visitIfExpression(IfExpression $node): Value
    {
        [$positional, $named] = $this->evaluateMacroArguments($node);

        $this->verifyArguments(\count($positional), $named, IfExpression::getDeclaration(), $node);

        $condition = $positional[0] ?? $named['condition'];
        $ifTrue = $positional[1] ?? $named['if-true'];
        $ifFalse = $positional[2] ?? $named['if-false'];

        $result = $condition->accept($this)->isTruthy() ? $ifTrue : $ifFalse;

        return $this->withoutSlash($result->accept($this), $this->expressionNode($result));
    }

    public function visitNullExpression(NullExpression $node): Value
    {
        return SassNull::create();
    }

    public function visitNumberExpression(NumberExpression $node): Value
    {
        return SassNumber::create($node->getValue(), $node->getUnit());
    }

    public function visitParenthesizedExpression(ParenthesizedExpression $node): Value
    {
        if ($this->getStylesheet()->isPlainCss()) {
            throw $this->exception("Parentheses aren't allowed in plain CSS.", $node->getSpan());
        }

        return $node->getExpression()->accept($this);
    }

    public function visitColorExpression(ColorExpression $node): Value
    {
        return $node->getValue();
    }

    public function visitListExpression(ListExpression $node): Value
    {
        return new SassList(array_map(function (Expression $expression) {
            return $expression->accept($this);
        }, $node->getContents()), $node->getSeparator(), $node->hasBrackets());
    }

    public function visitMapExpression(MapExpression $node): Value
    {
        /** @var Map<Value> $map */
        $map = new Map();
        /** @var Map<AstNode> $keyNodes */
        $keyNodes = new Map();

        foreach ($node->getPairs() as $pair) {
            $keyValue = $pair[0]->accept($this);
            $valueValue = $pair[1]->accept($this);

            $oldValue = $map->get($keyValue);

            if ($oldValue !== null) {
                $oldValueSpan = $keyNodes->get($keyValue)?->getSpan();
                throw new MultiSpanSassRuntimeException(
                    'Duplicate key.',
                    $pair[0]->getSpan(),
                    'second key',
                    $oldValueSpan !== null ? ['first key' => $oldValueSpan] : [],
                    $this->stackTrace($pair[0]->getSpan())
                );
            }

            $map->put($keyValue, $valueValue);
            $keyNodes->put($keyValue, $pair[0]);
        }

        return SassMap::create($map);
    }

    private function getBuiltinFunction(string $name): ?SassCallable
    {
        if (!isset($this->builtInFunctions[$name]) && FunctionRegistry::has($name)) {
            $this->builtInFunctions[$name] = FunctionRegistry::get($name);
        }

        return $this->builtInFunctions[$name] ?? null;
    }

    public function visitFunctionExpression(FunctionExpression $node): Value
    {
        $function = $this->getStylesheet()->isPlainCss() ? null : $this->addExceptionSpan($node, function () use ($node) {
            return $this->environment->getFunction($node->getName());
        });

        if ($function === null) {
            if ($node->getNamespace() !== null) {
                throw $this->exception('Undefined function.', $node->getSpan());
            }

            switch (strtolower($node->getName())) {
                case 'min':
                case 'max':
                case 'round':
                case 'abs':
                    if (
                        $node->getArguments()->getNamed() === []
                        && $node->getArguments()->getRest() === null
                        && IterableUtil::every($node->getArguments()->getPositional(), function (Expression $argument) {
                            return $argument->accept(new IsCalculationSafeVisitor());
                        })
                    ) {
                        return $this->visitCalculation($node, true);
                    }
                    break;

                case 'calc':
                case 'clamp':
                case 'hypot':
                case 'sin':
                case 'cos':
                case 'tan':
                case 'asin':
                case 'acos':
                case 'atan':
                case 'sqrt':
                case 'exp':
                case 'sign':
                case 'mod':
                case 'rem':
                case 'atan2':
                case 'pow':
                case 'log':
                    return $this->visitCalculation($node);
            }

            $function = ($this->getStylesheet()->isPlainCss() ? null : $this->getBuiltinFunction($node->getName())) ?? new PlainCssCallable($node->getOriginalName());
        }

        if (str_starts_with($node->getOriginalName(), '--') && $function instanceof UserDefinedCallable && !str_starts_with($function->getDeclaration()->getOriginalName(), '--')) {
            $this->warn("Sass @function names beginning with -- are deprecated for forward-compatibility with plain CSS functions.\n\nFor details, see https://sass-lang.com/d/css-function-mixin", $node->getNameSpan(), Deprecation::cssFunctionMixin);
        }

        $oldInFunction = $this->inFunction;
        $this->inFunction = true;
        $result = $this->addErrorSpan($node, function () use ($function, $node) {
            return $this->runFunctionCallable($node->getArguments(), $function, $node);
        });
        $this->inFunction = $oldInFunction;

        return $result;
    }

    private function visitCalculation(FunctionExpression $node, bool $inLegacySassFunction = false): Value
    {
        if ($node->getArguments()->getNamed() !== []) {
            throw $this->exception("Keyword arguments can't be used with calculations.", $node->getSpan());
        }

        if ($node->getArguments()->getRest() !== null) {
            throw $this->exception("Rest arguments can't be used with calculations.", $node->getSpan());
        }

        $this->checkCalculationArguments($node);
        $arguments = array_map(function ($argument) use ($inLegacySassFunction) {
            return $this->visitCalculationExpression($argument, $inLegacySassFunction);
        }, $node->getArguments()->getPositional());

        if ($this->inSupportsDeclaration) {
            return SassCalculation::unsimplified($node->getName(), $arguments);
        }

        $oldCallableNode = $this->callableNode;
        $this->callableNode = $node;

        try {
            return match (strtolower($node->getName())) {
                'calc' => SassCalculation::calc($arguments[0]),
                'sqrt' => SassCalculation::sqrt($arguments[0]),
                'sin' => SassCalculation::sin($arguments[0]),
                'cos' => SassCalculation::cos($arguments[0]),
                'tan' => SassCalculation::tan($arguments[0]),
                'asin' => SassCalculation::asin($arguments[0]),
                'acos' => SassCalculation::acos($arguments[0]),
                'atan' => SassCalculation::atan($arguments[0]),
                'abs' => SassCalculation::abs($arguments[0]),
                'exp' => SassCalculation::exp($arguments[0]),
                'sign' => SassCalculation::sign($arguments[0]),
                'min' => SassCalculation::min($arguments),
                'max' => SassCalculation::max($arguments),
                'hypot' => SassCalculation::hypot($arguments),
                'pow' => SassCalculation::pow($arguments[0], $arguments[1] ?? null),
                'atan2' => SassCalculation::atan2($arguments[0], $arguments[1] ?? null),
                'log' => SassCalculation::log($arguments[0], $arguments[1] ?? null),
                'mod' => SassCalculation::mod($arguments[0], $arguments[1] ?? null),
                'rem' => SassCalculation::rem($arguments[0], $arguments[1] ?? null),
                'round' => SassCalculation::round($arguments[0], $arguments[1] ?? null, $arguments[2] ?? null),
                'clamp' => SassCalculation::clamp($arguments[0], $arguments[1] ?? null, $arguments[2] ?? null),
                default => throw new \UnexpectedValueException(sprintf('Unknown calculation name "%s".', $node->getName())),
            };
        } catch (SassScriptException $e) {
            // The simplification logic in the SassCalculation static methods will
            // throw an error if the arguments aren't compatible, but we have access
            // to the original spans so we can throw a more informative error.
            if (str_contains($e->getMessage(), 'compatible')) {
                $this->verifyCompatibleNumbers($arguments, $node->getArguments()->getPositional());
            }

            throw $this->exception($e->getMessage(), $node->getSpan(), $e);
        } finally {
            $this->callableNode = $oldCallableNode;
        }
    }

    private function checkCalculationArguments(FunctionExpression $node): void
    {
        $check = function (?int $maxArgs = null) use ($node) {
            if ($node->getArguments()->getPositional() === []) {
                throw $this->exception('Missing argument.', $node->getSpan());
            }

            if ($maxArgs !== null && \count($node->getArguments()->getPositional()) > $maxArgs) {
                throw $this->exception(sprintf(
                    'Only %d %s allowed, but %d %s passed.',
                    $maxArgs,
                    StringUtil::pluralize('argument', $maxArgs),
                    \count($node->getArguments()->getPositional()),
                    StringUtil::pluralize('was', \count($node->getArguments()->getPositional()), 'were')
                ), $node->getSpan());
            }
        };

        switch (strtolower($node->getName())) {
            case 'calc':
            case 'sqrt':
            case 'sin':
            case 'cos':
            case 'tan':
            case 'asin':
            case 'acos':
            case 'atan':
            case 'abs':
            case 'exp':
            case 'sign':
                $check(1);
                break;
            case 'min':
            case 'max':
            case 'hypot':
                $check();
                break;
            case 'pow':
            case 'atan2':
            case 'log':
            case 'mod':
            case 'rem':
                $check(2);
                break;
            case 'round':
            case 'clamp':
                $check(3);
                break;
            default:
                throw new \UnexpectedValueException(sprintf('Unknown calculation name "%s".', $node->getName()));
        }
    }

    /**
     * Verifies that $args all have compatible units that can be used for CSS
     * calculations, and throws a {@see SassException} if not.
     *
     * The $nodesWithSpans should correspond to the spans for $args.
     *
     * @param object[]  $args
     * @param AstNode[] $nodesWithSpans
     *
     * @throws SassException
     */
    private function verifyCompatibleNumbers(array $args, array $nodesWithSpans): void
    {
        for ($i = 0; $i < \count($args); $i++) {
            $arg = $args[$i];
            if ($arg instanceof SassNumber && $arg->hasComplexUnits()) {
                throw $this->exception("Number $arg isn't compatible with CSS calculations.", $nodesWithSpans[$i]->getSpan());
            }
        }

        for ($i = 0; $i < \count($args); $i++) {
            $number1 = $args[$i];

            if (!$number1 instanceof SassNumber) {
                continue;
            }

            for ($j = $i + 1; $j < \count($args); $j++) {
                $number2 = $args[$j];

                if (!$number2 instanceof SassNumber) {
                    continue;
                }

                if ($number1->hasPossiblyCompatibleUnits($number2)) {
                    continue;
                }

                throw new MultiSpanSassRuntimeException(
                    "$number1 and $number2 are incompatible.",
                    $nodesWithSpans[$i]->getSpan(),
                    (string) $number1,
                    [(string) $number2 => $nodesWithSpans[$j]->getSpan()],
                    $this->stackTrace($nodesWithSpans[$i]->getSpan())
                );
            }
        }
    }

    /**
     * Evaluates $node as a component of a calculation.
     *
     * If $inLegacySassFunction is `true`, this allows unitless numbers to be added and
     * subtracted with numbers with units, for backwards-compatibility with the
     * old global `min()`, `max()`, `round()` and `abs()` functions.
     *
     * @return SassNumber|CalculationOperation|SassString|SassCalculation|Value
     */
    private function visitCalculationExpression(Expression $node, bool $inLegacySassFunction): object
    {
        if ($node instanceof ParenthesizedExpression) {
            $result = $this->visitCalculationExpression($node->getExpression(), $inLegacySassFunction);

            return $result instanceof SassString
                ? new SassString('(' . $result->getText() . ')', false)
                : $result;
        }

        if ($node instanceof StringExpression) {
            if (!$node->accept(new IsCalculationSafeVisitor())) {
                throw $this->exception("This expression can't be used in a calculation.", $node->getSpan());
            }

            assert(!$node->hasQuotes());

            $text = $node->getText()->getAsPlain();
            if ($text === null) {
                return new SassString($this->performInterpolation($node->getText()), false);
            }

            return match (strtolower($text)) {
                'pi' => SassNumber::create(M_PI),
                'e' => SassNumber::create(M_E),
                'infinity' => SassNumber::create(INF),
                '-infinity' => SassNumber::create(-INF),
                'nan' => SassNumber::create(NAN),
                default => new SassString($text, false),
            };
        }

        if ($node instanceof BinaryOperationExpression) {
            $this->checkWhitespaceAroundCalculationOperator($node);
            return $this->addExceptionSpan($node, function () use ($node, $inLegacySassFunction) {
                return SassCalculation::operateInternal(
                    $this->binaryOperatorToCalculationOperator($node->getOperator(), $node),
                    $this->visitCalculationExpression($node->getLeft(), $inLegacySassFunction),
                    $this->visitCalculationExpression($node->getRight(), $inLegacySassFunction),
                    $inLegacySassFunction,
                    !$this->inSupportsDeclaration
                );
            });
        }

        if ($node instanceof NumberExpression || $node instanceof VariableExpression || $node instanceof FunctionExpression || $node instanceof IfExpression) {
            $result = $node->accept($this);

            if ($result instanceof SassNumber || $result instanceof SassCalculation) {
                return $result;
            }

            if ($result instanceof SassString && !$result->hasQuotes()) {
                return $result;
            }

            throw $this->exception("Value $result can't be used in a calculation.", $node->getSpan());
        }

        if ($node instanceof ListExpression && !$node->hasBrackets() && $node->getSeparator() === ListSeparator::SPACE && \count($node->getContents()) > 1) {
            $elements = [];
            foreach ($node->getContents() as $element) {
                $elements[] = $this->visitCalculationExpression($element, $inLegacySassFunction);
            }

            $this->checkAdjacentCalculationValues($elements, $node);

            foreach ($elements as $i => $element) {
                if ($element instanceof CalculationOperation && $node->getContents()[$i] instanceof ParenthesizedExpression) {
                    $elements[$i] = new SassString("($element)", false);
                }
            }

            return new SassString(implode(' ', $elements), false);
        }

        \assert(!$node->accept(new IsCalculationSafeVisitor()));

        throw $this->exception("This expression can't be used in a calculation.", $node->getSpan());
    }

    /**
     * Throws an error if $node requires whitespace around its operator in a
     * calculation but doesn't have it.
     */
    private function checkWhitespaceAroundCalculationOperator(BinaryOperationExpression $node): void
    {
        if ($node->getOperator() !== BinaryOperator::PLUS && $node->getOperator() !== BinaryOperator::MINUS) {
            return;
        }

        // We _should_ never be able to violate these conditions since we always
        // parse binary operations from a single file, but it's better to be safe
        // than have this crash bizarrely.
        if ($node->getLeft()->getSpan()->getFile() !== $node->getRight()->getSpan()->getFile()) {
            return;
        }
        if ($node->getLeft()->getSpan()->getEnd()->getOffset() >= $node->getRight()->getSpan()->getStart()->getOffset()) {
            return;
        }
        $textBetweenOperands = $node->getLeft()->getSpan()->getFile()->getText($node->getLeft()->getSpan()->getEnd()->getOffset(), $node->getRight()->getSpan()->getStart()->getOffset());

        $first = $textBetweenOperands[0];
        $last = $textBetweenOperands[\strlen($textBetweenOperands) - 1];

        if (!(Character::isWhitespace($first) || $first === '/') || !(Character::isWhitespace($last) || $last === '/')) {
            throw $this->exception('"+" and "-" must be surrounded by whitespace in calculations.', $node->getOperatorSpan());
        }
    }

    /**
     * Returns the {@see CalculationOperator} that corresponds to $operator.
     */
    private function binaryOperatorToCalculationOperator(BinaryOperator $operator, BinaryOperationExpression $node): CalculationOperator
    {
        return match ($operator) {
            BinaryOperator::PLUS => CalculationOperator::PLUS,
            BinaryOperator::MINUS => CalculationOperator::MINUS,
            BinaryOperator::TIMES => CalculationOperator::TIMES,
            BinaryOperator::DIVIDED_BY => CalculationOperator::DIVIDED_BY,
            default => throw $this->exception("This operation can't be used in a calculation.", $node->getOperatorSpan()),
        };
    }

    /**
     * @param list<object> $elements
     */
    private function checkAdjacentCalculationValues(array $elements, ListExpression $node): void
    {
        \assert(\count($elements) > 1);

        for ($i = 1; $i < \count($elements); $i++) {
            $previous = $elements[$i - 1];
            $current = $elements[$i];

            if ($previous instanceof SassString || $current instanceof SassString) {
                continue;
            }

            $previousNode = $node->getContents()[$i - 1];
            $currentNode = $node->getContents()[$i];

            if (
                $currentNode instanceof UnaryOperationExpression && ($currentNode->getOperator() === UnaryOperator::MINUS || $currentNode->getOperator() === UnaryOperator::PLUS)
                || $currentNode instanceof NumberExpression && $currentNode->getValue() < 0
            ) {
                // `calc(1 -2)` parses as a space-separated list whose second value is a
                // unary operator or a negative number, but just saying it's an invalid
                // expression doesn't help the user understand what's going wrong. We
                // add special case error handling to help clarify the issue.
                throw $this->exception('"+" and "-" must be surrounded by whitespace in calculations.', $currentNode->getSpan()->subspan(0, 1));
            }

            throw $this->exception('Missing math operator.', $previousNode->getSpan()->expand($currentNode->getSpan()));
        }
    }

    public function visitInterpolatedFunctionExpression(InterpolatedFunctionExpression $node): Value
    {
        $function = new PlainCssCallable($this->performInterpolation($node->getName()));

        $oldInFunction = $this->inFunction;
        $this->inFunction = true;
        $result = $this->addErrorSpan($node, function () use ($function, $node) {
            return $this->runFunctionCallable($node->getArguments(), $function, $node);
        });
        $this->inFunction = $oldInFunction;

        return $result;
    }

    /**
     * @template V of Value|null
     *
     * @param callable(): V $run
     *
     * @return V
     *
     * @param-immediately-invoked-callable $run
     */
    private function runUserDefinedCallable(ArgumentInvocation $arguments, UserDefinedCallable $callable, AstNode $nodeWithSpan, callable $run): ?Value
    {
        $evaluated = $this->evaluateArguments($arguments);
        $name = $callable->getName();

        if ($name !== '@content') {
            $name .= '()';
        }

        $oldCallable = $this->currentCallable;
        $this->currentCallable = $callable;

        $result = $this->withStackFrame($name, $nodeWithSpan, function () use ($callable, $evaluated, $nodeWithSpan, $run) {
            // Add an extra closure() call so that modifications to the environment
            // don't affect the underlying environment closure.
            return $this->withEnvironment($callable->getEnvironment()->closure(), function () use ($callable, $evaluated, $nodeWithSpan, $run) {
                return $this->environment->scope(function () use ($callable, $evaluated, $nodeWithSpan, $run) {
                    $this->verifyArguments(\count($evaluated->getPositional()), $evaluated->getNamed(), $callable->getDeclaration()->getArguments(), $nodeWithSpan);

                    $declaredArguments = $callable->getDeclaration()->getArguments()->getArguments();
                    $minLength = min(\count($evaluated->getPositional()), \count($declaredArguments));

                    for ($i = 0; $i < $minLength; $i++) {
                        $this->environment->setLocalVariable($declaredArguments[$i]->getName(), $evaluated->getPositional()[$i], $evaluated->getPositionalNodes()[$i]);
                    }

                    $named = $evaluated->getNamed();
                    $namedNodes = $evaluated->getNamedNodes();

                    for ($i = \count($evaluated->getPositional()); $i < \count($declaredArguments); $i++) {
                        $argument = $declaredArguments[$i];

                        if (isset($named[$argument->getName()])) {
                            $value = $named[$argument->getName()];
                            unset($named[$argument->getName()]);
                            $nodeForSpan = $namedNodes[$argument->getName()];
                        } else {
                            assert($argument->getDefaultValue() !== null);
                            $value = $this->withoutSlash($argument->getDefaultValue()->accept($this), $this->expressionNode($argument->getDefaultValue()));
                            $nodeForSpan = $this->expressionNode($argument->getDefaultValue());
                        }

                        $this->environment->setLocalVariable($argument->getName(), $value, $nodeForSpan);
                    }

                    $argumentList = null;
                    $restArgument = $callable->getDeclaration()->getArguments()->getRestArgument();
                    if ($restArgument !== null) {
                        $rest = array_values(array_slice($evaluated->getPositional(), \count($declaredArguments)));
                        $argumentList = new SassArgumentList($rest, $named, $evaluated->getSeparator() === ListSeparator::UNDECIDED ? ListSeparator::COMMA : $evaluated->getSeparator());
                        $this->environment->setLocalVariable($restArgument, $argumentList, $nodeWithSpan);
                    }

                    $result = $run();

                    if ($argumentList === null) {
                        return $result;
                    }
                    if ($named === []) {
                        return $result;
                    }
                    if ($argumentList->wereKeywordAccessed()) {
                        return $result;
                    }

                    $unknownNames = array_keys($named);
                    $lastName = array_pop($unknownNames);
                    $message = sprintf(
                        'No argument%s named $%s%s.',
                        $unknownNames ? 's' : '',
                        $unknownNames ? implode(', $', $unknownNames) . ' or $' : '',
                        $lastName
                    );

                    throw new MultiSpanSassRuntimeException(
                        $message,
                        $nodeWithSpan->getSpan(),
                        'invocation',
                        ['declaration' => $callable->getDeclaration()->getArguments()->getSpanWithName()],
                        $this->stackTrace($nodeWithSpan->getSpan())
                    );
                });
            });
        });

        $this->currentCallable = $oldCallable;

        return $result;
    }

    private function runFunctionCallable(ArgumentInvocation $arguments, ?SassCallable $callable, AstNode $nodeWithSpan): Value
    {
        if ($callable instanceof BuiltInCallable) {
            return $this->withoutSlash($this->runBuiltInCallable($arguments, $callable, $nodeWithSpan), $nodeWithSpan);
        }

        if ($callable instanceof UserDefinedCallable) {
            return $this->runUserDefinedCallable($arguments, $callable, $nodeWithSpan, function () use ($callable) {
                foreach ($callable->getDeclaration()->getChildren() as $statement) {
                    $returnValue = $statement->accept($this);

                    if ($returnValue instanceof Value) {
                        return $returnValue;
                    }
                }

                throw $this->exception('Function finished without @return.', $callable->getDeclaration()->getSpan());
            });
        }

        if ($callable instanceof PlainCssCallable) {
            if (\count($arguments->getNamed()) > 0 || $arguments->getKeywordRest() !== null) {
                throw $this->exception("Plain CSS functions don't support keyword arguments.", $nodeWithSpan->getSpan());
            }

            $buffer = $callable->getName() . '(';

            try {
                $first = true;

                foreach ($arguments->getPositional() as $argument) {
                    if ($first) {
                        $first = false;
                    } else {
                        $buffer .= ', ';
                    }

                    $buffer .= $this->evaluateToCss($argument);
                }

                $restArg = $arguments->getRest();
                if ($restArg !== null) {
                    $rest = $restArg->accept($this);
                    if (!$first) {
                        $buffer .= ', ';
                    }

                    $buffer .= $this->serialize($rest, $restArg);
                }
            } catch (SassRuntimeException $e) {
                if (!str_ends_with($e->getOriginalMessage(), "isn't a valid CSS value.")) {
                    throw $e;
                }

                throw new MultiSpanSassRuntimeException(
                    $e->getOriginalMessage(),
                    $e->getSpan(),
                    'value',
                    ['unknown function treated as plain CSS' => $nodeWithSpan->getSpan()],
                    $e->getSassTrace()
                );
            }

            $buffer .= ')';

            return new SassString($buffer, false);
        }

        throw new \InvalidArgumentException('Unknown callable type ' . (\is_object($callable) ? get_class($callable) : gettype($callable) ) . '.');
    }

    private function runBuiltInCallable(ArgumentInvocation $arguments, BuiltInCallable $callable, AstNode $nodeWithSpan): Value
    {
        $evaluated = $this->evaluateArguments($arguments);
        $oldCallableNode = $this->callableNode;
        $this->callableNode = $nodeWithSpan;

        /** @var ArgumentDeclaration $overload */
        [$overload, $callback] = $callable->callbackFor(\count($evaluated->getPositional()), $evaluated->getNamed());

        $this->addExceptionSpan($nodeWithSpan, function () use ($overload, $evaluated) {
            $overload->verify(\count($evaluated->getPositional()), $evaluated->getNamed());
        });

        $declaredArguments = $overload->getArguments();

        $positional = $evaluated->getPositional();
        $named = $evaluated->getNamed();

        for ($i = \count($positional); $i < \count($declaredArguments); $i++) {
            $argument = $declaredArguments[$i];

            if (isset($named[$argument->getName()])) {
                $positional[] = $named[$argument->getName()];
                unset($named[$argument->getName()]);
            } else {
                assert($argument->getDefaultValue() !== null);
                $positional[] = $this->withoutSlash($argument->getDefaultValue()->accept($this), $argument->getDefaultValue());
            }
        }

        $argumentList = null;
        if ($overload->getRestArgument() !== null) {
            $rest = array_values(array_splice($positional, \count($declaredArguments)));
            \assert(array_is_list($positional));
            $argumentList = new SassArgumentList($rest, $named, $evaluated->getSeparator() === ListSeparator::UNDECIDED ? ListSeparator::COMMA : $evaluated->getSeparator());
            $positional[] = $argumentList;
        }

        try {
            $result = $this->addExceptionSpan($nodeWithSpan, function () use ($callback, $positional) {
                return $callback($positional);
            });
        } catch (SassException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw $this->exception($e->getMessage(), $nodeWithSpan->getSpan(), $e);
        }

        $this->callableNode = $oldCallableNode;

        if ($argumentList === null) {
            return $result;
        }
        if ($named === []) {
            return $result;
        }
        if ($argumentList->wereKeywordAccessed()) {
            return $result;
        }

        $unknownNames = array_keys($named);
        $lastName = array_pop($unknownNames);
        $message = sprintf(
            'No argument%s named $%s%s.',
            $unknownNames ? 's' : '',
            $unknownNames ? implode(', $', $unknownNames) . ' or $' : '',
            $lastName
        );

        throw new MultiSpanSassRuntimeException(
            $message,
            $nodeWithSpan->getSpan(),
            'invocation',
            ['declaration' => $overload->getSpanWithName()],
            $this->stackTrace($nodeWithSpan->getSpan())
        );
    }

    private function evaluateArguments(ArgumentInvocation $arguments): ArgumentResults
    {
        $positional = [];
        $positionalNodes = [];

        foreach ($arguments->getPositional() as $expression) {
            $nodeForSpan = $this->expressionNode($expression);
            $positional[] = $this->withoutSlash($expression->accept($this), $nodeForSpan);
            $positionalNodes[] = $nodeForSpan;
        }

        $named = [];
        $namedNodes = [];

        foreach ($arguments->getNamed() as $key => $value) {
            $nodeForSpan = $this->expressionNode($value);
            $named[$key] = $this->withoutSlash($value->accept($this), $nodeForSpan);
            $namedNodes[$key] = $nodeForSpan;
        }

        $restArgs = $arguments->getRest();
        if ($restArgs === null) {
            return new ArgumentResults($positional, $positionalNodes, $named, $namedNodes, ListSeparator::UNDECIDED);
        }

        $rest = $restArgs->accept($this);
        $restNodeForSpan = $this->expressionNode($restArgs);
        $separator = ListSeparator::UNDECIDED;

        if ($rest instanceof SassMap) {
            $this->addRestMap($named, $rest, $restArgs, fn($value) => $value);
            foreach ($rest->getContents() as $key => $_) {
                assert($key instanceof SassString);
                $namedNodes[$key->getText()] = $restNodeForSpan;
            }
        } elseif ($rest instanceof SassList) {
            foreach ($rest->asList() as $value) {
                $positional[] = $this->withoutSlash($value, $restNodeForSpan);
                $positionalNodes[] = $restNodeForSpan;
                $separator = $rest->getSeparator();
            }

            if ($rest instanceof SassArgumentList) {
                foreach ($rest->getKeywords() as $key => $value) {
                    $named[$key] = $this->withoutSlash($value, $restNodeForSpan);
                    $namedNodes[$key] = $restNodeForSpan;
                }
            }
        } else {
            $positional[] = $this->withoutSlash($rest, $restNodeForSpan);
            $positionalNodes[] = $restNodeForSpan;
        }

        $keywordRestArgs = $arguments->getKeywordRest();
        if ($keywordRestArgs === null) {
            return new ArgumentResults($positional, $positionalNodes, $named, $namedNodes, $separator);
        }

        $keywordRest = $keywordRestArgs->accept($this);
        $keywordRestNodeForSpan = $this->expressionNode($keywordRestArgs);

        if ($keywordRest instanceof SassMap) {
            $this->addRestMap($named, $keywordRest, $keywordRestArgs, fn($value) => $value);
            foreach ($keywordRest->getContents() as $key => $_) {
                assert($key instanceof SassString);
                $namedNodes[$key->getText()] = $keywordRestNodeForSpan;
            }

            return new ArgumentResults($positional, $positionalNodes, $named, $namedNodes, $separator);
        }

        throw $this->exception("Variable keyword arguments must be a map (was $keywordRest).", $keywordRestArgs->getSpan());
    }

    /**
     * Evaluates the arguments in [arguments] only as much as necessary to
     * separate out positional and named arguments.
     *
     * Returns the arguments as expressions so that they can be lazily evaluated
     * for macros such as `if()`.
     *
     * @return array{list<Expression>, array<string, Expression>}
     */
    private function evaluateMacroArguments(CallableInvocation $invocation): array
    {
        $restArgs = $invocation->getArguments()->getRest();
        if ($restArgs === null) {
            return [$invocation->getArguments()->getPositional(), $invocation->getArguments()->getNamed()];
        }

        $positional = $invocation->getArguments()->getPositional();
        $named = $invocation->getArguments()->getNamed();
        $rest = $restArgs->accept($this);
        $restNodeForSpan = $this->expressionNode($restArgs);

        if ($rest instanceof SassMap) {
            $this->addRestMap($named, $rest, $restArgs, function ($value) use ($restArgs) {
                return new ValueExpression($value, $restArgs->getSpan());
            });
        } elseif ($rest instanceof SassList) {
            foreach ($rest->asList() as $value) {
                $positional[] = new ValueExpression($this->withoutSlash($value, $restNodeForSpan), $restArgs->getSpan());
            }

            if ($rest instanceof SassArgumentList) {
                foreach ($rest->getKeywords() as $key => $value) {
                    $named[$key] = new ValueExpression($this->withoutSlash($value, $restNodeForSpan), $restArgs->getSpan());
                }
            }
        } else {
            $positional[] = new ValueExpression($this->withoutSlash($rest, $restNodeForSpan), $restArgs->getSpan());
        }

        $keywordRestArgs = $invocation->getArguments()->getKeywordRest();
        if ($keywordRestArgs === null) {
            return [$positional, $named];
        }

        $keywordRest = $keywordRestArgs->accept($this);
        $keywordRestNodeForSpan = $this->expressionNode($keywordRestArgs);

        if ($keywordRest instanceof SassMap) {
            $this->addRestMap($named, $keywordRest, $keywordRestArgs, function ($value) use ($keywordRestArgs, $keywordRestNodeForSpan) {
                return new ValueExpression($this->withoutSlash($value, $keywordRestNodeForSpan), $keywordRestArgs->getSpan());
            });

            return [$positional, $named];
        }

        throw $this->exception("Variable keyword arguments must be a map (was $keywordRest).", $keywordRestArgs->getSpan());
    }

    /**
     * Adds the values in $map to $values.
     *
     * Throws a {@see SassRuntimeException} associated with $nodeWithSpan's source
     * span if any $map keys aren't strings.
     *
     * @template T
     *
     * @param array<string, T>   $values
     * @param callable(Value): T $convert
     *
     * @param-immediately-invoked-callable $convert
     */
    private function addRestMap(array &$values, SassMap $map, AstNode $nodeWithSpan, callable $convert): void
    {
        $expressionNode = $this->expressionNode($nodeWithSpan);

        foreach ($map->getContents() as $key => $value) {
            if ($key instanceof SassString) {
                $values[$key->getText()] = $convert($this->withoutSlash($value, $expressionNode));
            } else {
                throw $this->exception("Variable keyword argument map must have string keys.\n$key is not a string in $map.", $nodeWithSpan->getSpan());
            }
        }
    }

    /**
     * @param array<string, mixed> $named
     *
     * @throws SassRuntimeException if $positional and $named aren't valid when applied to $arguments.
     */
    private function verifyArguments(int $positional, array $named, ArgumentDeclaration $arguments, AstNode $nodeWithSpan): void
    {
        $this->addExceptionSpan($nodeWithSpan, function () use ($positional, $named, $arguments) {
            $arguments->verify($positional, $named);
        });
    }

    public function visitSelectorExpression(SelectorExpression $node): Value
    {
        if ($this->styleRuleIgnoringAtRoot === null) {
            return SassNull::create();
        }

        return $this->styleRuleIgnoringAtRoot->getOriginalSelector()->asSassList();
    }

    public function visitStringExpression(StringExpression $node): Value
    {
        // Don't use [performInterpolation] here because we need to get the raw text
        // from strings, rather than the semantic value.
        $oldInSupportsDeclaration = $this->inSupportsDeclaration;
        $this->inSupportsDeclaration = false;

        $result = new SassString(implode('', array_map(function ($value) {
            if (\is_string($value)) {
                return $value;
            }

            $expression = $value;
            $result = $expression->accept($this);

            if ($result instanceof SassString) {
                return $result->getText();
            }

            return $this->serialize($result, $expression, false);
        }, $node->getText()->getContents())), $node->hasQuotes());

        $this->inSupportsDeclaration = $oldInSupportsDeclaration;

        return $result;
    }

    public function visitSupportsExpression(SupportsExpression $node): Value
    {
        return new SassString($this->visitSupportsCondition($node->getCondition()), false);
    }

    /**
     * Runs $callback for each value in $list until it returns a {@see Value}.
     *
     * Returns the value returned by $callback, or `null` if it only ever
     * returned `null`.
     *
     * @template T
     *
     * @param T[]                 $list
     * @param callable(T): ?Value $callback
     *
     * @param-immediately-invoked-callable $callback
     */
    private function handleReturn(array $list, callable $callback): ?Value
    {
        foreach ($list as $value) {
            $result = $callback($value);

            if ($result !== null) {
                return $result;
            }
        }

        return null;
    }

    /**
     * Runs $callback with $environment as the current environment.
     *
     * @template T
     *
     * @param Environment   $environment
     * @param callable(): T $callback
     *
     * @return T
     *
     * @param-immediately-invoked-callable $callback
     */
    private function withEnvironment(Environment $environment, callable $callback)
    {
        $oldEnvironment = $this->environment;
        $this->environment = $environment;
        $result = $callback();
        $this->environment = $oldEnvironment;

        return $result;
    }

    /**
     * @return CssValue<string>
     */
    private function interpolationToValue(Interpolation $interpolation, bool $warnForColor = false, bool $trim = false): CssValue
    {
        $result = $this->performInterpolation($interpolation, $warnForColor);

        return new CssValue($trim ? StringUtil::trimAscii($result, true) : $result, $interpolation->getSpan());
    }

    /**
     * Evaluates $interpolation.
     *
     * If $warnForColor is `true`, this will emit a warning for any named color
     * values passed into the interpolation.
     */
    private function performInterpolation(Interpolation $interpolation, bool $warnForColor = false): string
    {
        $tuple = $this->performInterpolationHelper($interpolation, false, $warnForColor);

        return $tuple[0];
    }

    /**
     * Like {@see performInterpolation}, but also returns a {@see InterpolationMap} that
     * can map spans from the resulting string back to the original
     * $interpolation.
     *
     * @return array{string, InterpolationMap}
     */
    private function performInterpolationWithMap(Interpolation $interpolation, bool $warnForColor = false): array
    {
        $tuple = $this->performInterpolationHelper($interpolation, true, $warnForColor);
        \assert($tuple[1] !== null);

        return $tuple;
    }

    /**
     * A helper that implements the core logic of both {@see performInterpolation}
     * and {@see performInterpolationWithMap}.
     *
     * @return array{string, InterpolationMap|null}
     */
    private function performInterpolationHelper(Interpolation $interpolation, bool $sourceMap, bool $warnForColor = false): array
    {
        $targetLocations = $sourceMap ? [] : null;

        $oldInSupportsDeclaration = $this->inSupportsDeclaration;
        $this->inSupportsDeclaration = false;

        $buffer = '';
        $first = true;

        foreach ($interpolation->getContents() as $value) {
            if (!$first && $targetLocations !== null) {
                $targetLocations[] = new SimpleSourceLocation(\strlen($buffer));
            }

            $first = false;

            if (\is_string($value)) {
                $buffer .= $value;
                continue;
            }

            $expression = $value;
            $result = $expression->accept($this);

            if ($warnForColor && $result instanceof SassColor && null !== $colorName = Colors::RGBaToColorName($result->getRed(), $result->getGreen(), $result->getBlue(), $result->getAlpha())) {
                $alternative = new BinaryOperationExpression(
                    BinaryOperator::PLUS,
                    new StringExpression(new Interpolation([''], $interpolation->getSpan()), true),
                    $expression
                );
                $this->warn("You probably don't mean to use the color value $colorName in interpolation here.\nIt may end up represented as $result, which will likely produce invalid CSS.\nAlways quote color names when using them as strings or map keys (for example, \"$colorName\").\nIf you really want to use the color value here, use '$alternative'.", $expression->getSpan());
            }

            $buffer .= $this->serialize($result, $expression, false);
        }

        $this->inSupportsDeclaration = $oldInSupportsDeclaration;

        return [$buffer, $targetLocations === null ? null : new InterpolationMap($interpolation, $targetLocations)];
    }

    /**
     * Evaluates $expression and calls `toCssString()` and wraps a
     * {@see SassScriptException} to associate it with its span.
     */
    private function evaluateToCss(Expression $expression, bool $quote = true): string
    {
        return $this->serialize($expression->accept($this), $expression, $quote);
    }

    /**
     * Calls `value->toCssString()` and wraps a {@see SassScriptException} to associate
     * it with $nodeWithSpan's source span.
     *
     * This takes an {@see AstNode} rather than a {@see FileSpan} so it can avoid calling
     * {@see AstNode::getSpan} if the span isn't required, since some nodes need to do
     * real work to manufacture a source span.
     */
    private function serialize(Value $value, AstNode $nodeWithSpan, bool $quote = true): string
    {
        return $this->addExceptionSpan($nodeWithSpan, function () use ($value, $quote) {
            return $value->toCssString($quote);
        });
    }

    /**
     * Runs $callback with $rule as the current style rule.
     *
     * @template T
     *
     * @param ModifiableCssStyleRule $rule
     * @param callable(): T          $callback
     *
     * @return T
     *
     * @param-immediately-invoked-callable $callback
     */
    private function withStyleRule(ModifiableCssStyleRule $rule, callable $callback)
    {
        $oldRule = $this->styleRuleIgnoringAtRoot;
        $this->styleRuleIgnoringAtRoot = $rule;
        $result = $callback();
        $this->styleRuleIgnoringAtRoot = $oldRule;

        return $result;
    }

    /**
     * Runs $callback with $queries as the current media queries.
     *
     * This also sets $sources as the current set of media queries that were
     * merged together to create $queries. This is used to determine when it's
     * safe to bubble one query through another.
     *
     * @template T
     *
     * @param list<CssMediaQuery>|null $queries
     * @param CssMediaQuery[]|null     $sources
     * @param callable(): T            $callback
     *
     * @return T
     *
     * @param-immediately-invoked-callable $callback
     */
    private function withMediaQueries(?array $queries, ?array $sources, callable $callback)
    {
        $oldMediaQueries = $this->mediaQueries;
        $oldSources = $this->mediaQuerySources;
        $this->mediaQueries = $queries;
        $this->mediaQuerySources = $sources;
        $result = $callback();
        $this->mediaQueries = $oldMediaQueries;
        $this->mediaQuerySources = $oldSources;

        return $result;
    }

    /**
     * Returns the {@see AstNode} whose span should be used for $expression.
     *
     * If $expression is a variable reference, {@see AstNode}'s span will be the span
     * where that variable was originally declared. Otherwise, this will just
     * return $expression.
     */
    private function expressionNode(AstNode $expression): AstNode
    {
        if ($expression instanceof VariableExpression) {
            return $this->addExceptionSpan($expression, function () use ($expression) {
                return $this->environment->getVariableNode($expression->getName()) ?? $expression;
            });
        }

        return $expression;
    }

    /**
     * Adds $node as a child of the current parent, then runs $callback with
     * $node as the current parent.
     *
     * If $through is passed, $node is added as a child of the first parent for
     * which $through returns `false`. That parent is copied unless it's the
     * lattermost child of its parent.
     *
     * Runs $callback in a new environment scope unless $scopeWhen is false.
     *
     * @template S of ModifiableCssParentNode
     * @template T
     *
     * @param S                            $node
     * @param callable(): T                $callback
     * @param null|callable(CssNode): bool $through
     * @param bool                         $scopeWhen
     *
     * @return T
     *
     * @param-immediately-invoked-callable $callback
     * @param-immediately-invoked-callable $through
     */
    private function withParent(ModifiableCssParentNode $node, callable $callback, ?callable $through = null, bool $scopeWhen = true)
    {
        $this->addChild($node, $through);

        $oldParent = $this->parent;
        $this->parent = $node;
        $result = $this->environment->scope($callback, $scopeWhen);
        $this->parent = $oldParent;

        return $result;
    }

    /**
     * Adds $node as a child of the current parent.
     *
     * If $through is passed, $node is added as a child of the first parent for
     * which $through returns `false` instead. That parent is copied unless it's the
     * lattermost child of its parent.
     *
     * @param null|callable(CssNode): bool $through
     *
     * @param-immediately-invoked-callable $through
     */
    private function addChild(ModifiableCssNode $node, ?callable $through = null): void
    {
        // Go up through parents that match [through].
        $parent = $this->getParent();
        if ($through !== null) {
            while ($through($parent)) {
                $grandParent = $parent->getParent();

                if ($grandParent === null) {
                    throw new \InvalidArgumentException('$through() must return false for at least one parent of the node.');
                }

                $parent = $grandParent;
            }
        }

        // If the parent has a (visible) following sibling, we shouldn't add to
        // the parent. Instead, we should create a copy and add it after the
        // interstitial sibling.
        if ($parent->hasFollowingSibling()) {
            $grandParent = $parent->getParent();
            // A node with siblings must have a parent
            assert($grandParent !== null);
            $lastChild = ListUtil::last($grandParent->getChildren());
            if ($parent->equalsIgnoringChildren($lastChild)) {
                \assert($lastChild instanceof ModifiableCssParentNode);
                $parent = $lastChild;
            } else {
                $parent = $parent->copyWithoutChildren();
                $grandParent->addChild($parent);
            }
        }

        $parent->addChild($node);
    }

    /**
     * Adds a frame to the stack with the given $member name, and $nodeWithSpan
     * as the site of the new frame.
     *
     * Runs $callback with the new stack.
     *
     * This takes an {@see AstNode} rather than a {@see FileSpan} so it can avoid calling
     * {@see AstNode::getSpan} if the span isn't required, since some nodes need to do
     * real work to manufacture a source span.
     *
     * @template T
     *
     * @param callable(): T $callback
     *
     * @return T
     *
     * @param-immediately-invoked-callable $callback
     */
    private function withStackFrame(string $member, AstNode $nodeWithSpan, callable $callback)
    {
        $this->stack[] = [$this->member, $nodeWithSpan];
        $oldMember = $this->member;
        $this->member = $member;
        $result = $callback();
        $this->member = $oldMember;
        array_pop($this->stack);

        return $result;
    }

    /**
     * Like {@see Value::withoutSlash}, but produces a deprecation warning if $value
     * was a slash-separated number.
     */
    private function withoutSlash(Value $value, AstNode $nodeForSpan): Value
    {
        if ($value instanceof SassNumber && $value->getAsSlash() !== null) {
            $recommendation = function (SassNumber $number) use (&$recommendation): string {
                if ($number->getAsSlash() !== null) {
                    [$before, $after] = $number->getAsSlash();
                    return "math.div({$recommendation($before)}, {$recommendation($after)})";
                }

                return (string) $number;
            };

            $message = <<<WARNING
Using / for division is deprecated and will be removed in Dart Sass 2.0.0.

Recommendation: {$recommendation($value)}

More info and automated migrator: https://sass-lang.com/d/slash-div
WARNING;
            $this->warn($message, $nodeForSpan->getSpan(), Deprecation::slashDiv);
        }

        return $value->withoutSlash();
    }

    /**
     * Creates a new stack frame with location information from $member$ and
     * $span.
     */
    private function stackFrame(string $member, FileSpan $span): Frame
    {
        $url = $span->getSourceUrl();

        if ($url !== null) {
            $url = $this->importCache->humanize($url);
        }

        return Util::frameForSpan($span, $member, $url);
    }

    /**
     * Returns a stack trace at the current point.
     *
     * If $span is passed, it's used for the innermost stack frame.
     */
    private function stackTrace(?FileSpan $span = null): Trace
    {
        $frames = [];

        foreach ($this->stack as [$member, $nodeWithSpan]) {
            $frames[] = $this->stackFrame($member, $nodeWithSpan->getSpan());
        }

        if ($span !== null) {
            $frames[] = $this->stackFrame($this->member, $span);
        }

        return new Trace(array_reverse($frames));
    }

    public function warn(string $message, FileSpan $span, ?Deprecation $deprecation = null): void
    {
        if ($this->quietDeps && ($this->inDependency || ($this->currentCallable !== null && $this->currentCallable->isInDependency()))) {
            return;
        }

        $spanString = ($span->getSourceUrl() ?? '') . "\0" . $span->getStart()->getOffset() . "\0" . $span->getEnd()->getOffset();

        if (isset($this->warningsEmitted[$message][$spanString])) {
            return;
        }
        $this->warningsEmitted[$message][$spanString] = true;

        $trace = $this->stackTrace($span);

        if ($deprecation === null) {
            $this->logger->warn($message, null, $span, $trace);
        } else {
            LoggerUtil::warnForDeprecation($this->logger, $deprecation, $message, $span, $trace);
        }
    }

    /**
     * Returns a {@see SassRuntimeException} with the given $message.
     *
     * If $span is passed, it's used for the innermost stack frame.
     */
    private function exception(string $message, ?FileSpan $span = null, ?\Throwable $previous = null): SassRuntimeException
    {
        return new SimpleSassRuntimeException($message, $span ?? ListUtil::last($this->stack)[1]->getSpan(), $this->stackTrace($span), $previous);
    }

    /**
     * Returns a {@see MultiSpanSassRuntimeException} with the given $message,
     * $primaryLabel, and $secondaryLabels.
     *
     * The primary span is taken from the current stack trace span.
     *
     * @param array<string, FileSpan> $secondarySpans
     */
    private function multiSpanException(string $message, string $primaryLabel, array $secondarySpans): SassRuntimeException
    {
        return new MultiSpanSassRuntimeException($message, ListUtil::last($this->stack)[1]->getSpan(), $primaryLabel, $secondarySpans, $this->stackTrace());
    }

    /**
     * Runs $callback, and converts any {@see SassScriptException}s it throws to
     * {@see SassRuntimeException}s with $nodeWithSpan's source span.
     *
     * This takes an {@see AstNode} rather than a {@see FileSpan} so it can avoid calling
     * {@see AstNode::getSpan} if the span isn't required, since some nodes need to do
     * real work to manufacture a source span.
     *
     * If $addStackFrame is true (the default), this will add an innermost stack
     * frame for $nodeWithSpan. Otherwise, it will use the existing stack as-is.
     *
     * @template T
     *
     * @param callable(): T $callback
     *
     * @return T
     *
     * @throws SassRuntimeException
     *
     * @param-immediately-invoked-callable $callback
     */
    private function addExceptionSpan(AstNode $nodeWithSpan, callable $callback, bool $addStackFrame = true)
    {
        try {
            return $callback();
        } catch (SassScriptException $e) {
            throw $e->withSpan($nodeWithSpan->getSpan())->withTrace($this->stackTrace($addStackFrame ? $nodeWithSpan->getSpan() : null), $e);
        }
    }

    /**
     * Runs $callback, and converts any {@see SassException}s that aren't already
     * {@see SassRuntimeException}s to {@see SassRuntimeException}s with the current stack
     * trace.
     *
     * @template T
     *
     * @param callable(): T $callback
     * @return T
     *
     * @param-immediately-invoked-callable $callback
     */
    private function addExceptionTrace(callable $callback)
    {
        try {
            return $callback();
        } catch (SassRuntimeException $e) {
            throw $e;
        } catch (SassException $e) {
            throw $e->withTrace($this->stackTrace($e->getSpan()), $e);
        }
    }

    /**
     * Runs $callback, and converts any {@see SassRuntimeException}s containing an
     * `@error` to throw a more relevant {@see SassRuntimeException}s with $nodeWithSpan's
     * source span.
     *
     * @template T
     *
     * @param AstNode       $nodeWithSpan
     * @param callable(): T $callback
     *
     * @return T
     *
     * @param-immediately-invoked-callable $callback
     */
    private function addErrorSpan(AstNode $nodeWithSpan, callable $callback)
    {
        try {
            return $callback();
        } catch (SassRuntimeException $e) {
            if (!str_starts_with($e->getSpan()->getText(), '@error')) {
                throw $e;
            }

            throw new SimpleSassRuntimeException($e->getOriginalMessage(), $nodeWithSpan->getSpan(), $this->stackTrace(), $e);
        }
    }
}
