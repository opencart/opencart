<?php
/**
 * Filter Data
 *
 * @param array<string, mixed> $filter array of filter
 * @param array<string, mixed> $data   array of data
 *
 * @return array<string, mixed>
 */
function oc_filter_data(array $filter = [], array $data = []): array {
	$output_data = [];

	foreach ($filter as $key => $value) {
		if (!isset($data[$key]) || gettype($data[$key]) != gettype($value)) {
			if (!is_array($value)) {
				$output_data[$key] = $value;
			} else {
				$output_data[$key] = oc_filter_data($value, $data[$key]);
			}
		} else {
			$output_data[$key] = $data[$key];
		}
	}

	return $output_data;
}
