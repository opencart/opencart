<?php
/**
 * BBCode Converter that converts BBCode written for OpenCart.
 *
 * @param string $string
 *
 * @return string
 */
function oc_bbcode_decode(string $string): string {
	// Simple, safe replacements (no attribute output)
	$pattern = [
		'/\[b\](.*?)\[\/b\]/is'                                            => '<strong>$1</strong>',
		'/\[i\](.*?)\[\/i\]/is'                                            => '<em>$1</em>',
		'/\[u\](.*?)\[\/u\]/is'                                            => '<u>$1</u>',
		'/\[quote\](.*?)\[\/quote]/is'                                     => '<blockquote>$1</blockquote>',
		'/\[code\](.*?)\[\/code\]/is'                                      => '<code>$1</code>',
		'/\[s\](.*?)\[\/s\]/is'                                            => '<s>$1</s>',
		'/\[\*\]([\w\W]+?)\n?(?=(?:(?:\[\*\])|(?:\[\/list\])))/'           => '<li>$1</li>',
		'/\[list\](.*?)\[\/list\]/is'                                      => '<ul>$1</ul>',
		'/\[list\=(1|A|a|I|i)\](.*?)\[\/list\]/is'                         => '<ol type="$1">$2</ol>',
		'/\[size\=([\-\+]?\d+)\](.*?)\[\/size\]/is'                        => '<span style="font-size: $1%;">$2</span>',
		'/\[color\=(#[0-9a-f]{3}|#[0-9a-f]{6}|[a-z\-]+)\](.*?)\[\/color\]/is' => '<span style="color: $1;">$2</span>',
	];

	$string = preg_replace(array_keys($pattern), array_values($pattern), $string);

	// Image: validate URL protocol and escape src
	$string = preg_replace_callback('/\[img\](.*?)\[\/img\]/is', function (array $m): string {
		$url = oc_bbcode_safe_url($m[1]);

		return $url === '' ? '' : '<img src="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '" alt="" class="img-fluid" />';
	}, $string);

	// URL: [url]https://...[/url]
	$string = preg_replace_callback('/\[url\](.*?)\[\/url\]/is', function (array $m): string {
		$url = oc_bbcode_safe_url($m[1]);

		if ($url === '') {
			return '';
		}

		$escaped = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');

		return '<a href="' . $escaped . '" rel="nofollow" target="_blank">' . $escaped . '</a>';
	}, $string);

	// URL (named): [url=https://...]label[/url]
	$string = preg_replace_callback('/\[url\=([^\[]+?)\](.*?)\[\/url\]/is', function (array $m): string {
		$url = oc_bbcode_safe_url($m[1]);

		if ($url === '') {
			return '';
		}

		return '<a href="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '" rel="nofollow" target="_blank">' . htmlspecialchars($m[2], ENT_QUOTES, 'UTF-8') . '</a>';
	}, $string);

	// YouTube: only allow ID matching expected character set
	$string = preg_replace_callback('/\[youtube\](.*?)\[\/youtube\]/is', function (array $m): string {
		$id = trim($m[1]);

		if (!preg_match('/^[A-Za-z0-9_-]{6,20}$/', $id)) {
			return '';
		}

		return '<iframe width="560" height="315" src="https://www.youtube.com/embed/' . $id . '" allowfullscreen></iframe>';
	}, $string);

	return $string;
}

/**
 * Returns the input URL only if it uses a safe protocol (http, https or
 * relative). Otherwise returns an empty string. Used by oc_bbcode_decode to
 * defend against javascript:/data: injection through user-supplied BBCode.
 *
 * @param string $url
 *
 * @return string
 */
function oc_bbcode_safe_url(string $url): string {
	$url = trim($url);

	if ($url === '') {
		return '';
	}

	if (preg_match('#^(https?:)?//#i', $url) || str_starts_with($url, '/')) {
		return $url;
	}

	return '';
}
