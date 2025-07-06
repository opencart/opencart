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

namespace ScssPhp\ScssPhp\Exception;

use ScssPhp\ScssPhp\StackTrace\Trace;
use ScssPhp\ScssPhp\Util\ErrorUtil;
use SourceSpan\FileSpan;

/**
 * @internal
 */
final class SimpleSassRuntimeException extends \Exception implements SassRuntimeException
{
    /**
     * @var string
     * @readonly
     */
    private $originalMessage;

    /**
     * @var FileSpan
     * @readonly
     */
    private $span;

    private readonly Trace $sassTrace;

    public function __construct(string $message, FileSpan $span, Trace $sassTrace, ?\Throwable $previous = null)
    {
        $this->originalMessage = $message;
        $this->span = $span;
        $this->sassTrace = $sassTrace;

        parent::__construct(ErrorUtil::formatErrorMessage($message, $span, $this->sassTrace), 0, $previous);
    }

    /**
     * Gets the original message without the location info in it.
     */
    public function getOriginalMessage(): string
    {
        return $this->originalMessage;
    }

    public function getSpan(): FileSpan
    {
        return $this->span;
    }

    public function getSassTrace(): Trace
    {
        return $this->sassTrace;
    }

    public function withAdditionalSpan(FileSpan $span, string $label, ?\Throwable $previous = null): MultiSpanSassRuntimeException
    {
        return new MultiSpanSassRuntimeException($this->originalMessage, $this->span, '', [$label => $span], $this->sassTrace, $previous);
    }

    public function withTrace(Trace $trace, ?\Throwable $previous = null): SassRuntimeException
    {
        return new SimpleSassRuntimeException($this->originalMessage, $this->span, $trace, $previous);
    }
}
