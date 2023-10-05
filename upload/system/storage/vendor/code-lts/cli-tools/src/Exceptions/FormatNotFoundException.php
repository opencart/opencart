<?php

declare(strict_types = 1);

namespace CodeLts\CliTools\Exceptions;

use CodeLts\CliTools\OutputFormat;

class FormatNotFoundException extends \Exception
{

    public function __construct(string $formatName)
    {
        parent::__construct(
            sprintf(
                'Error formatter "%s" is not implemented. Available error formatters are: %s',
                $formatName,
                implode(', ', OutputFormat::VALID_OUTPUT_FORMATS)
            )
        );
    }

    public function getTip(): ?string
    {
        return null;
    }

}
