<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Runtime;

use Latte;
use Latte\Compiler\Escaper;
use Latte\Engine;


/**
 * Template.
 */
class Template
{
	use Latte\Strict;

	public const
		LayerTop = 0,
		LayerSnippet = 'snippet',
		LayerLocal = 'local';

	public const ContentType = Latte\ContentType::Html;

	public const Blocks = [];

	/** global accumulators for intermediate results */
	public \stdClass $global;

	/** @var mixed[]  @internal */
	protected array $params = [];

	protected FilterExecutor $filters;

	/** @internal */
	protected string|false|null $parentName = null;

	/** @var mixed[][] */
	protected array $varStack = [];

	/** @var Block[][] */
	protected array $blocks;

	/** @var mixed[][] */
	private array $blockStack = [];

	private Engine $engine;
	private string $name;
	private ?Template $referringTemplate = null;
	private ?string $referenceType = null;


	/**
	 * @param  mixed[]  $params
	 */
	public function __construct(
		Engine $engine,
		array $params,
		FilterExecutor $filters,
		\stdClass $providers,
		string $name,
	) {
		$this->engine = $engine;
		$this->params = $params;
		$this->filters = $filters;
		$this->name = $name;
		$this->global = $providers;
		$this->initBlockLayer(self::LayerTop);
		$this->initBlockLayer(self::LayerLocal);
		$this->initBlockLayer(self::LayerSnippet);
	}


	public function getEngine(): Engine
	{
		return $this->engine;
	}


	public function getName(): string
	{
		return $this->name;
	}


	/**
	 * Returns array of all parameters.
	 * @return mixed[]
	 */
	public function getParameters(): array
	{
		return $this->params;
	}


	/**
	 * @return string[]
	 */
	public function getBlockNames(int|string $layer = self::LayerTop): array
	{
		return array_keys($this->blocks[$layer] ?? []);
	}


	public function getParentName(): ?string
	{
		return $this->parentName ?: null;
	}


	public function getReferringTemplate(): ?self
	{
		return $this->referringTemplate;
	}


	public function getReferenceType(): ?string
	{
		return $this->referenceType;
	}


	/**
	 * Renders template.
	 * @internal
	 */
	public function render(?string $block = null): void
	{
		foreach ($this->engine->getExtensions() as $extension) {
			$extension->beforeRender($this);
		}

		$params = $this->prepare();

		if ($this->parentName === null && isset($this->global->coreParentFinder)) {
			$this->parentName = ($this->global->coreParentFinder)($this);
		}

		Filters::$xml = static::ContentType === Latte\ContentType::Xml;

		if ($this->referenceType === 'import') {
			if ($this->parentName) {
				throw new Latte\RuntimeException('Imported template cannot use {extends} or {layout}, use {import}');
			}

		} elseif ($this->parentName) { // extends
			$this->params = $params;
			$this->createTemplate($this->parentName, $params, 'extends')->render($block);

		} elseif ($block !== null) { // single block rendering
			$this->renderBlock($block, $this->params);

		} else {
			$this->main($params);
		}
	}


	/**
	 * Renders template.
	 * @param  mixed[]  $params
	 * @internal
	 */
	public function createTemplate(string $name, array $params, string $referenceType): self
	{
		$name = $this->engine->getLoader()->getReferredName($name, $this->name);
		$referred = $referenceType === 'sandbox'
			? (clone $this->engine)->setSandboxMode()->createTemplate($name, $params)
			: $this->engine->createTemplate($name, $params);

		$referred->referringTemplate = $this;
		$referred->referenceType = $referenceType;
		$referred->global = $this->global;

		if (in_array($referenceType, ['extends', 'includeblock', 'import', 'embed'], true)) {
			foreach ($referred->blocks[self::LayerTop] as $nm => $block) {
				$this->addBlock($nm, $block->contentType, $block->functions);
			}

			$referred->blocks[self::LayerTop] = &$this->blocks[self::LayerTop];

			$this->blocks[self::LayerSnippet] += $referred->blocks[self::LayerSnippet];
			$referred->blocks[self::LayerSnippet] = &$this->blocks[self::LayerSnippet];
		}

		return $referred;
	}


	/**
	 * @param  string|\Closure|null  $mod  content-type name or modifier closure
	 * @internal
	 */
	public function renderToContentType(string|\Closure|null $mod, ?string $block = null): void
	{
		$this->filter(
			fn() => $this->render($block),
			$mod,
			static::ContentType,
			"'$this->name'",
		);
	}


	/** @return mixed[] */
	public function prepare(): array
	{
		return $this->params;
	}


	/** @param mixed[] $params */
	public function main(array $params): void
	{
	}


	/********************* blocks ****************d*g**/


	/**
	 * Renders block.
	 * @param  mixed[]  $params
	 * @param  string|\Closure|null  $mod  content-type name or modifier closure
	 * @internal
	 */
	public function renderBlock(
		string $name,
		array $params,
		string|\Closure|null $mod = null,
		int|string|null $layer = null,
	): void
	{
		$block = $layer
			? ($this->blocks[$layer][$name] ?? null)
			: ($this->blocks[self::LayerLocal][$name] ?? $this->blocks[self::LayerTop][$name] ?? null);

		if (!$block) {
			$hint = $layer && ($t = Latte\Helpers::getSuggestion($this->getBlockNames($layer), $name))
				? ", did you mean '$t'?"
				: '.';
			$name = $layer ? "$layer $name" : $name;
			throw new Latte\RuntimeException("Cannot include undefined block '$name'$hint");
		}

		$this->filter(
			fn() => reset($block->functions)($params),
			$mod,
			$block->contentType,
			"block $name",
		);
	}


	/**
	 * Renders parent block.
	 * @param  mixed[]  $params
	 * @internal
	 */
	public function renderBlockParent(string $name, array $params): void
	{
		$block = $this->blocks[self::LayerLocal][$name] ?? $this->blocks[self::LayerTop][$name] ?? null;
		if (!$block || ($function = next($block->functions)) === false) {
			throw new Latte\RuntimeException("Cannot include undefined parent block '$name'.");
		}
		$function($params);
		prev($block->functions);
	}


	/**
	 * Creates block if doesn't exist and checks if content type is the same.
	 * @param  callable[]  $functions
	 * @internal
	 */
	protected function addBlock(
		string $name,
		string $contentType,
		array $functions,
		int|string|null $layer = null,
	): void
	{
		$block = &$this->blocks[$layer ?? self::LayerTop][$name];
		$block ??= new Block;
		if ($block->contentType === null) {
			$block->contentType = $contentType;

		} elseif (!Escaper::getConvertor($contentType, $block->contentType)) {
			throw new Latte\RuntimeException(sprintf(
				"Overridden block $name with content type %s by incompatible type %s.",
				strtoupper($contentType),
				strtoupper($block->contentType),
			));
		}

		$block->functions = array_merge($block->functions, $functions);
	}


	/**
	 * @param  string|\Closure|null  $mod  content-type name or modifier closure
	 */
	private function filter(callable $function, string|\Closure|null $mod, string $contentType, string $name): void
	{
		if ($mod === null || $mod === $contentType) {
			$function();

		} elseif ($mod instanceof \Closure) {
			echo $mod($this->capture($function), $contentType);

		} elseif ($filter = Escaper::getConvertor($contentType, $mod)) {
			echo $filter($this->capture($function));

		} else {
			throw new Latte\RuntimeException(sprintf(
				"Including $name with content type %s into incompatible type %s.",
				strtoupper($contentType),
				strtoupper($mod),
			));
		}
	}


	/**
	 * Captures output to string.
	 * @internal
	 */
	public function capture(callable $function): string
	{
		try {
			ob_start(fn() => '');
			$function();
			return ob_get_clean();
		} catch (\Throwable $e) {
			ob_end_clean();
			throw $e;
		}
	}


	private function initBlockLayer(int|string $staticId, ?int $destId = null): void
	{
		$destId ??= $staticId;
		$this->blocks[$destId] = [];
		foreach (static::Blocks[$staticId] ?? [] as $nm => $info) {
			[$method, $contentType] = is_array($info) ? $info : [$info, static::ContentType];
			$this->addBlock($nm, $contentType, [[$this, $method]], $destId);
		}
	}


	protected function enterBlockLayer(int $staticId, array $vars): void
	{
		$this->blockStack[] = $this->blocks[self::LayerTop];
		$this->initBlockLayer($staticId, self::LayerTop);
		$this->varStack[] = $vars;
	}


	protected function copyBlockLayer(): void
	{
		foreach (end($this->blockStack) as $nm => $block) {
			$this->addBlock($nm, $block->contentType, $block->functions);
		}
	}


	protected function leaveBlockLayer(): void
	{
		$this->blocks[self::LayerTop] = array_pop($this->blockStack);
		array_pop($this->varStack);
	}


	public function hasBlock(string $name): bool
	{
		return isset($this->blocks[self::LayerLocal][$name]) || isset($this->blocks[self::LayerTop][$name]);
	}
}
