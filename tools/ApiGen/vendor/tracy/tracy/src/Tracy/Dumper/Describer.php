<?php

/**
 * This file is part of the Tracy (https://tracy.nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Tracy\Dumper;

use Tracy;
use Tracy\Helpers;


/**
 * Converts PHP values to internal representation.
 * @internal
 */
final class Describer
{
	public const HiddenValue = '*****';

	// Number.MAX_SAFE_INTEGER
	private const JsSafeInteger = 1 << 53 - 1;

	public int $maxDepth = 7;
	public int $maxLength = 150;
	public int $maxItems = 100;

	/** @var Value[] */
	public array $snapshot = [];
	public bool $debugInfo = false;
	public array $keysToHide = [];

	/** @var (callable(string, mixed): bool)|null */
	public $scrubber;

	public bool $location = false;

	/** @var callable[] */
	public array $resourceExposers = [];

	/** @var array<string,callable> */
	public array $objectExposers = [];

	/** @var array<string, array{bool, string[]}> */
	public array $enumProperties = [];

	/** @var (int|\stdClass)[] */
	public array $references = [];


	public function describe(mixed $var): \stdClass
	{
		uksort($this->objectExposers, fn($a, $b): int => $b === '' || (class_exists($a, false) && is_subclass_of($a, $b)) ? -1 : 1);

		try {
			return (object) [
				'value' => $this->describeVar($var),
				'snapshot' => $this->snapshot,
				'location' => $this->location ? self::findLocation() : null,
			];

		} finally {
			$free = [[], []];
			$this->snapshot = &$free[0];
			$this->references = &$free[1];
		}
	}


	private function describeVar(mixed $var, int $depth = 0, ?int $refId = null): mixed
	{
		if ($var === null || is_bool($var)) {
			return $var;
		}

		$m = 'describe' . explode(' ', gettype($var))[0];
		return $this->$m($var, $depth, $refId);
	}


	private function describeInteger(int $num): Value|int
	{
		return $num <= self::JsSafeInteger && $num >= -self::JsSafeInteger
			? $num
			: new Value(Value::TypeNumber, "$num");
	}


	private function describeDouble(float $num): Value|float
	{
		if (!is_finite($num)) {
			return new Value(Value::TypeNumber, (string) $num);
		}

		$js = json_encode($num);
		return strpos($js, '.')
			? $num
			: new Value(Value::TypeNumber, "$js.0"); // to distinct int and float in JS
	}


	private function describeString(string $s, int $depth = 0): Value|string
	{
		$encoded = Helpers::encodeString($s, $depth ? $this->maxLength : null);
		if ($encoded === $s) {
			return $encoded;
		} elseif (Helpers::isUtf8($s)) {
			return new Value(Value::TypeStringHtml, $encoded, Helpers::utf8Length($s));
		} else {
			return new Value(Value::TypeBinaryHtml, $encoded, strlen($s));
		}
	}


	private function describeArray(array $arr, int $depth = 0, ?int $refId = null): Value|array
	{
		if ($refId) {
			$res = new Value(Value::TypeRef, 'p' . $refId);
			$value = &$this->snapshot[$res->value];
			if ($value && $value->depth <= $depth) {
				return $res;
			}

			$value = new Value(Value::TypeArray);
			$value->id = $res->value;
			$value->depth = $depth;
			if ($this->maxDepth && $depth >= $this->maxDepth) {
				$value->length = count($arr);
				return $res;
			} elseif ($depth && $this->maxItems && count($arr) > $this->maxItems) {
				$value->length = count($arr);
				$arr = array_slice($arr, 0, $this->maxItems, true);
			}

			$items = &$value->items;

		} elseif ($arr && $this->maxDepth && $depth >= $this->maxDepth) {
			return new Value(Value::TypeArray, null, count($arr));

		} elseif ($depth && $this->maxItems && count($arr) > $this->maxItems) {
			$res = new Value(Value::TypeArray, null, count($arr));
			$res->depth = $depth;
			$items = &$res->items;
			$arr = array_slice($arr, 0, $this->maxItems, true);
		}

		$items = [];
		foreach ($arr as $k => $v) {
			$refId = $this->getReferenceId($arr, $k);
			$items[] = [
				$this->describeVar($k, $depth + 1),
				$this->isSensitive((string) $k, $v)
					? new Value(Value::TypeText, self::hideValue($v))
					: $this->describeVar($v, $depth + 1, $refId),
			] + ($refId ? [2 => $refId] : []);
		}

		return $res ?? $items;
	}


	private function describeObject(object $obj, int $depth = 0): Value
	{
		$id = spl_object_id($obj);
		$value = &$this->snapshot[$id];
		if ($value && $value->depth <= $depth) {
			return new Value(Value::TypeRef, $id);
		}

		$value = new Value(Value::TypeObject, get_debug_type($obj));
		$value->id = $id;
		$value->depth = $depth;
		$value->holder = $obj; // to be not released by garbage collector in collecting mode
		if ($this->location) {
			$rc = $obj instanceof \Closure
				? new \ReflectionFunction($obj)
				: new \ReflectionClass($obj);
			if ($rc->getFileName() && ($editor = Helpers::editorUri($rc->getFileName(), $rc->getStartLine()))) {
				$value->editor = (object) ['file' => $rc->getFileName(), 'line' => $rc->getStartLine(), 'url' => $editor];
			}
		}

		if ($this->maxDepth && $depth < $this->maxDepth) {
			$value->items = [];
			$props = $this->exposeObject($obj, $value);
			foreach ($props ?? [] as $k => $v) {
				$this->addPropertyTo($value, (string) $k, $v, Value::PropertyVirtual, $this->getReferenceId($props, $k));
			}
		}

		return new Value(Value::TypeRef, $id);
	}


	/**
	 * @param  resource  $resource
	 */
	private function describeResource($resource, int $depth = 0): Value
	{
		$id = 'r' . (int) $resource;
		$value = &$this->snapshot[$id];
		if (!$value) {
			$type = is_resource($resource) ? get_resource_type($resource) : 'closed';
			$value = new Value(Value::TypeResource, $type . ' resource');
			$value->id = $id;
			$value->depth = $depth;
			$value->items = [];
			if (isset($this->resourceExposers[$type])) {
				foreach (($this->resourceExposers[$type])($resource) as $k => $v) {
					$value->items[] = [htmlspecialchars($k), $this->describeVar($v, $depth + 1)];
				}
			}
		}

		return new Value(Value::TypeRef, $id);
	}


	public function describeKey(string $key): Value|string
	{
		if (preg_match('#^[\w!\#$%&*+./;<>?@^{|}~-]{1,50}$#D', $key) && !preg_match('#^(true|false|null)$#iD', $key)) {
			return $key;
		}

		$value = $this->describeString($key);
		return is_string($value) // ensure result is Value
			? new Value(Value::TypeStringHtml, $key, Helpers::utf8Length($key))
			: $value;
	}


	public function addPropertyTo(
		Value $value,
		string $k,
		mixed $v,
		int $type = Value::PropertyVirtual,
		?int $refId = null,
		?string $class = null,
		?Value $described = null,
	): void
	{
		if ($value->depth && $this->maxItems && count($value->items ?? []) >= $this->maxItems) {
			$value->length = ($value->length ?? count($value->items)) + 1;
			return;
		}

		$class ??= $value->value;
		$value->items[] = [
			$this->describeKey($k),
			$type !== Value::PropertyVirtual && $this->isSensitive($k, $v, $class)
				? new Value(Value::TypeText, self::hideValue($v))
				: ($described ?? $this->describeVar($v, $value->depth + 1, $refId)),
			$type === Value::PropertyPrivate ? $class : $type,
		] + ($refId ? [3 => $refId] : []);
	}


	private function exposeObject(object $obj, Value $value): ?array
	{
		foreach ($this->objectExposers as $type => $dumper) {
			if (!$type || $obj instanceof $type) {
				return $dumper($obj, $value, $this);
			}
		}

		if ($this->debugInfo && method_exists($obj, '__debugInfo')) {
			return $obj->__debugInfo();
		}

		Exposer::exposeObject($obj, $value, $this);
		return null;
	}


	private function isSensitive(string $key, mixed $val, ?string $class = null): bool
	{
		return $val instanceof \SensitiveParameterValue
			|| ($this->scrubber !== null && ($this->scrubber)($key, $val, $class))
			|| isset($this->keysToHide[strtolower($key)])
			|| isset($this->keysToHide[strtolower($class . '::$' . $key)]);
	}


	private static function hideValue(mixed $val): string
	{
		if ($val instanceof \SensitiveParameterValue) {
			$val = $val->getValue();
		}

		return self::HiddenValue . ' (' . get_debug_type($val) . ')';
	}


	public function describeEnumProperty(string $class, string $property, mixed $value): ?Value
	{
		[$set, $constants] = $this->enumProperties["$class::$property"] ?? null;
		if (!is_int($value)
			|| !$constants
			|| !($constants = Helpers::decomposeFlags($value, $set, $constants))
		) {
			return null;
		}

		$constants = array_map(fn(string $const): string => str_replace("$class::", 'self::', $const), $constants);
		return new Value(Value::TypeNumber, implode(' | ', $constants) . " ($value)");
	}


	public function getReferenceId(array $arr, string|int $key): ?int
	{
		return ($rr = \ReflectionReference::fromArrayElement($arr, $key))
			? ($this->references[$rr->getId()] ??= count($this->references) + 1)
			: null;
	}


	/**
	 * Finds the location where dump was called. Returns [file, line, code]
	 */
	private static function findLocation(): ?array
	{
		foreach (debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS) as $item) {
			if (isset($item['class']) && ($item['class'] === self::class || $item['class'] === Tracy\Dumper::class)) {
				$location = $item;
				continue;
			} elseif (isset($item['function'])) {
				try {
					$reflection = isset($item['class'])
						? new \ReflectionMethod($item['class'], $item['function'])
						: new \ReflectionFunction($item['function']);
					if (
						$reflection->isInternal()
						|| preg_match('#\s@tracySkipLocation\s#', (string) $reflection->getDocComment())
					) {
						$location = $item;
						continue;
					}
				} catch (\ReflectionException $e) {
				}
			}

			break;
		}

		if (isset($location['file'], $location['line']) && is_file($location['file'])) {
			$lines = file($location['file']);
			$line = $lines[$location['line'] - 1];
			return [
				$location['file'],
				$location['line'],
				trim(preg_match('#\w*dump(er::\w+)?\(.*\)#i', $line, $m) ? $m[0] : $line),
			];
		}

		return null;
	}
}
