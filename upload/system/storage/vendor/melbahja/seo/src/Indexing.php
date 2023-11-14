<?php
namespace Melbahja\Seo;

use Melbahja\Seo\Interfaces\SeoInterface;

/**
 * @package Melbahja\Seo
 * @since v2.0
 * @see https://git.io/phpseo
 * @license MIT
 * @copyright 2019-present Mohamed Elabhja
 */
class Indexing implements SeoInterface
{

	/**
	 * website hostname.
	 * @var string
	 */
	protected $host;

	/**
	 * Engines api keys.
	 * @var array
	 */
	protected $keys = [];

	/**
	 * Initialize indexer.
	 * @param string $host
	 * @param array  $keys
	 */
	public function __construct(string $host, array $keys)
	{
		$this->host = $host;
		$this->keys = $keys;
	}

	/**
	 * Instant index single url.
	 *
	 * @param  string $url
	 * @return array
	 */
	public function indexUrl(string $url): array
	{
		return $this->indexUrls([$url]);
	}

	/**
	 * Instant index multiple urls.
	 *
	 * @param  array  $urls
	 * @return array
	 */
	public function indexUrls(array $urls): array
	{
		$accepted = [];

		foreach($this->keys as $engine => $key)
		{
			$accepted[$engine] = $this->index($engine, $key, $urls);
		}

		return $accepted;
	}

	/**
	 * Send index request to search engine.
	 *
	 * @param  string $engine
	 * @param  string $apiKey
	 * @param  array  $urls
	 * @return bool
	 */
	protected function index(string $engine, string $apiKey, array $urls): bool
	{
		$ch = curl_init("https://{$engine}/indexnow");
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['host' => $this->host, 'key' => $apiKey, 'urlList' => $urls]));
		curl_setopt($ch, CURLOPT_HTTPHEADER, ['content-type: application/json']);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_exec($ch);
		$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		return $code >= 200 && $code < 300;
	}
}
