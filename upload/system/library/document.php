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
namespace Opencart\System\Library;
/**
 * Class Document
 */
class Document {
	/**
	 * @var string
	 */
	private string $title = '';
	/**
	 * @var string
	 */
	private string $description = '';
	/**
	 * @var string
	 */
	private string $keywords = '';
	/**
	 * @var array<string, array<string, string>>
	 */
	private array $links = [];
	/**
	 * @var array<string, array<string, string>>
	 */
	private array $styles = [];
	/**
	 * @var array<string, array<string, array<string, string>>>
	 */
	private array $scripts = [];
	/**
	 * @var array<int, array<string, string>> Meta tags with their attributes
	 */
	private array $metas = [];

	/**
	 * Set Title
	 *
	 * @param string $title
	 *
	 * @return void
	 */
	public function setTitle(string $title): void {
		$this->title = $title;
	}

	/**
	 * Get Title
	 *
	 * @return string
	 */
	public function getTitle(): string {
		return $this->title;
	}

	/**
	 * Set Description
	 *
	 * @param string $description
	 *
	 * @return void
	 */
	public function setDescription(string $description): void {
		$this->description = $description;
	}

	/**
	 * Get Description
	 *
	 * @return string
	 */
	public function getDescription(): string {
		return $this->description;
	}

	/**
	 * Set Keywords
	 *
	 * @param string $keywords
	 */
	public function setKeywords(string $keywords): void {
		$this->keywords = $keywords;
	}

	/**
	 * Get Keywords
	 *
	 * @return string
	 */
	public function getKeywords(): string {
		return $this->keywords;
	}

	/**
	 * Add Link
	 *
	 * @param string $href
	 * @param string $rel
	 *
	 * @return void
	 */
	public function addLink(string $href, string $rel): void {
		$this->links[$href] = [
			'href' => $href,
			'rel'  => $rel
		];
	}

	/**
	 * Get Links
	 *
	 * @return array<string, array<string, string>>
	 */
	public function getLinks(): array {
		return $this->links;
	}

	/**
	 * Add Style
	 *
	 * @param string $href
	 * @param string $rel
	 * @param string $media
	 *
	 * @return void
	 */
	public function addStyle(string $href, string $rel = 'stylesheet', string $media = 'screen'): void {
		$this->styles[$href] = [
			'href'  => $href,
			'rel'   => $rel,
			'media' => $media
		];
	}

	/**
	 * Get Styles
	 *
	 * @return array<string, array<string, string>>
	 */
	public function getStyles(): array {
		return $this->styles;
	}

	/**
	 * Add Script
	 *
	 * @param string $href
	 * @param string $position
	 *
	 * @return void
	 */
	public function addScript(string $href): void {
		$this->scripts[$href] = ['href'  => $href];
	}

	/**
	 * Get Scripts
	 *
	 * @param string $position
	 *
	 * @return array<string, array<string, string>>
	 */
	public function getScripts(): array {
		return $this->scripts;
	}

	/**
	 * Add Meta
	 *
	 * Adds a meta tag with specified attributes to the document.
	 *
	 * @param array<string, string> $attributes Associative array of meta tag attributes
	 *                                          Common attributes:
	 *                                          - 'name' => 'description' (for standard meta tags)
	 *                                          - 'property' => 'og:title' (for Open Graph)
	 *                                          - 'content' => 'The content value'
	 *                                          - 'media' => '(prefers-color-scheme: dark)' (for conditional meta tags)
	 *
	 * @return void
	 *
	 * @example
	 * $this->document->addMeta(['name' => 'description', 'content' => 'Page description']);
	 * $this->document->addMeta(['property' => 'og:title', 'content' => 'Page Title']);
	 * $this->document->addMeta(['name' => 'theme-color', 'content' => '#000', 'media' => '(prefers-color-scheme: dark)']);
	 */
	public function addMeta(array $attributes): void {
		$this->metas[] = $attributes;
	}

	/**
	 * Get Metas
	 *
	 * @return array<int, array<string, string>>
	 */
	public function getMetas(): array {
		return $this->metas;
	}
}
