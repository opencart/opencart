<?php
namespace Melbahja\Seo\Schema;

use Melbahja\Seo\Interfaces\SchemaInterface;


/**
 * @package Melbahja\Seo
 * @since v2.0
 * @see https://git.io/phpseo
 * @license MIT
 * @copyright 2019-present Mohamed Elabhja
 */
class Thing implements SchemaInterface
{

	protected $type;
	protected $data    = [];
	public    $context = null;


	public function __construct(string $type, array $data = [])
	{
		$this->data = $data;
		$this->type = $type;
	}

	public function __get(string $name)
	{
		return $this->data[$name] ?? null;
	}


	public function __set(string $name, $value)
	{
		$this->data[$name] = $value;
	}

	public function jsonSerialize(): array
	{
		$data = [
			'@type' => $this->type,
			'@context' => $this->context ?? "https://schema.org/",
		];

		return array_merge($this->data, $data);
	}

	public function __toString(): string
	{
		return '<script type="application/ld+json">'. json_encode($this) . '</script>';
	}
}
