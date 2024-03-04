<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Runtime;

use Latte;
use Latte\ContentType;
use Latte\Helpers;


/**
 * Filter executor.
 * @internal
 */
#[\AllowDynamicProperties]
class FilterExecutor
{
	/** @var callable[] */
	private array $_dynamic = [];

	/** @var array<string, array{callable, ?bool}> */
	private array $_static = [];


	/**
	 * Registers run-time filter.
	 */
	public function add(?string $name, callable $callback): static
	{
		if ($name === null) {
			array_unshift($this->_dynamic, $callback);
		} else {
			$this->_static[$name] = [$callback, null];
			unset($this->$name);
		}

		return $this;
	}


	/**
	 * Returns all run-time filters.
	 * @return callable[]
	 */
	public function getAll(): array
	{
		return array_combine(array_keys($this->_static), array_column($this->_static, 0));
	}


	/**
	 * Returns filter for classic calling.
	 */
	public function __get(string $name): callable
	{
		if (isset($this->_static[$name])) {
			[$callback, $aware] = $this->prepareFilter($name);
			if ($aware) { // FilterInfo aware filter
				return $this->$name = function (...$args) use ($callback) {
					array_unshift($args, $info = new FilterInfo);
					if ($args[1] instanceof HtmlStringable) {
						$args[1] = $args[1]->__toString();
						$info->contentType = ContentType::Html;
					}

					$res = $callback(...$args);
					return $info->contentType === ContentType::Html
						? new Html($res)
						: $res;
				};
			} else { // classic filter
				return $this->$name = $callback;
			}
		}

		// dynamic filter
		foreach ($this->_dynamic as $loader) {
			$callback = $loader($name);
			if ($callback !== null) {
				$this->_static[$name] = [$callback, null];
				return $this->__get($name);
			}
		}

		$hint = ($t = Helpers::getSuggestion(array_keys($this->_static), $name))
			? ", did you mean '$t'?"
			: '.';
		throw new \LogicException("Filter '$name' is not defined$hint");
	}


	/**
	 * Calls filter with FilterInfo.
	 */
	public function filterContent(string $name, FilterInfo $info, mixed ...$args): mixed
	{
		if (!isset($this->_static[$name])) {
			$hint = ($t = Helpers::getSuggestion(array_keys($this->_static), $name))
				? ", did you mean '$t'?"
				: '.';
			throw new \LogicException("Filter |$name is not defined$hint");
		}

		[$callback, $aware] = $this->prepareFilter($name);

		if ($info->contentType === ContentType::Html && $args[0] instanceof HtmlStringable) {
			$args[0] = $args[0]->__toString();
		}

		if ($aware) { // FilterInfo aware filter
			array_unshift($args, $info);
			return $callback(...$args);
		}

		// classic filter
		if ($info->contentType !== ContentType::Text) {
			throw new Latte\RuntimeException("Filter |$name is called with incompatible content type " . strtoupper($info->contentType)
				. ($info->contentType === ContentType::Html ? ', try to prepend |stripHtml.' : '.'));
		}

		$res = ($this->$name)(...$args);
		if ($res instanceof HtmlStringable) {
			trigger_error("Filter |$name should be changed to content-aware filter.");
			$info->contentType = ContentType::Html;
			$res = $res->__toString();
		}

		return $res;
	}


	/**
	 * @return array{callable, bool}
	 */
	private function prepareFilter(string $name): array
	{
		if (!isset($this->_static[$name][1])) {
			$params = Helpers::toReflection($this->_static[$name][0])->getParameters();
			$this->_static[$name][1] = $params
				&& $params[0]->getType() instanceof \ReflectionNamedType
				&& $params[0]->getType()->getName() === FilterInfo::class;
		}

		return $this->_static[$name];
	}
}
