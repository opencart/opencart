<?php
namespace Opencart\Application\Controller\Startup;
class Startup extends \Opencart\System\Engine\Controller {
	public function index() {
		// Store
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "store` WHERE REPLACE(`url`, 'www.', '') = '" . $this->db->escape(($this->request->server['HTTPS'] ? 'https://' : 'http://') . str_replace('www.', '', $this->request->server['HTTP_HOST']) . rtrim(dirname($this->request->server['PHP_SELF']), '/.\\') . '/') . "'");

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

			// Sync PHP and DB time zones.
			$this->db->query("SET time_zone = '" . $this->db->escape(date('P')) . "'");
		}

		// Session
		if (isset($this->request->get['route']) && substr((string)$this->request->get['route'], 0, 4) == 'api/') {
			$this->load->model('setting/api');

			$this->model_setting_api->cleanSessions();

			// Make sure the IP is allowed
			$api_info = $this->model_setting_api->getApiByToken($this->request->get['api_token']);

			if ($api_info) {
				$this->session->start($this->request->get['api_token']);

				$this->model_setting_api->updateSession($api_info['api_session_id']);
			}
		} else {
			if (isset($this->request->cookie[$this->config->get('session_name')])) {
				$session_id = $this->request->cookie[$this->config->get('session_name')];
			} else {
				$session_id = '';
			}

			$this->session->start($session_id);

			$option = [
				'max-age'  => time() + $this->config->get('session_expire'),
				'path'     => !empty($_SERVER['PHP_SELF']) ? dirname($_SERVER['PHP_SELF']) . '/' : '',
				'domain'   => $this->request->server['HTTP_HOST'],
				'secure'   => $this->request->server['HTTPS'],
				'httponly' => false,
				'SameSite' => 'strict'
			];

			oc_setcookie($this->config->get('session_name'), $this->session->getId(), $option);
		}

		// Response output compression level
		if ($this->config->get('config_compression')) {
			$this->response->setCompression($this->config->get('config_compression'));
		}

		// Url
		$this->registry->set('url', new \Opencart\System\Library\Url($this->config->get('config_url')));

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

			$browser_codes = [];

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

			$sort_order = [];

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
			$option = [
				'max-age'  => time() + 60 * 60 * 24 * 30,
				'path'     => '/',
				'SameSite' => 'lax'
			];

			oc_setcookie('language', $code, $option);
		}

		// Replace the default language object
		$language = new \Opencart\System\Library\Language($code);
		$language->load($code);
		$this->registry->set('language', $language);

		// Set the config language_id
		$this->config->set('config_language_id', $language_codes[$code]);
		$this->config->set('config_language', $code);

		// Customer
		$customer = new \Opencart\System\Library\Cart\Customer($this->registry);
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
			$option = [
				'max-age'  => time() + 60 * 60 * 24 * 30,
				'path'     => '/',
				'SameSite' => 'lax'
			];

			oc_setcookie('currency', $code, $option);
		}

		$this->registry->set('currency', new \Opencart\System\Library\Cart\Currency($this->registry));

		// Tax
		$this->registry->set('tax', new \Opencart\System\Library\Cart\Tax($this->registry));

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
		$this->registry->set('weight', new \Opencart\System\Library\Cart\Weight($this->registry));

		// Length
		$this->registry->set('length', new \Opencart\System\Library\Cart\Length($this->registry));

		// Cart
		$this->registry->set('cart', new \Opencart\System\Library\Cart\Cart($this->registry));

		// Encryption
		$this->registry->set('encryption', new \Opencart\System\Library\Encryption());
	}
}