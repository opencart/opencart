<?php
namespace Opencart\Application\Controller\Event;
class Theme extends \Opencart\System\Engine\Controller {
	public function index(&$route, &$args, &$code) {
		echo 'hi';



		if (substr($filename, 0, 19) == 'extension/opencart/') {
			$filename = substr($filename, 19);
		}

		if (substr($route, 0, strpos('/')) == 'extension') {


			//file_get_contents(EXTENSIONDIR_TEMPLATE . )
		}
	}
}