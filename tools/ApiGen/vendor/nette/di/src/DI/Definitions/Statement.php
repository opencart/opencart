<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\DI\Definitions;

use Nette;
use Nette\Utils\Strings;


/**
 * Assignment or calling statement.
 *
 * @property string|array|Definition|Reference|null $entity
 */
final class Statement implements Nette\Schema\DynamicParameter
{
	use Nette\SmartObject;

	/** @var array */
	public $arguments;

	/** @var string|array|Definition|Reference|null */
	private $entity;


	/**
	 * @param  string|array|Definition|Reference|null  $entity
	 */
	public function __construct($entity, array $arguments = [])
	{
		if (
			$entity !== null
			&& !is_string($entity) // Class, @service, not, tags, types, PHP literal, entity::member
			&& !$entity instanceof Definition
			&& !$entity instanceof Reference
			&& !(is_array($entity)
				&& array_keys($entity) === [0, 1]
				&& (is_string($entity[0])
					|| $entity[0] instanceof self
					|| $entity[0] instanceof Reference
					|| $entity[0] instanceof Definition)
			)) {
			throw new Nette\InvalidArgumentException('Argument is not valid Statement entity.');
		}

		// normalize Class::method to [Class, method]
		if (is_string($entity) && Strings::contains($entity, '::') && !Strings::contains($entity, '?')) {
			$entity = explode('::', $entity, 2);
		}

		if (is_string($entity) && substr($entity, 0, 1) === '@') { // normalize @service to Reference
			$entity = new Reference(substr($entity, 1));
		} elseif (is_array($entity) && is_string($entity[0]) && substr($entity[0], 0, 1) === '@') {
			$entity[0] = new Reference(substr($entity[0], 1));
		}

		$this->entity = $entity;
		$this->arguments = $arguments;
	}


	/** @return string|array|Definition|Reference|null */
	public function getEntity()
	{
		return $this->entity;
	}
}


class_exists(Nette\DI\Statement::class);
