<?php
function oc_filter_url(string $string): string {
	return urlencode(html_entity_decode($string, ENT_QUOTES, 'UTF-8'));
}