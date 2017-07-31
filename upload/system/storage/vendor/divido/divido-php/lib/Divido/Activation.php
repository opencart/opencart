<?php

class Divido_Activation extends Divido_ApiResource
{
  /**
   * @param string $id The ID of the charge to retrieve.
   * @param string|null $apiKey
   *
   * @return Divido_Activation
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
   * @return array An array of Divido_Activation.
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
   * @return Divido_Activate
   */
  public static function activate($params=null, $merchant=null)
  {
    $class = get_class();
    return self::_scopedActivateRequest($class, $params, $merchant);
  }

  /**
   * @return Divido_Activation The saved charge.
   */
  public function save()
  {
    $class = get_class();
    return self::_scopedSave($class);
  }

}
