<?php

$provider = require __DIR__ . '/provider.php';

if (!empty($_GET['error'])) {

    // Got an error, probably user denied access
    exit('Got error: ' . $_GET['error']);

} elseif (empty($_GET['code'])) {

    // If we don't have an authorization code then get one
    $authUrl = $provider->getAuthorizationUrl();
    $_SESSION['oauth2state'] = $provider->state;
    header('Location: ' . $authUrl);
    exit;

} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {

    // State is invalid, possible CSRF attack in progress
    unset($_SESSION['oauth2state']);
    exit('Invalid state');

} else {

    // Try to get an access token (using the authorization code grant)
    $token = $provider->getAccessToken('authorization_code', [
        'code' => $_GET['code']
    ]);

    $_SESSION['token'] = serialize($token);

    // Optional: Now you have a token you can look up a users profile data
    header('Location: /user.php');
}
