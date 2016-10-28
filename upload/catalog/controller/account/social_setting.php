<?php
/**
 * Created by PhpStorm.
 * User: Jack Wang
 * Date: 2016-08-11
 * Time: 18:38
 */

// facebook
require_once DIR_SYSTEM . 'vendor/facebook/autoload.php';

if(!session_id()) {
    session_start();
}

$fb_provider = new \League\OAuth2\Client\Provider\Facebook([
    'clientId'		=>	FB_CLIENTID,
    'clientSecret'	=>	FB_CLIENTSECRET,
    'redirectUri'		=>	FB_REDIRECTURI,
    'graphApiVersion'		=>	'v2.7'
]);

if(!isset($_GET['code'])) {
    // If we don't have an authorization code then get one
    $data['fb_authUrl'] = $fb_provider->getAuthorizationUrl([
        'scope'	=>	['email']
    ]);

    $_SESSION['oauth2state'] = $fb_provider->getState();

    //echo '<a href="' . $authUrl . '">Log in with Facebook</a>';
    //exit;
} elseif(empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
    unset($_SESSION['oauth2state']);
    //echo 'Invalid state.';
    //exit;
}

// instagram
require_once DIR_SYSTEM . 'vendor/instagram/autoload.php';

if(!session_id()) {
    session_start();
}

$ins_provider = new League\OAuth2\Client\Provider\Instagram([
    'clientId'		=>	INS_CLIENTID,
    'clientSecret'	=>	INS_CLIENTSECRET,
    'redirectUri'		=>	INS_REDIRECTURI,
]);

if(!isset($_GET['code'])) {
    // If we don't have an authorization code then get one
    $data['ins_authUrl'] = $ins_provider->getAuthorizationUrl();
    $_SESSION['oauth2state'] = $ins_provider->getState();
} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
    unset($_SESSION['oauth2state']);
    //exit('Invalid state');
}

// google plus
require_once DIR_SYSTEM . 'vendor/googleplus/autoload.php';

// Create a project at https://console.developers.google.com/
$clientId 	= GP_CLIENTID;
$clientSecret = GP_CLIENTSECRET;
// Change this if you are not using the built-in PHP server
$redirectUri = GP_REDIRECTURI;

if(!session_id()) {
    session_start();
}

$gp_provider = new \League\OAuth2\Client\Provider\Google(compact('clientId','clientSecret','redirectUri'));
if (!isset($_GET['code'])) {
    // If we don't have an authorization code then get one.
    $data['gp_authUrl'] = $gp_provider->getAuthorizationUrl();
    $_SESSION['oauth2state'] = $gp_provider->getState();
} else if(empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
    // State is invalid, possible CSRF attack in progress
    unset($_SESSION['oauth2state']);
}

// twitter
require_once DIR_SYSTEM . 'vendor/twitter/autoload.php';

if(!session_id()) {
    session_start();
}

define('DEBUG', getenv('DEBUG') === 'true');
define('FORCE_TLS', getenv('FORCE_TLS') === 'true');
$httpHost = $_SERVER['HTTP_HOST'];
$expectedHost = getenv('HOST');
if (!DEBUG) {
    if (FORCE_TLS) {
        header("Strict-Transport-Security: max-age=31415926; includeSubDomains; preload");
    }
    if (
        FORCE_TLS &&
        isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
        $_SERVER['HTTP_X_FORWARDED_PROTO'] !== 'https'
    ) {
        header('Location: https://' . $expectedHost . $_SERVER['REQUEST_URI']);
        exit;
    }
    if ($httpHost !== $expectedHost) {
        //header('Location: https://' . $expectedHost . $_SERVER['REQUEST_URI']);
        //exit;
    }
}

define('CONSUMER_KEY',TW_CLIENTID);
define('CONSUMER_SECRET',TW_CLIENTSECRET);
define('OAUTH_CALLBACK',TW_REDIRECTURI);

// Build TwitterOAuth object with client credentials.
$connection = new \Abraham\TwitterOAuth\TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
// Get temporary credentials
try {
    $request_token = $connection->oauth('oauth/request_token', ['oauth_callback' => OAUTH_CALLBACK]);
} catch (\Exception $e) {
    $this->log->write($e);
}

// If last connection failed don't display authorization link.
switch ($connection->getLastHttpCode()) {
    case 200:
        // Save temporary credentials to session
        $_SESSION['oauth_token'] = $request_token['oauth_token'];
        $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

        // Build authorize URL and redirect user to Twitter.
        $url = $connection->url('oauth/authorize', ['oauth_token' => $request_token['oauth_token']]);
        $data['tw_authUrl'] = $url;
        break;
    default:
        // Show notification if something went wrong.
        //echo 'Could not connect to Twitter. Refresh the page or try again later.';
}
