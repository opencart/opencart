<?php

declare(strict_types=1);

namespace libphonenumber;

use libphonenumber\Leniency\Possible;
use libphonenumber\Leniency\StrictGrouping;
use libphonenumber\Leniency\Valid;
use libphonenumber\Leniency\ExactGrouping;

class Leniency
{
    public static function POSSIBLE(): Possible
    {
        return new Possible();
    }

    public static function VALID(): Valid
    {
        return new Valid();
    }

    public static function STRICT_GROUPING(): StrictGrouping
    {
        return new StrictGrouping();
    }

    public static function EXACT_GROUPING(): ExactGrouping
    {
        return new ExactGrouping();
    }
}
