<?php
function oc_validate_length($string, $minimum, $maximum) {
	return (strlen(trim($string)) >= $minimum && strlen(trim($string)) <= $maximum);
}