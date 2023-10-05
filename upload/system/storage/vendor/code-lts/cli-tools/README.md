# CLI tools

[![codecov](https://codecov.io/gh/code-lts/cli-tools/branch/main/graph/badge.svg?branch=main)](https://codecov.io/gh/code-lts/cli-tools?branch=main)
[![Version](https://poser.pugx.org/code-lts/cli-tools/version)](//packagist.org/packages/code-lts/cli-tools)
[![License](https://poser.pugx.org/code-lts/cli-tools/license)](//packagist.org/packages/code-lts/cli-tools)

CLI tools to manage output errors formatting for junit, checkstyle, teamcity, gitlab and github error formats.

## Motivation

Have formatters that I can re-use in projects to format errors.
Some code is copied from phpstan. See [#4122](https://github.com/phpstan/phpstan/issues/4122)

## Example usages

### Format errors

```php
use CodeLts\CliTools\Error;
use CodeLts\CliTools\AnalysisResult;
use CodeLts\CliTools\OutputFormat;
use CodeLts\CliTools\Exceptions\FormatNotFoundException;

// if the value is from an user input
// Will throw FormatNotFoundException
// See valid formats at OutputFormat::VALID_OUTPUT_FORMATS
OutputFormat::checkOutputFormatIsValid('notValidFormat');

$fileSpecificErrors = [
    new Error(
        'My error message',
        '/file/path/to/File.php',
        15 // Line number
    ),
];

$notFileSpecificErrors = [
    'Just an error message',
];

$internalErrors = [
    'Oops the internal engine did crash !',
];

$warnings = [
    'I warn you that most of this code is tested',
    'Contributions are very welcome',
];

$analysisResult = new AnalysisResult(
    $fileSpecificErrors,
    $notFileSpecificErrors,
    $internalErrors,
    $warnings
);

OutputFormat::displayUserChoiceFormat(
    OutputFormat::OUTPUT_FORMAT_TABLE,// The phpstan table error format, you can have a lot more formats in OutputFormat::VALID_OUTPUT_FORMATS
    $analysisResult,
    $this->sourceRootDirectory,// can be null, if null the error paths will not be pretty and will be displayed as given
    $this->output // Implement CodeLts\CliTools\Output or use CodeLts\CliTools\Symfony\SymfonyOutput
);
```

### Read/Write files

```php
use CodeLts\CliTools\File\FileReader;
use CodeLts\CliTools\File\FileWriter;

$fileName = 'myFile.txt';

FileWriter::write(
    $fileName,
    'Some data, blob, blob, blob.'
);

FileReader::read($fileName);// -> Some data, blob, blob, blob.
```

### ANSI codes

```php
use CodeLts\CliTools\File\AnsiEscapeSequences;

$this->output->writeFormatted(AnsiEscapeSequences::ERASE_TO_LINE_END);
```

### Detect a CI

```php
use CodeLts\CliTools\Utils;

// See supported CIs at https://github.com/OndraM/ci-detector#supported-continuous-integration-servers

Utils::isCiDetected(); // true of false
```
