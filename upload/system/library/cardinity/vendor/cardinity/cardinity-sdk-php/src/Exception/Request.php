<?php

namespace Cardinity\Exception;

use Cardinity\Method\ResultObjectInterface;

class Request extends Runtime
{
    /** @type ResultObjectInterface */
    private $result;

    /**
     * @param \Exception|null $previous
     * @param ResultObjectInterface|null $result
     */
    public function __construct(?\Exception $previous = null, ?ResultObjectInterface $result = null)
    {
        $this->message .= ' Response data: ' . serialize($result);
        parent::__construct($this->message, $this->code, $previous);

        $this->result = $result;
    }

    /**
     * Get result object of particular response
     * @return ResultObjectInterface
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * List of errors occured
     * @return array
     */
    public function getErrors()
    {
        return $this->result->getErrors();
    }

    /**
     * Errors in string form
     * @return string
     */
    public function getErrorsAsString()
    {
        $string = '';
        foreach ($this->getErrors() as $error) {
            $string .= sprintf(
                "%s: %s",
                $error['field'],
                $error['message']
            );
            if (isset($error['rejected'])) {
                $string .= sprintf(" ('%s' given)", $error['rejected']);
            }
            $string .= ";\n";
        }

        return trim($string);
    }
}
