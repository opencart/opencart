<?php
namespace Opencart\Catalog\Controller\Startup;
/**
 * Class Sass
 *
 * @package Opencart\Catalog\Controller\Startup
 */
class Sass extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @throws \Exception\ScssPhp\ScssPhp\Exception\SassException
	 *
	 * @return void
	 */
	public function index(): void {
		$file = DIR_APPLICATION . 'view/stylesheet/stylesheet.css';

		if (!is_file($file) || $this->config->get('developer_sass')) {
			$scss = new \ScssPhp\ScssPhp\Compiler();
			$scss->setImportPaths(DIR_APPLICATION . 'view/stylesheet/');

			$output = $scss->compileString('@import "stylesheet.scss"')->getCss();

			$handle = fopen($file, 'w');

			flock($handle, LOCK_EX);

			fwrite($handle, $output);

			fflush($handle);

			flock($handle, LOCK_UN);

			fclose($handle);
		}
	}
}
