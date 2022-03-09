#!/usr/bin/env php
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

error_reporting(E_ALL);

if (version_compare(PHP_VERSION, '5.6') < 0) {
    die('Requires PHP 5.6 or above');
}

include __DIR__ . '/../scss.inc.php';

use ScssPhp\ScssPhp\Compiler;
use ScssPhp\ScssPhp\Parser;
use ScssPhp\ScssPhp\Version;

$style = null;
$loadPaths = null;
$precision = null;
$dumpTree = false;
$inputFile = null;
$changeDir = false;
$debugInfo = false;
$lineNumbers = false;
$ignoreErrors = false;
$encoding = false;
$sourceMap = false;

/**
 * Parse argument
 *
 * @param integer $i
 * @param array $options
 *
 * @return string|null
 */
function parseArgument(&$i, $options) {
    global $argc;
    global $argv;

    if (! preg_match('/^(?:' . implode('|', (array) $options) . ')=?(.*)/', $argv[$i], $matches)) {
        return;
    }

    if (strlen($matches[1])) {
        return $matches[1];
    }

    if ($i + 1 < $argc) {
        $i++;

        return $argv[$i];
    }
}

for ($i = 1; $i < $argc; $i++) {
    if ($argv[$i] === '-?' || $argv[$i] === '-h' || $argv[$i] === '--help') {
        $exe = $argv[0];

        $HELP = <<<EOT
Usage: $exe [options] [input-file]

Options include:

    --help              Show this message [-h, -?]
    --continue-on-error Continue compilation (as best as possible) when error encountered
    --debug-info        Annotate selectors with CSS referring to the source file and line number [-g]
    --dump-tree         Dump formatted parse tree [-T]
    --iso8859-1         Use iso8859-1 encoding instead of default utf-8
    --line-numbers      Annotate selectors with comments referring to the source file and line number [--line-comments]
    --load-path=PATH    Set import path [-I]
    --precision=N       Set decimal number precision (default 10) [-p]
    --sourcemap         Create source map file
    --style=FORMAT      Set the output format (compact, compressed, crunched, expanded, or nested) [-s, -t]
    --version           Print the version [-v]

EOT;
        exit($HELP);
    }

    if ($argv[$i] === '-v' || $argv[$i] === '--version') {
        exit(Version::VERSION . "\n");
    }

    if ($argv[$i] === '--continue-on-error') {
        $ignoreErrors = true;
        continue;
    }

    if ($argv[$i] === '-g' || $argv[$i] === '--debug-info') {
        $debugInfo = true;
        continue;
    }

    if ($argv[$i] === '--iso8859-1') {
        $encoding = 'iso8859-1';
        continue;
    }

    if ($argv[$i] === '--line-numbers' || $argv[$i] === '--line-comments') {
        $lineNumbers = true;
        continue;
    }

    if ($argv[$i] === '--sourcemap') {
        $sourceMap = true;
        continue;
    }

    if ($argv[$i] === '-T' || $argv[$i] === '--dump-tree') {
        $dumpTree = true;
        continue;
    }

    $value = parseArgument($i, array('-t', '-s', '--style'));

    if (isset($value)) {
        $style = $value;
        continue;
    }

    $value = parseArgument($i, array('-I', '--load-path'));

    if (isset($value)) {
        $loadPaths = $value;
        continue;
    }

    $value = parseArgument($i, array('-p', '--precision'));

    if (isset($value)) {
        $precision = $value;
        continue;
    }

    if (file_exists($argv[$i])) {
        $inputFile = $argv[$i];
        continue;
    }
}


if ($inputFile) {
    $data = file_get_contents($inputFile);

    $newWorkingDir = dirname(realpath($inputFile));
    $oldWorkingDir = getcwd();

    if ($oldWorkingDir !== $newWorkingDir) {
        $changeDir = chdir($newWorkingDir);
        $inputFile = basename($inputFile);
    }
} else {
    $data = '';

    while (! feof(STDIN)) {
        $data .= fread(STDIN, 8192);
    }
}

if ($dumpTree) {
    $parser = new Parser($inputFile);

    print_r(json_decode(json_encode($parser->parse($data)), true));

    exit();
}

$scss = new Compiler();

if ($debugInfo) {
    $scss->setLineNumberStyle(Compiler::DEBUG_INFO);
}

if ($lineNumbers) {
    $scss->setLineNumberStyle(Compiler::LINE_COMMENTS);
}

if ($ignoreErrors) {
    $scss->setIgnoreErrors($ignoreErrors);
}

if ($loadPaths) {
    $scss->setImportPaths(explode(PATH_SEPARATOR, $loadPaths));
}

if ($precision) {
    $scss->setNumberPrecision($precision);
}

if ($style) {
    $scss->setFormatter('ScssPhp\\ScssPhp\\Formatter\\' . ucfirst($style));
}

if ($sourceMap) {
    $scss->setSourceMap(Compiler::SOURCE_MAP_INLINE);
}

if ($encoding) {
    $scss->setEncoding($encoding);
}

echo $scss->compile($data, $inputFile);

if ($changeDir) {
    chdir($oldWorkingDir);
}
