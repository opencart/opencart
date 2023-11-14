<?php
namespace Melbahja\Seo\Interfaces;

/**
 * @package Melbahja\Seo
 * @since v2.0
 * @see https://git.io/phpseo
 * @license MIT
 * @copyright 2019-present Mohamed Elabhja
 */
interface SitemapIndexInterface extends SitemapInterface
{
	public function __construct(string $domain, array $options = null);

	public function setOptions(array $options): SitemapIndexInterface;

	public function getOptions(): array;

	public function saveTo(string $path): bool;

	public function save(): bool;

	public function build(SitemapBuilderInterface $builder, array $options, callable $func): SitemapIndexInterface;

	public function __call(string $builder, array $args): SitemapIndexInterface;	
}
