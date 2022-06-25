<?php
// aws/aws-crt-php
$autoloader->register('AWS/CRT', DIR_STORAGE . 'vendor/aws/aws-crt-php/src/AWS/CRT', true);
$autoloader->register('AWS/CRT/Auth', DIR_STORAGE . 'vendor/aws/aws-crt-php/src/AWS/CRT/Auth', true);
$autoloader->register('AWS/CRT/HTTP', DIR_STORAGE . 'vendor/aws/aws-crt-php/src/AWS/CRT/HTTP', true);
$autoloader->register('AWS/CRT/IO', DIR_STORAGE . 'vendor/aws/aws-crt-php/src/AWS/CRT/IO', true);
$autoloader->register('AWS/CRT/Internal', DIR_STORAGE . 'vendor/aws/aws-crt-php/src/AWS/CRT/Internal', true);

// aws/aws-sdk-php
$autoloader->register('Aws', DIR_STORAGE . 'vendor/aws/aws-sdk-php/src/', true);
require_once(DIR_STORAGE . 'vendor/aws/aws-sdk-php/src/functions.php');

// guzzlehttp/guzzle
$autoloader->register('GuzzleHttp', DIR_STORAGE . 'vendor/guzzlehttp/guzzle/src/', true);
require_once(DIR_STORAGE . 'vendor/guzzlehttp/guzzle/src/functions_include.php');

// guzzlehttp/promises
$autoloader->register('GuzzleHttp\Promise', DIR_STORAGE . 'vendor/guzzlehttp/promises/src/', true);
require_once(DIR_STORAGE . 'vendor/guzzlehttp/promises/src/functions_include.php');

// guzzlehttp/psr7
$autoloader->register('GuzzleHttp\Psr7', DIR_STORAGE . 'vendor/guzzlehttp/psr7/src/', true);

// mtdowling/jmespath.php
$autoloader->register('JmesPath', DIR_STORAGE . 'vendor/mtdowling/jmespath.php/src/', true);
require_once(DIR_STORAGE . 'vendor/mtdowling/jmespath.php/src/JmesPath.php');

// psr/http-client
$autoloader->register('Psr\Http\Client', DIR_STORAGE . 'vendor/psr/http-client/src/', true);

// psr/http-factory
$autoloader->register('Psr\Http\Message', DIR_STORAGE . 'vendor/psr/http-factory/src/', true);

// psr/http-message
$autoloader->register('Psr\Http\Message', DIR_STORAGE . 'vendor/psr/http-message/src/', true);

// ralouphie/getallheaders
require_once(DIR_STORAGE . 'vendor/ralouphie/getallheaders/src/getallheaders.php');

// scssphp/scssphp
$autoloader->register('ScssPhp\ScssPhp', DIR_STORAGE . 'vendor/scssphp/scssphp/src/', true);

// symfony/deprecation-contracts
require_once(DIR_STORAGE . 'vendor/symfony/deprecation-contracts/function.php');

// symfony/polyfill-ctype
$autoloader->register('Symfony\Polyfill\Ctype', DIR_STORAGE . 'vendor/symfony/polyfill-ctype/', true);
require_once(DIR_STORAGE . 'vendor/symfony/polyfill-ctype/bootstrap.php');

// symfony/polyfill-mbstring
$autoloader->register('Symfony\Polyfill\Mbstring', DIR_STORAGE . 'vendor/symfony/polyfill-mbstring/', true);
require_once(DIR_STORAGE . 'vendor/symfony/polyfill-mbstring/bootstrap.php');

// twig/twig
$autoloader->register('Twig', DIR_STORAGE . 'vendor/twig/twig/src/', true);