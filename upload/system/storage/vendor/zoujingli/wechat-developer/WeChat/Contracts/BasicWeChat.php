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

/**
 * Class BasicWeChat
 * @package WeChat\Contracts
 */
class BasicWeChat
{

    /**
     * 当前微信配置
     * @var DataArray
     */
    public $config;

    /**
     * 访问AccessToken
     * @var string
     */
    public $access_token = '';

    /**
     * 当前请求方法参数
     * @var array
     */
    protected $currentMethod = [];

    /**
     * 当前模式
     * @var bool
     */
    protected $isTry = false;

    /**
     * 静态缓存
     * @var static
     */
    protected static $cache;

    /**
     * 注册代替函数
     * @var string
     */
    protected $GetAccessTokenCallback;

    /**
     * BasicWeChat constructor.
     * @param array $options
     */
    public function __construct(array $options)
    {
        if (empty($options['appid'])) {
            throw new InvalidArgumentException("Missing Config -- [appid]");
        }
        if (empty($options['appsecret'])) {
            throw new InvalidArgumentException("Missing Config -- [appsecret]");
        }
        if (isset($options['GetAccessTokenCallback']) && is_callable($options['GetAccessTokenCallback'])) {
            $this->GetAccessTokenCallback = $options['GetAccessTokenCallback'];
        }
        if (!empty($options['cache_path'])) {
            Tools::$cache_path = $options['cache_path'];
        }
        $this->config = new DataArray($options);
    }

    /**
     * 静态创建对象
     * @param array $config
     * @return static
     */
    public static function instance(array $config)
    {
        $key = md5(get_called_class() . serialize($config));
        if (isset(self::$cache[$key])) return self::$cache[$key];
        return self::$cache[$key] = new static($config);
    }

    /**
     * 获取访问 AccessToken
     * @return string
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function getAccessToken()
    {
        if (!empty($this->access_token)) {
            return $this->access_token;
        }
        $cache = $this->config->get('appid') . '_access_token';
        $this->access_token = Tools::getCache($cache);
        if (!empty($this->access_token)) {
            return $this->access_token;
        }
        // 处理开放平台授权公众号获取AccessToken
        if (!empty($this->GetAccessTokenCallback) && is_callable($this->GetAccessTokenCallback)) {
            $this->access_token = call_user_func_array($this->GetAccessTokenCallback, [$this->config->get('appid'), $this]);
            if (!empty($this->access_token)) {
                Tools::setCache($cache, $this->access_token, 7000);
            }
            return $this->access_token;
        }
        list($appid, $secret) = [$this->config->get('appid'), $this->config->get('appsecret')];
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$secret}";
        $result = Tools::json2arr(Tools::get($url));
        if (!empty($result['access_token'])) {
            Tools::setCache($cache, $result['access_token'], 7000);
        }
        return $this->access_token = $result['access_token'];
    }

    /**
     * 设置外部接口 AccessToken
     * @param string $accessToken
     * @throws \WeChat\Exceptions\LocalCacheException
     * @author 高一平 <iam@gaoyiping.com>
     *
     * 当用户使用自己的缓存驱动时，直接实例化对象后可直接设置 AccessToken
     * - 多用于分布式项目时保持 AccessToken 统一
     * - 使用此方法后就由用户来保证传入的 AccessToken 为有效 AccessToken
     */
    public function setAccessToken($accessToken)
    {
        if (!is_string($accessToken)) {
            throw new InvalidArgumentException("Invalid AccessToken type, need string.");
        }
        $cache = $this->config->get('appid') . '_access_token';
        Tools::setCache($cache, $this->access_token = $accessToken);
    }

    /**
     * 清理删除 AccessToken
     * @return bool
     */
    public function delAccessToken()
    {
        $this->access_token = '';
        return Tools::delCache($this->config->get('appid') . '_access_token');
    }

    /**
     * 以GET获取接口数据并转为数组
     * @param string $url 接口地址
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    protected function httpGetForJson($url)
    {
        try {
            return Tools::json2arr(Tools::get($url));
        } catch (InvalidResponseException $exception) {
            if (isset($this->currentMethod['method']) && empty($this->isTry)) {
                if (in_array($exception->getCode(), ['40014', '40001', '41001', '42001'])) {
                    [$this->delAccessToken(), $this->isTry = true];
                    return call_user_func_array([$this, $this->currentMethod['method']], $this->currentMethod['arguments']);
                }
            }
            throw new InvalidResponseException($exception->getMessage(), $exception->getCode());
        }
    }

    /**
     * 以POST获取接口数据并转为数组
     * @param string $url 接口地址
     * @param array $data 请求数据
     * @param bool $toJson 转换JSON
     * @param array $options 请求扩展数据
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    protected function httpPostForJson($url, array $data, $toJson = true, array $options = [])
    {
        try {
            $options['headers'] = isset($options['headers']) ? $options['headers'] : [];
            if ($toJson) $options['headers'][] = 'Content-Type: application/json';
            return Tools::json2arr(Tools::post($url, $toJson ? Tools::arr2json($data) : $data, $options));
        } catch (InvalidResponseException $exception) {
            if (!$this->isTry && in_array($exception->getCode(), ['40014', '40001', '41001', '42001'])) {
                [$this->delAccessToken(), $this->isTry = true];
                return call_user_func_array([$this, $this->currentMethod['method']], $this->currentMethod['arguments']);
            }
            throw new InvalidResponseException($exception->getMessage(), $exception->getCode());
        }
    }

    /**
     * 注册当前请求接口
     * @param string $url 接口地址
     * @param string $method 当前接口方法
     * @param array $arguments 请求参数
     * @return string
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    protected function registerApi(&$url, $method, $arguments = [])
    {
        $this->currentMethod = ['method' => $method, 'arguments' => $arguments];
        if (empty($this->access_token)) $this->access_token = $this->getAccessToken();
        return $url = str_replace('ACCESS_TOKEN', urlencode($this->access_token), $url);
    }

    /**
     * 接口通用POST请求方法
     * @param string $url 接口URL
     * @param array $data POST提交接口参数
     * @param bool $toJson 是否转换为JSON参数
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function callPostApi($url, array $data, $toJson = true, array $options = [])
    {
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, $data, $toJson, $options);
    }

    /**
     * 接口通用GET请求方法
     * @param string $url 接口URL
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function callGetApi($url)
    {
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpGetForJson($url);
    }

}