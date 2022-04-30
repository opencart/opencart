<?php

class Divido_CreditRequest extends Divido_ApiResource
{
  /**
   * @param string $id The ID of the charge to retrieve.
   * @param string|null $apiKey
   *
   * @return Divido_Charge
   */
  public static function retrieve($id, $apiKey=null)
  {
    $class = get_class();
    return self::_scopedRetrieve($class, $id, $apiKey);
  }

  /**
   * @param array|null $params
   * @param string|null $apiKey
   *
   * @return array An array of Divido_Charges.
   */
  public static function all($params=null, $apiKey=null)
  {
    $class = get_class();
    return self::_scopedAll($class, $params, $apiKey);
  }

  /**
   * @param array|null $params
   * @param string|null $apiKey
   *
   * @return Divido_Charge The created credit request.
   */
  public static function create($params=null, $merchant=null)
  {
    $class = get_class();
    return self::_scopedCreditRequest($class, $params, $merchant);
  }

  /**
   * @param array|null $params
   * @param string|null $apiKey
   *
   * @return Divido_Finalize The created charge.
   */
  public static function finalize($params=null, $merchant=null)
  {
    $class = get_class();
    return self::_scopedFinalizeRequest($class, $params, $merchant);
  }

  /**
   * @return Divido_Charge The saved charge.
   */
  public function save()
  {
    $class = get_class();
    return self::_scopedSave($class);
  }

}
