<?php
/**
 * Filter Data
 *
 * @param array $filter
 * @param array $data
 *
 * @return array
 */
function oc_filter_data(array $filter = [], array $data = []): array {
	$output_data = [];

	foreach ($filter as $key => $value) {
		if (!isset($data[$key]) || (is_array($value) !== is_array($data[$key]))) {
			if (!is_array($value)) {
				$output_data[$key] = $value;
			} else {
				$output_data[$key] = isset($data[$key]) && is_array($data[$key]) ? oc_filter_data($value, $data[$key]) : $value;
			}
		} else {
			$output_data[$key] = $data[$key];
		}
	}

	return $output_data;
}
