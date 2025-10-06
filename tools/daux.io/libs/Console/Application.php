<?php namespace Todaymade\Daux\Console;

use Symfony\Component\Console\Application as SymfonyApplication;

class Application extends SymfonyApplication
{
    public function __construct($name = 'UNKNOWN', $version = 'UNKNOWN')
    {
        parent::__construct($name, $version);

        $this->add(new Generate());
        $this->add(new Serve());
        $this->add(new ClearCache());

        $appName = 'daux/daux.io';

        $up = '..' . DIRECTORY_SEPARATOR;
        $composer = __DIR__ . DIRECTORY_SEPARATOR . $up . $up . $up . $up . $up . 'composer.lock';
        $version = 'unknown-version';

        if (file_exists($composer)) {
            $app = json_decode(file_get_contents($composer));
            $packages = $app->packages;

            foreach ($packages as $package) {
                if ($package->name == $appName) {
                    $version = $package->version;
                }
            }
        }

        // When running inside a Docker image
        // Daux isn't installed through composer
        // In exchange the version is set as an env variable
        if (array_key_exists('DAUX_VERSION', $_ENV)) {
            $version = $_ENV['DAUX_VERSION'];
        }

        $this->setVersion($version);
        $this->setName($appName);
    }
}
