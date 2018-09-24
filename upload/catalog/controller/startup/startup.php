<?php
class ControllerStartupStartup extends Controller {
	public function index() {
		// Store
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "store` WHERE REPLACE(`url`, 'www.', '') = '" . $this->db->escape(($this->request->server['HTTPS'] ? 'https://' : 'http://') . str_replace('www.', '', $_SERVER['HTTP_HOST']) . rtrim(dirname($_SERVER['PHP_SELF']), '/.\\') . '/') . "'");

		if (isset($this->request->get['store_id'])) {
			$this->config->set('config_store_id', (int)$this->request->get['store_id']);
		} else if ($query->num_rows) {
			$this->config->set('config_store_id', $query->row['store_id']);
		} else {
			$this->config->set('config_store_id', 0);
		}

		if (!$query->num_rows) {
			$this->config->set('config_url', HTTP_SERVER);
		}

		// Settings
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE store_id = '0' OR store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY store_id ASC");

		foreach ($query->rows as $result) {
			if (!$result['serialized']) {
				$this->config->set($result['key'], $result['value']);
			} else {
				$this->config->set($result['key'], json_decode($result['value'], true));
			}
		}

		// Set time zone
		if ($this->config->get('config_timezone')) {
			date_default_timezone_set($this->config->get('config_timezone'));
		}

		// Response output compression level
		if ($this->config->get('config_compression')) {
			$this->response->setCompression($this->config->get('config_compression'));
		}

		/*
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "startup` WHERE ORDER BY store_id ASC");

		// Config Autoload
		foreach ($query->rows as $result) {
			$loader->config($result['catalog/']);
		}

		// Language Autoload
		if ($config->has('language_autoload')) {
			foreach ($config->get('language_autoload') as $value) {
				$loader->language($value);
			}
		}

		// Library Autoload
		if ($config->has('library_autoload')) {
			foreach ($config->get('library_autoload') as $value) {
				$loader->library($value);
			}
		}

		// Model Autoload
		if ($config->has('model_autoload')) {
			foreach ($config->get('model_autoload') as $value) {
				$loader->model($value);
			}
		}

		// Pre Actions
		if ($config->has('action_pre_action')) {
			foreach ($config->get('action_pre_action') as $value) {
				$route->addPreAction(new Action($value));
			}
		}
		*/

		// Theme
		$this->config->set('template_cache', $this->config->get('developer_theme'));

		// Url
		$this->registry->set('url', new Url($this->config->get('config_url')));

		// Language
		$code = '';

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		$language_codes = array_column($languages, 'language_id', 'code');

		// Language Cookie
		if (isset($this->request->cookie['language']) && array_key_exists($this->request->cookie['language'], $language_codes)) {
			$code = $this->request->cookie['language'];
		}

		// No cookie then use the language in the url
		if (!$code && isset($this->request->get['language']) && array_key_exists($this->request->get['language'], $language_codes)) {
			$code = $this->request->get['language'];
		}

		// Language Detection
		if (!$code && !empty($this->request->server['HTTP_ACCEPT_LANGUAGE'])) {
			$detect = '';

			$browser_codes = array();

			$browser_languages = explode(',', strtolower($this->request->server['HTTP_ACCEPT_LANGUAGE']));

			// Try using local to detect the language
			foreach ($browser_languages as $browser_language) {
				$position = strpos($browser_language, ';q=');

				if ($position !== false) {
					$browser_codes[][substr($browser_language, 0, $position)] = (float)substr($browser_language, $position + 3);
				} else {
					$browser_codes[][$browser_language] = 1.0;
				}
			}

			$sort_order = array();

			foreach ($browser_codes as $key => $value) {
				$sort_order[$key] = $value[key($value)];
			}

			array_multisort($sort_order, SORT_ASC, $browser_codes);

			$browser_codes = array_reverse($browser_codes);

			foreach (array_values($browser_codes) as $browser_code) {
				foreach ($languages as $key => $value) {
					if ($value['status']) {
						$locale = explode(',', $value['locale']);

						if (in_array(key($browser_code), $locale)) {
							$detect = $value['code'];

							break 2;
						}
					}
				}
			}

			$code = ($detect) ? $detect : '';
		}

		// Language not available then use default
		if (!array_key_exists($code, $language_codes)) {
			$code = $this->config->get('config_language');
		}

		// Redirect to the new language
		if (isset($this->request->get['language']) && $this->request->get['language'] != $code) {
			if (isset($this->request->get['route'])) {
				$route = $this->request->get['route'];
			} else {
				$route = $this->config->get('action_default');
			}

			unset($this->request->get['_route_']);
			unset($this->request->get['route']);
			unset($this->request->get['language']);

			$url = '';

			if ($this->request->get) {
				$url = '&' . urldecode(http_build_query($this->request->get));
			}

			$this->response->redirect($this->url->link($route, 'language=' . $code . $url));
		}

		// Set a new language cookie if the code does not match the current one
		if (!isset($this->request->cookie['language']) || $this->request->cookie['language'] != $code) {
			setcookie('language', $code, time() + 60 * 60 * 24 * 30, '/', $this->request->server['HTTP_HOST']);
		}

		// Replace the default language object
		$language = new Language($code);
		$language->load($code);
		$this->registry->set('language', $language);

		// Set the config language_id
		$this->config->set('config_language_id', $language_codes[$code]);
		$this->config->set('config_language', $code);

		// Customer
		$customer = new Cart\Customer($this->registry);
		$this->registry->set('customer', $customer);

		// Customer Group
		if (isset($this->session->data['customer']) && isset($this->session->data['customer']['customer_group_id'])) {
			// For API calls
			$this->config->set('config_customer_group_id', $this->session->data['customer']['customer_group_id']);
		} elseif ($this->customer->isLogged()) {
			// Logged in customers
			$this->config->set('config_customer_group_id', $this->customer->getGroupId());
		} elseif (isset($this->session->data['guest']) && isset($this->session->data['guest']['customer_group_id'])) {
			$this->config->set('config_customer_group_id', $this->session->data['guest']['customer_group_id']);
		}

		// Currency
		$code = '';

		$this->load->model('localisation/currency');

		$currencies = $this->model_localisation_currency->getCurrencies();

		if (isset($this->session->data['currency'])) {
			$code = $this->session->data['currency'];
		}

		if (isset($this->request->cookie['currency']) && !array_key_exists($code, $currencies)) {
			$code = $this->request->cookie['currency'];
		}

		if (!array_key_exists($code, $currencies)) {
			$code = $this->config->get('config_currency');
		}

		if (!isset($this->session->data['currency']) || $this->session->data['currency'] != $code) {
			$this->session->data['currency'] = $code;
		}

		// Set a new currency cookie if the code does not match the current one
		if (!isset($this->request->cookie['currency']) || $this->request->cookie['currency'] != $code) {
			setcookie('currency', $code, time() + 60 * 60 * 24 * 30, '/', $this->request->server['HTTP_HOST']);
		}

		$this->registry->set('currency', new Cart\Currency($this->registry));

		// Tax
		$this->registry->set('tax', new Cart\Tax($this->registry));

		if (isset($this->session->data['shipping_address'])) {
			$this->tax->setShippingAddress($this->session->data['shipping_address']['country_id'], $this->session->data['shipping_address']['zone_id']);
		} elseif ($this->config->get('config_tax_default') == 'shipping') {
			$this->tax->setShippingAddress($this->config->get('config_country_id'), $this->config->get('config_zone_id'));
		}

		if (isset($this->session->data['payment_address'])) {
			$this->tax->setPaymentAddress($this->session->data['payment_address']['country_id'], $this->session->data['payment_address']['zone_id']);
		} elseif ($this->config->get('config_tax_default') == 'payment') {
			$this->tax->setPaymentAddress($this->config->get('config_country_id'), $this->config->get('config_zone_id'));
		}

		$this->tax->setStoreAddress($this->config->get('config_country_id'), $this->config->get('config_zone_id'));

		// Weight
		$this->registry->set('weight', new Cart\Weight($this->registry));

		// Length
		$this->registry->set('length', new Cart\Length($this->registry));

		// Cart
		$this->registry->set('cart', new Cart\Cart($this->registry));

		// Encryption
		$this->registry->set('encryption', new Encryption());
	}
}