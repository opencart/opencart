<?php

/**
 * Nette Tester.
 * Copyright (c) 2009 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

require __DIR__ . '/Runner/exceptions.php';
require __DIR__ . '/Runner/Test.php';
require __DIR__ . '/Runner/PhpInterpreter.php';
require __DIR__ . '/Runner/Runner.php';
require __DIR__ . '/Runner/CliTester.php';
require __DIR__ . '/Runner/Job.php';
require __DIR__ . '/Runner/CommandLine.php';
require __DIR__ . '/Runner/TestHandler.php';
require __DIR__ . '/Runner/OutputHandler.php';
require __DIR__ . '/Runner/Output/Logger.php';
require __DIR__ . '/Runner/Output/TapPrinter.php';
require __DIR__ . '/Runner/Output/ConsolePrinter.php';
require __DIR__ . '/Runner/Output/JUnitPrinter.php';
require __DIR__ . '/Framework/Helpers.php';
require __DIR__ . '/Framework/Environment.php';
require __DIR__ . '/Framework/Assert.php';
require __DIR__ . '/Framework/AssertException.php';
require __DIR__ . '/Framework/Dumper.php';
require __DIR__ . '/Framework/DataProvider.php';
require __DIR__ . '/Framework/TestCase.php';
require __DIR__ . '/CodeCoverage/Collector.php';
require __DIR__ . '/CodeCoverage/PhpParser.php';
require __DIR__ . '/CodeCoverage/Generators/AbstractGenerator.php';
require __DIR__ . '/CodeCoverage/Generators/HtmlGenerator.php';
require __DIR__ . '/CodeCoverage/Generators/CloverXMLGenerator.php';


die((new Tester\Runner\CliTester)->run());
