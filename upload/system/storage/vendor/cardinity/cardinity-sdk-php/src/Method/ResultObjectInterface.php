<?php

namespace Cardinity\Method;

/**
 * Represents value object to store response result data
 */
interface ResultObjectInterface extends \Serializable
{
    /**
     * Return errors
     * @return array
     */
    public function getErrors();
}
