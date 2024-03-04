<?php

declare(strict_types=1);

require __DIR__ . '/../src/tracy.php';

use Tracy\Debugger;

// For security reasons, Tracy is visible only on localhost.
// You may force Tracy to run in development mode by passing the Debugger::Development instead of Debugger::Detect.
Debugger::enable(Debugger::Detect, __DIR__ . '/log');

?>
<!DOCTYPE html><link rel="stylesheet" href="assets/style.css">

<h1>Tracy: exception demo</h1>

<?php

class DemoClass
{
	public function first($arg1, $arg2)
	{
		$this->second(true, false);
	}


	public function second($arg1, $arg2)
	{
		self::third([1, 2, 3]);
	}


	public static function third($arg1)
	{
		throw new Exception('The my exception', 123);
	}
}


function demo($a, $b)
{
	$demo = new DemoClass;
	$demo->first($a, $b);
}


if (Debugger::$productionMode) {
	echo '<p><b>For security reasons, Tracy is visible only on localhost. Look into the source code to see how to enable Tracy.</b></p>';
}

demo(10, 'any string');
