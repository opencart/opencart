<?php // phpcs:disable Generic.Commenting PEAR.Commenting

spl_autoload_register(function ($className) {
    if (strpos($className, 'Braintree') !== 0) {
        return;
    }

    $fileName = dirname(__DIR__) . '/lib/';

    if ($lastNsPos = strripos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName  .= str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }

    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

    if (is_file($fileName)) {
        require_once $fileName;
    }
});
// phpcs:enable Generic.Commenting PEAR.Commenting
