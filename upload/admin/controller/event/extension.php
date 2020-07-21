<?php
class ControllerEventExtension {
	public function controller($route) {
		$position = strpos('/', $route);

		$code = substr($route, 0, $position);

		$extension_install_info = $this->setting_event->getInstallByCode($code);

		if ($extension_install_info) {

			$path_info = $this->setting_extension->getPath($code . $route);

			if ($extension_install_info) {
				$file = DIR_EXTENSION . $route . '.php';

				include($file);
			}
		}
	}

	public function model() {


	}

	public function view() {


	}
}