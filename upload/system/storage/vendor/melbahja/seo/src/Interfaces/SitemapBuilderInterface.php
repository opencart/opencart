<?php
namespace Melbahja\Seo\Interfaces;

/**
 * @package Melbahja\Seo
 * @since v2.0
 * @see https://git.io/phpseo
 * @license MIT
 * @copyright 2019-present Mohamed Elabhja
 */
interface SitemapBuilderInterface extends SeoInterface
{
	/**
	 * Images namespace
	 */
	public const IMAGE_NS = 'http://www.google.com/schemas/sitemap-image/1.1';

	/**
	 * Videos namespace
	 */
	public const VIDEO_NS = 'http://www.google.com/schemas/sitemap-video/1.1';


	/**
	 * XHTML links namespace
	 */
	public const XHTML_NS = 'http://www.w3.org/1999/xhtml';

	/**
	 * News namespace
	 * @var string
	 */
	public const NEWS_NS = 'https://www.google.com/schemas/sitemap-news/0.9';

	public function loc(string $path): SitemapBuilderInterface;

	public function lastMod($date): SitemapBuilderInterface;

	public function image(string $imageUrl, array $options = []): SitemapBuilderInterface;

	public function video(string $title, array $options = []): SitemapBuilderInterface;

	public function changeFreq(string $freq): SitemapBuilderInterface;

	public function priority(string $priority): SitemapBuilderInterface;

	public function saveTo(string $path): bool;

	public function saveTemp(): string;
}
