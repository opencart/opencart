<?php
class ControllerExtensionModulePPLogin extends Controller {
	private $error = array();

	public function index() {
		if (!$this->customer->isLogged()) {
			$data['client_id'] = $this->config->get('module_pp_login_client_id');
			$data['return_url'] = $this->url->link('extension/module/pp_login/login', 'language=' . $this->config->get('config_language'));

			if ($this->config->get('module_pp_login_sandbox')) {
				$data['sandbox'] = 'sandbox';
			} else {
				$data['sandbox'] = '';
			}

			if ($this->config->get('module_pp_login_button_colour') == 'grey') {
				$data['button_colour'] = 'neutral';
			} else {
				$data['button_colour'] = '';
			}

			$locale = $this->config->get('module_pp_login_locale');

			$this->load->model('localisation/language');

			$languages = $this->model_localisation_language->getLanguages();

			foreach ($languages as $language) {
				if ($language['status'] && ($language['code'] == $this->config->get('config_language')) && isset($locale[$language['language_id']])) {
					$data['locale'] = $locale[$language['language_id']];
				}
			}

			if (!isset($data['locale'])) {
				$data['locale'] = 'en-gb';
			}

			$scopes = array(
				'profile',
				'email',
				'address',
				'phone'
			);

			if ($this->config->get('module_pp_login_seamless')) {
				$scopes[] = 'https://uri.paypal.com/services/expresscheckout';
			}

			$data['scopes'] = implode(' ', $scopes);

			return $this->load->view('extension/module/pp_login', $data);
		}
	}

	public function login() {
		$this->load->model('extension/module/pp_login');
		$this->load->model('account/customer');
		$this->load->model('account/customer_group');

		if ($this->customer->isLogged()) {
			echo '<script type="text/javascript">window.opener.location = "' . $this->url->link('account/account', 'language=' . $this->config->get('config_language')) . '"; window.close();</script>';
		}

		if (!isset($this->request->get['code'])) {
			if (isset($this->request->get['error']) && isset($this->request->get['error_description'])) {
				$this->model_extension_module_pp_login->log('No code returned. Error: ' . $this->request->get['error'] . ', Error Description: ' . $this->request->get['error_description']);
			}

			echo '<script type="text/javascript">window.opener.location = "' . $this->url->link('account/login', 'language=' . $this->config->get('config_language')) . '"; window.close();</script>';
		} else {
			$tokens = $this->model_extension_module_pp_login->getTokens($this->request->get['code']);
		}

		if (isset($tokens->access_token) && !isset($tokens->error)) {
			$user = $this->model_extension_module_pp_login->getUserInfo($tokens->access_token);
		}

		if (isset($user)) {
			$customer_info = $this->model_account_customer->getCustomerByEmail($user->email);

			if ($customer_info) {
				if ($this->validate($user->email)) {
					$this->completeLogin($customer_info['customer_id'], $customer_info['email'], $tokens->access_token);
				} else {
					$this->model_extension_module_pp_login->log('Could not login to - ID: ' . $customer_info['customer_id'] . ', Email: ' . $customer_info['email']);
					echo '<script type="text/javascript">window.opener.location = "' . $this->url->link('account/login', 'language=' . $this->config->get('config_language')) . '"; window.close();</script>';
				}
			} else {
				$country = $this->db->query("SELECT `country_id` FROM `" . DB_PREFIX . "country` WHERE iso_code_2 = '" . $this->db->escape($user->address->country) . "'");

				if ($country->num_rows) {
					$country_id = $country->row['country_id'];

					$zone = $this->db->query("SELECT `zone_id` FROM `" . DB_PREFIX . "zone` WHERE country_id = '" . (int)$country_id . "' AND name = '" . $this->db->escape($user->address->region) . "'");

					if ($zone->num_rows) {
						$zone_id = $zone->row['zone_id'];
					} else {
						$zone_id = 0;
					}
				} else {
					$country_id = 0;
					$zone_id = 0;
				}

				if ($this->config->get('module_pp_login_customer_group_id')) {
					$customer_group_id = $this->config->get('module_pp_login_customer_group_id');
				} else {
					$customer_group_id = $this->config->get('config_customer_group_id');
				}

				$data = array(
					'customer_group_id' => (int)$customer_group_id,
					'firstname'         => $user->given_name,
					'lastname'          => $user->family_name,
					'email'             => $user->email,
					'telephone'         => $user->phone_number,
					'password'          => uniqid(rand(), true),
					'company'           => '',
					'address_1'         => $user->address->street_address,
					'address_2'         => '',
					'city'              => $user->address->locality,
					'postcode'          => $user->address->postal_code,
					'country_id'        => (int)$country_id,
					'zone_id'           => (int)$zone_id,
				);

				$customer_id = $this->model_account_customer->addCustomer($data);

				$this->model_extension_module_pp_login->log('Customer ID date_added: ' . $customer_id);

				if ($this->validate($user->email)) {
					$this->completeLogin($customer_id, $user->email, $tokens->access_token);
				} else {
					$this->model_extension_module_pp_login->log('Could not login to - ID: ' . $customer_id . ', Email: ' . $user->email);
					echo '<script type="text/javascript">window.opener.location = "' . $this->url->link('account/login', 'language=' . $this->config->get('config_language')) . '"; window.close();</script>';
				}
			}
		}
	}

	public function logout() {
		if (isset($this->session->data['pp_login'])) {
			unset($this->session->data['pp_login']);
		}
	}

	protected function completeLogin($customer_id, $email, $access_token) {
		unset($this->session->data['guest']);

		// Default Shipping Address
		$this->load->model('account/address');

		if ($this->config->get('config_tax_customer') == 'payment') {
			$this->session->data['payment_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
		}

		if ($this->config->get('config_tax_customer') == 'shipping') {
			$this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
		}

		if ($this->config->get('module_pp_login_seamless')) {
			$this->session->data['pp_login']['seamless']['customer_id'] = $this->customer->getId();
			$this->session->data['pp_login']['seamless']['access_token'] = $access_token;
		} else {
			if (isset($this->session->data['pp_login']['seamless'])) {
				unset($this->session->data['pp_login']['seamless']);
			}
		}

		$this->model_extension_module_pp_login->log('Customer logged in - ID: ' . $customer_id . ', Email: ' . $email);
		echo '<script type="text/javascript">window.opener.location = "' . $this->url->link('account/account', 'language=' . $this->config->get('config_language')) . '"; window.close();</script>';
	}

	protected function validate($email) {
		// Check how many login attempts have been made.
		$login_info = $this->model_account_customer->getLoginAttempts($email);

		if ($login_info && ($login_info['total'] >= $this->config->get('config_login_attempts')) && strtotime('-1 hour') < strtotime($login_info['date_modified'])) {
			$this->error['warning'] = $this->language->get('error_attempts');
		}

		// Check if customer has been approved.
		$customer_info = $this->model_account_customer->getCustomerByEmail($email);

		if ($customer_info && !$customer_info['status']) {
			$this->error['warning'] = $this->language->get('error_approved');
		}

		if (!$this->error) {
			if (!$this->customer->login($email, '', true)) {
				$this->error['warning'] = $this->language->get('error_login');

				$this->model_account_customer->addLoginAttempt($email);
			} else {
				$this->model_account_customer->deleteLoginAttempts($email);
			}
		}

		return !$this->error;
	}
}