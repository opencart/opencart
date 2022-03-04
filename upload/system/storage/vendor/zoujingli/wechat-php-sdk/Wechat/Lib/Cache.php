<?php

// +----------------------------------------------------------------------
// | wechat-php-sdk
// +----------------------------------------------------------------------
// | 版权所有 2014~2017 广州楚才信息科技有限公司 [ http://www.cuci.cc ]
// +----------------------------------------------------------------------
// | 官方文档: https://www.kancloud.cn/zoujingli/wechat-php-sdk
// +----------------------------------------------------------------------
// | 开源协议 ( https://mit-license.org )
// +----------------------------------------------------------------------
// | github开源项目：https://github.com/zoujingli/wechat-php-sdk
// +----------------------------------------------------------------------


namespace Wechat\Lib;

use Wechat\Loader;

/**
 * 微信SDK基础缓存类
 *
 * @author Anyon <zoujingli@qq.com>
 * @date 2016-08-20 17:50
 */
class Cache
{

    /**
     * 缓存位置
     * @var string
     */
    static public $cachepath;

    /**
     * 设置缓存
     * @param string $name
     * @param string $value
     * @param int $expired
     * @return mixed
     */
    static public function set($name, $value, $expired = 0)
    {
        if (isset(Loader::$callback['CacheSet'])) {
            return call_user_func_array(Loader::$callback['CacheSet'], func_get_args());
        }
        $data = serialize(array('value' => $value, 'expired' => $expired > 0 ? time() + $expired : 0));
        return self::check() && file_put_contents(self::$cachepath . $name, $data);
    }

    /**
     * 读取缓存
     * @param string $name
     * @return mixed
     */
    static public function get($name)
    {
        if (isset(Loader::$callback['CacheGet'])) {
            return call_user_func_array(Loader::$callback['CacheGet'], func_get_args());
        }
        if (self::check() && ($file = self::$cachepath . $name) && file_exists($file) && ($data = file_get_contents($file)) && !empty($data)) {
            $data = unserialize($data);
            if (isset($data['expired']) && ($data['expired'] > time() || $data['expired'] === 0)) {
                return isset($data['value']) ? $data['value'] : null;
            }
        }
        return null;
    }

    /**
     * 删除缓存
     * @param string $name
     * @return mixed
     */
    static public function del($name)
    {
        if (isset(Loader::$callback['CacheDel'])) {
            return call_user_func_array(Loader::$callback['CacheDel'], func_get_args());
        }
        return self::check() && @unlink(self::$cachepath . $name);
    }

    /**
     * 输出内容到日志
     * @param string $line
     * @param string $filename
     * @return mixed
     */
    static public function put($line, $filename = '')
    {
        if (isset(Loader::$callback['CachePut'])) {
            return call_user_func_array(Loader::$callback['CachePut'], func_get_args());
        }
        empty($filename) && $filename = date('Ymd') . '.log';
        return self::check() && file_put_contents(self::$cachepath . $filename, '[' . date('Y/m/d H:i:s') . "] {$line}\n", FILE_APPEND);
    }

    /**
     * 检查缓存目录
     * @return bool
     */
    static protected function check()
    {
        empty(self::$cachepath) && self::$cachepath = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'Cache' . DIRECTORY_SEPARATOR;
        self::$cachepath = rtrim(self::$cachepath, '/\\') . DIRECTORY_SEPARATOR;
        if (!is_dir(self::$cachepath) && !mkdir(self::$cachepath, 0755, true)) {
            return false;
        }
        return true;
    }

    /**
     * 文件缓存，成功返回文件路径
     * @param string $content 文件内容
     * @param string $filename 文件名称
     * @return bool|string
     */
    static public function file($content, $filename = '')
    {
        if (isset(Loader::$callback['CacheFile'])) {
            return call_user_func_array(Loader::$callback['CacheFile'], func_get_args());
        }
        empty($filename) && $filename = md5($content) . '.' . self::getFileExt($content);
        if (self::check() && file_put_contents(self::$cachepath . $filename, $content)) {
            return self::$cachepath . $filename;
        }
        return false;
    }

    /**
     * 根据文件流读取文件后缀
     * @param string $content
     * @return string
     */
    static public function getFileExt($content)
    {
        $types = array(
            255216 => 'jpg', 7173 => 'gif', 6677 => 'bmp', 13780 => 'png',
            7368   => 'mp3', 4838 => 'wma', 7784 => 'mid', 6063 => 'xml',
        );
        $typeInfo = @unpack("C2chars", substr($content, 0, 2));
        $typeCode = intval($typeInfo['chars1'] . $typeInfo['chars2']);
        return isset($types[$typeCode]) ? $types[$typeCode] : 'mp4';
    }

}
