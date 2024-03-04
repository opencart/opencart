<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte;

use Latte\Compiler\Nodes\TemplateNode;


/**
 * Templating engine Latte.
 */
class Engine
{
	use Strict;

	public const VERSION = '3.0.6';
	public const VERSION_ID = 30006;

	/** @deprecated use ContentType::* */
	public const
		CONTENT_HTML = ContentType::Html,
		CONTENT_XML = ContentType::Xml,
		CONTENT_JS = ContentType::JavaScript,
		CONTENT_CSS = ContentType::Css,
		CONTENT_ICAL = ContentType::ICal,
		CONTENT_TEXT = ContentType::Text;

	private ?Loader $loader = null;
	private Runtime\FilterExecutor $filters;
	private \stdClass $functions;
	private \stdClass $providers;

	/** @var Extension[] */
	private array $extensions = [];
	private string $contentType = ContentType::Html;
	private ?string $tempDirectory = null;
	private bool $autoRefresh = true;
	private bool $strictTypes = false;
	private ?Policy $policy = null;
	private bool $sandboxed = false;


	public function __construct()
	{
		$this->filters = new Runtime\FilterExecutor;
		$this->functions = new \stdClass;
		$this->providers = new \stdClass;
		$this->addExtension(new Essential\CoreExtension);
		$this->addExtension(new Sandbox\SandboxExtension);
	}


	/**
	 * Renders template to output.
	 * @param  object|mixed[]  $params
	 */
	public function render(string $name, object|array $params = [], ?string $block = null): void
	{
		$template = $this->createTemplate($name, $this->processParams($params));
		$template->global->coreCaptured = false;
		$template->render($block);
	}


	/**
	 * Renders template to string.
	 * @param  object|mixed[]  $params
	 */
	public function renderToString(string $name, object|array $params = [], ?string $block = null): string
	{
		$template = $this->createTemplate($name, $this->processParams($params));
		$template->global->coreCaptured = true;
		return $template->capture(fn() => $template->render($block));
	}


	/**
	 * Creates template object.
	 * @param  mixed[]  $params
	 */
	public function createTemplate(string $name, array $params = []): Runtime\Template
	{
		$class = $this->getTemplateClass($name);
		if (!class_exists($class, false)) {
			$this->loadTemplate($name);
		}

		$this->providers->fn = $this->functions;
		return new $class(
			$this,
			$params,
			$this->filters,
			$this->providers,
			$name,
		);
	}


	/**
	 * Compiles template to PHP code.
	 */
	public function compile(string $name): string
	{
		if ($this->sandboxed && !$this->policy) {
			throw new \LogicException('In sandboxed mode you need to set a security policy.');
		}

		$source = $this->getLoader()->getContent($name);

		try {
			$node = $this->parse($source);
			$this->applyPasses($node);
			$code = $this->generate($node, $name);

		} catch (\Throwable $e) {
			if (!$e instanceof CompileException && !$e instanceof SecurityViolationException) {
				$e = new CompileException("Thrown exception '{$e->getMessage()}'", previous: $e);
			}

			throw $e->setSource($source, $name);
		}

		return $code;
	}


	/**
	 * Parses template to AST node.
	 */
	public function parse(string $source): TemplateNode
	{
		$lexer = new Compiler\TemplateLexer;
		$parser = new Compiler\TemplateParser;

		foreach ($this->extensions as $extension) {
			$extension->beforeCompile($this);
			$parser->addTags($extension->getTags());
		}

		return $parser
			->setContentType($this->contentType)
			->setPolicy($this->getPolicy(effective: true))
			->parse($source, $lexer);
	}


	/**
	 * Calls node visitors.
	 */
	public function applyPasses(TemplateNode &$node): void
	{
		$passes = [];
		foreach ($this->extensions as $extension) {
			$passes = array_merge($passes, $extension->getPasses());
		}

		$passes = Helpers::sortBeforeAfter($passes);
		foreach ($passes as $pass) {
			$pass = $pass instanceof \stdClass ? $pass->subject : $pass;
			($pass)($node);
		}
	}


	/**
	 * Generates template PHP code.
	 */
	public function generate(TemplateNode $node, string $name): string
	{
		$comment = preg_match('#\n|\?#', $name) ? null : "source: $name";
		$generator = new Compiler\TemplateGenerator;
		return $generator->generate(
			$node,
			$this->getTemplateClass($name),
			$comment,
			$this->strictTypes,
		);
	}


	/**
	 * Compiles template to cache.
	 * @throws \LogicException
	 */
	public function warmupCache(string $name): void
	{
		if (!$this->tempDirectory) {
			throw new \LogicException('Path to temporary directory is not set.');
		}

		$class = $this->getTemplateClass($name);
		if (!class_exists($class, false)) {
			$this->loadTemplate($name);
		}
	}


	private function loadTemplate(string $name): void
	{
		if (!$this->tempDirectory) {
			$code = $this->compile($name);
			if (@eval(substr($code, 5)) === false) { // @ is escalated to exception, substr removes <?php
				throw (new CompileException('Error in template: ' . error_get_last()['message']))
					->setSource($code, "$name (compiled)");
			}

			return;
		}

		// Solving atomicity to work everywhere is really pain in the ass.
		// 1) We want to do as little as possible IO calls on production and also directory and file can be not writable
		// so on Linux we include the file directly without shared lock, therefore, the file must be created atomically by renaming.
		// 2) On Windows file cannot be renamed-to while is open (ie by include), so we have to acquire a lock.
		$file = $this->getCacheFile($name);
		$lock = defined('PHP_WINDOWS_VERSION_BUILD')
			? $this->acquireLock("$file.lock", LOCK_SH)
			: null;

		if (!$this->isExpired($file, $name) && (@include $file) !== false) { // @ - file may not exist
			return;
		}

		if ($lock) {
			flock($lock, LOCK_UN); // release shared lock so we can get exclusive
		}

		$lock = $this->acquireLock("$file.lock", LOCK_EX);

		// while waiting for exclusive lock, someone might have already created the cache
		if (!is_file($file) || $this->isExpired($file, $name)) {
			$code = $this->compile($name);
			if (file_put_contents("$file.tmp", $code) !== strlen($code) || !rename("$file.tmp", $file)) {
				@unlink("$file.tmp"); // @ - file may not exist
				throw new RuntimeException("Unable to create '$file'.");
			}

			if (function_exists('opcache_invalidate')) {
				@opcache_invalidate($file, true); // @ can be restricted
			}
		}

		if ((include $file) === false) {
			throw new RuntimeException("Unable to load '$file'.");
		}

		flock($lock, LOCK_UN);
	}


	/**
	 * @return resource
	 */
	private function acquireLock(string $file, int $mode)
	{
		$dir = dirname($file);
		if (!is_dir($dir) && !@mkdir($dir) && !is_dir($dir)) { // @ - dir may already exist
			throw new RuntimeException("Unable to create directory '$dir'. " . error_get_last()['message']);
		}

		$handle = @fopen($file, 'w'); // @ is escalated to exception
		if (!$handle) {
			throw new RuntimeException("Unable to create file '$file'. " . error_get_last()['message']);
		} elseif (!@flock($handle, $mode)) { // @ is escalated to exception
			throw new RuntimeException('Unable to acquire ' . ($mode & LOCK_EX ? 'exclusive' : 'shared') . " lock on file '$file'. " . error_get_last()['message']);
		}

		return $handle;
	}


	private function isExpired(string $file, string $name): bool
	{
		if (!$this->autoRefresh) {
			return false;
		}

		$time = @filemtime($file); // @ - file may not exist
		if ($time === false) {
			return true;
		}

		foreach ($this->extensions as $extension) {
			$r = new \ReflectionObject($extension);
			if (is_file($r->getFileName()) && filemtime($r->getFileName()) > $time) {
				return true;
			}
		}

		return $this->getLoader()->isExpired($name, $time);
	}


	public function getCacheFile(string $name): string
	{
		$hash = substr($this->getTemplateClass($name), 8);
		$base = preg_match('#([/\\\\][\w@.-]{3,35}){1,3}$#D', $name, $m)
			? preg_replace('#[^\w@.-]+#', '-', substr($m[0], 1)) . '--'
			: '';
		return "$this->tempDirectory/$base$hash.php";
	}


	public function getTemplateClass(string $name): string
	{
		$key = [
			$this->getLoader()->getUniqueId($name),
			self::VERSION,
			array_keys((array) $this->functions),
			$this->contentType,
		];
		foreach ($this->extensions as $extension) {
			$key[] = [
				get_debug_type($extension),
				$extension->getCacheKey($this),
			];
		}

		return 'Template' . substr(md5(serialize($key)), 0, 10);
	}


	/**
	 * Registers run-time filter.
	 */
	public function addFilter(string $name, callable $callback): static
	{
		if (!preg_match('#^[a-z]\w*$#iD', $name)) {
			throw new \LogicException("Invalid filter name '$name'.");
		}

		$this->filters->add($name, $callback);
		return $this;
	}


	/**
	 * Registers filter loader.
	 */
	public function addFilterLoader(callable $callback): static
	{
		$this->filters->add(null, $callback);
		return $this;
	}


	/**
	 * Returns all run-time filters.
	 * @return callable[]
	 */
	public function getFilters(): array
	{
		return $this->filters->getAll();
	}


	/**
	 * Call a run-time filter.
	 * @param  mixed[]  $args
	 */
	public function invokeFilter(string $name, array $args): mixed
	{
		return ($this->filters->$name)(...$args);
	}


	/**
	 * Adds new extension.
	 */
	public function addExtension(Extension $extension): static
	{
		$this->extensions[] = $extension;
		foreach ($extension->getFilters() as $name => $value) {
			$this->filters->add($name, $value);
		}

		foreach ($extension->getFunctions() as $name => $value) {
			$this->functions->$name = $value;
		}

		foreach ($extension->getProviders() as $name => $value) {
			$this->providers->$name = $value;
		}
		return $this;
	}


	/** @return Extension[] */
	public function getExtensions(): array
	{
		return $this->extensions;
	}


	/**
	 * Registers run-time function.
	 */
	public function addFunction(string $name, callable $callback): static
	{
		if (!preg_match('#^[a-z]\w*$#iD', $name)) {
			throw new \LogicException("Invalid function name '$name'.");
		}

		$this->functions->$name = $callback;
		return $this;
	}


	/**
	 * Call a run-time function.
	 * @param  mixed[]  $args
	 */
	public function invokeFunction(string $name, array $args): mixed
	{
		if (!isset($this->functions->$name)) {
			$hint = ($t = Helpers::getSuggestion(array_keys((array) $this->functions), $name))
				? ", did you mean '$t'?"
				: '.';
			throw new \LogicException("Function '$name' is not defined$hint");
		}

		return ($this->functions->$name)(...$args);
	}


	/**
	 * @return callable[]
	 */
	public function getFunctions(): array
	{
		return (array) $this->functions;
	}


	/**
	 * Adds new provider.
	 */
	public function addProvider(string $name, mixed $value): static
	{
		if (!preg_match('#^[a-z]\w*$#iD', $name)) {
			throw new \LogicException("Invalid provider name '$name'.");
		}

		$this->providers->$name = $value;
		return $this;
	}


	/**
	 * Returns all providers.
	 * @return mixed[]
	 */
	public function getProviders(): array
	{
		return (array) $this->providers;
	}


	public function setPolicy(?Policy $policy): static
	{
		$this->policy = $policy;
		return $this;
	}


	public function getPolicy(bool $effective = false): ?Policy
	{
		return !$effective || $this->sandboxed
			? $this->policy
			: null;
	}


	public function setExceptionHandler(callable $callback): static
	{
		$this->providers->coreExceptionHandler = $callback;
		return $this;
	}


	public function setSandboxMode(bool $on = true): static
	{
		$this->sandboxed = $on;
		return $this;
	}


	public function setContentType(string $type): static
	{
		$this->contentType = $type;
		return $this;
	}


	/**
	 * Sets path to temporary directory.
	 */
	public function setTempDirectory(?string $path): static
	{
		$this->tempDirectory = $path;
		return $this;
	}


	/**
	 * Sets auto-refresh mode.
	 */
	public function setAutoRefresh(bool $on = true): static
	{
		$this->autoRefresh = $on;
		return $this;
	}


	/**
	 * Enables declare(strict_types=1) in templates.
	 */
	public function setStrictTypes(bool $on = true): static
	{
		$this->strictTypes = $on;
		return $this;
	}


	public function setLoader(Loader $loader): static
	{
		$this->loader = $loader;
		return $this;
	}


	public function getLoader(): Loader
	{
		if (!$this->loader) {
			$this->loader = new Loaders\FileLoader;
		}

		return $this->loader;
	}


	/**
	 * @param  object|mixed[]  $params
	 * @return mixed[]
	 */
	private function processParams(object|array $params): array
	{
		if (is_array($params)) {
			return $params;
		}

		$methods = (new \ReflectionClass($params))->getMethods(\ReflectionMethod::IS_PUBLIC);
		foreach ($methods as $method) {
			if ($method->getAttributes(Attributes\TemplateFilter::class)) {
				$this->addFilter($method->name, [$params, $method->name]);
			}

			if ($method->getAttributes(Attributes\TemplateFunction::class)) {
				$this->addFunction($method->name, [$params, $method->name]);
			}

			if (strpos((string) $method->getDocComment(), '@filter')) {
				trigger_error('Annotation @filter is deprecated, use attribute #[Latte\Attributes\TemplateFilter]', E_USER_DEPRECATED);
				$this->addFilter($method->name, [$params, $method->name]);
			}

			if (strpos((string) $method->getDocComment(), '@function')) {
				trigger_error('Annotation @function is deprecated, use attribute #[Latte\Attributes\TemplateFunction]', E_USER_DEPRECATED);
				$this->addFunction($method->name, [$params, $method->name]);
			}
		}

		return array_filter((array) $params, fn($key) => $key[0] !== "\0", ARRAY_FILTER_USE_KEY);
	}


	public function __get(string $name)
	{
		if ($name === 'onCompile') {
			$trace = debug_backtrace(0)[0];
			$loc = isset($trace['file'], $trace['line'])
				? ' (in ' . $trace['file'] . ' on ' . $trace['line'] . ')'
				: '';
			throw new \LogicException('You use Latte 3 together with the code designed for Latte 2' . $loc);
		}
	}
}
