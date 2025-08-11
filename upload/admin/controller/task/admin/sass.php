<?php
namespace Opencart\Admin\Controller\Task\Admin;
/**
 * Class Sass
 *
 * @package Opencart\Admin\Controller\Task\Admin
 */
class Sass extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @throws \Exception\ScssPhp\ScssPhp\Exception\SassException
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/admin/sass');

		$file = DIR_APPLICATION . 'view/stylesheet/stylesheet.scss';

		if (!is_file($file)) {
			return ['error' => $this->language->get('error_file')];
		}

		$filename = basename($file, '.scss');

		$stylesheet = dirname($file) . '/' . $filename . '.css';

		$scss = new \ScssPhp\ScssPhp\Compiler();
		$scss->setImportPaths(DIR_APPLICATION . 'view/stylesheet/');

		$output = $scss->compileString('@import "' . $filename . '.scss"')->getCss();

		$handle = fopen($stylesheet, 'w');

		flock($handle, LOCK_EX);

		fwrite($handle, $output);

		fflush($handle);

		flock($handle, LOCK_UN);

		fclose($handle);

		return ['success' => $this->language->get('text_success')];
	}

	/**
	 * Clear
	 *
	 * Clears generated sass files.
	 *
	 * @return array
	 */
	public function clear(array $args = []): array {
		$this->load->language('task/admin/sass');

		$file = DIR_APPLICATION . 'view/stylesheet/stylesheet.css';

		if (is_file($file)) {
			unlink($file);
		}

		return ['success' => $this->language->get('text_clear')];
	}
}