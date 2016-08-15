<?php
/**
 * Created by PhpStorm.
 * User: Jack Wang
 * Date: 2016-08-09
 * Time: 10:45
 */
class ControllerAccountSocialLoginCallBack extends Controller {
    private $error = array();

    // facebook
    public function facebook() {
        require_once DIR_SYSTEM . 'vendor/facebook/autoload.php';

        if(!session_id()) {
            session_start();
        }

        $provider = new \League\OAuth2\Client\Provider\Facebook([
            'clientId'		=>	FB_CLIENTID,
            'clientSecret'	=>	FB_CLIENTSECRET,
            'redirectUri'		=>	FB_REDIRECTURI,
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

        // social login
        $this->social_login($social_field, $data, $user);
    }

    // instagram
    public function instagram() {
        require_once DIR_SYSTEM . 'vendor/instagram/autoload.php';

        if(!session_id()) {
            session_start();
        }

        $provider = new \League\OAuth2\Client\Provider\Instagram([
            'clientId'		=>	INS_CLIENTID,
            'clientSecret'	=>	INS_CLIENTSECRET,
            'redirectUri'		=>	INS_REDIRECTURI,
        ]);

        $token = $provider->getAccessToken('authorization_code',[
            'code'  =>  $_GET['code']
        ]);

        $user = $provider->getResourceOwner($token);
        $social_field = 'instagram_id';
        $data = array(
            'social_field'  =>  $social_field,
            $social_field       =>  $user->getId(),
            'firstname'     =>  $user->getName(),
            'lastname'      => "",
            'email'         => "",
        );

        $this->social_login($social_field, $data);
    }

    // google plus
    public function googleplus() {
        require_once DIR_SYSTEM . 'vendor/googleplus/autoload.php';

        if(!session_id()) {
            session_start();
        }

        // Create a project at https://console.developers.google.com/
        // Create a project at https://console.developers.google.com/
        $clientId 	= GP_CLIENTID;
        $clientSecret = GP_CLIENTSECRET;
        // Change this if you are not using the built-in PHP server
        $redirectUri = GP_REDIRECTURI;
        $provider = new \League\OAuth2\Client\Provider\Google(compact('clientId', 'clientSecret', 'redirectUri'));

        $token = $provider->getAccessToken('authorization_code',[
            'code'  =>  $_GET['code']
        ]);

        $_SESSION['token'] = serialize($token);
        if(!empty($_SESSION['token'])) {
            $token = unserialize($_SESSION['token']);
        }

        $user = $provider->getResourceOwner($token);

        $social_field = 'google_id';
        $data = array(
            'social_field'  =>  $social_field,
            $social_field       =>  $user->getId(),
            'firstname'     =>  $user->getFirstName(),
            'lastname'      => $user->getLastName(),
            'email'         => $user->getEmail(),
        );

        $this->social_login($social_field, $data);
    }

    // twitter
    public function twitter() {
        require_once DIR_SYSTEM . 'vendor/twitter/autoload.php';

        define('CONSUMER_KEY',TW_CLIENTID);
        define('CONSUMER_SECRET',TW_CLIENTSECRET);
        define('OAUTH_CALLBACK',TW_REDIRECTURI);

        // Get temporary credentials from session.
        $request_token = [];
        $request_token['oauth_token'] = $_SESSION['oauth_token'];
        $request_token['oauth_token_secret'] = $_SESSION['oauth_token_secret'];

        // If denied , bail.
        if(isset($_REQUEST['denied'])) {
            exit('Permission was denied. Please start over.');
        }

        // If the oauth_token is not what we expect, bail
        if(isset($_REQUEST['oauth_token']) && $request_token['oauth_token'] !== $_REQUEST['oauth_token']) {
            $_SESSION['oauth_status'] = 'oldtoken';
            session_start();
            session_destroy();
        }

        // Create TwitteroAuth object with app key/secret and token key/secret from default phase
        $connection = new \Abraham\TwitterOAuth\TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $request_token['oauth_token'], $request_token['oauth_token_secret']);

        // Request access tokens from twitter.
        $user = $connection->oauth("oauth/access_token", ["oauth_verifier" => $_REQUEST['oauth_verifier']]);

        // If HTTP response is 200 continue otherwise send to connect page to retry.
        if(200 == $connection->getLastHttpCode()) {
            // Save the access tokens. Normally these would be saved in a database for future use.
            $_SESSION['access_token'] = $user['oauth_token'];

            // Remove no longer needed request tokens.
            unset($_SESSION['oauth_token']);
            unset($_SESSION['oauth_token_secret']);
            // The user has been verified and the access tokens can be saved for future use.
            $_SESSION['status'] = 'verified';
        } else {
            session_start();
            session_destroy();
            exit;
        }

        // If method is set change API call made. Test is called by default.
        //$user = $connection->get('account/verify_credentials');
        $social_field = 'twitter_id';
        $data = array(
            'social_field'  =>  $social_field,
            $social_field       =>  $user['user_id'],
            'firstname'     =>  $user['screen_name'],
            'lastname'      => "",
            'email'         => "",
        );

        $this->social_login($social_field, $data);
    }

    // social login
    public function social_login($social_field, $data) {
        $this->load->model('account/customer');

        $customer_info = $this->model_account_customer->getCustomerByEmail($data['email']);

        if($customer_info) {
            $this->model_account_customer->editCustomerByEmail($data);
        } else {
            $this->model_account_customer->addCustomerForSocial($data);
        }

        // login
        if($this->validate($social_field, $data[$social_field], $data['email'])) {
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

            $this->response->redirect($this->url->link('common/home', '', true));
        }
    }

    // validate
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