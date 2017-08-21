<?php

abstract class Divido_Util
{
  /**
   * Whether the provided array (or other) is a list rather than a dictionary.
   *
   * @param array|mixed $array
   * @return boolean True if the given object is a list.
   */
  public static function isList($array)
  {
    if (!is_array($array))
      return false;

    // TODO: generally incorrect, but it's correct given Divido's response
    foreach (array_keys($array) as $k) {
      if (!is_numeric($k))
        return false;
    }
    return true;
  }

  /**
   * Recursively converts the PHP Divido object to an array.
   *
   * @param array $values The PHP Divido object to convert.
   * @return array
   */
  public static function convertDividoObjectToArray($values)
  {
    $results = array();
    foreach ($values as $k => $v) {
      // FIXME: this is an encapsulation violation
      if ($k[0] == '_') {
        continue;
      }
      if ($v instanceof Divido_Object) {
        $results[$k] = $v->__toArray(true);
      } else if (is_array($v)) {
        $results[$k] = self::convertDividoObjectToArray($v);
      } else {
        $results[$k] = $v;
      }
    }
    return $results;
  }

  /**
   * Converts a response from the Divido API to the corresponding PHP object.
   *
   * @param array $resp The response from the Divido API.
   * @param string $apiKey
   * @return Divido_Object|array
   */
  public static function convertToDividoObject($resp, $apiKey)
  {
    $types = array(
      'customer' => 'Divido_Customer',
      'list' => 'Divido_List',
      'invoice' => 'Divido_Invoice',
      'invoiceitem' => 'Divido_InvoiceItem',
      'event' => 'Divido_Event',
      'transfer' => 'Divido_Transfer',
      'plan' => 'Divido_Plan',
      'recipient' => 'Divido_Recipient',
      'refund' => 'Divido_Refund',
      'subscription' => 'Divido_Subscription',
      'fee_refund' => 'Divido_ApplicationFeeRefund'
    );
    if (self::isList($resp)) {
      $mapped = array();
      foreach ($resp as $i)
        array_push($mapped, self::convertToDividoObject($i, $apiKey));
      return $mapped;
    } else if (is_array($resp)) {
      if (isset($resp['object'])
          && is_string($resp['object'])
          && isset($types[$resp['object']])) {
        $class = $types[$resp['object']];
      } else {
        $class = 'Divido_Object';
      }
      return Divido_Object::scopedConstructFrom($class, $resp, $apiKey);
    } else {
      return $resp;
    }
  }
}
