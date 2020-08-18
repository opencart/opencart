<?php
class ControllerEventExtension extends Controller {
	private $load = '';

	public function controller(&$route, &$args) {
		if (substr($route, 0, 1) == '~') {

			$loader = new Loader(DIR_EXTENSION . substr($route, 1));

			$path = substr($route, 1);

			$results = $this->model_setting_extension->getPaths('admin/controller/' . $path . '%');

			print_r($results);

			$file = DIR_EXTENSION . substr($route, 1) . '.php';

			if (is_file()) {
				//$parts = explode('/', $route);
			}
		}

		/*
		$position = strpos('/', $route);

		$code = substr($route, 0, $position);

		$this->load->model('setting/event');

		$path_info = $this->model_setting_extension->getPathsByCode($route);

		if ($extension_install_info) {
			$file = DIR_EXTENSION . $route . '.php';

			include($file);
		}
		*/
	}

	public function model() {

	}

	public function view() {


	}

	public function library() {


	}
}