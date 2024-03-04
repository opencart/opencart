<?php

namespace StubTests;

use StubTests\Model\PHPFunction;

class StubsCompositeMixedReturnTypeTest extends AbstractBaseStubsTestCase
{
    /**
     * @dataProvider \StubTests\TestData\Providers\Stubs\StubsCompositeMixedProvider::expectedFunctionsMixedFalseReturnProvider
     */
    public function testPhpDocContainsMixedFalseReturnType(PHPFunction $function)
    {
        $returnTypesFromPhpDoc = $function->returnTypesFromPhpDoc;
        self::assertContains('false', $returnTypesFromPhpDoc, "Return type of " . $function->name .
            " should contain 'false' in PhpDoc. See https://youtrack.jetbrains.com/issue/WI-57991 for details");
        self::assertContains('mixed', $returnTypesFromPhpDoc, "Return type of " . $function->name .
            " should contain 'mixed' in PhpDoc. See https://youtrack.jetbrains.com/issue/WI-57991 for details");
    }

    /**
     * @dataProvider \StubTests\TestData\Providers\Stubs\StubsCompositeMixedProvider::expectedFunctionsMixedNullReturnProvider
     */
    public function testPhpDocContainsMixedNullReturnType(PHPFunction $function)
    {
        $returnTypesFromPhpDoc = $function->returnTypesFromPhpDoc;
        self::assertContains('mixed', $returnTypesFromPhpDoc, "Return type of " . $function->name .
            " should contain 'mixed' in PhpDoc. See https://youtrack.jetbrains.com/issue/WI-57991 for details");
        self::assertContains('null', $returnTypesFromPhpDoc, "Return type of " . $function->name .
            " should contain 'null' in PhpDoc. See https://youtrack.jetbrains.com/issue/WI-57991 for details");
    }
}
