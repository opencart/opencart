<?php
class ControllerStartupSass extends Controller {
	public function index() {
		$files = glob(DIR_APPLICATION . 'view/theme/' . $this->config->get('config_theme') . '/stylesheet/*.scss');

		if ($files) {
			foreach ($files as $file) {
				// Get the filename
				$filename = basename($file, '.scss');

				$stylesheet = DIR_APPLICATION . 'view/theme/' . $this->config->get('config_theme') . '/stylesheet/' . $filename . '.css';

				if (!is_file($stylesheet) || !$this->config->get('developer_sass')) {
					$scss = new \ScssPhp\ScssPhp\Compiler();
					$scss->setImportPaths(DIR_APPLICATION . 'view/theme/' . $this->config->get('config_theme') . '/stylesheet/');

					$output = $scss->compile('@import "' . $filename . '.scss"');

					$handle = fopen($stylesheet, 'w');

					flock($handle, LOCK_EX);

					fwrite($handle, $output);

					fflush($handle);

					flock($handle, LOCK_UN);

					fclose($handle);
				}
			}
		}
	}
}
