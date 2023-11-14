<?php
namespace Melbahja\Seo;

use Melbahja\Seo\Interfaces\SchemaInterface;

/**
 * @package Melbahja\Seo
 * @since v2.0
 * @see https://git.io/phpseo
 * @license MIT
 * @copyright 2019-present Mohamed Elabhja
 */
class Schema implements SchemaInterface
{

	protected $things = [];

	/**
	 * @param string               $type
	 * @param array                $data
	 * @param SchemaInterface|null $parent
	 * @param SchemaInterface|null $root
	 */
	public function __construct(SchemaInterface ...$things)
	{
		$this->things = $things;
	}


	/**
	 * Add schema item to the graph.
	 *
	 * @param SchemaInterface $thing
	 */
	public function add(SchemaInterface $thing): SchemaInterface
	{
		$this->things[] = $thing;
		return $this;
	}

	/**
	 * Get data as array
	 *
	 * @return array
	 */
	public function jsonSerialize(): array
	{
		return [
			'@context' => 'https://schema.org',
			'@graph'   => $this->things
		];
	}


	/**
	 * Serialize root schema
	 *
	 * @return string
	 */
	public function __toString(): string
	{
		return '<script type="application/ld+json">'. json_encode($this->jsonSerialize()) .'</script>';
	}

}
