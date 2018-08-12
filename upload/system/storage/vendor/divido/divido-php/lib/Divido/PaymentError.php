<?php

class Divido_PaymentError extends Divido_Error
{
  public function __construct($message, $param, $code, $httpStatus, 
      $httpBody, $jsonBody
  )
  {
    parent::__construct($message, $httpStatus, $httpBody, $jsonBody);
    $this->param = $param;
    $this->code = $code;
  }
}
