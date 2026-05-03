<?php
// Simulate OpenCart's session cookie setting
$option = [
    'expires'  => 0,
    'path'     => '/',
    'domain'   => '',
    'secure'   => false,
    'httponly' => true, // THE FIX
    'SameSite' => 'Lax'
];

setcookie('OCSESSID', 'test_session_id', $option);

$headers = headers_list();
foreach ($headers as $header) {
    if (strpos($header, 'Set-Cookie') !== false) {
        echo $header . PHP_EOL;
    }
}
