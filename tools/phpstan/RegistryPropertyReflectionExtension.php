<?php

namespace Tools\PHPStan;

use Opencart\System\Engine\Registry;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\PropertiesClassReflectionExtension;
use PHPStan\Reflection\PropertyReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\Generic\GenericObjectType;
use PHPStan\Type\NullType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\TypeCombinator;

class RegistryPropertyReflectionExtension implements PropertiesClassReflectionExtension {
	private const DEFAULTS = [
		'config'     => \Opencart\System\Engine\Config::class,
		'event'      => \Opencart\System\Engine\Event::class,
		'factory'    => \Opencart\System\Engine\Factory::class,
		'load'       => \Opencart\System\Engine\Loader::class,
		'autoloader' => \Opencart\System\Engine\Autoloader::class,
		'cache'      => \Opencart\System\Library\Cache::class,
		'cart'       => \Opencart\System\Library\Cart\Cart::class,
		'currency'   => \Opencart\System\Library\Cart\Currency::class,
		'customer'   => \Opencart\System\Library\Cart\Customer::class,
		'length'     => \Opencart\System\Library\Cart\Length::class,
		'tax'        => \Opencart\System\Library\Cart\Tax::class,
		'weight'     => \Opencart\System\Library\Cart\Weight::class,
		'db'         => \Opencart\System\Library\DB::class,
		'document'   => \Opencart\System\Library\Document::class,
		'language'   => \Opencart\System\Library\Language::class,
		'log'        => \Opencart\System\Library\Log::class,
		'request'    => \Opencart\System\Library\Request::class,
		'response'   => \Opencart\System\Library\Response::class,
		'session'    => \Opencart\System\Library\Session::class,
		'template'   => \Opencart\System\Library\Template::class,
		'url'        => \Opencart\System\Library\Url::class,
		'user'       => \Opencart\System\Library\Cart\User::class,
	];

	public function __construct(private ReflectionProvider $reflectionProvider) {}

	public function hasProperty(ClassReflection $classReflection, string $propertyName): bool {
		if (!$classReflection->is(Registry::class)) {
			return false;
		}

		if (isset(self::DEFAULTS[$propertyName])) {
			return true;
		}

		return preg_match('/^(controller|model)_(.+)$/', $propertyName, $matches) === 1;
	}

	public function getProperty(ClassReflection $classReflection, string $propertyName): PropertyReflection {
		if (isset(self::DEFAULTS[$propertyName])) {
			$type = new ObjectType(self::DEFAULTS[$propertyName]);
			$writable = false;
			if ($propertyName === 'user') {
				$writable = true;
				$type = TypeCombinator::addNull($type);
			}

			return new LoadedProperty($classReflection, $type, $writable);
		}

		preg_match('/^(controller|model)_(.+)$/', $propertyName, $matches);
		$classType = $this->convertSnakeToStudly($matches[1]);
		$commonName = $this->convertSnakeToStudly($matches[2]);

		$type = null;
		foreach (['Admin', 'Catalog', 'Install'] as $domain) {
			$className1 = '\Opencart\\' . $domain . '\\' . $classType . '\\' . $commonName;
			$className2 = preg_replace('/\\\(?=[^\\\]+$)/', '', $className1, 1);
			foreach ([$className1, $className2] as $className) {
				if ($this->reflectionProvider->hasClass($className)) {
					$found = new ObjectType($className);
					if ($classType === 'Model') {
						$found = new GenericObjectType('\Opencart\System\Engine\Proxy', [$found]);
					}
					$type = $type ? TypeCombinator::union($type, $found) : $found;
				}
			}
		}
		if ($type) {
			$type = TypeCombinator::addNull($type);
		} else {
			$type = new NullType();
		}

		return new LoadedProperty($classReflection, $type);
	}

	private function convertSnakeToStudly(string $value): string {
		return str_replace(' ', '\\', ucwords(str_replace('_', ' ', $value)));
	}
}
