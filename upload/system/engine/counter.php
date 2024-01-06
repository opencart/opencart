<?php
/**
 * @package		OpenCart
 *
 * @author		Daniel Kerr
 * @copyright	Copyright (c) 2005 - 2022, OpenCart, Ltd. (https://www.opencart.com/)
 * @license		https://opensource.org/licenses/GPL-3.0
 *
 * @see		https://www.opencart.com
 */
namespace Opencart\System\Engine;
/**
 * Class Counter
 */
class Counter {
	/**
	 * @var array<string, mixed>
	 */
	public array $totals = [];
	/**
	 * @var array<int, float>
	 */
	public array $taxes;
	/**
	 * @var float
	 */
	public float $total = 0.0;

	/**
	 * Constructor
	 *
	 * @param array<int, float> $taxes
	 */
	public function __construct(array $taxes) {
		$this->taxes = $taxes;
	}
}
