<?php
// scssphp/scssphp
$autoloader->register('ScssPhp\ScssPhp', DIR_STORAGE . 'vendor/scssphp/scssphp/src/', true);

// symfony/deprecation-contracts
if (is_file(DIR_STORAGE . 'vendor/symfony/deprecation-contracts/function.php')) {
	require_once(DIR_STORAGE . 'vendor/symfony/deprecation-contracts/function.php');
}

// symfony/polyfill-ctype
$autoloader->register('Symfony\Polyfill\Ctype', DIR_STORAGE . 'vendor/symfony/polyfill-ctype//', true);
if (is_file(DIR_STORAGE . 'vendor/symfony/polyfill-ctype/bootstrap.php')) {
	require_once(DIR_STORAGE . 'vendor/symfony/polyfill-ctype/bootstrap.php');
}

// symfony/polyfill-mbstring
$autoloader->register('Symfony\Polyfill\Mbstring', DIR_STORAGE . 'vendor/symfony/polyfill-mbstring//', true);
if (is_file(DIR_STORAGE . 'vendor/symfony/polyfill-mbstring/bootstrap.php')) {
	require_once(DIR_STORAGE . 'vendor/symfony/polyfill-mbstring/bootstrap.php');
}

// twig/twig
$autoloader->register('Twig', DIR_STORAGE . 'vendor/twig/twig/src/', true);
if (is_file(DIR_STORAGE . 'vendor/twig/twig/src/Resources/core.php')) {
	require_once(DIR_STORAGE . 'vendor/twig/twig/src/Resources/core.php');
}
if (is_file(DIR_STORAGE . 'vendor/twig/twig/src/Resources/debug.php')) {
	require_once(DIR_STORAGE . 'vendor/twig/twig/src/Resources/debug.php');
}
if (is_file(DIR_STORAGE . 'vendor/twig/twig/src/Resources/escaper.php')) {
	require_once(DIR_STORAGE . 'vendor/twig/twig/src/Resources/escaper.php');
}
if (is_file(DIR_STORAGE . 'vendor/twig/twig/src/Resources/string_loader.php')) {
	require_once(DIR_STORAGE . 'vendor/twig/twig/src/Resources/string_loader.php');
}