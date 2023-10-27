<?php
namespace Opencart\Catalog\Controller\Extension\OcThemeExample\Startup;
class ThemeExample extends \Opencart\System\Engine\Controller {
	public function index(): void {
		if ($this->config->get('config_theme') == 'theme_example' && $this->config->get('theme_theme_example_status')) {
			// Add event via code instead of DB
			// Could also just set view/common/header/before
			$this->event->register('view/*/before', new \Opencart\System\Engine\Action('extension/oc_theme_example/startup/theme_example.event'));
		}
	}

	public function event(string &$route, array &$args, mixed &$output): void {
		$override = ['common/header'];

		if (in_array($route, $override)) {
			$route = 'extension/oc_theme_example/' . $route;
		}
	}
}