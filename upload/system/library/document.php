<?php
/**
 * @package		OpenCart
 * @author		Daniel Kerr
 * @copyright	Copyright (c) 2005 - 2017, OpenCart, Ltd. (https://www.opencart.com/)
 * @license		https://opensource.org/licenses/GPL-3.0
 * @link		https://www.opencart.com
*/

/**
* Document class
*/
namespace Opencart\System\Library;
class Document {
	private $title;
	private $description;
	private $keywords;
	private $links = [];
	private $styles = [];
	private $scripts = [];

	/**
     *
     *
     * @param	string	$title
     */
	public function setTitle(string $title) {
		$this->title = $title;
	}

	/**
     *
	 *
	 * @return	string
     */
	public function getTitle() {
		return $this->title;
	}

	/**
     *
     *
     * @param	string	$description
     */
	public function setDescription(string $description) {
		$this->description = $description;
	}

	/**
     *
     *
     * @param	string	$description
	 *
	 * @return	string
     */
	public function getDescription() {
		return $this->description;
	}

	/**
     *
     *
     * @param	string	$keywords
     */
	public function setKeywords(string $keywords) {
		$this->keywords = $keywords;
	}

	/**
     *
	 *
	 * @return	string
     */
	public function getKeywords() {
		return $this->keywords;
	}

	/**
     *
     *
     * @param	string	$href
	 * @param	string	$rel
     */
	public function addLink(string $href, string $rel) {
		$this->links[$href] = [
			'href' => $href,
			'rel'  => $rel
		];
	}

	/**
     *
	 *
	 * @return	array
     */
	public function getLinks() {
		return $this->links;
	}

	/**
     *
     *
     * @param	string	$href
	 * @param	string	$rel
	 * @param	string	$media
     */
	public function addStyle(string $href, $rel = 'stylesheet', $media = 'screen') {
		$this->styles[$href] = [
			'href'  => $href,
			'rel'   => $rel,
			'media' => $media
		];
	}

	/**
     *
	 *
	 * @return	array
     */
	public function getStyles() {
		return $this->styles;
	}

	/**
     *
     *
     * @param	string	$href
	 * @param	string	$position
     */
	public function addScript(string $href, $position = 'header') {
		$this->scripts[$position][$href] = $href;
	}

	/**
     *
     *
     * @param	string	$position
	 *
	 * @return	array
     */
	public function getScripts($position = 'header') {
		if (isset($this->scripts[$position])) {
			return $this->scripts[$position];
		} else {
			return [];
		}
	}
}
