<?php

declare(strict_types = 1);

namespace CodeLts\CliTools;

class Error
{
    /** @var string */
    protected $message;
    /** @var string|null */
    protected $file;
    /** @var int|null */
    protected $line;
    /** @var int */
    protected $severity;
    /** @var string|null */
    protected $tip = null;

    public const LEVEL_EMERGENCY = 0;
    public const LEVEL_ALERT     = 1;
    public const LEVEL_CRITICAL  = 2;
    public const LEVEL_ERROR     = 3;
    public const LEVEL_WARNING   = 4;
    public const LEVEL_NOTICE    = 5;
    public const LEVEL_INFO      = 6;
    public const LEVEL_DEBUG     = 7;

    public function __construct(
        string $message,
        ?string $file,
        ?int $line,
        int $severity = Error::LEVEL_ERROR,
        ?string $tip = null
    ) {
        $this->message  = $message;
        $this->file     = $file;
        $this->line     = $line;
        $this->severity = $severity;
        $this->tip      = $tip;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function getLine(): ?int
    {
        return $this->line;
    }

    public function canBeIgnored(): bool
    {
        return $this->severity < Error::LEVEL_WARNING;
    }

    public function getTip(): ?string
    {
        return $this->tip;
    }

}
