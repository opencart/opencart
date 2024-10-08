<?php
/**
 * Filter Keyword
 *
 * @param string $string
 *
 * @return string
 */
function oc_filter_keyword(string $string): string {
	return urlencode(html_entity_decode($string, ENT_QUOTES, 'UTF-8'));
}
