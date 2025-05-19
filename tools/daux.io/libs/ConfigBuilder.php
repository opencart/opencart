<?php namespace Todaymade\Daux;

class ConfigBuilder
{
    private Config $config;

    private array $overrideValues = [];

    private $configurationOverrideFile;

    private function __construct(string $mode)
    {
        $this->config = new Config();
        $this->config['mode'] = $mode;
        $this->config['local_base'] = dirname(__DIR__);
    }

    public static function fromFile($file): Config
    {
        return unserialize(file_get_contents($file));
    }

    public static function withMode($mode = Daux::STATIC_MODE): ConfigBuilder
    {
        $builder = new ConfigBuilder($mode);
        $builder->loadBaseConfiguration();

        return $builder;
    }

    public function with(array $values): ConfigBuilder
    {
        $this->config->merge($values);

        return $this;
    }

    private function setValue(Config $array, $key, $value)
    {
        if (is_null($key)) {
            return $array = $value;
        }
        $keys = explode('.', $key);
        while (count($keys) > 1) {
            $key = array_shift($keys);
            if (!isset($array[$key]) || !is_array($array[$key])) {
                $array[$key] = [];
            }
            $array = &$array[$key];
        }
        $array[array_shift($keys)] = $value;
    }

    public function withValues(array $values): ConfigBuilder
    {
        $this->overrideValues = $values;

        return $this;
    }

    public function withDocumentationDirectory($dir): ConfigBuilder
    {
        $this->config['docs_directory'] = $dir;

        return $this;
    }

    public function withValidContentExtensions(array $value): ConfigBuilder
    {
        $this->config['valid_content_extensions'] = $value;

        return $this;
    }

    public function withThemesPath($themePath): ConfigBuilder
    {
        $this->config['themes_path'] = $themePath;

        return $this;
    }

    public function withThemesDirectory($directory): ConfigBuilder
    {
        $this->config['themes_directory'] = $directory;

        return $this;
    }

    public function withCache(bool $value): ConfigBuilder
    {
        $this->config['cache'] = $value;

        return $this;
    }

    public function withFormat($format): ConfigBuilder
    {
        $this->config['format'] = $format;

        return $this;
    }

    public function withConfigurationOverride($file): ConfigBuilder
    {
        $this->configurationOverrideFile = $file;

        return $this;
    }

    public function withProcessor($value): ConfigBuilder
    {
        $this->config['processor'] = $value;

        return $this;
    }

    public function withConfluenceDelete($value): ConfigBuilder
    {
        $this->config['confluence']['delete'] = $value;

        return $this;
    }

    public function withConfluencePrintDiff($value): ConfigBuilder
    {
        $this->config['confluence']['print_diff'] = $value;

        return $this;
    }

    public function build(): Config
    {
        $this->initializeConfiguration();

        foreach ($this->overrideValues as $value) {
            $this->setValue($this->config, $value[0], $value[1]);
        }

        return $this->config;
    }

    private function resolveThemeVariant()
    {
        $theme = $this->config->getHTML()->getTheme();
        $themesPath = $this->config->getThemesPath() . DIRECTORY_SEPARATOR;

        // If theme directory exists, we're good with that
        if (is_dir($themesPath . $theme)) {
            return [$theme, ''];
        }

        $themePieces = explode('-', $theme);
        $variant = '';

        // Do we have a variant or only a theme ?
        if (count($themePieces) > 1) {
            $variant = array_pop($themePieces);
            $theme = implode('-', $themePieces);
        }

        if (!is_dir($themesPath . $theme)) {
            throw new ConfigurationException("Theme '{$theme}' not found");
        }

        return [$theme, $variant];
    }

    /**
     * @throws Exception
     */
    private function initializeConfiguration()
    {
        // Validate and set theme path
        $docsPath = $this->normalizeDocumentationPath($this->config->getDocumentationDirectory());
        $this->config['docs_directory'] = $docsPath;

        // Read documentation overrides
        $this->loadConfiguration($docsPath . DIRECTORY_SEPARATOR . 'config.json');

        // Read command line overrides
        $overrideFile = $this->getConfigurationOverride($this->configurationOverrideFile);
        if ($overrideFile !== null) {
            $this->loadConfiguration($overrideFile);
        }

        // Validate and set theme path
        $this->withThemesPath($this->normalizeThemePath($this->config->getThemesDirectory()));

        // Resolve variant once
        $theme = $this->resolveThemeVariant();
        $this->config['html']['theme'] = $theme[0];
        $this->config['html']['theme-variant'] = $theme[1];

        // Set a valid default timezone
        if ($this->config->hasTimezone()) {
            date_default_timezone_set($this->config->getTimezone());
        } elseif (!ini_get('date.timezone')) {
            date_default_timezone_set('GMT');
        }

        // Text search would be too slow on live server
        if ($this->config->isLive()) {
            $this->config['html']['search'] = false;
        }
    }

    private function normalizeThemePath($path)
    {
        $validPath = $this->findLocation($path, $this->config->getLocalBase(), 'dir');

        if (!$validPath) {
            throw new Exception('The Themes directory does not exist. Check the path again : ' . $path);
        }

        return $validPath;
    }

    private function normalizeDocumentationPath($path)
    {
        $validPath = $this->findLocation($path, $this->config->getLocalBase(), 'dir');

        if (!$validPath) {
            throw new Exception('The Docs directory does not exist. Check the path again : ' . $path);
        }

        return $validPath;
    }

    /**
     * Load and validate the global configuration.
     *
     * @throws Exception
     */
    private function loadBaseConfiguration()
    {
        // Set the default configuration
        $this->config->merge([
            'docs_directory' => 'docs',
            'valid_content_extensions' => ['md', 'markdown'],

            // Paths and tree
            'templates' => 'templates',

            'base_url' => '',
        ]);

        // Load the global configuration
        $this->loadConfiguration($this->config->getLocalBase() . DIRECTORY_SEPARATOR . 'global.json', false);
    }

    /**
     * @param string $configFile
     * @param bool $optional
     *
     * @throws Exception
     */
    private function loadConfiguration($configFile, $optional = true)
    {
        if (!file_exists($configFile)) {
            if ($optional) {
                return;
            }

            throw new Exception('The configuration file is missing. Check path : ' . $configFile);
        }

        $config = json_decode(file_get_contents($configFile), true);
        if (!isset($config)) {
            throw new Exception('The configuration file "' . $configFile . '" is corrupt. Is your JSON well-formed ?');
        }
        $this->config->merge($config);
    }

    /**
     * Get the file requested for configuration overrides.
     *
     * @param null|string $path
     *
     * @return null|string the path to a file to load for configuration overrides
     *
     * @throws Exception
     */
    private function getConfigurationOverride($path)
    {
        $validPath = $this->findLocation($path, $this->config->getLocalBase(), 'file');

        if ($validPath === null) {
            return null;
        }

        if (!$validPath) {
            throw new Exception('The configuration override file does not exist. Check the path again : ' . $path);
        }

        return $validPath;
    }

    /**
     * @param null|string $path
     * @param string $basedir
     * @param string $type
     *
     * @return null|false|string
     */
    private function findLocation($path, $basedir, $type)
    {
        // If Path is explicitly null, it's useless to go further
        if ($path === null) {
            return null;
        }

        // VFS, used only in tests
        if (substr($path, 0, 6) == 'vfs://') {
            return $path;
        }

        // Check if it's relative to the current directory or an absolute path
        if (DauxHelper::is($path, $type)) {
            return realpath(DauxHelper::getAbsolutePath($path));
        }

        // Check if it exists relative to Daux's root
        $newPath = $basedir . DIRECTORY_SEPARATOR . $path;
        if (DauxHelper::is($newPath, $type)) {
            return realpath($newPath);
        }

        return false;
    }
}
