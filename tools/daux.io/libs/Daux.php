<?php namespace Todaymade\Daux;

use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Todaymade\Daux\ContentTypes\ContentTypeHandler;
use Todaymade\Daux\Format\Base\Generator;
use Todaymade\Daux\Tree\Builder;
use Todaymade\Daux\Tree\Root;

class Daux
{
    public const STATIC_MODE = 'DAUX_STATIC';
    public const LIVE_MODE = 'DAUX_LIVE';

    public static $output;

    public ?Root $tree;

    public Config $config;

    protected Generator $generator;

    protected ContentTypeHandler $typeHandler;

    /** @var string[] */
    protected $validExtensions;

    protected ?Processor $processor;

    private bool $mergedTree = false;

    public function __construct(Config $config, OutputInterface $output)
    {
        Daux::$output = $output;

        $this->config = $config;
        $this->tree = null;
    }

    /**
     * Generate the tree that will be used.
     */
    public function generateTree()
    {
        $this->config->setValidContentExtensions($this->getContentExtensions());

        $this->tree = new Root($this->getConfig());
        Builder::build($this->tree, $this->config->getIgnore());

        // Apply the language name as Section title
        if ($this->config->isMultilanguage()) {
            foreach ($this->config->getLanguages() as $key => $node) {
                $this->tree->getEntries()[$key]->setTitle($node);
            }
        }

        // Enhance the tree with processors
        $this->getProcessor()->manipulateTree($this->tree);

        // Sort the tree one last time before it is finalized
        Builder::sortTree($this->tree);

        Builder::finalizeTree($this->tree);
    }

    /**
     * @return Config
     */
    public function getConfig()
    {
        if ($this->tree && !$this->mergedTree) {
            $this->config->setTree($this->tree);
            $this->config->setIndex($this->tree->getIndexPage() ?: $this->tree->getFirstPage());
            if ($this->config->isMultilanguage()) {
                $entryPage = [];
                foreach ($this->config->getLanguages() as $key => $name) {
                    $entryPage[$key] = $this->tree->getEntries()[$key]->getFirstPage();
                }
            } else {
                $entryPage = $this->tree->getFirstPage();
            }
            $this->config->setEntryPage($entryPage);
            $this->mergedTree = true;
        }

        return $this->config;
    }

    /**
     * @return Config
     *
     * @deprecated Use getConfig instead
     */
    public function getParams()
    {
        return $this->getConfig();
    }

    /**
     * @return Processor
     */
    public function getProcessor()
    {
        if (!isset($this->processor)) {
            $this->processor = new Processor($this, Daux::getOutput(), 0);
        }

        return $this->processor;
    }

    public function setProcessor(Processor $processor)
    {
        $this->processor = $processor;

        // This is not the cleanest but it's
        // the best I've found to use the
        // processor in very remote places
        $this->config->setProcessorInstance($processor);
    }

    public function getGenerators()
    {
        $default = [
            'confluence' => '\Todaymade\Daux\Format\Confluence\Generator',
            'html-file' => '\Todaymade\Daux\Format\HTMLFile\Generator',
            'html' => '\Todaymade\Daux\Format\HTML\Generator',
        ];

        $extended = $this->getProcessor()->addGenerators();

        return array_replace($default, $extended);
    }

    /**
     * Processor class.
     *
     * The Processor's name can be an absolute class name or a
     * short class name if processor is located in \Todaymade\Daux\Extension namespace.
     *
     * Location: vendor/daux/daux.io/daux
     *
     * @see \Todaymade\Daux\Extension\Processor
     *
     * @example -p \\Todaymade\\Daux\\Extension\\Processor
     *
     * @return null|string
     *
     * @throws ConfigurationException
     */
    public function getProcessorClass()
    {
        $processor = $this->getConfig()->getProcessor();

        if (empty($processor)) {
            return null;
        }

        if (!strstr($processor, '\\')) {
            $processor = '\\Todaymade\\Daux\\Extension\\' . $processor;
        }

        if (!class_exists($processor)) {
            throw new ConfigurationException("Class '$processor' not found. We cannot use it as a Processor");
        }

        if (!array_key_exists('Todaymade\\Daux\\Processor', class_parents($processor))) {
            throw new ConfigurationException(
                "Class '$processor' invalid, should extend '\\Todaymade\\Daux\\Processor'"
            );
        }

        return $processor;
    }

    protected function findAlternatives($input, $words)
    {
        $alternatives = [];

        foreach ($words as $word) {
            $lev = levenshtein($input, $word);

            if ($lev <= \strlen($word) / 3) {
                $alternatives[] = $word;
            }
        }

        return $alternatives;
    }

    /**
     * @return \Todaymade\Daux\Format\Base\Generator
     */
    public function getGenerator()
    {
        if (isset($this->generator)) {
            return $this->generator;
        }

        $generators = $this->getGenerators();

        $format = $this->getConfig()->getFormat();

        if (!array_key_exists($format, $generators)) {
            $message = "The format '$format' doesn't exist, did you forget to set your processor ?";

            $alternatives = $this->findAlternatives($format, array_keys($generators));

            if (0 == \count($alternatives)) {
                $message .= "\n\nAvailable formats are \n    " . implode("\n    ", array_keys($generators));
            } elseif (1 == \count($alternatives)) {
                $message .= "\n\nDid you mean this?\n    " . implode("\n    ", $alternatives);
            } else {
                $message .= "\n\nDid you mean one of these?\n    " . implode("\n    ", $alternatives);
            }

            throw new ConfigurationException($message);
        }

        $class = $generators[$format];
        if (!class_exists($class)) {
            throw new ConfigurationException("Class '$class' not found. We cannot use it as a Generator");
        }

        $interface = 'Todaymade\Daux\Format\Base\Generator';
        if (!in_array('Todaymade\Daux\Format\Base\Generator', class_implements($class))) {
            throw new ConfigurationException("The class '$class' does not implement the '$interface' interface");
        }

        return $this->generator = new $class($this);
    }

    public function getContentTypeHandler()
    {
        if (isset($this->typeHandler)) {
            return $this->typeHandler;
        }

        $baseTypes = $this->getGenerator()->getContentTypes();

        $extended = $this->getProcessor()->addContentType();

        $types = array_merge($baseTypes, $extended);

        return $this->typeHandler = new ContentTypeHandler($types);
    }

    /**
     * Get all content file extensions.
     *
     * @return string[]
     */
    public function getContentExtensions()
    {
        if (!empty($this->validExtensions)) {
            return $this->validExtensions;
        }

        return $this->validExtensions = $this->getContentTypeHandler()->getContentExtensions();
    }

    public static function getOutput()
    {
        if (!Daux::$output) {
            Daux::$output = new NullOutput();
        }

        return Daux::$output;
    }

    /**
     * Writes a message to the output.
     *
     * @param array|string $messages The message as an array of lines or a single string
     * @param bool         $newline  Whether to add a newline
     * @param int          $options  A bitmask of options (one of the OUTPUT or VERBOSITY constants)
     */
    public static function write($messages, $newline = false, $options = 0)
    {
        Daux::getOutput()->write($messages, $newline, $options);
    }

    /**
     * Writes a message to the output and adds a newline at the end.
     *
     * @param array|string $messages The message as an array of lines of a single string
     * @param int          $options  A bitmask of options (one of the OUTPUT or VERBOSITY constants)
     */
    public static function writeln($messages, $options = 0)
    {
        Daux::getOutput()->write($messages, true, $options);
    }

    public static function getVerbosity()
    {
        return Daux::getOutput()->getVerbosity();
    }
}
