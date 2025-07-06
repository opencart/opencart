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

namespace ScssPhp\ScssPhp\Ast\Css;

use ScssPhp\ScssPhp\StackTrace\Trace;
use ScssPhp\ScssPhp\Value\SassString;
use ScssPhp\ScssPhp\Value\Value;
use ScssPhp\ScssPhp\Visitor\ModifiableCssVisitor;
use SourceSpan\FileSpan;

/**
 * A modifiable version of {@see CssDeclaration} for use in the evaluation step.
 *
 * @internal
 */
final class ModifiableCssDeclaration extends ModifiableCssNode implements CssDeclaration
{
    /**
     * @var CssValue<string>
     */
    private readonly CssValue $name;

    /**
     * @var CssValue<Value>
     */
    private readonly CssValue $value;

    /**
     * @var list<CssStyleRule>
     */
    private readonly array $interleavedRules;

    private readonly ?Trace $trace;

    private readonly bool $parsedAsCustomProperty;

    private readonly FileSpan $valueSpanForMap;

    private readonly FileSpan $span;

    /**
     * @param CssValue<string> $name
     * @param CssValue<Value> $value
     * @param list<CssStyleRule> $interleavedRules
     */
    public function __construct(CssValue $name, CssValue $value, FileSpan $span, bool $parsedAsCustomProperty, array $interleavedRules = [], ?Trace $trace = null, ?FileSpan $valueSpanForMap = null)
    {
        $this->name = $name;
        $this->value = $value;
        $this->parsedAsCustomProperty = $parsedAsCustomProperty;
        $this->interleavedRules = $interleavedRules;
        $this->trace = $trace;
        $this->valueSpanForMap = $valueSpanForMap ?? $value->getSpan();
        $this->span = $span;

        if ($parsedAsCustomProperty) {
            if (!$this->isCustomProperty()) {
                throw new \InvalidArgumentException('parsedAsCustomProperty must be false if name doesn\'t begin with "--".');
            }

            if (!$value->getValue() instanceof SassString) {
                throw new \InvalidArgumentException(sprintf('If parsedAsCustomProperty is true, value must contain a SassString (was %s).', get_debug_type($value->getValue())));
            }
        }
    }

    public function getName(): CssValue
    {
        return $this->name;
    }

    public function getValue(): CssValue
    {
        return $this->value;
    }

    public function getInterleavedRules(): array
    {
        return $this->interleavedRules;
    }

    public function getTrace(): ?Trace
    {
        return $this->trace;
    }

    public function isParsedAsCustomProperty(): bool
    {
        return $this->parsedAsCustomProperty;
    }

    public function getValueSpanForMap(): FileSpan
    {
        return $this->valueSpanForMap;
    }

    public function getSpan(): FileSpan
    {
        return $this->span;
    }

    public function isCustomProperty(): bool
    {
        return str_starts_with($this->name->getValue(), '--');
    }

    public function accept(ModifiableCssVisitor $visitor)
    {
        return $visitor->visitCssDeclaration($this);
    }
}
