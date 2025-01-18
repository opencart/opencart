<?php
/**
 * Filter Keyword
 *
 * @param string $string
 *
 * @return string
 */
function oc_filter_data(string $data, array $filter = []): string {
	$output_data = [];

	foreach ($filter as $key => $value) {
		if (!isset($data[$key]) || gettype($data[$key]) != gettype($value)) {
			if (!is_array($value)) {
				$output_data[] = $value;
			} else {
				$output_data[] = oc_filter_data($value, $data[$key]);
			}
		}
	}

	return $output_data;
}
