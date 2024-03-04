<?php

namespace JetBrains\PhpStorm;

use Attribute;

#[Attribute(Attribute::TARGET_FUNCTION|Attribute::TARGET_METHOD|Attribute::TARGET_CLASS|Attribute::TARGET_CLASS_CONSTANT|Attribute::TARGET_PROPERTY|Attribute::TARGET_PARAMETER)]
class Deprecated
{
    public const PHP_VERSIONS = [
        "5.3",
        "5.4",
        "5.5",
        "5.6",
        "7.0",
        "7.1",
        "7.2",
        "7.3",
        "7.4",
        "8.0",
        "8.1",
        "8.2"
    ];

    /**
     * Mark element as deprecated
     *
     * @param string $reason Reason for deprecation. It will be displayed by PhpStorm via the Deprecated inspection instead of the  default message
     * @param string $replacement Applicable only to function/method calls: IDE will suggest replacing a deprecated function call with the provided code template.
     * The following variables are available in this template:
     * <ul>
     * <li>%parametersList%: parameters of the function call. For example, for the "f(1,2)" call, %parametersList% will be "1,2"</li>
     * <li>%parameter0%,%parameter1%,%parameter2%,...: parameters of the function call. For example, for the "f(1,2)" call, %parameter1% will be "2"</li>
     * <li>%name%: For "\x\f(1,2)", %name% will be "\x\f", for "$this->ff()", %name% will be "ff"</li>
     * <li>%class%: If the attribute is provided for method "m", then for "$this->f()->m()", %class% will be "$this->f()"</li>
     * </ul>
     * The following example shows how to wrap a function call in another call and swap arguments:<br />
     * "#[Deprecated(replacement: "wrappedCall(%name%(%parameter1%, %parameter0%))")] f($a, $b){}<br />
     * f(1,2) will be replaced with wrappedCall(f(2,1))
     * @param string $since Element is deprecated starting with the provided PHP language level, applicable only for PhpStorm stubs entries
     */
    public function __construct(
        $reason = "",
        $replacement = "",
        #[ExpectedValues(self::PHP_VERSIONS)] $since = "5.6"
    ) {}
}
