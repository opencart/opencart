<?php
/**
 * @package   OpenCart
 * @author    Daniel Kerr
 * @copyright Copyright (c) 2005 - 2022, OpenCart, Ltd. (https://www.opencart.com/)
 * @license   https://opensource.org/licenses/GPL-3.0
 * @author    Daniel Kerr
 * @see       https://www.opencart.com
 */

/**
 * URL class.
 */
namespace Opencart\System\Library;
class Url {
	private string $url;
	private array $rewrite = [];

	/**
	 * Constructor.
	 *
	 * @param 	string 	$url
	 */
	public function __construct(string $url) {
		$this->url = $url;
	}

	/**
	 * Add a rewrite method to the URL system
	 *
	 * @param	object	$rewrite
	 *
	 * @return 	void
	 */
	public function addRewrite(\Opencart\System\Engine\Controller $rewrite): void {
		$this->rewrite[] = $rewrite;
	}

	/**
	 * Generates a URL
	 *
	 * @param 	string        	$route
	 * @param 	string|array	$args
	 * @param 	bool			$js
	 *
	 * @return string
	 */
	public function link(string $route, string|array $args = '', bool $js = false): string {
		$url = $this->url . 'index.php?route=' . $route;

		if ($args) {
			if (is_array($args)) {
				$url .= '&' . http_build_query($args);
			} else {
				$url .= '&' . trim($args, '&');
			}
		}

		foreach ($this->rewrite as $rewrite) {
			$url = $rewrite->rewrite($url);
		}

		if (!$js) {
			return str_replace('&', '&amp;', $url);
		} else {
			return $url;
		}
	}
}
