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
	private string $title = '';
	private string $description = '';
	private string $keywords = '';
	private array $links = [];
	private array $styles = [];
	private array $scripts = [];

	/**
     *
     *
     * @param	string	$title
     */
	public function setTitle(string $title): void {
		$this->title = $title;
	}

	/**
     *
	 *
	 * @return	string
     */
	public function getTitle(): string {
		return $this->title;
	}

	/**
     *
     *
     * @param	string	$description
     */
	public function setDescription(string $description): void {
		$this->description = $description;
	}

	/**
     *
     *
     * @param	string	$description
	 *
	 * @return	string
     */
	public function getDescription(): string {
		return $this->description;
	}

	/**
     *
     *
     * @param	string	$keywords
     */
	public function setKeywords(string $keywords): void {
		$this->keywords = $keywords;
	}

	/**
     *
	 *
	 * @return	string
     */
	public function getKeywords(): string {
		return $this->keywords;
	}

	/**
     *
     *
     * @param	string	$href
	 * @param	string	$rel
     */
	public function addLink(string $href, string $rel): void {
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
	public function addStyle(string $href, $rel = 'stylesheet', $media = 'screen', $sort = 1): void {
		$this->styles[$href] = [
			'href'  => $href,
			'rel'   => $rel,
			'media' => $media,
			'sort'  => $sort
		];
	}

	/**
     *
	 *
	 * @return	array
     */
	public function getStyles(): array {
		$sort_order = [];

		foreach ($this->styles as $key => $value) {
			$sort_order[$key] = $value['sort'];
		}

		array_multisort($sort_order, SORT_ASC, $this->styles);

		return $this->styles;
	}

	/**
     *
     *
     * @param	string	$href
	 * @param	string	$position
     */
	public function addScript(string $href, $position = 'header', $sort = 1): void {
		$this->scripts[$position][$href] = [
			'href' => $href,
			'sort' => $sort
		];
	}

	/**
     *
     *
     * @param	string	$position
	 *
	 * @return	array
     */
	public function getScripts($position = 'header'): array {
		if (isset($this->scripts[$position])) {
			$sort_order = [];

			foreach ($this->scripts[$position] as $key => $value) {
				$sort_order[$key] = $value['sort'];
			}

			array_multisort($sort_order, SORT_ASC, $this->scripts[$position]);

			return $this->scripts[$position];
		} else {
			return [];
		}
	}
}
