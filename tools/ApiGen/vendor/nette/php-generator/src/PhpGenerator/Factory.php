<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\PhpGenerator;

use Nette;
use Nette\Utils\Reflection;


/**
 * Creates a representation based on reflection.
 */
final class Factory
{
	use Nette\SmartObject;

	/** @var string[][]  */
	private array $bodyCache = [];

	/** @var Extractor[]  */
	private array $extractorCache = [];


	/** @param  \ReflectionClass<object>  $from */
	public function fromClassReflection(
		\ReflectionClass $from,
		bool $withBodies = false,
		?bool $materializeTraits = null,
	): ClassLike
	{
		if ($materializeTraits !== null) {
			trigger_error(__METHOD__ . '() parameter $materializeTraits has been removed (is always false).', E_USER_DEPRECATED);
		}
		if ($withBodies && $from->isAnonymous()) {
			throw new Nette\NotSupportedException('The $withBodies parameter cannot be used for anonymous functions.');
		}

		$enumIface = null;
		if (PHP_VERSION_ID >= 80100 && $from->isEnum()) {
			$class = new EnumType($from->getShortName(), new PhpNamespace($from->getNamespaceName()));
			$from = new \ReflectionEnum($from->getName());
			$enumIface = $from->isBacked() ? \BackedEnum::class : \UnitEnum::class;
		} elseif ($from->isAnonymous()) {
			$class = new ClassType;
		} elseif ($from->isInterface()) {
			$class = new InterfaceType($from->getShortName(), new PhpNamespace($from->getNamespaceName()));
		} elseif ($from->isTrait()) {
			$class = new TraitType($from->getShortName(), new PhpNamespace($from->getNamespaceName()));
		} else {
			$class = new ClassType($from->getShortName(), new PhpNamespace($from->getNamespaceName()));
			$class->setFinal($from->isFinal() && $class->isClass());
			$class->setAbstract($from->isAbstract() && $class->isClass());
			$class->setReadOnly(PHP_VERSION_ID >= 80200 && $from->isReadOnly());
		}

		$ifaces = $from->getInterfaceNames();
		foreach ($ifaces as $iface) {
			$ifaces = array_filter($ifaces, fn(string $item): bool => !is_subclass_of($iface, $item));
		}

		if ($from->isInterface()) {
			$class->setExtends($ifaces);
		} elseif ($ifaces) {
			$ifaces = array_diff($ifaces, [$enumIface]);
			$class->setImplements($ifaces);
		}

		$class->setComment(Helpers::unformatDocComment((string) $from->getDocComment()));
		$class->setAttributes($this->getAttributes($from));
		if ($from->getParentClass()) {
			$class->setExtends($from->getParentClass()->name);
			$class->setImplements(array_diff($class->getImplements(), $from->getParentClass()->getInterfaceNames()));
		}

		$props = [];
		foreach ($from->getProperties() as $prop) {
			$declaringClass = Reflection::getPropertyDeclaringClass($prop);

			if ($prop->isDefault()
				&& $declaringClass->name === $from->name
				&& !$prop->isPromoted()
				&& !$class->isEnum()
			) {
				$props[] = $this->fromPropertyReflection($prop);
			}
		}

		if ($props) {
			$class->setProperties($props);
		}

		$methods = $resolutions = [];
		foreach ($from->getMethods() as $method) {
			$declaringMethod = Reflection::getMethodDeclaringMethod($method);
			$declaringClass = $declaringMethod->getDeclaringClass();

			if (
				$declaringClass->name === $from->name
				&& (!$enumIface || !method_exists($enumIface, $method->name))
			) {
				$methods[] = $m = $this->fromMethodReflection($method);
				if ($withBodies) {
					$bodies = &$this->bodyCache[$declaringClass->name];
					$bodies ??= $this->getExtractor($declaringClass)->extractMethodBodies($declaringClass->name);
					if (isset($bodies[$declaringMethod->name])) {
						$m->setBody($bodies[$declaringMethod->name]);
					}
				}
			}

			$modifier = $declaringMethod->getModifiers() !== $method->getModifiers()
				? ' ' . $this->getVisibility($method)
				: null;
			$alias = $declaringMethod->name !== $method->name ? ' ' . $method->name : '';
			if ($modifier || $alias) {
				$resolutions[] = $declaringMethod->name . ' as' . $modifier . $alias;
			}
		}

		$class->setMethods($methods);

		foreach ($from->getTraitNames() as $trait) {
			$class->addTrait($trait, $resolutions);
			$resolutions = [];
		}

		$consts = $cases = [];
		foreach ($from->getReflectionConstants() as $const) {
			if ($class->isEnum() && $from->hasCase($const->name)) {
				$cases[] = $this->fromCaseReflection($const);
			} elseif ($const->getDeclaringClass()->name === $from->name) {
				$consts[] = $this->fromConstantReflection($const);
			}
		}

		if ($consts) {
			$class->setConstants($consts);
		}
		if ($cases) {
			$class->setCases($cases);
		}

		return $class;
	}


	public function fromMethodReflection(\ReflectionMethod $from): Method
	{
		$method = new Method($from->name);
		$method->setParameters(array_map([$this, 'fromParameterReflection'], $from->getParameters()));
		$method->setStatic($from->isStatic());
		$isInterface = $from->getDeclaringClass()->isInterface();
		$method->setVisibility($isInterface ? null : $this->getVisibility($from));
		$method->setFinal($from->isFinal());
		$method->setAbstract($from->isAbstract() && !$isInterface);
		$method->setReturnReference($from->returnsReference());
		$method->setVariadic($from->isVariadic());
		$method->setComment(Helpers::unformatDocComment((string) $from->getDocComment()));
		$method->setAttributes($this->getAttributes($from));
		$method->setReturnType((string) $from->getReturnType());

		return $method;
	}


	public function fromFunctionReflection(\ReflectionFunction $from, bool $withBody = false): GlobalFunction|Closure
	{
		$function = $from->isClosure() ? new Closure : new GlobalFunction($from->name);
		$function->setParameters(array_map([$this, 'fromParameterReflection'], $from->getParameters()));
		$function->setReturnReference($from->returnsReference());
		$function->setVariadic($from->isVariadic());
		if (!$from->isClosure()) {
			$function->setComment(Helpers::unformatDocComment((string) $from->getDocComment()));
		}

		$function->setAttributes($this->getAttributes($from));
		$function->setReturnType((string) $from->getReturnType());

		if ($withBody) {
			if ($from->isClosure()) {
				throw new Nette\NotSupportedException('The $withBody parameter cannot be used for closures.');
			}

			$function->setBody($this->getExtractor($from)->extractFunctionBody($from->name));
		}

		return $function;
	}


	public function fromCallable(callable $from): Method|GlobalFunction|Closure
	{
		$ref = Nette\Utils\Callback::toReflection($from);
		return $ref instanceof \ReflectionMethod
			? $this->fromMethodReflection($ref)
			: $this->fromFunctionReflection($ref);
	}


	public function fromParameterReflection(\ReflectionParameter $from): Parameter
	{
		$param = $from->isPromoted()
			? new PromotedParameter($from->name)
			: new Parameter($from->name);
		$param->setReference($from->isPassedByReference());
		$param->setType((string) $from->getType());

		if ($from->isDefaultValueAvailable()) {
			if ($from->isDefaultValueConstant()) {
				$parts = explode('::', $from->getDefaultValueConstantName());
				if (count($parts) > 1) {
					$parts[0] = Helpers::tagName($parts[0]);
				}

				$param->setDefaultValue(new Literal(implode('::', $parts)));
			} elseif (is_object($from->getDefaultValue())) {
				$param->setDefaultValue($this->fromObject($from->getDefaultValue()));
			} else {
				$param->setDefaultValue($from->getDefaultValue());
			}

			$param->setNullable($param->isNullable() && $param->getDefaultValue() !== null);
		}

		$param->setAttributes($this->getAttributes($from));
		return $param;
	}


	public function fromConstantReflection(\ReflectionClassConstant $from): Constant
	{
		$const = new Constant($from->name);
		$const->setValue($from->getValue());
		$const->setVisibility($this->getVisibility($from));
		$const->setFinal(PHP_VERSION_ID >= 80100 ? $from->isFinal() : false);
		$const->setComment(Helpers::unformatDocComment((string) $from->getDocComment()));
		$const->setAttributes($this->getAttributes($from));
		return $const;
	}


	public function fromCaseReflection(\ReflectionClassConstant $from): EnumCase
	{
		$const = new EnumCase($from->name);
		$const->setValue($from->getValue()->value ?? null);
		$const->setComment(Helpers::unformatDocComment((string) $from->getDocComment()));
		$const->setAttributes($this->getAttributes($from));
		return $const;
	}


	public function fromPropertyReflection(\ReflectionProperty $from): Property
	{
		$defaults = $from->getDeclaringClass()->getDefaultProperties();
		$prop = new Property($from->name);
		$prop->setValue($defaults[$prop->getName()] ?? null);
		$prop->setStatic($from->isStatic());
		$prop->setVisibility($this->getVisibility($from));
		$prop->setType((string) $from->getType());

		$prop->setInitialized($from->hasType() && array_key_exists($prop->getName(), $defaults));
		$prop->setReadOnly(PHP_VERSION_ID >= 80100 ? $from->isReadOnly() : false);
		$prop->setComment(Helpers::unformatDocComment((string) $from->getDocComment()));
		$prop->setAttributes($this->getAttributes($from));
		return $prop;
	}


	public function fromObject(object $obj): Literal
	{
		return new Literal('new \\' . $obj::class . '(/* unknown */)');
	}


	public function fromClassCode(string $code): ClassLike
	{
		$classes = $this->fromCode($code)->getClasses();
		return reset($classes) ?: throw new Nette\InvalidStateException('The code does not contain any class.');
	}


	public function fromCode(string $code): PhpFile
	{
		$reader = new Extractor($code);
		return $reader->extractAll();
	}


	private function getAttributes($from): array
	{
		return array_map(function ($attr) {
			$args = $attr->getArguments();
			foreach ($args as &$arg) {
				if (is_object($arg)) {
					$arg = $this->fromObject($arg);
				}
			}

			return new Attribute($attr->getName(), $args);
		}, $from->getAttributes());
	}


	private function getVisibility($from): string
	{
		return $from->isPrivate()
			? ClassLike::VisibilityPrivate
			: ($from->isProtected() ? ClassLike::VisibilityProtected : ClassLike::VisibilityPublic);
	}


	private function getExtractor($from): Extractor
	{
		$file = $from->getFileName();
		$cache = &$this->extractorCache[$file];
		if ($cache !== null) {
			return $cache;
		} elseif (!$file) {
			throw new Nette\InvalidStateException("Source code of $from->name not found.");
		}

		return new Extractor(file_get_contents($file));
	}
}
