<?php
// aws/aws-crt-php
$autoloader->register('AWS/CRT', DIR_STORAGE . 'vendor/aws/aws-crt-php/src/AWS/CRT/', true);
$autoloader->register('AWS/CRT/Auth', DIR_STORAGE . 'vendor/aws/aws-crt-php/src/AWS/CRT/Auth/', true);
$autoloader->register('AWS/CRT/HTTP', DIR_STORAGE . 'vendor/aws/aws-crt-php/src/AWS/CRT/HTTP/', true);
$autoloader->register('AWS/CRT/IO', DIR_STORAGE . 'vendor/aws/aws-crt-php/src/AWS/CRT/IO/', true);
$autoloader->register('AWS/CRT/Internal', DIR_STORAGE . 'vendor/aws/aws-crt-php/src/AWS/CRT/Internal/', true);

// aws/aws-sdk-php
$autoloader->register('Aws', DIR_STORAGE . 'vendor/aws/aws-sdk-php/src/', true);
if (is_file(DIR_STORAGE . 'vendor/aws/aws-sdk-php/src/functions.php')) {
	require_once(DIR_STORAGE . 'vendor/aws/aws-sdk-php/src/functions.php');
}

// code-lts/cli-tools
$autoloader->register('CodeLts\CliTools', DIR_STORAGE . 'vendor/code-lts/cli-tools/src/', true);

// code-lts/doctum
$autoloader->register('Doctum', DIR_STORAGE . 'vendor/code-lts/doctum/src/', true);

// doctrine/deprecations
$autoloader->register('Doctrine\Deprecations', DIR_STORAGE . 'vendor/doctrine/deprecations/lib/Doctrine/Deprecations/', true);

// erusev/parsedown
$autoloader->register('Parsedown', DIR_STORAGE . 'vendor/erusev/parsedown//', true);

// guzzlehttp/guzzle
$autoloader->register('GuzzleHttp', DIR_STORAGE . 'vendor/guzzlehttp/guzzle/src/', true);
if (is_file(DIR_STORAGE . 'vendor/guzzlehttp/guzzle/src/functions_include.php')) {
	require_once(DIR_STORAGE . 'vendor/guzzlehttp/guzzle/src/functions_include.php');
}

// guzzlehttp/promises
$autoloader->register('GuzzleHttp\Promise', DIR_STORAGE . 'vendor/guzzlehttp/promises/src/', true);

// guzzlehttp/psr7
$autoloader->register('GuzzleHttp\Psr7', DIR_STORAGE . 'vendor/guzzlehttp/psr7/src/', true);

// mtdowling/jmespath.php
$autoloader->register('JmesPath', DIR_STORAGE . 'vendor/mtdowling/jmespath.php/src/', true);
if (is_file(DIR_STORAGE . 'vendor/mtdowling/jmespath.php/src/JmesPath.php')) {
	require_once(DIR_STORAGE . 'vendor/mtdowling/jmespath.php/src/JmesPath.php');
}

// nikic/php-parser
$autoloader->register('PhpParser', DIR_STORAGE . 'vendor/nikic/php-parser/lib/PhpParser/', true);

// ondram/ci-detector
$autoloader->register('OndraM\CiDetector', DIR_STORAGE . 'vendor/ondram/ci-detector/src/', true);

// phpdocumentor/reflection-common
$autoloader->register('phpDocumentor\Reflection', DIR_STORAGE . 'vendor/phpdocumentor/reflection-common/src/', true);

// phpdocumentor/reflection-docblock
$autoloader->register('phpDocumentor\Reflection', DIR_STORAGE . 'vendor/phpdocumentor/reflection-docblock/src/', true);

// phpdocumentor/type-resolver
$autoloader->register('phpDocumentor\Reflection', DIR_STORAGE . 'vendor/phpdocumentor/type-resolver/src/', true);

// phpmyadmin/twig-i18n-extension
$autoloader->register('PhpMyAdmin\Twig\Extensions', DIR_STORAGE . 'vendor/phpmyadmin/twig-i18n-extension/src/', true);

// phpstan/phpdoc-parser
$autoloader->register('PHPStan\PhpDocParser', DIR_STORAGE . 'vendor/phpstan/phpdoc-parser/src/', true);

// psr/container
$autoloader->register('Psr\Container', DIR_STORAGE . 'vendor/psr/container/src/', true);

// psr/http-client
$autoloader->register('Psr\Http\Client', DIR_STORAGE . 'vendor/psr/http-client/src/', true);

// psr/http-factory
$autoloader->register('Psr\Http\Message', DIR_STORAGE . 'vendor/psr/http-factory/src/', true);

// psr/http-message
$autoloader->register('Psr\Http\Message', DIR_STORAGE . 'vendor/psr/http-message/src/', true);

// ralouphie/getallheaders
if (is_file(DIR_STORAGE . 'vendor/ralouphie/getallheaders/src/getallheaders.php')) {
	require_once(DIR_STORAGE . 'vendor/ralouphie/getallheaders/src/getallheaders.php');
}

// scssphp/scssphp
$autoloader->register('ScssPhp\ScssPhp', DIR_STORAGE . 'vendor/scssphp/scssphp/src/', true);

// symfony/console
$autoloader->register('Symfony\Component\Console', DIR_STORAGE . 'vendor/symfony/console//', true);

// symfony/deprecation-contracts
if (is_file(DIR_STORAGE . 'vendor/symfony/deprecation-contracts/function.php')) {
	require_once(DIR_STORAGE . 'vendor/symfony/deprecation-contracts/function.php');
}

// symfony/filesystem
$autoloader->register('Symfony\Component\Filesystem', DIR_STORAGE . 'vendor/symfony/filesystem//', true);

// symfony/finder
$autoloader->register('Symfony\Component\Finder', DIR_STORAGE . 'vendor/symfony/finder//', true);

// symfony/polyfill-ctype
$autoloader->register('Symfony\Polyfill\Ctype', DIR_STORAGE . 'vendor/symfony/polyfill-ctype//', true);
if (is_file(DIR_STORAGE . 'vendor/symfony/polyfill-ctype/bootstrap.php')) {
	require_once(DIR_STORAGE . 'vendor/symfony/polyfill-ctype/bootstrap.php');
}

// symfony/polyfill-intl-grapheme
$autoloader->register('Symfony\Polyfill\Intl\Grapheme', DIR_STORAGE . 'vendor/symfony/polyfill-intl-grapheme//', true);
if (is_file(DIR_STORAGE . 'vendor/symfony/polyfill-intl-grapheme/bootstrap.php')) {
	require_once(DIR_STORAGE . 'vendor/symfony/polyfill-intl-grapheme/bootstrap.php');
}

// symfony/polyfill-intl-normalizer
$autoloader->register('Symfony\Polyfill\Intl\Normalizer', DIR_STORAGE . 'vendor/symfony/polyfill-intl-normalizer//', true);
if (is_file(DIR_STORAGE . 'vendor/symfony/polyfill-intl-normalizer/bootstrap.php')) {
	require_once(DIR_STORAGE . 'vendor/symfony/polyfill-intl-normalizer/bootstrap.php');
}

// symfony/polyfill-mbstring
$autoloader->register('Symfony\Polyfill\Mbstring', DIR_STORAGE . 'vendor/symfony/polyfill-mbstring//', true);
if (is_file(DIR_STORAGE . 'vendor/symfony/polyfill-mbstring/bootstrap.php')) {
	require_once(DIR_STORAGE . 'vendor/symfony/polyfill-mbstring/bootstrap.php');
}

// symfony/process
$autoloader->register('Symfony\Component\Process', DIR_STORAGE . 'vendor/symfony/process//', true);

// symfony/service-contracts
$autoloader->register('Symfony\Contracts\Service', DIR_STORAGE . 'vendor/symfony/service-contracts//', true);

// symfony/string
$autoloader->register('Symfony\Component\String', DIR_STORAGE . 'vendor/symfony/string//', true);
if (is_file(DIR_STORAGE . 'vendor/symfony/string/Resources/functions.php')) {
	require_once(DIR_STORAGE . 'vendor/symfony/string/Resources/functions.php');
}

// symfony/yaml
$autoloader->register('Symfony\Component\Yaml', DIR_STORAGE . 'vendor/symfony/yaml//', true);

// twig/twig
$autoloader->register('Twig', DIR_STORAGE . 'vendor/twig/twig/src/', true);

// wdes/php-i18n-l10n
$autoloader->register('Wdes\phpI18nL10n', DIR_STORAGE . 'vendor/wdes/php-i18n-l10n/src/', true);

// webmozart/assert
$autoloader->register('Webmozart\Assert', DIR_STORAGE . 'vendor/webmozart/assert/src/', true);