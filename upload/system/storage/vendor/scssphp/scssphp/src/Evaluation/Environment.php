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

use ScssPhp\ScssPhp\Ast\AstNode;
use ScssPhp\ScssPhp\SassCallable\SassCallable;
use ScssPhp\ScssPhp\SassCallable\UserDefinedCallable;
use ScssPhp\ScssPhp\Value\Value;
use SourceSpan\FileSpan;

/**
 * The lexical environment in which Sass is executed.
 *
 * This tracks lexically-scoped information, such as variables, functions, and
 * mixins.
 *
 * @internal
 */
final class Environment
{
    /**
     * A list of variables defined at each lexical scope level.
     *
     * Each scope maps the names of declared variables to their values.
     *
     * The first element is the global scope, and each successive element is
     * deeper in the tree.
     *
     * @var array<int, \ArrayObject<string, Value>>
     */
    private array $variables;

    /**
     * The nodes where each variable in {@see variables} was defined.
     *
     * This stores {@see AstNode}s rather than {@see FileSpan}s so it can avoid calling
     * {@see AstNode::getSspan} if the span isn't required, since some nodes need to do
     * real work to manufacture a source span.
     *
     * @var array<int, \ArrayObject<string, AstNode>>
     */
    private array $variableNodes;

    /**
     * A map of variable names to their indices in {@see variables}.
     *
     * This map is filled in as-needed, and may not be complete.
     *
     * @var array<string, int>
     */
    private array $variableIndices = [];

    /**
     * A list of functions defined at each lexical scope level.
     *
     * Each scope maps the names of declared functions to their values.
     *
     * The first element is the global scope, and each successive element is
     * deeper in the tree.
     *
     * @var array<int, \ArrayObject<string, SassCallable>>
     */
    private array $functions;

    /**
     * A map of function names to their indices in {@see functions}.
     *
     * This map is filled in as-needed, and may not be complete.
     *
     * @var array<string, int>
     */
    private array $functionIndices = [];

    /**
     * A list of mixins defined at each lexical scope level.
     *
     * Each scope maps the names of declared mixins to their values.
     *
     * The first element is the global scope, and each successive element is
     * deeper in the tree.
     *
     * @var array<int, \ArrayObject<string, SassCallable>>
     */
    private array $mixins;

    /**
     * A map of mixin names to their indices in {@see mixins}.
     *
     * This map is filled in as-needed, and may not be complete.
     *
     * @var array<string, int>
     */
    private array $mixinIndices = [];

    /**
     * The content block passed to the lexically-enclosing mixin, or `null` if
     * this is not in a mixin, or if no content block was passed.
     */
    private ?UserDefinedCallable $content;

    /**
     * Whether the environment is lexically within a mixin.
     */
    private bool $inMixin = false;

    /**
     * Whether the environment is currently in a global or semi-global scope.
     *
     * A semi-global scope can assign to global variables, but it doesn't declare
     * them by default.
     */
    private bool $inSemiGlobalScope = true;

    /**
     * The name of the last variable that was accessed.
     *
     * This is cached to speed up repeated references to the same variable, as
     * well as references to the last variable's {@see FileSpan}.
     */
    private ?string $lastVariableName = null;

    /**
     * The index in {@see variables} of the last variable that was accessed.
     */
    private ?int $lastVariableIndex = null;

    public static function create(): Environment
    {
        return new Environment([new \ArrayObject()], [new \ArrayObject()], [new \ArrayObject()], [new \ArrayObject()]);
    }

    /**
     * @param array<int, \ArrayObject<string, Value>>        $variables
     * @param array<int, \ArrayObject<string, AstNode>>      $variableNodes
     * @param array<int, \ArrayObject<string, SassCallable>> $functions
     * @param array<int, \ArrayObject<string, SassCallable>> $mixins
     */
    private function __construct(array $variables, array $variableNodes, array $functions, array $mixins, ?UserDefinedCallable $content = null)
    {
        $this->variables = $variables;
        $this->variableNodes = $variableNodes;
        $this->functions = $functions;
        $this->mixins = $mixins;
        $this->content = $content;
    }

    public function getContent(): ?UserDefinedCallable
    {
        return $this->content;
    }

    /**
     * Whether the environment is lexically at the root of the document.
     */
    public function atRoot(): bool
    {
        return \count($this->variables) === 1;
    }

    public function isInMixin(): bool
    {
        return $this->inMixin;
    }

    /**
     * Creates a closure based on this environment.
     *
     * Any scope changes in this environment will not affect the closure.
     * However, any new declarations or assignments in scopes that are visible
     * when the closure was created will be reflected.
     */
    public function closure(): Environment
    {
        return new Environment($this->variables, $this->variableNodes, $this->functions, $this->mixins, $this->content);
    }

    /**
     * Returns a new environment to use for an imported file.
     *
     * The returned environment shares this environment's variables, functions,
     * and mixins, but excludes most modules (except for global modules that
     * result from importing a file with forwards).
     */
    public function forImport(): Environment
    {
        return new Environment($this->variables, $this->variableNodes, $this->functions, $this->mixins, $this->content);
    }

    public function getVariable(string $name): ?Value
    {
        if ($this->lastVariableName === $name) {
            assert($this->lastVariableIndex !== null);

            return $this->variables[$this->lastVariableIndex][$name] ?? null;
        }

        $index = $this->variableIndices[$name] ?? null;

        if ($index !== null) {
            $this->lastVariableName = $name;
            $this->lastVariableIndex = $index;

            return $this->variables[$index][$name] ?? null;
        }

        $index = $this->variableIndex($name);

        if ($index === null) {
            return null;
        }

        $this->lastVariableName = $name;
        $this->lastVariableIndex = $index;
        $this->variableIndices[$name] = $index;

        return $this->variables[$index][$name] ?? null;
    }

    public function getVariableNode(string $name): ?AstNode
    {
        if ($this->lastVariableName === $name) {
            assert($this->lastVariableIndex !== null);

            return $this->variableNodes[$this->lastVariableIndex][$name] ?? null;
        }

        $index = $this->variableIndices[$name] ?? null;

        if ($index !== null) {
            $this->lastVariableName = $name;
            $this->lastVariableIndex = $index;

            return $this->variableNodes[$index][$name] ?? null;
        }

        $index = $this->variableIndex($name);

        if ($index === null) {
            return null;
        }

        $this->lastVariableName = $name;
        $this->lastVariableIndex = $index;
        $this->variableIndices[$name] = $index;

        return $this->variableNodes[$index][$name] ?? null;
    }

    /**
     * Returns whether a variable named $name exists.
     */
    public function variableExists(string $name): bool
    {
        return $this->getVariable($name) !== null;
    }

    /**
     * Returns whether a global variable named $name exists.
     */
    public function globalVariableExists(string $name): bool
    {
        return isset($this->variables[0][$name]);
    }

    /**
     * Returns the index of the last map in {@see variables} that has a $name key,
     * or `null` if none exists.
     */
    private function variableIndex(string $name): ?int
    {
        for ($i = \count($this->variables) - 1; $i >= 0; $i--) {
            if (isset($this->variables[$i][$name])) {
                return $i;
            }
        }

        return null;
    }

    /**
     * Sets the variable named $name to $value.
     *
     * If $global is `true`, this sets the variable at the top-level scope.
     * Otherwise, if the variable was already defined, it'll set it in the
     * previous scope. If it's undefined, it'll set it in the current scope.
     */
    public function setVariable(string $name, Value $value, AstNode $nodeWithSpan, bool $global = false): void
    {
        if ($global || $this->atRoot()) {
            // Don't set the index if there's already a variable with the given name,
            // since local accesses should still return the local variable.
            if (!isset($this->variableIndices[$name])) {
                $this->lastVariableName = $name;
                $this->lastVariableIndex = 0;
                $this->variableIndices[$name] = 0;
            }

            $this->variables[0][$name] = $value;
            $this->variableNodes[0][$name] = $nodeWithSpan;
            return;
        }

        if ($this->lastVariableName === $name) {
            assert($this->lastVariableIndex !== null);
            $index = $this->lastVariableIndex;
        } else {
            if (!isset($this->variableIndices[$name])) {
                $this->variableIndices[$name] = $this->variableIndex($name) ?? \count($this->variables) - 1;
            }
            $index = $this->variableIndices[$name];
        }

        if (!$this->inSemiGlobalScope && $index === 0) {
            $index = \count($this->variables) - 1;
            $this->variableIndices[$name] = $index;
        }

        $this->lastVariableName = $name;
        $this->lastVariableIndex = $index;
        $this->variables[$index][$name] = $value;
        $this->variableNodes[$index][$name] = $nodeWithSpan;
    }

    /**
     * Sets the variable named $name to $value.
     *
     * Unlike {@see setVariable}, this will declare the variable in the current scope
     * even if a declaration already exists in an outer scope.
     */
    public function setLocalVariable(string $name, Value $value, AstNode $nodeWithSpan): void
    {
        $index = \count($this->variables) - 1;
        $this->lastVariableName = $name;
        $this->lastVariableIndex = $index;
        $this->variableIndices[$name] = $index;
        $this->variables[$index][$name] = $value;
        $this->variableNodes[$index][$name] = $nodeWithSpan;
    }

    public function getFunction(string $name): ?SassCallable
    {
        $index = $this->functionIndices[$name] ?? null;

        if ($index !== null) {
            return $this->functions[$index][$name] ?? null;
        }

        $index = $this->functionIndex($name);
        if ($index === null) {
            return null;
        }

        $this->functionIndices[$name] = $index;

        return $this->functions[$index][$name] ?? null;
    }

    /**
     * Returns the index of the last map in {@see functions} that has a $name key,
     * or `null` if none exists.
     */
    private function functionIndex(string $name): ?int
    {
        for ($i = \count($this->functions) - 1; $i >= 0; $i--) {
            if (isset($this->functions[$i][$name])) {
                return $i;
            }
        }

        return null;
    }

    /**
     * Returns whether a function named $name exists.
     */
    public function functionExists(string $name): bool
    {
        return $this->getFunction($name) !== null;
    }

    public function setFunction(SassCallable $callable): void
    {
        $index = \count($this->functions) - 1;
        $name = $callable->getName();
        $this->functionIndices[$name] = $index;
        $this->functions[$index][$name] = $callable;
    }

    public function getMixin(string $name): ?SassCallable
    {
        $index = $this->mixinIndices[$name] ?? null;

        if ($index !== null) {
            return $this->mixins[$index][$name] ?? null;
        }

        $index = $this->mixinIndex($name);
        if ($index === null) {
            return null;
        }

        $this->mixinIndices[$name] = $index;

        return $this->mixins[$index][$name] ?? null;
    }

    /**
     * Returns the index of the last map in {@see mixins} that has a $name key,
     * or `null` if none exists.
     */
    private function mixinIndex(string $name): ?int
    {
        for ($i = \count($this->mixins) - 1; $i >= 0; $i--) {
            if (isset($this->mixins[$i][$name])) {
                return $i;
            }
        }

        return null;
    }

    /**
     * Returns whether a mixin named $name exists.
     */
    public function mixinExists(string $name): bool
    {
        return $this->getMixin($name) !== null;
    }

    public function setMixin(SassCallable $callable): void
    {
        $index = \count($this->mixins) - 1;
        $name = $callable->getName();
        $this->mixinIndices[$name] = $index;
        $this->mixins[$index][$name] = $callable;
    }

    /**
     * Sets $content as {@see content} for the duration of $callback.
     *
     * @param callable(): void $callback
     *
     * @param-immediately-invoked-callable $callback
     */
    public function withContent(?UserDefinedCallable $content, callable $callback): void
    {
        $oldContent = $this->content;
        $this->content = $content;
        $callback();
        $this->content = $oldContent;
    }

    /**
     * Sets {@see inMixin} to `true` for the duration of $callback.
     *
     * @param callable(): void $callback
     *
     * @param-immediately-invoked-callable $callback
     */
    public function asMixin(callable $callback): void
    {
        $oldInMixin = $this->inMixin;
        $this->inMixin = true;
        $callback();
        $this->inMixin = $oldInMixin;
    }

    /**
     * Runs $callback in a new scope.
     *
     * Variables, functions, and mixins declared in a given scope are
     * inaccessible outside of it. If $semiGlobal is passed, this scope can
     * assign to global variables without a `!global` declaration.
     *
     * If $when is false, this doesn't create a new scope and instead just
     * executes $callback and returns its result.
     *
     * @template T
     *
     * @param callable(): T $callback
     *
     * @return T
     *
     * @param-immediately-invoked-callable $callback
     */
    public function scope(callable $callback, bool $when = true, bool $semiGlobal = false)
    {
        // We have to track semi-globalness even if `!$when` so that
        //
        //     div {
        //       @if ... {
        //         $x: y;
        //       }
        //     }
        //
        // doesn't assign to the global scope.
        $semiGlobal = $semiGlobal && $this->inSemiGlobalScope;
        $wasInSemiGlobalScope = $this->inSemiGlobalScope;
        $this->inSemiGlobalScope = $semiGlobal;

        if (!$when) {
            try {
                return $callback();
            } finally {
                $this->inSemiGlobalScope = $wasInSemiGlobalScope;
            }
        }

        $this->variables[] = new \ArrayObject();
        $this->variableNodes[] = new \ArrayObject();
        $this->functions[] = new \ArrayObject();
        $this->mixins[] = new \ArrayObject();

        try {
            return $callback();
        } finally {
            $this->inSemiGlobalScope = $wasInSemiGlobalScope;
            $this->lastVariableName = null;
            $this->lastVariableIndex = null;

            $removedVariables = array_pop($this->variables);
            assert($removedVariables !== null);
            foreach ($removedVariables as $name => $_) {
                unset($this->variableIndices[$name]);
            }
            array_pop($this->variableNodes);

            $removedFunctions = array_pop($this->functions);
            assert($removedFunctions !== null);
            foreach ($removedFunctions as $name => $_) {
                unset($this->functionIndices[$name]);
            }

            $removedMixins = array_pop($this->mixins);
            assert($removedMixins !== null);
            foreach ($removedMixins as $name => $_) {
                unset($this->mixinIndices[$name]);
            }
        }
    }
}
