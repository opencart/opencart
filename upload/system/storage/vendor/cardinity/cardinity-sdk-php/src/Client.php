<?php

namespace Cardinity;

use Cardinity\Http\ClientInterface;
use Cardinity\Http\Guzzle;
use Cardinity\Method\MethodInterface;
use Cardinity\Method\MethodResultCollectionInterface;
use Cardinity\Method\ResultObjectInterface;
use Cardinity\Method\ResultObjectMapper;
use Cardinity\Method\ResultObjectMapperInterface;
use Cardinity\Method\Validator;
use Cardinity\Method\ValidatorInterface;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Subscriber\Oauth\Oauth1;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Url;

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
     * @param LoggerInterface $logger Logs messages.
     * @return self
     */
    public static function create(array $options = [], $logger = Client::LOG_NONE)
    {
        $oauth = new Oauth1([
            'token_secret' => '',
            'consumer_key' => $options['consumerKey'],
            'consumer_secret' => $options['consumerSecret']
        ]);

        $validator = Validation::createValidator();

        if(isset($options['apiEndpoint'])){
            self::validateClientEndpoint($options, $validator);
            self::$url = $options['apiEndpoint'];
        }

        $stack = HandlerStack::create();
        $stack->push($oauth);

        if (!empty($logger)) {
            $stack->push(
                Middleware::log($logger, new MessageFormatter(MessageFormatter::DEBUG))
            );
        }

        $client = new \GuzzleHttp\Client([
            'base_uri' => self::$url,
            'handler' => $stack,
            'auth' => 'oauth'
        ]);

        $mapper = new ResultObjectMapper();

        return new self(
            new Guzzle\ClientAdapter($client, new Guzzle\ExceptionMapper($mapper)),
            new Validator($validator),
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

        if(!is_array($result)){
            throw new Exception\UnexpectedError('Unexpected error :'.$result);
        }

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

    /**
     * Validate endPoint is a valid URL
     *
     * @param [array] $options
     * @param [Validator] $validator
     * @return void
     */
    private static function validateClientEndpoint($options, $validator){
        $violations = $validator->validate($options['apiEndpoint'], [
            new Url(),
        ]);
        if(count($violations) != 0){
            throw new Exception\InvalidAttributeValue(
                'Your API endpoint is not a valid URL',
                $violations
            );
        }
    }
}
