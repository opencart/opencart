<?php

namespace Tools\PHPStan;

use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\PropertiesClassReflectionExtension;
use PHPStan\Reflection\PropertyReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\Generic\GenericObjectType;
use PHPStan\Type\NullType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\TypeCombinator;
use Registry;

class RegistryPropertyReflectionExtension implements PropertiesClassReflectionExtension {
	public function __construct(private ReflectionProvider $reflectionProvider) {}

	public function hasProperty(ClassReflection $classReflection, string $propertyName): bool {
		if (!$classReflection->is(Registry::class)) {
			return false;
		}

		return preg_match('/^model_.+$/', $propertyName, $matches) === 1;
	}

	public function getProperty(ClassReflection $classReflection, string $propertyName): PropertyReflection {
		preg_match('/^(model_.+)$/', $propertyName, $matches);
		$className = $this->convertSnakeToStudly($matches[1]);

		$type = new NullType();
		if ($this->reflectionProvider->hasClass($className)) {
			$found = new ObjectType($className);
			$type = new GenericObjectType('\Proxy', [$found]);
			$type = TypeCombinator::addNull($type);
		}

		return new LoadedProperty($classReflection, $type);
	}

	private function convertSnakeToStudly(string $value): string {
		return str_replace(' ', '', ucwords(str_replace('_', ' ', $value)));
	}
}
