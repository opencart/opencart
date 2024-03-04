<?php declare(strict_types = 1);

namespace ApiGen;

use ApiGen\Index\FileIndex;
use ApiGen\Index\Index;
use ApiGen\Index\NamespaceIndex;
use ApiGen\Info\ClassInfo;
use ApiGen\Info\ClassLikeInfo;
use ApiGen\Info\EnumInfo;
use ApiGen\Info\FunctionInfo;
use ApiGen\Info\InterfaceInfo;
use ApiGen\Info\MissingInfo;
use ApiGen\Info\NameInfo;
use ApiGen\Info\TraitInfo;

use function array_filter;
use function array_keys;
use function array_map;
use function count;
use function implode;
use function ksort;


class Indexer
{
	public function indexFile(Index $index, ?string $file, bool $primary): void
	{
		if ($file === null) {
			$file = '';
		}

		if (isset($index->files[$file])) {
			$index->files[$file]->primary = $index->files[$file]->primary || $primary;
			return;
		}

		$index->files[$file] = new FileIndex($file, $primary);
	}


	public function indexNamespace(Index $index, string $namespace, string $namespaceLower, bool $primary, bool $deprecated): void
	{
		if (isset($index->namespace[$namespaceLower])) {
			if ($primary) {
				for (
					$info = $index->namespace[$namespaceLower];
					$info !== null && !$info->primary;
					$info = $info->name->fullLower === '' ? null : $index->namespace[$info->name->namespaceLower]
				) {
					$info->primary = true;
				}
			}

			if (!$deprecated) {
				for (
					$info = $index->namespace[$namespaceLower];
					$info !== null && $info->deprecated;
					$info = $info->name->fullLower === '' ? null : $index->namespace[$info->name->namespaceLower]
				) {
					$info->deprecated = false;
				}
			}

			return;
		}

		$info = new NamespaceIndex(new NameInfo($namespace, $namespaceLower), $primary, $deprecated);

		if ($namespaceLower !== '') {
			$primary = $primary && $info->name->namespaceLower !== '';
			$deprecated = $deprecated && $info->name->namespaceLower !== '';
			$this->indexNamespace($index, $info->name->namespace, $info->name->namespaceLower, $primary, $deprecated);
		}

		$index->namespace[$namespaceLower] = $info;
		$index->namespace[$info->name->namespaceLower]->children[$info->name->shortLower] = $info;
	}


	public function indexClassLike(Index $index, ClassLikeInfo $info): void
	{
		$index->classLike[$info->name->fullLower] = $info;

		foreach ($info->constants as $constantName => $_) {
			$index->constants[$constantName][] = $info;
		}

		foreach ($info->properties as $propertyName => $_) {
			$index->properties[$propertyName][] = $info;
		}

		foreach ($info->methods as $methodLowerName => $_) {
			$index->methods[$methodLowerName][] = $info;
		}

		if ($info instanceof ClassInfo) {
			$this->indexClass($info, $index);

		} elseif ($info instanceof InterfaceInfo) {
			$this->indexInterface($info, $index);

		} elseif ($info instanceof TraitInfo) {
			$this->indexTrait($info, $index);

		} elseif ($info instanceof EnumInfo) {
			$this->indexEnum($info, $index);

		} elseif ($info instanceof MissingInfo) {
			$this->indexMissing($info, $index);

		} else {
			throw new \LogicException();
		}
	}


	public function indexFunction(Index $index, FunctionInfo $info): void
	{
		$index->function[$info->name->fullLower] = $info;
		$index->files[$info->file ?? '']->function[$info->name->fullLower] = $info;
		$index->namespace[$info->name->namespaceLower]->function[$info->name->shortLower] = $info;
	}


	public function postProcess(Index $index): void
	{
		// DAG
		$this->indexDirectedAcyclicGraph($index);

		// instance of
		foreach ([$index->class, $index->interface, $index->enum] as $infos) {
			foreach ($infos as $info) {
				$this->indexInstanceOf($index, $info);
			}
		}

		// exceptions
		foreach ($index->namespace as $namespaceIndex) {
			foreach ($namespaceIndex->class as $info) {
				if ($info->isThrowable($index)) {
					unset($namespaceIndex->class[$info->name->shortLower]);
					$namespaceIndex->exception[$info->name->shortLower] = $info;
				}
			}
		}

		// method overrides & implements
		foreach ($index->classExtends[''] ?? [] as $info) {
			$this->indexClassMethodOverrides($index, $info, [], []);
		}

		foreach ($index->enum as $info) {
			$this->indexClassMethodOverrides($index, $info, [], []);
		}

		// sort
		$this->sort($index);
	}


	protected function indexClass(ClassInfo $info, Index $index): void
	{
		$index->class[$info->name->fullLower] = $info;
		$index->files[$info->file ?? '']->classLike[$info->name->fullLower] = $info;
		$index->namespace[$info->name->namespaceLower]->class[$info->name->shortLower] = $info;
		$index->classExtends[$info->extends ? $info->extends->fullLower : ''][$info->name->fullLower] = $info;

		foreach ($info->implements as $interfaceNameLower => $interfaceName) {
			$index->classImplements[$interfaceNameLower][$info->name->fullLower] = $info;
		}

		foreach ($info->uses as $traitNameLower => $traitName) {
			$index->classUses[$traitNameLower][$info->name->fullLower] = $info;
		}
	}


	protected function indexInterface(InterfaceInfo $info, Index $index): void
	{
		$index->interface[$info->name->fullLower] = $info;
		$index->files[$info->file ?? '']->classLike[$info->name->fullLower] = $info;
		$index->namespace[$info->name->namespaceLower]->interface[$info->name->shortLower] = $info;

		if ($info->extends) {
			foreach ($info->extends as $interfaceNameLower => $interfaceName) {
				$index->interfaceExtends[$interfaceNameLower][$info->name->fullLower] = $info;
			}

		} else {
			$index->interfaceExtends[''][$info->name->fullLower] = $info;
		}
	}


	protected function indexTrait(TraitInfo $info, Index $index): void
	{
		$index->trait[$info->name->fullLower] = $info;
		$index->files[$info->file ?? '']->classLike[$info->name->fullLower] = $info;
		$index->namespace[$info->name->namespaceLower]->trait[$info->name->shortLower] = $info;
	}


	protected function indexEnum(EnumInfo $info, Index $index): void
	{
		$index->enum[$info->name->fullLower] = $info;
		$index->files[$info->file ?? '']->classLike[$info->name->fullLower] = $info;
		$index->namespace[$info->name->namespaceLower]->enum[$info->name->shortLower] = $info;

		foreach ($info->implements as $interfaceNameLower => $interfaceName) {
			$index->enumImplements[$interfaceNameLower][$info->name->fullLower] = $info;
		}
	}


	protected function indexMissing(MissingInfo $info, Index $index): void
	{
		// nothing to index
	}


	protected function indexDirectedAcyclicGraph(Index $index): void
	{
		$edgeGroups = [
			'class extends' => $index->classExtends,
			'class implements' => $index->classImplements,
			'class uses' => $index->classUses,
			'interface extends' => $index->interfaceExtends,
			'enum implements' => $index->enumImplements,
		];

		$dag = [];
		foreach ($edgeGroups as $edgeGroup) {
			foreach ($edgeGroup as $classLikeNameA => $classLikeGroup) {
				foreach ($classLikeGroup as $classLikeNameB => $classLike) {
					if (isset($dag[$classLikeNameA][$classLikeNameB])) {
						$classLikeA = $index->classLike[$classLikeNameA];
						$classLikeB = $index->classLike[$classLikeNameB];
						$duplicateEdgeGroups = array_filter($edgeGroups, fn(array $edgeGroup) => isset($edgeGroup[$classLikeNameA][$classLikeNameB]));
						$note = '(used both as \'' . implode('\' and \'', array_keys($duplicateEdgeGroups)) . '\')';
						$path = "{$classLikeB->name->full} -> {$classLikeA->name->full}";
						throw new \RuntimeException("Invalid directed acyclic graph because it contains duplicate edge {$note}:\n{$path}");
					}

					$dag[$classLikeNameA][$classLikeNameB] = $classLike;
				}
			}
		}

		$findCycle = static function (array $node, array $visited) use ($index, $dag, &$findCycle): void {
			foreach ($node as $childKey => $_) {
				if (isset($visited[$childKey])) {
					$path = [...array_keys($visited), $childKey];
					$path = array_map(fn(string $item) => $index->classLike[$item]->name->full, $path);
					throw new \RuntimeException("Invalid directed acyclic graph because it contains cycle:\n" . implode(' -> ', $path));

				} else {
					$findCycle($dag[$childKey] ?? [], $visited + [$childKey => true]);
				}
			}
		};

		foreach ($dag as $nodeKey => $node) {
			$findCycle($node, [$nodeKey => true]);
		}

		$index->dag = $dag;
	}


	protected function indexInstanceOf(Index $index, ClassLikeInfo $info): void
	{
		if (isset($index->instanceOf[$info->name->fullLower])) {
			return; // already computed
		}

		$index->instanceOf[$info->name->fullLower] = [$info->name->fullLower => $info];
		foreach ([$index->classExtends, $index->classImplements, $index->interfaceExtends, $index->enumImplements] as $edges) {
			foreach ($edges[$info->name->fullLower] ?? [] as $childInfo) {
				$this->indexInstanceOf($index, $childInfo);
				$index->instanceOf[$info->name->fullLower] += $index->instanceOf[$childInfo->name->fullLower];
			}
		}
	}


	/**
	 * @param ClassLikeInfo[] $normal   indexed by [methodName]
	 * @param ClassLikeInfo[] $abstract indexed by [methodName]
	 */
	protected function indexClassMethodOverrides(Index $index, ClassInfo|EnumInfo $info, array $normal, array $abstract): void
	{
		$stack = array_keys($info->implements);
		$stackIndex = count($stack);

		while ($stackIndex > 0) {
			$interfaceNameLower = $stack[--$stackIndex];
			$interface = $index->interface[$interfaceNameLower] ?? null;

			if ($interface !== null) {
				foreach ($interface->methods as $method) {
					$abstract[$method->nameLower] = $interface;
				}

				foreach ($interface->extends as $extendLower => $extend) {
					$stack[$stackIndex++] = $extendLower;
				}
			}
		}

		foreach ($info->methods as $method) {
			if ($method->private) {
				continue;
			}

			if (isset($normal[$method->nameLower])) {
				$ancestor = $normal[$method->nameLower];
				$index->methodOverrides[$info->name->fullLower][$method->nameLower][] = $ancestor;
				$index->methodOverriddenBy[$ancestor->name->fullLower][$method->nameLower][] = $info;
			}

			if (isset($abstract[$method->nameLower])) {
				$ancestor = $abstract[$method->nameLower];
				$index->methodImplements[$info->name->fullLower][$method->nameLower][] = $ancestor;
				$index->methodImplementedBy[$ancestor->name->fullLower][$method->nameLower][] = $info;
			}

			if ($method->abstract) {
				$abstract[$method->nameLower] = $info;

			} else {
				unset($abstract[$method->nameLower]);
				$normal[$method->nameLower] = $info;
			}
		}

		foreach ($index->classExtends[$info->name->fullLower] ?? [] as $child) {
			$this->indexClassMethodOverrides($index, $child, $normal, $abstract);
		}
	}


	protected function sort(Index $index): void
	{
		ksort($index->files);
		ksort($index->namespace);
		ksort($index->classLike);
		ksort($index->class);
		ksort($index->interface);
		ksort($index->trait);
		ksort($index->enum);

		foreach ($index->classExtends as &$arr) {
			ksort($arr);
		}

		foreach ($index->classImplements as &$arr) {
			ksort($arr);
		}

		foreach ($index->classUses as &$arr) {
			ksort($arr);
		}

		foreach ($index->interfaceExtends as &$arr) {
			ksort($arr);
		}

		foreach ($index->enumImplements as &$arr) {
			ksort($arr);
		}

		foreach ($index->namespace as $namespaceIndex) {
			ksort($namespaceIndex->class);
			ksort($namespaceIndex->interface);
			ksort($namespaceIndex->trait);
			ksort($namespaceIndex->enum);
			ksort($namespaceIndex->exception);
			ksort($namespaceIndex->children);
		}

		// move root namespace to end
		if (isset($index->namespace[''])) {
			$rootNamespace = $index->namespace[''];
			unset($index->namespace[''], $rootNamespace->children['']);
			$index->namespace[''] = $rootNamespace;
			$rootNamespace->children[''] = $rootNamespace;
		}
	}
}
