<?php

declare(strict_types = 1);

namespace CodeLts\CliTools\Tests;

use CodeLts\CliTools\ErrorFormatter\ErrorFormatter;
use CodeLts\CliTools\Exceptions\FormatNotFoundException;
use CodeLts\CliTools\File\NullRelativePathHelper;
use CodeLts\CliTools\OutputFormat;

class OutputFormatTest extends ErrorFormatterTestCase
{
    /**
     * @var string
     */
    private $availablesFormats = 'raw, rawtext, table, checkstyle, json, junit, prettyJson, gitlab, github, teamcity';

    /**
     * @return array[]
     */
    public function dataProviderFormatsNames(): array
    {
        $formats = [];
        foreach (OutputFormat::VALID_OUTPUT_FORMATS as $format) {
            $formats[] = [$format];
        }
        return $formats;
    }

    /**
     * @dataProvider dataProviderFormatsNames
     */
    public function testValidFormats(string $formatName): void
    {
        $this->assertTrue(OutputFormat::checkOutputFormatIsValid($formatName));
    }

    /**
     * @dataProvider dataProviderFormatsNames
     */
    public function testInValidFormats(string $formatName): void
    {
        $formatName = 'foo' . $formatName;
        $this->expectException(FormatNotFoundException::class);
        $this->expectExceptionMessage(
            'Error formatter "' . $formatName . '" is not implemented. Available error formatters are: '
            . $this->availablesFormats
        );
        $this->assertTrue(OutputFormat::checkOutputFormatIsValid($formatName));
    }

    /**
     * @dataProvider dataProviderFormatsNames
     */
    public function testGetFormatterForChoice(string $formatName): void
    {
        $this->assertInstanceOf(ErrorFormatter::class, OutputFormat::getFormatterForChoice($formatName, new NullRelativePathHelper()));
    }

    /**
     * @dataProvider dataProviderFormatsNames
     */
    public function testGetFormatterForChoiceInvalid(string $formatName): void
    {
        $formatName = 'foo' . $formatName;
        $this->expectException(FormatNotFoundException::class);
        $this->expectExceptionMessage(
            'Error formatter "' . $formatName . '" is not implemented. Available error formatters are: ' . $this->availablesFormats
        );
        OutputFormat::getFormatterForChoice($formatName, new NullRelativePathHelper());
    }

    /**
     * @dataProvider dataProviderFormatsNames
     */
    public function testFormatterForChoice(string $formatName): void
    {
        OutputFormat::displayUserChoiceFormat(
            $formatName,
            $this->getAnalysisResult(3, 2, 2),
            null,
            $this->getOutput()
        );
        $outputContent = $this->getOutputContent();
        $this->assertNotEmpty($outputContent);
    }

}
