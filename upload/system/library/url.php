<?php
/**
 * @package   OpenCart
 *
 * @author    Daniel Kerr
 * @copyright Copyright (c) 2005 - 2022, OpenCart, Ltd. (https://www.opencart.com/)
 * @license   https://opensource.org/licenses/GPL-3.0
 * @author    Daniel Kerr
 *
 * @see       https://www.opencart.com
 */
namespace Opencart\System\Library;
/**
 * Class URL
 */
class Url {
	/**
	 * @var string
	 */
	private string $url;
	private string $scheme;
	private string $host;
	private int $port;
	private string $path;
	private string $query;
	private string $fragment;

	/**
	 * @var array<int, object>
	 */
	private array $rewrite = [];

	/**
	 * Constructor
	 *
	 * @param string $url
	 */
	public function __construct(string $url) {
		$this->url = $url;

		$parts = parse_url($url);

		foreach ($parts as $key => $value) {
			$this->{$key} = $value;
		}
	}

	/**
	 * Add Rewrite
	 *
	 * Add a rewrite method to the URL system
	 *
	 * @param \Opencart\System\Engine\Controller $rewrite
	 *
	 * @return void
	 */
	public function addRewrite(object $rewrite): void {
		if (is_callable([$rewrite, 'rewrite'])) {
			$this->rewrite[] = $rewrite;
		}
	}

	/**
	 * Link
	 *
	 * Generates a URL
	 *
	 * @param string $route
	 * @param mixed  $args
	 * @param bool   $js
	 *
	 * @return string
	 */
	public function link(string $route, $args = '', bool $js = false): string {
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

		// See https://stackoverflow.com/questions/78729429/403-forbidden-when-url-contains-get-with-encoded-question-mark-unsafeallow3f
		// https://github.com/opencart/opencart/issues/14202
		$url = str_replace('%3F', '?', $url);

		if (!$js) {
			return str_replace('&', '&amp;', $url);
		} else {
			return $url;
		}
	}
}
