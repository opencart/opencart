<?php

namespace App\Validation;

use Illuminate\Validation\Factory;
use Illuminate\Validation\DatabasePresenceVerifier;
use Illuminate\Container\Container;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Translation\TranslatorInterface;
use Illuminate\Translation\Translator;
use Illuminate\Translation\FileLoader;
use Illuminate\Support\Fluent;
use Illuminate\Database\ConnectionResolver;
use Illuminate\Database\ConnectionResolverInterface;
use Illuminate\Database\Connectors\ConnectionFactory;

class ValidatorManager
{
    /**
     * The current globally used instance.
     *
     * @var \Services\Validation\Capsule\Manager
     */
    protected static $instance;

    /**
     * The validation factory instance.
     *
     * @var \Illuminate\Validation\Factory
     */
    protected $validator;

    /**
     * The Translator implementation.
     *
     * @var \Symfony\Component\Translation\TranslatorInterface
     */
    protected $translator;

    /**
     * The IoC container instance.
     *
     * @var \Illuminate\Container\Container
     */
    protected $container;

    /**
     * Create a new validation capsule manager.
     *
     * @param string $fallbackLocale
     * @param string $path
     * @param \Illuminate\Container\Container $container
     */
    public function __construct($fallbackLocale = null, $path = null, Container $container = null)
    {
        $this->setupContainer($container);

        $this->setupTranslator($fallbackLocale, $path);

        $this->setupValidator();
    }

    /**
     * Setup the IoC container instance.
     *
     * @param \Illuminate\Container\Container $container
     * @return void
     */
    protected function setupContainer($container)
    {
        $this->container = $container ?: new Container;

        $this->container->instance('config', new Fluent);
    }

    /**
     * Setup the translator instance.
     * 
     * @param string $fallbackLocale
     * @param string $path
     * @return void
     */
    protected function setupTranslator($fallbackLocale, $path)
    {
        $file = new Filesystem;
        $loader = new FileLoader($file, $path);
        $trans = new Translator($loader, $this->container['config']['app.locale']);

        // $trans->setFallback($fallbackLocale);
        $this->translator = $trans;
    }

    /**
     * Set the Translator implementation.
     *
     * @param \Symfony\Component\Translation\TranslatorInterface $translator
     * @return void
     */
    public function setTranslator(TranslatorInterface $translator)
    {
        $this->translator = $translator;
        $this->validator->setTranslator($this->translator);
    }

    /**
     * Build the validation factory instance.
     *
     * @return void
     */
    protected function setupValidator()
    {
        $this->validator = new Factory($this->translator, $this->container);
    }

    /**
     * Set the database connection.
     *
     * @param array $config
     * @return void
     */
    public function setConnection(array $config)
    {
        $connection = new ConnectionFactory($this->container);

        $db = new ConnectionResolver(array(
            null => $connection->make($config)
        ));

        $this->setPresenceVerifier($db);
    }

    /**
     * Register the database presence verifier.
     *
     * @param \Illuminate\Database\ConnectionResolverInterface $db
     * @return void
     */
    public function setPresenceVerifier(ConnectionResolverInterface $db)
    {
        $presence = new DatabasePresenceVerifier($db);
        $this->validator->setPresenceVerifier($presence);
    }

    /**
     * Make this capsule instance available globally.
     *
     * @return void
     */
    public function setAsGlobal()
    {
        static::$instance = $this;
    }

    /**
     * Get the validation factory instance.
     *
     * @return \Illuminate\Validation\Factory
     */
    public function getValidator()
    {
        return $this->validator;
    }

    /**
     * Get the IoC container instance.
     *
     * @return \Illuminate\Container\Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Set the IoC container instance.
     *
     * @param \Illuminate\Container\Container $container
     * @return void
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;
    }
}