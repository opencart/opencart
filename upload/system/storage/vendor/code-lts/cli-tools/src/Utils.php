<?php

declare(strict_types = 1);

namespace CodeLts\CliTools;

use OndraM\CiDetector\CiDetector;

/**
 * Some utilities
 */
class Utils
{

    public static function isCiDetected(): bool
    {
        $ciDetector = new CiDetector();
        return $ciDetector->isCiDetected();
    }

}
