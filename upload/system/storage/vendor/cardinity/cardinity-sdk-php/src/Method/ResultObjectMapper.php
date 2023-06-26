<?php

namespace Cardinity\Method;

use Cardinity\Exception;
use Cardinity\Method\Payment\AuthorizationInformation;
use Cardinity\Method\Payment\PaymentInstrumentCard;
use Cardinity\Method\Payment\PaymentInstrumentRecurring;

class ResultObjectMapper implements ResultObjectMapperInterface
{
    /**
     * Map each item in response data to instance of ResultObjectInterface
     * @param array $response
     * @param MethodResultCollectionInterface $method
     *
     * @return array
     */
    public function mapCollection(array $response, MethodResultCollectionInterface $method)
    {
        $return = [];

        foreach ($response as $item) {
            $return[] = $this->map($item, $method->createResultObject());
        }

        return $return;
    }

    /**
     * Map response data to instance of ResultObjectInterface
     * @param array $response
     * @param ResultObjectInterface $result
     *
     * @return ResultObjectInterface
     */
    public function map(array $response, ResultObjectInterface $result)
    {
        foreach ($response as $field => $value) {
            $method = $this->getSetterName($field);

            if (!method_exists($result, $method)) {
                continue;
            }

            if ($field == 'payment_instrument') {
                $value = $this->transformPaymentInstrumentValue($value, $response['payment_method']);
            } elseif ($field == 'authorization_information') {
                $value = $this->transformAuthorizationInformationValue($value);
            }

            $result->$method($value);
        }

        return $result;
    }

    /**
     * Extracts camelCased setter name from underscore notation.
     * Eg. my_field_name => myFieldName
     * @param string $field
     * @return string
     */
    private function getSetterName($field)
    {
        $parts = explode('_', $field);
        array_map('ucfirst', $parts);
        $name = 'set' . implode('', $parts);

        return $name;
    }

    /**
     * Transform PaymentInstrument result array to object
     * @param array $data
     * @param string $method
     * @return PaymentInstrumentCard|PaymentInstrumentRecurring
     * @throws Exception\Runtime for unsupported methods
     */
    private function transformPaymentInstrumentValue(array $data, $method)
    {
        if ($method == 'card') {
            $instrument = new PaymentInstrumentCard();
        } elseif ($method == 'recurring') {
            $instrument = new PaymentInstrumentRecurring();
        } else {
            throw new Exception\Runtime(sprintf('Method "%s" is not supported', $method));
        }

        $this->map($data, $instrument);

        return $instrument;
    }

    /**
     * Transform AuthorizationInformation result array to object
     * @param array $data
     * @return AuthorizationInformation
     */
    private function transformAuthorizationInformationValue($data)
    {
        $info = new AuthorizationInformation();
        $this->map($data, $info);

        return $info;
    }
}
