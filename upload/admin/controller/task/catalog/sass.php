<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class SASS
 *
 * Can be loaded using $this->load->controller('common/developer');
 *
 * @package Opencart\Admin\Controller\Common
 */
class Sass extends \Opencart\System\Engine\Controller {
	/**
	 * SASS Admin
	 *
	 * Generate admin SASS file.
	 *
	 * @return void
	 */
	public function index(array $args = []): array {
		$this->load->language('task/catalog/sass');

		$file = DIR_OPENCART . 'static/stylesheet/stylesheet.scss';

		if (!is_file($file)) {
			return ['error' => $this->language->get('error_file')];
		}

		$filename = basename($file, '.scss');
		$directory = dirname($file) . '/';

		$stylesheet = $directory . $filename . '.css';

		if (is_file($stylesheet)) {
			unlink($stylesheet);
		}

		$scss = new \ScssPhp\ScssPhp\Compiler();
		$scss->setImportPaths($directory);

		$output = $scss->compileString('@import "' . $filename . '.scss"')->getCss();

		$handle = fopen($stylesheet, 'w');

		fwrite($handle, $output);

		fclose($handle);

		return ['success' => $this->language->get('text_success')];
	}
}
