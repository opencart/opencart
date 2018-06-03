<?php
class ControllerStartupSass extends Controller {
	public function index() {
		$file = DIR_APPLICATION . 'view/theme/' . $this->config->get('config_theme') . '/stylesheet/bootstrap.css';

		if (!is_file($file) || (is_file(DIR_APPLICATION . 'view/theme/' . $this->config->get('config_theme') . '/stylesheet/scss/bootstrap.scss') && !$this->config->get('developer_sass'))) {
			$scss = new Leafo\ScssPhp\Compiler();
			$scss->setImportPaths(DIR_APPLICATION . 'view/theme/' . $this->config->get('config_theme') . '/stylesheet/scss/');

			$output = $scss->compile('@import "bootstrap.scss"');

			$handle = fopen($file, 'w');

			flock($handle, LOCK_EX);

			fwrite($handle, $output);

			fflush($handle);

			flock($handle, LOCK_UN);

			fclose($handle);
		}

		$file = DIR_APPLICATION . 'view/theme/' . $this->config->get('config_theme') . '/stylesheet/stylesheet.css';

		if (!is_file($file) || (is_file(DIR_APPLICATION . 'view/theme/' . $this->config->get('config_theme') . '/stylesheet/_stylesheet.scss') && !$this->config->get('developer_sass'))) {
			include_once(DIR_STORAGE . 'vendor/leafo/scssphp/scss.inc.php');

			$scss = new Leafo\ScssPhp\Compiler();
			$scss->setImportPaths(DIR_APPLICATION . 'view/theme/' . $this->config->get('config_theme') . '/stylesheet/');

			$output = $scss->compile('@import "_stylesheet.scss"');

			$handle = fopen($file, 'w');

			flock($handle, LOCK_EX);

			fwrite($handle, $output);

			fflush($handle);

			flock($handle, LOCK_UN);

			fclose($handle);
		}
	}
}
