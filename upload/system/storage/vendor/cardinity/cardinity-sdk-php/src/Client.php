<?php

namespace Cardinity;

use Cardinity\Http\ClientInterface;
use Cardinity\Http\Guzzle;
use Cardinity\Method\MethodInterface;
use Cardinity\Method\MethodResultCollectionInterface;
use Cardinity\Method\ResultObjectMapper;
use Cardinity\Method\ResultObjectMapperInterface;
use Cardinity\Method\Validator;
use Cardinity\Method\ValidatorInterface;
use GuzzleHttp\Subscriber\Log\Formatter;
use GuzzleHttp\Subscriber\Log\LogSubscriber;
use GuzzleHttp\Subscriber\Oauth\Oauth1;
use Symfony\Component\Validator\Validation;

class Client
{
    /**
     * Disable logger
     */
    const LOG_NONE = false;

    /**
     * Turn on debug mode
     */
    const LOG_DEBUG = null;

    /** @type ClientInterface */
    private $client;

    /** @type ValidatorInterface */
    private $validator;

    /** @type ResultObjectMapperInterface */
    private $mapper;

    /** @type string */
    private static $url = 'https://api.cardinity.com/v1/';

    /**
     * Public factory method to create instance of Client.
     *
     * @param array $options Available properties: [
     *     'consumerKey' => 'foo',
     *     'consumerSecret' => 'bar',
     * ]
     * @param mixed $logger Logger used to log
     *     messages. Pass a LoggerInterface to use a PSR-3 logger. Pass a
     *     callable to log messages to a function that accepts a string of
     *     data. Pass a resource returned from ``fopen()`` to log to an open
     *     resource. Pass null or leave empty to write log messages using
     *     ``echo()``.
     * @return self
     */
    public static function create(array $options = [], $logger = Client::LOG_NONE)
    {
        $client = new \GuzzleHttp\Client([
            'base_url' => self::$url,
            'defaults' => ['auth' => 'oauth']
        ]);

        if ($logger !== false) {
            $subscriber = new LogSubscriber($logger, Formatter::DEBUG);
            $client->getEmitter()->attach($subscriber);
        }

        $oauth = new Oauth1([
            'consumer_key' => $options['consumerKey'],
            'consumer_secret' => $options['consumerSecret']
        ]);
        $client->getEmitter()->attach($oauth);

        $mapper = new ResultObjectMapper();

        return new self(
            new Guzzle\ClientAdapter($client, new Guzzle\ExceptionMapper($mapper)),
            new Validator(Validation::createValidator()),
            $mapper
        );
    }
    /**
     * @param ClientInterface $client
     * @param ValidatorInterface $validator
     * @param ResultObjectMapperInterface $mapper
     */
    public function __construct(
        ClientInterface $client,
        ValidatorInterface $validator,
        ResultObjectMapperInterface $mapper
    ) {
        $this->client = $client;
        $this->validator = $validator;
        $this->mapper = $mapper;
    }

    /**
     * Call the given method.
     * @param MethodInterface $method
     * @return ResultObjectInterface|array
     */
    public function call(MethodInterface $method)
    {
        $this->validator->validate($method);

        return $this->handleRequest($method);
    }

    /**
     * Call the particular method without data validation
     * @param MethodInterface $method
     * @return ResultObjectInterface|array
     */
    public function callNoValidate(MethodInterface $method)
    {
        return $this->handleRequest($method);
    }

    /**
     * Handle all the request/response hard work
     * @param MethodInterface $method
     * @return ResultObjectInterface|array
     */
    private function handleRequest(MethodInterface $method)
    {
        $result = $this->client->sendRequest(
            $method,
            $method->getMethod(),
            $method->getAction(),
            $this->getOptions($method)
        );

        if ($method instanceof MethodResultCollectionInterface) {
            return $this->mapper->mapCollection($result, $method);
        }

        return $this->mapper->map($result, $method->createResultObject());
    }

    /**
     * Prepare request options for particular method
     * @param MethodInterface $method
     * @return array
     */
    private function getOptions(MethodInterface $method)
    {
        if ($method->getMethod() == $method::GET) {
            return [
                'query' => $method->getAttributes(),
            ];
        }

        return [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => json_encode(
                $this->prepareAttributes($method->getAttributes()),
                JSON_FORCE_OBJECT
            )
        ];
    }

    /**
     * Prepare request attributes
     * @param array $data
     * @return array
     */
    private function prepareAttributes(array $data)
    {
        foreach ($data as $key => &$value) {
            if (is_array($value)) {
                $data[$key] = $this->prepareAttributes($value);
                continue;
            }

            if (is_float($value)) {
                $value = sprintf("%01.2f", $value);
            }
        }

        return $data;
    }
}
