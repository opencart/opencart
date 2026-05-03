<?php
$option = [
    'expires'  => 0,
    'path'     => '/',
    'domain'   => '',
    'secure'   => false,
    'httponly' => false,
    'SameSite' => 'Lax'
];

setcookie('test_cookie', 'test_value', $option);
print_r(headers_list());
