<?php
/* BBCode Converter that converts BBCode writen in ckeditor */
function bbcode_decode($string) {
	$pattern = array();
	$replace = array();

	// Bold
	$pattern[0] = '/\[b\](.*?)\[\/b\]/is';
	$replace[0] = '<strong>$1</strong>';

	// Italic
	$pattern[1] = '/\[i\](.*?)\[\/i\]/is';
	$replace[1] = '<em>$1</em>';

	// Underlined
	$pattern[2] = '/\[u\](.*?)\[\/u\]/is';
	$replace[2] = '<u>$1</u>';

	// Quote
	$pattern[3] = '/\[quote\](.*?)\[\/quote]/is';
	$replace[3] = '<blockquote>$1</blockquote>';

	// Code
	$pattern[4] = '/\[code\](.*?)\[\/code\]/is';
	$replace[4] = '<code>$1</code>';

	// Strikethrough
	$pattern[16] = '/\[s\](.*?)\[\/s\]/is';
	$replace[16] = '<s>$1</s>';

	// List Item
	$pattern[7] = '/\[\*\]([\w\W]+?)\n?(?=(?:(?:\[\*\])|(?:\[\/list\])))/';
	$replace[7] = '<li>$1</li>';

	// List
	$pattern[5] = '/\[list\](.*?)\[\/list\]/is';
	$replace[5] = '<ul>$1</ul>';

	// Ordered List
	$pattern[6] = '/\[list\=(1|A|a|I|i)\](.*?)\[\/list\]/is';
	$replace[6] = '<ol type="$1">$2</ol>';

	// Image
	$pattern[8] = '/\[img\](.*?)\[\/img\]/is';
	$replace[8] = '<img src="$1" alt="" class="img-responsive" />';

	// URL
	$pattern[9] = '/\[url\](.*?)\[\/url\]/is';
	$replace[9] = '<a href="$1" rel="nofollow" target="_blank">$1</a>';

	// URL (named)
	$pattern[10] = '/\[url\=([^\[]+?)\](.*?)\[\/url\]/is';
	$replace[10] = '<a href="$1" rel="nofollow" target="_blank">$2</a>';

	// Font Size
	$pattern[11] = '/\[size\=([\-\+]?\d+)\](.*?)\[\/size\]/is';
	$replace[11] = '<span style="font-size: $1%;">$2</span>';

	// Font Color
	$pattern[12] = '/\[color\=(#[0-9a-f]{3}|#[0-9a-f]{6}|[a-z\-]+)\](.*?)\[\/color\]/is';
	$replace[12] = '<span style="color: $1;">$2</span>';

	// YouTube
	$pattern[9] = '/\[youtube\](.*?)\[\/youtube\]/is';
	$replace[9] = '<iframe width="560" height="315" src="http://www.youtube.com/embed/$1" frameborder="0" allowfullscreen></iframe>';

	$string = preg_replace($pattern, $replace, $string);

	return $string;
}  