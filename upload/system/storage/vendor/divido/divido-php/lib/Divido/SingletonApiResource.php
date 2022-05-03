<?php

abstract class Divido_SingletonApiResource extends Divido_ApiResource
{
  protected static function _scopedSingletonRetrieve($class, $apiKey=null)
  {
    $instance = new $class(null, $apiKey);
    $instance->refresh();
    return $instance;
  }

  /**
   * @param Divido_SingletonApiResource $class
   * @return string The endpoint associated with this singleton class.
   */
  public static function classUrl($class)
  {
    $base = self::className($class);
    return "/v1/${base}";
  }

  /**
   * @return string The endpoint associated with this singleton API resource.
   */
  public function instanceUrl()
  {
    $class = get_class($this);
    $base = self::classUrl($class);
    return "$base";
  }
}
