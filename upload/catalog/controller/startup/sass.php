<?php
class ControllerStartupSass extends Controller {
	public function index() {
		$file = DIR_APPLICATION . 'view/theme/' . $this->config->get('theme_directory') . '/stylesheet/bootstrap.css';

		if (!is_file($file) || (is_file(DIR_APPLICATION . 'view/theme/' . $this->config->get('theme_directory') . '/stylesheet/scss/bootstrap.scss') && !$this->config->get('developer_sass'))) {
			$scss = new Leafo\ScssPhp\Compiler();
			$scss->setImportPaths(DIR_APPLICATION . 'view/theme/' . $this->config->get('theme_directory') . '/stylesheet/scss/');

			$output = $scss->compile('@import "bootstrap.scss"');

			$handle = fopen($file, 'w');

			flock($handle, LOCK_EX);

			fwrite($handle, $output);

			fflush($handle);

			flock($handle, LOCK_UN);

			fclose($handle);
		}
	}
}