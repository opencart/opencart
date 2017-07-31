#!/usr/bin/env php
<?php
error_reporting(E_ALL);

require "scss.inc.php";

$opts = getopt('hvTf:', array('help', 'version'));

function has() {
	global $opts;
	foreach (func_get_args() as $arg) {
		if (isset($opts[$arg])) return true;
	}
	return false;
}

if (has("h", "help")) {
	$exe = array_shift($argv);

$HELP = <<<EOT
Usage: $exe [options] < input-file

Options include:

	-h, --help     Show this message
	-v, --version  Print the version
	-f=format      Set the output format
	-T             Dump formatted parse tree

EOT;
	exit($HELP);
}

if (has("v", "version")) {
	exit(scssc::$VERSION . "\n");
}

$data = "";
while (!feof(STDIN)) {
	$data .= fread(STDIN, 8192);
}

if (has("T")) {
	$parser = new scss_parser("STDIN");
	print_r($parser->parse($data));
	exit();
}

$scss = new scssc();
if (has("f")) {
	$scss->setFormatter($opts["f"]);
}
echo $scss->compile($data, "STDIN");
