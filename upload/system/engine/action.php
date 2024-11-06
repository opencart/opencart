<?php
/**
 * @package     OpenCart
 *
 * @author      Daniel Kerr
 * @copyright   Copyright (c) 2005 - 2017, OpenCart, Ltd. (https://www.opencart.com/)
 * @license     https://opensource.org/licenses/GPL-3.0
 *
 * @see        https://www.opencart.com
 */
namespace Opencart\System\Engine;
/**
 * Class Action
 *
 * @package Opencart\System\Engine
 */
class Action {
	/**
	 * @var string
	 */
	private string $route;

	/**
	 * Constructor
	 *
	 * @param string $route
	 */
	public function __construct(string $route) {
		$this->route = preg_replace('/[^a-zA-Z0-9_|\/\.]/', '', $route);

		if (strrpos($route, '.') == false) {
			$this->route .= '.index';
		}
	}

	/**
	 * Get Id
	 *
	 * @return string
	 */
	public function getId(): string {
		return $this->route;
	}

	/**
	 * Execute
	 *
	 * @param \Opencart\System\Engine\Registry $registry
	 * @param array<mixed>                     $args
	 *
	 * @return mixed
	 */
	public function execute(\Opencart\System\Engine\Registry $registry, array &$args = []) {
		return $registry->load->execute('controller/' . $this->route, $args);
	}
}
