<?php

namespace Cardinity\Method;

interface ResultObjectMapperInterface
{
    public function map(array $response, ResultObjectInterface $result);
    public function mapCollection(array $response, MethodResultCollectionInterface $result);
}
