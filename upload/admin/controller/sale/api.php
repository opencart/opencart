<?php
namespace Opencart\Admin\Controller\Sale;
class Api extends \Opencart\System\Engine\Controller {
	public function index(): void {

		// Autoloader
		$autoloader = new \Opencart\System\Engine\Autoloader();
		$autoloader->register('Opencart\Catalog', DIR_CATALOG);
		$autoloader->register('Opencart\Extension', DIR_EXTENSION);
		$autoloader->register('Opencart\System', DIR_SYSTEM);

		// Registry
		$registry = new \Opencart\System\Engine\Registry();
		$registry->set('autoloader', $autoloader);

		// Config
		$config = new \Opencart\System\Engine\Config();
		$config->addPath(DIR_CONFIG);
		$registry->set('config', $config);

		// Load the default config
		$config->load('default');
		$config->load('catalog');
		$config->set('application', 'Catalog');

		// Logging
		$registry->set('log', $this->log);

		// Event
		$event = new \Opencart\System\Engine\Event($registry);
		$registry->set('event', $event);

		// Event Register
		if ($config->has('action_event')) {
			foreach ($config->get('action_event') as $key => $value) {
				foreach ($value as $priority => $action) {
					$event->register($key, new \Opencart\System\Engine\Action($action), $priority);
				}
			}
		}

		// Loader
		$loader = new \Opencart\System\Engine\Loader($registry);
		$registry->set('load', $loader);

		// Request
		$registry->set('request', $this->request);

		// Response
		$response = new \Opencart\System\Library\Response();
		$registry->set('response', $response);

		// Database
		$registry->set('db', $this->db);

		// Cache
		$registry->set('cache', $this->cache);

		// Session
		$session = new \Opencart\System\Library\Session($config->get('session_engine'), $registry);
		$registry->set('session', $session);

		if (isset($request->cookie[$config->get('session_name')])) {
			$session_id = $request->cookie[$config->get('session_name')];
		} else {
			$session_id = '';
		}

		$session->start($session_id);

		$this->model_account_api->addSession($api_info['api_id'], $session->getId(), $this->request->server['REMOTE_ADDR']);

		$session->data['api_id'] = $api_info['api_id'];

		// Template
		$template = new \Opencart\System\Library\Template($config->get('template_engine'));
		$template->addPath(DIR_CATALOG . 'view/template/');
		$registry->set('template', $template);

		// Language
		$language = new \Opencart\System\Library\Language($config->get('language_code'));
		$language->addPath(DIR_LANGUAGE);
		$language->load($config->get('language_code'));
		$registry->set('language', $language);

		// Store
		if (isset($this->request->post['store_id'])) {
			$config->set('config_store_id', $this->request->post['store_id']);
		} else {
			$config->set('config_store_id', 0);
		}

		// Url
		$registry->set('url', new \Opencart\System\Library\Url($config->get('site_url')));

		// Document
		$registry->set('document', new \Opencart\System\Library\Document());

		// Event
		$loader->model('setting/event');

		$results = $this->model_setting_event->getEvents();

		$registry->set('event', $event);

		$_['action_pre_action'] = [
			'startup/setting',
			//'startup/session',
			'startup/language',
			'startup/application',
			'startup/extension',
			'startup/startup',
			'startup/event'
		];

		// Pre Actions
		foreach ($config->get('action_pre_action') as $pre_action) {
			$loader->controller($pre_action);
		}














		if (isset($this->request->get['action'])) {
			$loader->controller($this->request->get['action']);
		}

		$loader->controller('api/login');

		echo $response->getOutput();


		//}
		//$this->response->addHeader('Content-Type: application/json');
		//$this->response->setOutput($response->getOutput());
	}
}
