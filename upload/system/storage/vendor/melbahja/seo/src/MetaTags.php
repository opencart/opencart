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
class MetaTags implements SeoInterface
{
	/**
	 * Generated tags
	 * @var array
	 */
	protected $tags = [];

	/**
	 * Twitter tags.
	 * @var array
	 */
	protected $twitterTags = [];


	/**
	 * Open Graph tags.
	 * @var array
	 */
	protected $openGraphTags = [];

	/**
	 * Page title
	 * @var string
	 */
	public $title = null;

	/**
	 * Initiablize new meta tags builder
	 *
	 * @param array $tags
	 */
	public function __construct(array $tags = [])
	{
		foreach ($tags as $k => $v)
		{
			if (method_exists(static::class, $k)) {
				$this->$k($v);
				continue;
			}
			$this->meta($k, $v);
		}
	}


	/**
	 * Set page and meta title
	 *
	 * @param  string $title
	 * @return MetaTags
	 */
	public function title(string $title): MetaTags
	{
		$this->title = Helper::escape($title);
		return $this->meta('title', $title)->og('title', $title)->twitter('title', $title);
	}

	/**
	 * Set page description.
	 * @param  string $desc
	 * @return MetaTags
	 */
	public function description(string $desc): MetaTags
	{
		return $this->meta('description', $desc)->og('description', $desc)->twitter('description', $desc);
	}

	/**
	 * Set a mobile link (Http header "Vary: User-Agent" is required)
	 *
	 * @param  string $url
	 * @return MetaTags
	 */
	public function mobile(string $url): MetaTags
	{
		return $this->push('link', [
			'href' => $url,
			'rel'   => 'alternate',
			'media' => 'only screen and (max-width: 640px)',
		]);
	}

	/**
	 * Set robots meta tags.
	 *
	 * @param  string $options For example: follow, index, max-snippet:-1, max-video-preview:-1, max-image-preview:large
	 * @param  string $botName bot name or robots for all.
	 * @return MetaTags
	 */
	public function robots(string $options, string $botName = 'robots'): MetaTags
	{
		return $this->meta($botName, $options);
	}

	/**
	 * Set AMP link
	 *
	 * @param  string $url
	 * @return MetaTags
	 */
	public function amp(string $url): MetaTags
	{
		return $this->push('link', [
			'rel' => 'amphtml',
			'href' => $url
		]);
	}

	/**
	 * Set canonical url
	 *
	 * @param  string $url
	 * @return MetaTags
	 */
	public function canonical(string $url): MetaTags
	{
		return $this->push('link', [
			'rel' => 'canonical',
			'href' => $url
		]);
	}


	/**
	 * Set social media url.
	 *
	 * @param  string $url
	 * @return MetaTags
	 */
	public function url(string $url): MetaTags
	{
		return $this->og('url', $url)->twitter('url', $url);
	}

	/**
	 * Set alternate language url.
	 *
	 * @param  string $lang for eg: en
	 * @param  string $url  alternate language page url.
	 * @return MetaTags
	 */
	public function hreflang(string $lang, string $url): MetaTags
	{
		return $this->push('link', [
			'rel' => 'alternate',
			'href' => $url,
			'hreflang' => $lang,
		]);
	}

	/**
	 * Set a meta tag
	 *
	 * @param string $name
	 * @param string $value
	 * @return MetaTags
	 */
	public function meta(string $name, string $value): MetaTags
	{
		return $this->push('meta', [
			'name'    => $name,
			'content' => $value,
		]);
	}

	/**
	 * Append new tag
	 *
	 * @param string $name
	 * @param array  $attrs
	 * @return MetaTags
	 */
	public function push(string $name, array $attrs): MetaTags
	{
		foreach ($attrs as $k => $v)
		{
			$attrs[$k] = $v;
		}

		$this->tags[] = [$name, $attrs];
		return $this;
	}

	/**
	 * Set a open graph tag
	 *
	 * @param  string $name
	 * @param  string $value
	 * @return MetaTags
	 */
	public function og(string $name, string $value): MetaTags
	{
		$this->openGraphTags[] = ['meta', ['property' => "og:{$name}", 'content' => $value]];
		return $this;
	}


	/**
	 * Set a twitter tag
	 *
	 * @param  string $name
	 * @param  string $value
	 * @return MetaTags
	 */
	public function twitter(string $name, string $value): MetaTags
	{
		$this->twitterTags[] = ['meta', ['property' => "twitter:{$name}", 'content' => $value]];
		return $this;
	}

	/**
	 * Set short link tag
	 * 
	 * @param  string $url
	 * @return MetaTags
	 */
	public function shortlink(string $url): MetaTags
	{
		return $this->push('link', [
			'rel' => 'shortlink',
			'href' => $url
		]);
	}

	/**
	 * Set image meta
	 *
	 * @param  string $url
	 * @param  string $card Twitter card
	 * @return MetaTags
	 */
	public function image(string $url, string $card = 'summary_large_image'): MetaTags
	{
		return $this->og('image', $url)->twitter('card', $card)->twitter('image', $url);
	}

	/**
	 * Build meta tags
	 *
	 * @param  array  $tags
	 * @return string
	 */
	public function build(array $tags): string
	{
		$out = '';

		foreach ($tags as $tag)
		{
			$out .= "\n<{$tag[0]} ";

			foreach ($tag[1] as $a => $v)
			{
				$out .= $a .'="'. Helper::escape($v) .'" ';
			}

			$out .= "/>";
		}

		return $out;
	}


	/**
	 * Object to string
	 *
	 * @return string
	 */
	public function __toString(): string
	{
		$title = '';
		if ($this->title !== null) {
			$title = "<title>{$this->title}</title>";
		}

		return $title . $this->build($this->tags) . $this->build($this->twitterTags) . $this->build($this->openGraphTags) ;
	}
}
