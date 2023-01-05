<?php
namespace Opencart\Admin\Controller\Tool;
class Store extends \Opencart\System\Engine\Controller {
	public function createStoreInstance(int $store_id = 0, string $language = '', string $session_id = ''): object {
		// Autoloader
		$this->autoloader->register('Opencart\Catalog', DIR_CATALOG);

		// Registry
		$registry = new \Opencart\System\Engine\Registry();
		$registry->set('autoloader', $this->autoloader);

		// Config
		$config = new \Opencart\System\Engine\Config();
		$registry->set('config', $config);

		// Load the default config
		$config->addPath(DIR_CONFIG);
		$config->load('default');
		$config->set('application', 'Catalog');

		// Store
		$config->set('config_store_id', $store_id);

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

		// Create a dummy request class so we can feed the data to the order editor
		$request = new \stdClass();
		$request->get = [];
		$request->post = [];
		$request->server = $this->request->server;
		$request->cookie = [];

		// Request
		$registry->set('request', $request);

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

		// Start session
		$session->start($session_id);

		// Template
		$template = new \Opencart\System\Library\Template($config->get('template_engine'));
		$template->addPath(DIR_CATALOG . 'view/template/');
		$registry->set('template', $template);

		// Language
		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguageByCode($language);

		if ($language_info) {
			$config->set('config_language_id', $language_info['language_id']);
			$config->set('config_language', $language_info['code']);
		} else {
			$config->set('config_language_id', $this->config->get('config_language_id'));
			$config->set('config_language', $this->config->get('config_language'));
		}

		$language = new \Opencart\System\Library\Language($this->config->get('config_language'));
		$registry->set('language', $language);

		if (!$language_info['extension']) {
			$language->addPath(DIR_CATALOG . 'language/');
		} else {
			$language->addPath(DIR_EXTENSION . $language_info['extension'] . '/catalog/language/');
		}

		// Load default language file
		$language->load('default');

		// Url
		$registry->set('url', new \Opencart\System\Library\Url($config->get('site_url')));

		// Document
		$registry->set('document', new \Opencart\System\Library\Document());

		// Run pre actions to load key settings and classes.
		$pre_actions = [
			'startup/setting',
			'startup/extension',
			'startup/customer',
			'startup/tax',
			'startup/currency',
			'startup/application',
			'startup/startup',
			'startup/event'
		];

		// Pre Actions
		foreach ($pre_actions as $pre_action) {
			$loader->controller($pre_action);
		}

		return $registry;
	}
}