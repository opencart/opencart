<?php

declare(strict_types = 1);

namespace CodeLts\CliTools;

use CodeLts\CliTools\ErrorFormatter\CheckstyleErrorFormatter;
use CodeLts\CliTools\ErrorFormatter\ErrorFormatter;
use CodeLts\CliTools\ErrorFormatter\GithubErrorFormatter;
use CodeLts\CliTools\ErrorFormatter\GitlabErrorFormatter;
use CodeLts\CliTools\ErrorFormatter\JsonErrorFormatter;
use CodeLts\CliTools\ErrorFormatter\JunitErrorFormatter;
use CodeLts\CliTools\ErrorFormatter\RawErrorFormatter;
use CodeLts\CliTools\ErrorFormatter\RawTextErrorFormatter;
use CodeLts\CliTools\ErrorFormatter\TableErrorFormatter;
use CodeLts\CliTools\ErrorFormatter\TeamcityErrorFormatter;
use CodeLts\CliTools\Exceptions\FormatNotFoundException;
use CodeLts\CliTools\File\FuzzyRelativePathHelper;
use CodeLts\CliTools\File\NullRelativePathHelper;
use CodeLts\CliTools\File\RelativePathHelper;
use CodeLts\CliTools\File\SimpleRelativePathHelper;

class OutputFormat
{
    public const OUTPUT_FORMAT_RAW         = 'raw';
    public const OUTPUT_FORMAT_RAW_TEXT    = 'rawtext';
    public const OUTPUT_FORMAT_JSON        = 'json';
    public const OUTPUT_FORMAT_JSON_PRETTY = 'prettyJson';
    public const OUTPUT_FORMAT_JUNIT       = 'junit';
    public const OUTPUT_FORMAT_TABLE       = 'table';
    public const OUTPUT_FORMAT_CHECKSTYLE  = 'checkstyle';
    public const OUTPUT_FORMAT_GITLAB      = 'gitlab';
    public const OUTPUT_FORMAT_GITHUB      = 'github';
    public const OUTPUT_FORMAT_TEAMCITY    = 'teamcity';

    public const VALID_OUTPUT_FORMATS = [
        OutputFormat::OUTPUT_FORMAT_RAW,
        OutputFormat::OUTPUT_FORMAT_RAW_TEXT,
        OutputFormat::OUTPUT_FORMAT_TABLE,
        OutputFormat::OUTPUT_FORMAT_CHECKSTYLE,
        OutputFormat::OUTPUT_FORMAT_JSON,
        OutputFormat::OUTPUT_FORMAT_JUNIT,
        OutputFormat::OUTPUT_FORMAT_JSON_PRETTY,
        OutputFormat::OUTPUT_FORMAT_GITLAB,
        OutputFormat::OUTPUT_FORMAT_GITHUB,
        OutputFormat::OUTPUT_FORMAT_TEAMCITY,
    ];

    /**
     * Checks that the format is valid
     *
     * @param string $outputFormat The format to check
     * @return true
     *
     * @throws FormatNotFoundException if the format does not exist
     */
    public static function checkOutputFormatIsValid(string $outputFormat): bool
    {
        if (in_array($outputFormat, OutputFormat::VALID_OUTPUT_FORMATS)) {
            return true;
        }
        throw new FormatNotFoundException($outputFormat);
    }

    /**
     * Display to the output the AnalysisResult for the format
     */
    public static function displayUserChoiceFormat(
        string $outputFormat,
        AnalysisResult $analysisResult,
        ?string $sourceRootDirectory,
        Output $output
    ): void {
        $pathHelper = new NullRelativePathHelper();
        if ($sourceRootDirectory !== null) {
            $pathHelper = new FuzzyRelativePathHelper(
                new SimpleRelativePathHelper(
                    $sourceRootDirectory
                ),
                $sourceRootDirectory
            );
        }

        $formatter = OutputFormat::getFormatterForChoice($outputFormat, $pathHelper);
        $formatter->formatErrors(
            $analysisResult,
            $output
        );
    }

    /**
     * get an ErrorFormatter for the given format
     *
     * @throws FormatNotFoundException if the format is not implemented
     */
    public static function getFormatterForChoice(
        string $outputFormat,
        RelativePathHelper $pathHelper
    ): ErrorFormatter {
        if ($outputFormat === OutputFormat::OUTPUT_FORMAT_RAW) {
            return new RawErrorFormatter();
        }

        if ($outputFormat === OutputFormat::OUTPUT_FORMAT_RAW_TEXT) {
            return new RawTextErrorFormatter();
        }

        if ($outputFormat === OutputFormat::OUTPUT_FORMAT_JSON) {
            return new JsonErrorFormatter(false);
        }

        if ($outputFormat === OutputFormat::OUTPUT_FORMAT_JSON_PRETTY) {
            return new JsonErrorFormatter(true);
        }

        if ($outputFormat === OutputFormat::OUTPUT_FORMAT_JUNIT) {
            return new JunitErrorFormatter($pathHelper);
        }

        if ($outputFormat === OutputFormat::OUTPUT_FORMAT_TABLE) {
            return new TableErrorFormatter($pathHelper);
        }

        if ($outputFormat === OutputFormat::OUTPUT_FORMAT_CHECKSTYLE) {
            return new CheckstyleErrorFormatter($pathHelper);
        }

        if ($outputFormat === OutputFormat::OUTPUT_FORMAT_GITLAB) {
            return new GitlabErrorFormatter($pathHelper);
        }

        if ($outputFormat === OutputFormat::OUTPUT_FORMAT_GITHUB) {
            return new GithubErrorFormatter($pathHelper, new TableErrorFormatter($pathHelper));
        }

        if ($outputFormat === OutputFormat::OUTPUT_FORMAT_TEAMCITY) {
            return new TeamcityErrorFormatter($pathHelper);
        }

        throw new FormatNotFoundException($outputFormat);
    }

}
