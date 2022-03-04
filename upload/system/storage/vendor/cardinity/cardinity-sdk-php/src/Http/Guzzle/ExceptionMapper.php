<?php

namespace Cardinity\Http\Guzzle;

use Cardinity\Method\Error;
use Cardinity\Method\MethodInterface;
use Cardinity\Method\ResultObjectMapperInterface;
use GuzzleHttp\Exception\ClientException;

class ExceptionMapper
{
    /** @type ResultObjectMapperInterface */
    private $resultMapper;

    /**
     * @param ResultObjectMapperInterface $resultMapper
     */
    public function __construct(ResultObjectMapperInterface $resultMapper)
    {
        $this->resultMapper = $resultMapper;
    }

    /**
     * Get mapped exception
     * @param ClientException $exception
     * @param MethodInterface $method
     * @return Cardinity\Exception\Request
     */
    public function get(ClientException $exception, MethodInterface $method)
    {
        $map = $this->getMap();
        if ($this->supports($exception, $map)) {
            return $this->create(
                $this->getMappedClass($exception, $map),
                $exception,
                $method
            );
        }

        return $this->create(
            'Cardinity\\Exception\\UnexpectedResponse',
            $exception,
            $method
        );
    }

    private function getMappedClass(ClientException $exception, $map)
    {
        return $map[$exception->getCode()];
    }

    private function supports(ClientException $exception, $map)
    {
        return array_key_exists($exception->getCode(), $map);
    }

    private function getMap()
    {
        return [
            400 => 'Cardinity\\Exception\\ValidationFailed',
            401 => 'Cardinity\\Exception\\Unauthorized',
            402 => 'Cardinity\\Exception\\Declined',
            403 => 'Cardinity\\Exception\\Forbidden',
            404 => 'Cardinity\\Exception\\NotFound',
            405 => 'Cardinity\\Exception\\MethodNotAllowed',
            406 => 'Cardinity\\Exception\\NotAcceptable',
            500 => 'Cardinity\\Exception\\InternalServerError',
            503 => 'Cardinity\\Exception\\ServiceUnavailable',
        ];
    }

    private function create($class, ClientException $exception, MethodInterface $method)
    {
        $response = json_decode($exception->getResponse()->getBody(), true);

        // map declined response to result object
        if ($exception->getCode() == 402) {
            $resultObject = $method->createResultObject();
        } else {
            $resultObject = new Error();
        }

        $response = $this->resultMapper->map(
            $response,
            $resultObject
        );

        return new $class(
            $exception,
            $response
        );
    }
}
