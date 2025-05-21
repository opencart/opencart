<?php

namespace Opencart\Catalog\Controller\Event;

class Routes extends \Opencart\System\Engine\Controller {
	function skipCommonHome(&$key, &$value, &$results)
	{
		if ($key === 'route' && $value === 'common/home')
			$results[] = false;
	}

	function skipCurrentLanguage(&$key, &$value, &$results)
	{
		if ($key === 'language' && $value === $this->config->get('config_language_catalog'))
			$results[] = false;
	}
}
