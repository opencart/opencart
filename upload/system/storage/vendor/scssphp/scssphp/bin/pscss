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
use ScssPhp\ScssPhp\Exception\SassException;
use ScssPhp\ScssPhp\OutputStyle;
use ScssPhp\ScssPhp\Parser;
use ScssPhp\ScssPhp\Version;

$style = null;
$loadPaths = null;
$dumpTree = false;
$inputFile = null;
$changeDir = false;
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
    --continue-on-error [deprecated] Ignored
    --debug-info        [deprecated] Ignored [-g]
    --dump-tree         Dump formatted parse tree [-T]
    --iso8859-1         Use iso8859-1 encoding instead of default utf-8
    --line-numbers      [deprecated] Ignored [--line-comments]
    --load-path=PATH    Set import path [-I]
    --precision=N       [deprecated] Ignored. (default 10) [-p]
    --sourcemap         Create source map file
    --style=FORMAT      Set the output style (compressed or expanded) [-s, -t]
    --version           Print the version [-v]

EOT;
        exit($HELP);
    }

    if ($argv[$i] === '-v' || $argv[$i] === '--version') {
        exit(Version::VERSION . "\n");
    }

    // Keep parsing --continue-on-error to avoid BC breaks for scripts using it
    if ($argv[$i] === '--continue-on-error') {
        // TODO report it as a warning ?
        continue;
    }

    // Keep parsing it to avoid BC breaks for scripts using it
    if ($argv[$i] === '-g' || $argv[$i] === '--debug-info') {
        // TODO report it as a warning ?
        continue;
    }

    if ($argv[$i] === '--iso8859-1') {
        $encoding = 'iso8859-1';
        continue;
    }

    // Keep parsing it to avoid BC breaks for scripts using it
    if ($argv[$i] === '--line-numbers' || $argv[$i] === '--line-comments') {
        // TODO report it as a warning ?
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

    // Keep parsing --precision to avoid BC breaks for scripts using it
    $value = parseArgument($i, array('-p', '--precision'));

    if (isset($value)) {
        // TODO report it as a warning ?
        continue;
    }

    if (file_exists($argv[$i])) {
        $inputFile = $argv[$i];
        continue;
    }
}


if ($inputFile) {
    $data = file_get_contents($inputFile);
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

if ($loadPaths) {
    $scss->setImportPaths(explode(PATH_SEPARATOR, $loadPaths));
}

if ($style) {
    if ($style === OutputStyle::COMPRESSED || $style === OutputStyle::EXPANDED) {
        $scss->setOutputStyle($style);
    } else {
        fwrite(STDERR, "WARNING: the $style style is deprecated.\n");
        $scss->setFormatter('ScssPhp\\ScssPhp\\Formatter\\' . ucfirst($style));
    }
}

if ($sourceMap) {
    $scss->setSourceMap(Compiler::SOURCE_MAP_INLINE);
}

if ($encoding) {
    $scss->setEncoding($encoding);
}

try {
    echo $scss->compile($data, $inputFile);
} catch (SassException $e) {
    fwrite(STDERR, $e->getMessage()."\n");
    exit(1);
}
