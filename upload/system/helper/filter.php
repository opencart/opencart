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

/**
 * Filter Types
 *
 * @param array $keys array of keys to filter
 * @param array $data
 *
 * @return array
 */
function oc_filter_types(array $keys, array $data = []): array {

	foreach ($keys as $key => $value) {

		if (!isset($data[$key]) || gettype($data[$key]) !== gettype($value)) {

			if (is_array($value)) {
				$data[$key] = oc_filter_types($value, $data[$key]);


			} else {

				$data[$key] = $value;
			}

		}

	}

	return $data;
}
