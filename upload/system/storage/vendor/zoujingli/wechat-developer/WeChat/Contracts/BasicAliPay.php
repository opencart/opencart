<?php

// +----------------------------------------------------------------------
// | WeChatDeveloper
// +----------------------------------------------------------------------
// | 版权所有 2014~2018 广州楚才信息科技有限公司 [ http://www.cuci.cc ]
// +----------------------------------------------------------------------
// | 官方网站: http://think.ctolog.com
// +----------------------------------------------------------------------
// | 开源协议 ( https://mit-license.org )
// +----------------------------------------------------------------------
// | github开源项目：https://github.com/zoujingli/WeChatDeveloper
// +----------------------------------------------------------------------

namespace WeChat\Contracts;

use WeChat\Exceptions\InvalidArgumentException;
use WeChat\Exceptions\InvalidResponseException;

/**
 * 支付宝支付基类
 * Class AliPay
 * @package AliPay\Contracts
 */
abstract class BasicAliPay
{

    /**
     * 支持配置
     * @var DataArray
     */
    protected $config;

    /**
     * 当前请求数据
     * @var DataArray
     */
    protected $options;

    /**
     * DzContent数据
     * @var DataArray
     */
    protected $params;

    /**
     * 静态缓存
     * @var static
     */
    protected static $cache;

    /**
     * 正常请求网关
     * @var string
     */
    protected $gateway = 'https://openapi.alipay.com/gateway.do?charset=utf-8';

    /**
     * AliPay constructor.
     * @param array $options
     */
    public function __construct($options)
    {
        $this->params = new DataArray([]);
        $this->config = new DataArray($options);
        if (empty($options['appid'])) {
            throw new InvalidArgumentException("Missing Config -- [appid]");
        }
        if (empty($options['public_key'])) {
            throw new InvalidArgumentException("Missing Config -- [public_key]");
        }
        if (empty($options['private_key'])) {
            throw new InvalidArgumentException("Missing Config -- [private_key]");
        }
        if (!empty($options['debug'])) {
            $this->gateway = 'https://openapi.alipaydev.com/gateway.do?charset=utf-8';
        }
        $this->options = new DataArray([
            'app_id'    => $this->config->get('appid'),
            'charset'   => empty($options['charset']) ? 'utf-8' : $options['charset'],
            'format'    => 'JSON',
            'version'   => '1.0',
            'sign_type' => empty($options['sign_type']) ? 'RSA2' : $options['sign_type'],
            'timestamp' => date('Y-m-d H:i:s'),
        ]);
        if (isset($options['notify_url']) && $options['notify_url'] !== '') {
            $this->options->set('notify_url', $options['notify_url']);
        }
        if (isset($options['return_url']) && $options['return_url'] !== '') {
            $this->options->set('return_url', $options['return_url']);
        }
        if (isset($options['app_auth_token']) && $options['app_auth_token'] !== '') {
            $this->options->set('app_auth_token', $options['app_auth_token']);
        }
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
     * 查询支付宝订单状态
     * @param string $out_trade_no
     * @return array|boolean
     * @throws InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function query($out_trade_no = '')
    {
        $this->options->set('method', 'alipay.trade.query');
        return $this->getResult(['out_trade_no' => $out_trade_no]);
    }

    /**
     * 支付宝订单退款操作
     * @param array|string $options 退款参数或退款商户订单号
     * @param null $refund_amount 退款金额
     * @return array|boolean
     * @throws InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function refund($options, $refund_amount = null)
    {
        if (!is_array($options)) $options = ['out_trade_no' => $options, 'refund_amount' => $refund_amount];
        $this->options->set('method', 'alipay.trade.refund');
        return $this->getResult($options);
    }

    /**
     * 关闭支付宝进行中的订单
     * @param array|string $options
     * @return array|boolean
     * @throws InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function close($options)
    {
        if (!is_array($options)) $options = ['out_trade_no' => $options];
        $this->options->set('method', 'alipay.trade.close');
        return $this->getResult($options);
    }

    /**
     * 获取通知数据
     * @param boolean $needSignType 是否需要sign_type字段
     * @return boolean|array
     * @throws InvalidResponseException
     */
    public function notify($needSignType = false)
    {
        $data = $_POST;
        if (empty($data) || empty($data['sign'])) {
            throw new InvalidResponseException('Illegal push request.', 0, $data);
        }
        $string = $this->getSignContent($data, $needSignType);
        $content = wordwrap($this->config->get('public_key'), 64, "\n", true);
        $res = "-----BEGIN PUBLIC KEY-----\n{$content}\n-----END PUBLIC KEY-----";
        if (openssl_verify($string, base64_decode($data['sign']), $res, OPENSSL_ALGO_SHA256) !== 1) {
            throw new InvalidResponseException('Data signature verification failed.', 0, $data);
        }
        return $data;
    }

    /**
     * 验证接口返回的数据签名
     * @param array $data 通知数据
     * @param null|string $sign 数据签名
     * @return array|boolean
     * @throws InvalidResponseException
     */
    protected function verify($data, $sign)
    {
        $content = wordwrap($this->config->get('public_key'), 64, "\n", true);
        $res = "-----BEGIN PUBLIC KEY-----\n{$content}\n-----END PUBLIC KEY-----";
        if ($this->options->get('sign_type') === 'RSA2') {
            if (openssl_verify(json_encode($data, 256), base64_decode($sign), $res, OPENSSL_ALGO_SHA256) !== 1) {
                throw new InvalidResponseException('Data signature verification failed.');
            }
        } else {
            if (openssl_verify(json_encode($data, 256), base64_decode($sign), $res, OPENSSL_ALGO_SHA1) !== 1) {
                throw new InvalidResponseException('Data signature verification failed.');
            }
        }
        return $data;
    }

    /**
     * 获取数据签名
     * @return string
     */
    protected function getSign()
    {
        $content = wordwrap($this->config->get('private_key'), 64, "\n", true);
        $string = "-----BEGIN RSA PRIVATE KEY-----\n{$content}\n-----END RSA PRIVATE KEY-----";
        if ($this->options->get('sign_type') === 'RSA2') {
            openssl_sign($this->getSignContent($this->options->get(), true), $sign, $string, OPENSSL_ALGO_SHA256);
        } else {
            openssl_sign($this->getSignContent($this->options->get(), true), $sign, $string, OPENSSL_ALGO_SHA1);
        }
        return base64_encode($sign);
    }

    /**
     * 数据签名处理
     * @param array $data 需要进行签名数据
     * @param boolean $needSignType 是否需要sign_type字段
     * @return bool|string
     */
    private function getSignContent(array $data, $needSignType = false)
    {
        list($attrs,) = [[], ksort($data)];
        if (isset($data['sign'])) unset($data['sign']);
        if (empty($needSignType)) unset($data['sign_type']);
        foreach ($data as $key => $value) {
            if ($value === '' || is_null($value)) continue;
            array_push($attrs, "{$key}={$value}");
        }
        return join('&', $attrs);
    }

    /**
     * 数据包生成及数据签名
     * @param array $options
     */
    protected function applyData($options)
    {
        $this->options->set('biz_content', json_encode($this->params->merge($options), 256));
        $this->options->set('sign', $this->getSign());
    }

    /**
     * 请求接口并验证访问数据
     * @param array $options
     * @return array|boolean
     * @throws InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    protected function getResult($options)
    {
        $this->applyData($options);
        $method = str_replace('.', '_', $this->options['method']) . '_response';
        $data = json_decode(Tools::get($this->gateway, $this->options->get()), true);
        if (!isset($data[$method]['code']) || $data[$method]['code'] !== '10000') {
            throw new InvalidResponseException(
                "Error: " .
                (empty($data[$method]['code']) ? '' : "{$data[$method]['msg']} [{$data[$method]['code']}]\r\n") .
                (empty($data[$method]['sub_code']) ? '' : "{$data[$method]['sub_msg']} [{$data[$method]['sub_code']}]\r\n"),
                $data[$method]['code'], $data
            );
        }
        return $data[$method];
        // 去除返回结果签名检查
        // return $this->verify($data[$method], $data['sign']);
    }

    /**
     * 生成支付HTML代码
     * @return string
     */
    protected function buildPayHtml()
    {
        $html = "<form id='alipaysubmit' name='alipaysubmit' action='{$this->gateway}' method='post'>";
        foreach ($this->options->get() as $key => $value) {
            $value = str_replace("'", '&apos;', $value);
            $html .= "<input type='hidden' name='{$key}' value='{$value}'/>";
        }
        $html .= "<input type='submit' value='ok' style='display:none;'></form>";
        return "{$html}<script>document.forms['alipaysubmit'].submit();</script>";
    }

    /**
     * 应用数据操作
     * @param array $options
     * @return mixed
     */
    abstract public function apply($options);

}