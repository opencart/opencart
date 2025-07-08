<?php

declare(strict_types=1);

namespace GuzzleHttp\Subscriber\Oauth;

use GuzzleHttp\Psr7\Query;
use Psr\Http\Message\RequestInterface;

/**
 * OAuth 1.0 signature plugin.
 *
 * Portions of this code comes from HWIOAuthBundle and a Guzzle 3 pull request:
 *
 * @author Alexander <iam.asm89@gmail.com>
 * @author Joseph Bielawski <stloyd@gmail.com>
 * @author Francisco Facioni <fran6co@gmail.com>
 *
 * @see https://github.com/hwi/HWIOAuthBundle
 * @see https://github.com/guzzle/guzzle/pull/563 Original Guzzle 3 pull req.
 * @see https://oauth.net/core/1.0/#rfc.section.9.1.1 OAuth specification
 */
class Oauth1
{
    /**
     * Consumer request method constants. See https://oauth.net/core/1.0/#consumer_req_param
     */
    public const REQUEST_METHOD_HEADER = 'header';
    public const REQUEST_METHOD_QUERY = 'query';

    public const SIGNATURE_METHOD_HMAC = 'HMAC-SHA1';
    public const SIGNATURE_METHOD_HMACSHA256 = 'HMAC-SHA256';
    public const SIGNATURE_METHOD_RSA = 'RSA-SHA1';
    public const SIGNATURE_METHOD_PLAINTEXT = 'PLAINTEXT';

    /** @var array Configuration settings */
    private $config;

    /**
     * Create a new OAuth 1.0 plugin.
     *
     * The configuration array accepts the following options:
     *
     * - request_method: Consumer request method. One of 'header' or 'query'.
     *   Defaults to 'header'.
     * - callback: OAuth callback
     * - consumer_key: Consumer key string. Defaults to "anonymous".
     * - consumer_secret: Consumer secret. Defaults to "anonymous".
     * - private_key_file: The location of your private key file (RSA-SHA1
     *   signature method only)
     * - private_key_passphrase: The passphrase for your private key file
     *   (RSA-SHA1 signature method only)
     * - token: Client token
     * - token_secret: Client secret token
     * - verifier: OAuth verifier.
     * - version: OAuth version. Defaults to '1.0'.
     * - realm: OAuth realm.
     * - signature_method: Signature method. One of 'HMAC-SHA1', 'RSA-SHA1',
     *   'HMAC-SHA256', or 'PLAINTEXT'. Defaults to 'HMAC-SHA1'.
     *
     * @param array $config Configuration array.
     */
    public function __construct(array $config)
    {
        $this->config = [
            'version' => '1.0',
            'request_method' => self::REQUEST_METHOD_HEADER,
            'consumer_key' => 'anonymous',
            'consumer_secret' => 'anonymous',
            'signature_method' => self::SIGNATURE_METHOD_HMAC,
        ];

        foreach ($config as $key => $value) {
            $this->config[$key] = $value;
        }
    }

    /**
     * Called when the middleware is handled.
     *
     * @return \Closure
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function __invoke(callable $handler)
    {
        return function ($request, array $options) use ($handler) {
            if (isset($options['auth']) && $options['auth'] == 'oauth') {
                $request = $this->onBefore($request);
            }

            return $handler($request, $options);
        };
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    private function onBefore(RequestInterface $request): RequestInterface
    {
        $oauthparams = self::getOauthParams($this->config);

        $oauthparams['oauth_signature'] = $this->getSignature($request, $oauthparams);
        uksort($oauthparams, 'strcmp');

        switch ($this->config['request_method']) {
            case self::REQUEST_METHOD_HEADER:
                list($header, $value) = $this->buildAuthorizationHeader($oauthparams);
                $request = $request->withHeader($header, $value);
                break;
            case self::REQUEST_METHOD_QUERY:
                $queryParams = Query::parse($request->getUri()->getQuery());
                $preparedParams = Query::build($oauthparams + $queryParams);
                $request = $request->withUri($request->getUri()->withQuery($preparedParams));
                break;
            default:
                throw new \InvalidArgumentException(sprintf(
                    'Invalid consumer method "%s"',
                    $this->config['request_method']
                ));
        }

        return $request;
    }

    /**
     * Calculate signature for request
     *
     * @param RequestInterface $request Request to generate a signature for
     * @param array            $params  Oauth parameters.
     *
     * @throws \RuntimeException
     */
    public function getSignature(RequestInterface $request, array $params): string
    {
        // Remove oauth_signature if present
        // Ref: Spec: 9.1.1 ("The oauth_signature parameter MUST be excluded.")
        unset($params['oauth_signature']);

        // Add POST fields if the request uses POST fields and no files
        if ($request->getHeaderLine('Content-Type') === 'application/x-www-form-urlencoded') {
            $body = Query::parse($request->getBody()->getContents());
            $params += $body;
        }

        // Parse & add query string parameters as base string parameters
        $query = $request->getUri()->getQuery();
        $params += Query::parse($query);

        $baseString = $this->createBaseString(
            $request,
            self::prepareParameters($params)
        );

        // Implements double-dispatch to sign requests
        switch ($this->config['signature_method']) {
            case Oauth1::SIGNATURE_METHOD_HMAC:
                $signature = $this->signUsingHmac('sha1', $baseString);
                break;
            case Oauth1::SIGNATURE_METHOD_HMACSHA256:
                $signature = $this->signUsingHmac('sha256', $baseString);
                break;
            case Oauth1::SIGNATURE_METHOD_RSA:
                $signature = $this->signUsingRsaSha1($baseString);
                break;
            case Oauth1::SIGNATURE_METHOD_PLAINTEXT:
                $signature = $this->signUsingPlaintext($baseString);
                break;
            default:
                throw new \RuntimeException('Unknown signature method: '.$this->config['signature_method']);
                break;
        }

        return base64_encode($signature);
    }

    /**
     * Creates the Signature Base String.
     *
     * The Signature Base String is a consistent reproducible concatenation of
     * the request elements into a single string. The string is used as an
     * input in hashing or signing algorithms.
     *
     * @param RequestInterface $request Request being signed
     * @param array            $params  Associative array of OAuth parameters
     *
     * @see https://oauth.net/core/1.0/#sig_base_example
     */
    protected function createBaseString(RequestInterface $request, array $params): string
    {
        // Remove query params from URL. Ref: Spec: 9.1.2.
        return strtoupper($request->getMethod())
            .'&'.rawurlencode((string) $request->getUri()->withQuery(''))
            .'&'.rawurlencode(Query::build($params));
    }

    /**
     * @param array $data The data array
     */
    private static function prepareParameters(array $data): array
    {
        // Parameters are sorted by name, using lexicographical byte value
        // ordering. Ref: Spec: 9.1.1 (1).
        uksort($data, 'strcmp');

        foreach ($data as $key => $value) {
            if ($value === null) {
                unset($data[$key]);
            }
        }

        return $data;
    }

    /**
     * @param string $algo Name of selected hashing algorithm (i.e. "md5", "sha256", "haval160,4", etc..)
     */
    private function signUsingHmac(string $algo, string $baseString): string
    {
        $key = rawurlencode($this->config['consumer_secret']).'&';
        if (isset($this->config['token_secret'])) {
            $key .= rawurlencode($this->config['token_secret']);
        }

        return hash_hmac($algo, $baseString, $key, true);
    }

    /**
     * @throws RuntimeException
     */
    private function signUsingRsaSha1(string $baseString): string
    {
        if (!function_exists('openssl_pkey_get_private')) {
            throw new \RuntimeException('RSA-SHA1 signature method requires the OpenSSL extension.');
        }

        $privateKey = openssl_pkey_get_private(
            file_get_contents($this->config['private_key_file']),
            $this->config['private_key_passphrase']
        );

        $signature = '';
        openssl_sign($baseString, $signature, $privateKey);
        unset($privateKey);

        return $signature;
    }

    /**
     * @return string
     */
    private function signUsingPlaintext(string $baseString)
    {
        return $baseString;
    }

    /**
     * Builds the Authorization header for a request
     *
     * @param array $params Associative array of authorization parameters.
     */
    private function buildAuthorizationHeader(array $params): array
    {
        foreach ($params as $key => $value) {
            $params[$key] = $key.'="'.rawurlencode((string) $value).'"';
        }

        if (isset($this->config['realm'])) {
            array_unshift(
                $params,
                'realm="'.rawurlencode($this->config['realm']).'"'
            );
        }

        return ['Authorization', 'OAuth '.implode(', ', $params)];
    }

    /**
     * Get the oauth parameters as named by the oauth spec
     *
     * @param array $config Configuration options of the plugin.
     */
    private static function getOauthParams(array $config): array
    {
        $params = [
            'oauth_consumer_key' => $config['consumer_key'],
            'oauth_nonce' => bin2hex(random_bytes(20)),
            'oauth_signature_method' => $config['signature_method'],
            'oauth_timestamp' => time(),
        ];

        // Optional parameters should not be set if they have not been set in
        // the config as the parameter may be considered invalid by the Oauth
        // service.
        $optionalParams = [
            'callback' => 'oauth_callback',
            'token' => 'oauth_token',
            'verifier' => 'oauth_verifier',
            'version' => 'oauth_version',
            'bodyhash' => 'oauth_body_hash',
        ];

        foreach ($optionalParams as $optionName => $oauthName) {
            if (isset($config[$optionName])) {
                $params[$oauthName] = $config[$optionName];
            }
        }

        return $params;
    }
}
