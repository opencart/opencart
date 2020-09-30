<?php
namespace Opencart\Application\Controller\Startup;
class Install extends \Opencart\System\Engine\Controller {
	public function index() {
		$this->registry->set('document', new \Opencart\System\Library\Document(HTTP_SERVER));
		$this->registry->set('url', new \Opencart\System\Library\Url(HTTP_SERVER));
	}
}