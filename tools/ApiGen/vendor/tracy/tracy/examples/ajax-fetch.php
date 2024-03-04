<?php

declare(strict_types=1);

require __DIR__ . '/../src/tracy.php';

use Tracy\Debugger;

// For security reasons, Tracy is visible only on localhost.
// You may force Tracy to run in development mode by passing the Debugger::Development instead of Debugger::Detect.
Debugger::enable(Debugger::Detect, __DIR__ . '/log');


if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) { // AJAX request
	bdump('AJAX request ' . date('H:i:s'));
	if (!empty($_GET['error'])) {
		this_is_fatal_error();
	}

	$data = [rand(), rand(), rand()];
	header('Content-Type: application/json');
	header('Cache-Control: no-cache');
	echo json_encode($data);
	exit;
}

bdump('classic request ' . date('H:i:s'));

?>
<!DOCTYPE html><html class=arrow><link rel="stylesheet" href="assets/style.css">

<h1>Tracy: AJAX demo using fetch()</h1>

<p>
	<button>AJAX request</button> <span id=result>see Debug Bar in the bottom right corner</span>
</p>

<p>
	<button class=error>Request with error</button> use ESC to toggle BlueScreen
</p>


<script>

// default settings:
// window.TracyAutoRefresh = true;
// window.TracyMaxAjaxRows = 3;

var result = document.getElementById('result');

document.querySelectorAll('button').forEach((button) => {
	button.addEventListener('click', () => {
		result.innerText = 'loading…';

		fetch(
			button.classList.contains('error') ? '?error=1' : '?',
			{
				method: 'GET',
				headers: {
					'Content-Type': 'application/json',
					'X-Requested-With': 'XMLHttpRequest',
					//'X-Tracy-Ajax': Tracy.getAjaxHeader(), // use when auto-refresh is disabled via window.TracyAutoRefresh = false;
				}
			}
		)
		.then( response => response.json() )
		.then( json => result.innerText = 'loaded: ' + json )
		.catch( e => result.innerText = 'error' )
	});
});

</script>


<?php

if (Debugger::$productionMode) {
	echo '<p><b>For security reasons, Tracy is visible only on localhost. Look into the source code to see how to enable Tracy.</b></p>';
}
