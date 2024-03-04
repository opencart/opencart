<?php

declare(strict_types=1);

require __DIR__ . '/../src/tracy.php';

use Tracy\Debugger;

// For security reasons, Tracy is visible only on localhost.
// You may force Tracy to run in development mode by passing the Debugger::Development instead of Debugger::Detect.
Debugger::enable(Debugger::Detect, __DIR__ . '/log');
Debugger::$strictMode = true;

?>
<!DOCTYPE html><link rel="stylesheet" href="assets/style.css">

<h1>Tracy Notice and StrictMode demo</h1>

<?php


function foo($from)
{
	echo $form;
}


foo(123);


if (Debugger::$productionMode) {
	echo '<p><b>For security reasons, Tracy is visible only on localhost. Look into the source code to see how to enable Tracy.</b></p>';
}
