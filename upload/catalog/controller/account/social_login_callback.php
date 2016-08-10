<?php
/**
 * Created by PhpStorm.
 * User: Jack Wang
 * Date: 2016-08-09
 * Time: 10:45
 */
class ControllerAccountSocialLoginCallBack extends Controller {
    private $error = array();

    public function facebook() {
        require_once DIR_SYSTEM . '/vendor/autoload.php';

        if(!session_id()) {
            session_start();
        }

        $provider = new \League\OAuth2\Client\Provider\Facebook([
           'clientId'   =>  'yourclientId',
            'clientSecret'  =>  'yourclientSecret',
            'redirectUri'   =>  HTTP_SERVER . 'yourredirectUri',
            'graphApiVersion'     =>  'v2.7'
        ]);

        $token = $provider->getAccessToken('authorization_code',[
            'code'  =>  $_GET['code']
        ]);

        $user = $provider->getResourceOwner($token);
        $social_field = 'facebook_id';
        $data = array(
            'social_field'       => $social_field,
            $social_field           => $user->getId(),
            'firstname'         => $user->getFirstName(),
            'lastname'          => $user->getLastName(),
            'email'             => $user->getEmail(),
        );

        $this->load->model('account/customer');

        $customer_info = $this->model_account_customer->getCustomerByEmail($user->getEmail());

        if($customer_info) {
            $this->model_account_customer->editCustomerByEmail($data);
        } else {
            $this->model_account_customer->addCustomer($data);
        }

        // login
        if($this->validate($social_field, $user->getId(), $user->getEmail())) {
            // Default Shipping Address
            $this->load->model('account/address');

            if ($this->config->get('config_tax_customer') == 'payment') {
                $this->session->data['payment_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
            }

            if ($this->config->get('config_tax_customer') == 'shipping') {
                $this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
            }

            // Add to activity log
            $this->load->model('account/activity');

            $activity_data = array(
                'customer_id'   =>  $this->customer->getId(),
                'name'          =>  $this->customer->getFirstName() . ' ' .$this->customer->getLastName()
            );

            $this->model_account_activity->addActivity('login',$activity_data);

            $this->response->redirect($this->url->link('account/account', '', true));
        }

        // Optional:Now you have a token you can look up a uses profile data
        /*try {
            // We got an access token, let's now get the user's details
            $user = $provider->getResourceOwner($token);

            // Use these details to create a new profile
            printf('Hello &s',$user->getFirstName());

            echo '<pre>';

            var_dump($user);

            echo '</pre>';
        } catch (\Exception $e) {
            // Failed to get user details
            exit('Oh dear...');
        }

        echo '</pre>';
        $email = $user->getEmail();
        var_dump($email);
        echo '</pre>';

        echo '<pre>';
        // Use this to interact with an API on the users behalf
        var_dump($token->getToken());
        # string(217) "CAADAppfn3msBAI7tZBLWg..."

        // The time (in epoch time) when an access token will expire
        var_dump($token->getExpires());
        #int(1436825866)
        echo '</pre>';*/
    }

    public function validate($social_field, $social_id, $email = "") {
        if(!$this->error) {
            if (!$this->customer->social_login($social_field, $social_id)) {
                $this->error['warning'] = $this->language->get('error_login');

                if (!empty($email)) {
                    $this->model_account_customer->addLoginAttempt($email);
                }
            } else {
                $this->model_account_customer->deleteLoginAttempts($email);
            }
        }

        return !$this->error;
    }
}