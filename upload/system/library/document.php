<?php
/**
 * @package		OpenCart
 * @author		Daniel Kerr
 * @copyright	Copyright (c) 2005 - 2021, OpenCart, Ltd. (https://www.opencart.com/)
 * @license		https://opensource.org/licenses/GPL-3.0
 * @link		https://www.opencart.com
*/

namespace Opencart\System\Library;
class Document {
	private $title;
	private $description;
	private $keywords;
	private $links = [];
	private $styles = [];
	private $scripts = [];

	public function setTitle(string $title) {
		$this->title = $title;
	}

	public function getTitle() {
		return $this->title;
	}

	public function setDescription(string $description) {
		$this->description = $description;
	}

	public function getDescription() {
		return $this->description;
	}

	public function setKeywords(string $keywords) {
		$this->keywords = $keywords;
	}

	public function getKeywords() {
		return $this->keywords;
	}

	public function addLink(string $href, string $rel) {
		$this->links[$href] = [
			'href' => $href,
			'rel'  => $rel
		];
	}

	public function getLinks() {
		return $this->links;
	}

	public function addStyle($href, $params = array()) {
		if ($params) {
			$new_params = false;

			foreach ($params as $key => $param) {
				$new_params = ($new_params ? $new_params : false) . $key . '="' . $param . '"';
			}

			$params = $new_params;
		} else {
			$params = 'rel="stylesheet" media="screen"';
		}

		$this->styles[$href] = array(
			'href'   => $href,
			'params' => $params,
		);
	}

	public function getStyles() {
		return $this->styles;
	}

	public function addScript($href, $postion = 'header', $params = array()) {
		if ($params) {
			$new_params = false;

			foreach ($params as $key => $param) {
				$new_params = ($new_params ? $new_params : false) . $key . '="' . $param . '"';
			}

			$params = $new_params;
		} else {
			$params = '';
		}

		$this->scripts[$postion][$href] = array(
			'href'   => $href,
			'params' => $params,
		);
	}

	public function getScripts($position = 'header') {
		if (isset($this->scripts[$position])) {
			return $this->scripts[$position];
		} else {
			return [];
		}
	}
}
