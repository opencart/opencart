<?php
/**
 * @package   OpenCart
 *
 * @author    Daniel Kerr
 * @copyright Copyright (c) 2005 - 2022, OpenCart, Ltd. (https://www.opencart.com/)
 * @license   https://opensource.org/licenses/GPL-3.0
 * @author    Daniel Kerr
 *
 * @see       https://www.opencart.com
 */
function oc_yaml_encode($yaml) {
	return \Symfony\Component\Yaml\Yaml::dump($yaml);
}

function oc_yaml_decode($data) {
	return \Symfony\Component\Yaml\Yaml::parse($data);
}