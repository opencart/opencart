<?php

class ModelExtensionModuleAmazonLogin extends Model {
    const LOG_FILENAME = "amazon_login.log";
    const URL_PROFILE = "https://%s/user/profile";
    const URL_TOKENINFO = "https://%s/auth/o2/tokeninfo?access_token=%s";

    public function fetchProfile($access_token) {
        $url = sprintf(self::URL_PROFILE, $this->getApiDomainName());

        $profile = $this->curlGet($url, array(
            'Authorization: bearer ' . $access_token
        ));

        if (!empty($profile->error)) {
            $this->debugLog("ERROR", $this->language->get('error_login'));

            throw new \RuntimeException($this->language->get('error_login'));
        }

        $full_name = explode(' ', $profile->name);
        $profile->first_name = array_shift($full_name);
        $profile->last_name = implode(' ', $full_name);

        return $profile;
    }

    public function verifyAccessToken($access_token) {
        $url = sprintf(self::URL_TOKENINFO, $this->getApiDomainName(), urlencode($access_token));

        $token = $this->curlGet($url);

        if (!isset($token->aud) || $token->aud != $this->config->get('payment_amazon_login_pay_client_id')) {
            $this->debugLog("ERROR", $this->language->get('error_login'));

            throw new \RuntimeException($this->language->get('error_login'));
        }

        return true;
    }

    public function loginProfile($amazon_profile) {
        $this->load->model('account/address');
        $this->load->model('account/customer');
        $this->load->model('account/customer_group');

        $customer_info = $this->model_account_customer->getCustomerByEmail((string)$amazon_profile->email);

        // Create non-existing customer
        if (empty($customer_info)) {
            $data = array(
                'customer_group_id' => (int)$this->config->get('config_customer_group_id'),
                'firstname' => (string)$amazon_profile->first_name,
                'lastname' => (string)$amazon_profile->last_name,
                'email' => (string)$amazon_profile->email,
                'telephone' => '0000000',
                'password' => uniqid(rand(), true)
            );

            $customer_id = $this->model_account_customer->addCustomer($data);

            $customer_info = $this->model_account_customer->getCustomer($customer_id);

            $this->debugLog("CREATED_CUSTOMER", $customer_info);
        }

        // Customer is not logged in. Do a forced login.
        if (!$this->customer->isLogged()) {
            // If forced login fails, throw an exception
            $this->forceLoginCustomer($customer_info);

            $this->debugLog("LOGGED_IN", $customer_info);
        }

        // Set shipping and payment addresses for tax calculation
        if ($this->config->get('config_tax_customer') == 'payment') {
            $payment_address = $this->model_account_address->getAddress($customer_info['customer_id']);

            if ($payment_address) {
                $this->session->data['payment_address'] = $payment_address;
            }
        }

        if ($this->config->get('config_tax_customer') == 'shipping') {
            $shipping_address = $this->model_account_address->getAddress($customer_info['customer_id']);

            if ($shipping_address) {
                $this->session->data['shipping_address'] = $shipping_address;
            }
        }

        // Return the used customer corresponding to $amazon_profile. This may NOT be the same customer as the one who is currently logged in. This is acceptable.
        return $customer_info;
    }

    public function persistAddress($address) {
        if (!$this->customer->isLogged()) {
            return;
        }

        $this->load->model('account/address');

        $addresses = $this->model_account_address->getAddresses();

        if (!$this->addressMatches($address, $addresses)) {
            $this->model_account_address->addAddress($this->customer->getId(), $address);
        }
    }

    public function addressMatches($new, $addresses) {
        foreach ($addresses as $address) {
            if ($this->addressMatch($new, $address, array_keys($address))) {
                return true;
            }
        }

        return false;
    }

    public function addressMatch($a1, $a2, $keys) {
        //Skip comparison of custom_field. TODO introduce comparison for custom_field
        unset($keys[array_search('custom_field', $keys)]);

        $diff = array_diff_assoc(
            array_intersect_key($a1, array_flip($keys)),
            array_intersect_key($a2, array_flip($keys))
        );

        return empty($diff);
    }

    public function forceLoginCustomer($customer_info) {
        if (!$this->customer->login($customer_info['email'], '', true)) {
            $this->model_account_customer->addLoginAttempt($customer_info['email']);

            if (!$customer_info['status']) {
                $this->debugLog("ERROR", $this->language->get('error_approved'));

                throw new \RuntimeException($this->language->get('error_approved'));
            } else {
                $this->debugLog("ERROR", $this->language->get('error_login'));

                throw new \RuntimeException($this->language->get('error_login'));
            }
        } else {
            $this->model_account_customer->deleteLoginAttempts($customer_info['email']);

            if ($this->config->get('config_customer_activity')) {
                $this->load->model('account/activity');
                
                $activity_data = array(
                    'customer_id' => $customer_info['customer_id'],
                    'name'        => $customer_info['firstname'] . ' ' . $customer_info['lastname']
                );
                
                $this->model_account_activity->addActivity('login', $activity_data);
            }
        }
    }

    public function getApiDomainName() {
        if ($this->config->get('payment_amazon_login_pay_test') == 'sandbox') {
            switch ($this->config->get('payment_amazon_login_pay_payment_region')) {
                case "GBP" :
                    return "api.sandbox.amazon.co.uk";
                case "EUR" :
                    return "api.sandbox.amazon.de";
                default :
                    return "api.sandbox.amazon.com";
            }
        } else {
            switch ($this->config->get('payment_amazon_login_pay_payment_region')) {
                case "GBP" :
                    return "api.amazon.co.uk";
                case "EUR" :
                    return "api.amazon.de";
                default :
                    return "api.amazon.com";
            }
        }
    }

    public function curlGet($url, $headers = array()) {
        $this->debugLog("URL", $url);

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_PORT, 443);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->request->server['HTTP_USER_AGENT']);

        if (!empty($headers)) {
            $this->debugLog("HEADERS", $headers);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        $response = curl_exec($ch);

        if (empty($response)) {
            $debug = array(
                'curl_getinfo' => curl_getinfo($ch),
                'curl_errno' => curl_errno($ch),
                'curl_error' => curl_error($ch)
            );

            curl_close($ch);

            $this->debugLog("ERROR", $debug);

            throw new \RuntimeException($this->language->get('error_login'));
        } else {
            $this->debugLog("SUCCESS", $response);
        }

        curl_close($ch);

        return json_decode($response);
    }

    public function debugLog($type, $data) {
        if (!$this->config->get('payment_amazon_login_pay_debug')) {
            return;
        }

        if (is_array($data)) {
            $message = json_encode($data);
        } else {
            $message = $data;
        }

        ob_start();
            debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
            $message .= PHP_EOL . ob_get_contents();
        ob_end_clean();

        $log = new \Log(self::LOG_FILENAME);

        $log->write($type . " ---> " . $message);

        unset($log);
    }
}
