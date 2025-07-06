<?php

// +----------------------------------------------------------------------
// | WeChatDeveloper
// +----------------------------------------------------------------------
// | 版权所有 2014~2025 ThinkAdmin [ thinkadmin.top ]
// +----------------------------------------------------------------------
// | 官方网站: https://thinkadmin.top
// +----------------------------------------------------------------------
// | 开源协议 ( https://mit-license.org )
// | 免责声明 ( https://thinkadmin.top/disclaimer )
// +----------------------------------------------------------------------
// | gitee 代码仓库：https://gitee.com/zoujingli/WeChatDeveloper
// | github 代码仓库：https://github.com/zoujingli/WeChatDeveloper
// +----------------------------------------------------------------------

namespace WeChat\Contracts;

use WeChat\Exceptions\InvalidArgumentException;
use WeChat\Exceptions\InvalidResponseException;
use WeChat\Exceptions\LocalCacheException;

// =====================================================
// 配置缓存处理函数 ( 适配其他环境 )
// -----------------------------------------------------
// 数据缓存 (set|get|del) 操作可以将缓存写到任意位置或Redis
// 文件缓存 (put) 只能写在本地服务器，还需要返回可读的文件路径
// 未配置自定义缓存处理机制时，默认在 cache_path 写入文件缓存
// // =====================================================
// \WeChat\Contracts\Tools::$cache_callable = [
//    'set' => function ($name, $value, $expired = 360) {
//        var_dump(func_get_args());
//         return $value;
//    },
//    'get' => function ($name) {
//        var_dump(func_get_args());
//        return $value;
//    },
//    'del' => function ($name) {
//        var_dump(func_get_args());
//        return true;
//    },
//    'put' => function ($name) {
//        var_dump(func_get_args());
//        return $filePath;
//    },
// ];

/**
 * 网络请求支持
 * Class Tools
 * @package WeChat\Contracts
 */
class Tools
{
    /**
     * 缓存路径
     * @var null
     */
    public static $cache_path = null;

    /**
     * 缓存读写配置
     * @var array
     */
    public static $cache_callable = [
        'set' => null, // 写入缓存 ($name,$value='',$expired=3600):string
        'get' => null, // 获取缓存 ($name):mixed|null
        'del' => null, // 删除缓存 ($name):boolean
        'put' => null, // 写入文件 ($name,$content):string
    ];

    /**
     * 网络缓存
     * @var array
     */
    private static $cache_curl = [];

    /**
     * 产生随机字符串
     * @param int $length 指定字符长度
     * @param string $str 字符串前缀
     * @return string
     */
    public static function createNoncestr($length = 32, $str = "")
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    /**
     * 获取输入对象
     * @return string
     */
    public static function getRawInput()
    {
        if (empty($GLOBALS['HTTP_RAW_POST_DATA'])) {
            return file_get_contents('php://input');
        } else {
            return $GLOBALS['HTTP_RAW_POST_DATA'];
        }
    }

    /**
     * 设置输入内容
     * @param string $rawInput
     * @return void
     */
    public static function setRawInput($rawInput)
    {
        $GLOBALS['HTTP_RAW_POST_DATA'] = $rawInput;
    }

    /**
     * 根据文件后缀获取文件类型
     * @param string|array $ext 文件后缀
     * @param array $mine 文件后缀MINE信息
     * @return string
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public static function getExtMine($ext, $mine = [])
    {
        $mines = self::getMines();
        foreach (is_string($ext) ? explode(',', $ext) : $ext as $e) {
            $mine[] = isset($mines[strtolower($e)]) ? $mines[strtolower($e)] : 'application/octet-stream';
        }
        return join(',', array_unique($mine));
    }

    /**
     * 获取所有文件扩展的类型
     * @return array
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    private static function getMines()
    {
        $mines = self::getCache('all_ext_mine');
        if (empty($mines)) {
            $content = file_get_contents('http://svn.apache.org/repos/asf/httpd/httpd/trunk/docs/conf/mime.types');
            preg_match_all('#^([^\s]{2,}?)\s+(.+?)$#ism', $content, $matches, PREG_SET_ORDER);
            foreach ($matches as $match) foreach (explode(" ", $match[2]) as $ext) $mines[$ext] = $match[1];
            self::setCache('all_ext_mine', $mines);
        }
        return $mines;
    }

    /**
     * 创建CURL文件对象
     * @param mixed $filename
     * @param string $mimetype
     * @param string $postname
     * @return \CURLFile|string
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public static function createCurlFile($filename, $mimetype = null, $postname = null)
    {
        if (is_string($filename) && file_exists($filename)) {
            if (is_null($postname)) $postname = basename($filename);
            if (is_null($mimetype)) $mimetype = self::getExtMine(pathinfo($filename, 4));
            if (class_exists('CURLFile')) {
                return new \CURLFile($filename, $mimetype, $postname);
            } else {
                return "@{$filename};filename={$postname};type={$mimetype}";
            }
        }
        return $filename;
    }

    /**
     * 数组转XML内容
     * @param array $data
     * @return string
     */
    public static function arr2xml($data)
    {
        return "<xml>" . self::_arr2xml($data) . "</xml>";
    }

    /**
     * XML内容生成
     * @param array $data 数据
     * @param string $content
     * @return string
     */
    private static function _arr2xml($data, $content = '')
    {
        foreach ($data as $key => $val) {
            is_numeric($key) && $key = 'item';
            $content .= "<{$key}>";
            if (is_array($val) || is_object($val)) {
                $content .= self::_arr2xml($val);
            } elseif (is_string($val)) {
                $content .= '<![CDATA[' . preg_replace("/[\\x00-\\x08\\x0b-\\x0c\\x0e-\\x1f]/", '', $val) . ']]>';
            } else {
                $content .= $val;
            }
            $content .= "</{$key}>";
        }
        return $content;
    }

    /**
     * 解析XML内容到数组
     * @param string $xml
     * @return array
     */
    public static function xml2arr($xml)
    {
        if (PHP_VERSION_ID < 80000) {
            $backup = libxml_disable_entity_loader(true);
            $data = (array)simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
            libxml_disable_entity_loader($backup);
        } else {
            $data = (array)simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        }
        return json_decode(json_encode($data), true);
    }

    /**
     * 解析XML文本内容
     * @param string $xml
     * @return array|false
     */
    public static function xml3arr($xml)
    {
        $state = xml_parse($parser = xml_parser_create(), $xml, true);
        return xml_parser_free($parser) && $state ? self::xml2arr($xml) : false;
    }

    /**
     * 数组转xml内容
     * @param array $data
     * @return null|string
     */
    public static function arr2json($data)
    {
        $json = json_encode($data, JSON_UNESCAPED_UNICODE);
        return $json === '[]' ? '{}' : $json;
    }

    /**
     * 数组对象Emoji编译处理
     * @param array $data
     * @return array
     */
    public static function buildEnEmojiData(array $data)
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = self::buildEnEmojiData($value);
            } elseif (is_string($value)) {
                $data[$key] = self::emojiEncode($value);
            } else {
                $data[$key] = $value;
            }
        }
        return $data;
    }

    /**
     * 数组对象Emoji反解析处理
     * @param array $data
     * @return array
     */
    public static function buildDeEmojiData(array $data)
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = self::buildDeEmojiData($value);
            } elseif (is_string($value)) {
                $data[$key] = self::emojiDecode($value);
            } else {
                $data[$key] = $value;
            }
        }
        return $data;
    }

    /**
     * Emoji原形转换为String
     * @param string $content
     * @return string
     */
    public static function emojiEncode($content)
    {
        return json_decode(preg_replace_callback("/(\\\u[ed][0-9a-f]{3})/i", function ($string) {
            return addslashes($string[0]);
        }, json_encode($content)));
    }

    /**
     * Emoji字符串转换为原形
     * @param string $content
     * @return string
     */
    public static function emojiDecode($content)
    {
        return json_decode(preg_replace_callback('/\\\\\\\\/i', function () {
            return '\\';
        }, json_encode($content)));
    }

    /**
     * 解析JSON内容到数组
     * @param string $json
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public static function json2arr($json)
    {
        $result = json_decode($json, true);
        if (empty($result)) {
            throw new InvalidResponseException('invalid response.', '0');
        }
        if (!empty($result['errcode'])) {
            throw new InvalidResponseException($result['errmsg'], $result['errcode'], $result);
        }
        return $result;
    }

    /**
     * 以get访问模拟访问
     * @param string $url 访问URL
     * @param array $query GET数
     * @param array $options
     * @return boolean|string
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public static function get($url, $query = [], $options = [])
    {
        $options['query'] = $query;
        return self::doRequest('get', $url, $options);
    }

    /**
     * 以post访问模拟访问
     * @param string $url 访问URL
     * @param array $data POST数据
     * @param array $options
     * @return boolean|string
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public static function post($url, $data = [], $options = [])
    {
        $options['data'] = $data;
        return self::doRequest('post', $url, $options);
    }

    /**
     * CURL模拟网络请求
     * @param string $method 请求方法
     * @param string $url 请求方法
     * @param array $options 请求参数[headers,data,ssl_cer,ssl_key]
     * @return boolean|string
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public static function doRequest($method, $url, $options = [])
    {
        $curl = curl_init();
        // GET参数设置
        if (!empty($options['query'])) {
            $url .= (stripos($url, '?') !== false ? '&' : '?') . http_build_query($options['query']);
        }
        // CURL头信息设置
        if (!empty($options['headers'])) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $options['headers']);
        }
        // POST数据设置
        if (strtolower($method) === 'post') {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, self::_buildHttpData($options['data']));
        }
        // 证书文件设置
        if (!empty($options['ssl_cer'])) if (file_exists($options['ssl_cer'])) {
            curl_setopt($curl, CURLOPT_SSLCERTTYPE, 'PEM');
            curl_setopt($curl, CURLOPT_SSLCERT, $options['ssl_cer']);
        } else throw new InvalidArgumentException("Certificate files that do not exist. --- [ssl_cer]");
        // 证书文件设置
        if (!empty($options['ssl_key'])) if (file_exists($options['ssl_key'])) {
            curl_setopt($curl, CURLOPT_SSLKEYTYPE, 'PEM');
            curl_setopt($curl, CURLOPT_SSLKEY, $options['ssl_key']);
        } else throw new InvalidArgumentException("Certificate files that do not exist. --- [ssl_key]");
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_TIMEOUT, 60);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        $content = curl_exec($curl);
        curl_close($curl);
        // 清理 CURL 缓存文件
        if (!empty(self::$cache_curl)) foreach (self::$cache_curl as $key => $file) {
            Tools::delCache($file);
            unset(self::$cache_curl[$key]);
        }
        return $content;
    }

    /**
     * POST数据过滤处理
     * @param array $data 需要处理的数据
     * @param boolean $build 是否编译数据
     * @return array|string
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    private static function _buildHttpData($data, $build = true)
    {
        if (!is_array($data)) return $data;
        foreach ($data as $key => $value) if ($value instanceof \CURLFile) {
            $build = false;
        } elseif (is_object($value) && isset($value->datatype) && $value->datatype === 'MY_CURL_FILE') {
            $build = false;
            $mycurl = new MyCurlFile((array)$value);
            $data[$key] = $mycurl->get();
            self::$cache_curl[] = $mycurl->tempname;
        } elseif (is_array($value) && isset($value['datatype']) && $value['datatype'] === 'MY_CURL_FILE') {
            $build = false;
            $mycurl = new MyCurlFile($value);
            $data[$key] = $mycurl->get();
            self::$cache_curl[] = $mycurl->tempname;
        } elseif (is_string($value) && class_exists('CURLFile', false) && stripos($value, '@') === 0) {
            if (($filename = realpath(trim($value, '@'))) && file_exists($filename)) {
                $build = false;
                $data[$key] = self::createCurlFile($filename);
            }
        }
        return $build ? http_build_query($data) : $data;
    }

    /**
     * 写入文件
     * @param string $name 文件名称
     * @param string $content 文件内容
     * @return string
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public static function pushFile($name, $content)
    {
        if (is_callable(self::$cache_callable['put'])) {
            return call_user_func_array(self::$cache_callable['put'], func_get_args());
        }
        $file = self::_getCacheName($name);
        if (!file_put_contents($file, $content)) {
            throw new LocalCacheException('local file write error.', '0');
        }
        return $file;
    }

    /**
     * 缓存配置与存储
     * @param string $name 缓存名称
     * @param string $value 缓存内容
     * @param int $expired 缓存时间(0表示永久缓存)
     * @return string
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public static function setCache($name, $value = '', $expired = 3600)
    {
        if (is_callable(self::$cache_callable['set'])) {
            return call_user_func_array(self::$cache_callable['set'], func_get_args());
        }
        $file = self::_getCacheName($name);
        $data = ['name' => $name, 'value' => $value, 'expired' => time() + intval($expired)];
        if (!file_put_contents($file, serialize($data))) {
            throw new LocalCacheException('local cache error.', '0');
        }
        return $file;
    }

    /**
     * 获取缓存内容
     * @param string $name 缓存名称
     * @return null|mixed
     */
    public static function getCache($name)
    {
        if (is_callable(self::$cache_callable['get'])) {
            return call_user_func_array(self::$cache_callable['get'], func_get_args());
        }
        $file = self::_getCacheName($name);
        if (file_exists($file) && is_file($file) && ($content = file_get_contents($file))) {
            $data = unserialize($content);
            if (isset($data['expired']) && (intval($data['expired']) === 0 || intval($data['expired']) >= time())) {
                return $data['value'];
            }
            self::delCache($name);
        }
        return null;
    }

    /**
     * 移除缓存文件
     * @param string $name 缓存名称
     * @return boolean
     */
    public static function delCache($name)
    {
        if (is_callable(self::$cache_callable['del'])) {
            return call_user_func_array(self::$cache_callable['del'], func_get_args());
        }
        $file = self::_getCacheName($name);
        return !file_exists($file) || @unlink($file);
    }

    /**
     * 应用缓存目录
     * @param string $name
     * @return string
     */
    private static function _getCacheName($name)
    {
        if (empty(self::$cache_path)) {
            self::$cache_path = dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'Cache' . DIRECTORY_SEPARATOR;
        }
        self::$cache_path = rtrim(self::$cache_path, '/\\') . DIRECTORY_SEPARATOR;
        file_exists(self::$cache_path) || mkdir(self::$cache_path, 0755, true);
        return self::$cache_path . $name;
    }
}
