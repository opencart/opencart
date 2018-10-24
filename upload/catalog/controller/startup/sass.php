<?php
class ControllerStartupSass extends Controller {
	public function index() {
		$files = glob(DIR_APPLICATION . 'view/theme/' . $this->config->get('config_theme') . '/stylesheet/*.scss');

		if ($files) {
			foreach ($files as $file) {
				// Get the filename
				$filename = basename($file, '.scss') . '.css';

				$stylesheet = DIR_APPLICATION . 'view/theme/' . $this->config->get('config_theme') . '/stylesheet/' . $filename;

				if (!is_file($stylesheet) || !$this->config->get('developer_sass')) {
					$scss = new \Leafo\ScssPhp\Compiler();
					$scss->setImportPaths(DIR_APPLICATION . 'view/theme/' . $this->config->get('config_theme') . '/stylesheet/');

					$output = $scss->compile('@import "' . $filename . '"');

					$output = preg_replace('/\s*{\s*/', ' {' . "\n" . '    ', $output);
					$output = preg_replace('/;\s*/', ';' . "\n" . '    ', $output);
					$output = preg_replace('/,\s*/', ', ', $output);
					$output = preg_replace('/[ ]*}\s*/', '}' . "\n", $output);
					$output = preg_replace('/\}\s*(.+)/', '}' . "\n" . '$1', $output);
					$output = preg_replace('/\n    ([^:]+):\s*/', "\n" . '    $1: ', $output);
					$output = preg_replace('/([A-z0-9\)])}/', '$1;' . "\n" . '}', $output);

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
