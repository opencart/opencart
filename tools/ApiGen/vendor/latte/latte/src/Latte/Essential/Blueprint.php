<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential;

use Latte;
use Latte\Runtime\Template;
use Nette\PhpGenerator as Php;


/**
 * Generates blueprint of template class.
 * @internal
 */
final class Blueprint
{
	use Latte\Strict;

	public function printClass(Template $template, ?string $name = null): void
	{
		if (!class_exists(Php\ClassType::class)) {
			throw new \LogicException('Nette PhpGenerator is required to print template, install package `nette/php-generator`.');
		}

		$name = $name ?: 'Template';
		$namespace = new Php\PhpNamespace(Php\Helpers::extractNamespace($name));
		$class = $namespace->addClass(Php\Helpers::extractShortName($name));

		$this->addProperties($class, $template->getParameters());
		$functions = array_diff_key((array) $template->global->fn, (new Latte\Essential\CoreExtension)->getFunctions());
		$this->addFunctions($class, $functions);

		$end = $this->printCanvas();
		$this->printHeader('Native types');
		$this->printCode((string) $namespace);
		echo $end;
	}


	/**
	 * @param  mixed[]  $vars
	 */
	public function printVars(array $vars): void
	{
		if (!class_exists(Php\Type::class)) {
			throw new \LogicException('Nette PhpGenerator is required to print template, install package `nette/php-generator`.');
		}

		$res = '';
		foreach ($vars as $name => $value) {
			if (str_starts_with($name, 'ʟ_')) {
				continue;
			}

			$type = $this->getType($value);
			$res .= "{varType $type $$name}\n";
		}

		$end = $this->printCanvas();
		$this->printHeader('varPrint');
		$this->printCode($res ?: 'No variables', 'latte');
		echo $end;
	}


	/**
	 * @param  mixed[]  $props
	 */
	public function addProperties(Php\ClassType $class, array $props): void
	{
		foreach ($props as $name => $value) {
			$class->removeProperty($name);
			$type = $this->getType($value);
			$prop = $class->addProperty($name);
			$prop->setType($type);
		}
	}


	/**
	 * @param  callable[]  $funcs
	 */
	public function addFunctions(Php\ClassType $class, array $funcs): void
	{
		foreach ($funcs as $name => $func) {
			$method = (new Php\Factory)->fromCallable($func);
			$type = $this->printType($method->getReturnType(), $method->isReturnNullable(), $class->getNamespace()) ?: 'mixed';
			$class->addComment("@method $type $name" . $this->printParameters($method, $class->getNamespace()));
		}
	}


	private function printType(?string $type, bool $nullable, ?Php\PhpNamespace $namespace): string
	{
		if ($type === null) {
			return '';
		}

		if ($namespace) {
			$type = $namespace->simplifyName($type);
		}

		if ($nullable && strcasecmp($type, 'mixed')) {
			$type = str_contains($type, '|')
				? $type . '|null'
				: '?' . $type;
		}

		return $type;
	}


	public function printParameters(
		Php\Closure|Php\GlobalFunction|Php\Method $function,
		?Php\PhpNamespace $namespace = null,
	): string
	{
		$params = [];
		$list = $function->getParameters();
		foreach ($list as $param) {
			$variadic = $function->isVariadic() && $param === end($list);
			$params[] = ltrim($this->printType($param->getType(), $param->isNullable(), $namespace) . ' ')
				. ($param->isReference() ? '&' : '')
				. ($variadic ? '...' : '')
				. '$' . $param->getName()
				. ($param->hasDefaultValue() && !$variadic ? ' = ' . var_export($param->getDefaultValue(), true) : '');
		}

		return '(' . implode(', ', $params) . ')';
	}


	public function printCanvas(): string
	{
		echo '<script src="https://nette.github.io/resources/prism/prism.js"></script>';
		echo '<link rel="stylesheet" href="https://nette.github.io/resources/prism/prism.css">';
		echo "<div style='all:initial;position:fixed;overflow:auto;z-index:1000;left:0;right:0;top:0;bottom:0;color:black;background:white;padding:1em'>\n";
		return "</div>\n";
	}


	public function printHeader(string $string): void
	{
		echo "<h1 style='all:initial;display:block;font-size:2em;margin:1em 0'>",
			htmlspecialchars($string),
			"</h1>\n";
	}


	public function printCode(string $code, string $lang = 'php'): void
	{
		echo '<pre><code class="language-', htmlspecialchars($lang), '">',
			htmlspecialchars($code),
			"</code></pre>\n";
	}


	private function getType($value): string
	{
		return $value === null ? 'mixed' : get_debug_type($value);
	}
}
