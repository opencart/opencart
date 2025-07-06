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
        if (empty($options['appid'])) {
            throw new InvalidArgumentException('Missing Config -- [appid]');
        }
        if (empty($options['public_key']) && !empty($options['alipay_cert_path']) && is_file($options['alipay_cert_path'])) {
            $options['public_key'] = file_get_contents($options['alipay_cert_path']);
        }
        if (empty($options['private_key']) && !empty($options['private_key_path']) && is_file($options['private_key_path'])) {
            $options['private_key'] = file_get_contents($options['private_key_path']);
        }
        if (empty($options['public_key'])) {
            throw new InvalidArgumentException('Missing Config -- [public_key]');
        }
        if (empty($options['private_key'])) {
            throw new InvalidArgumentException('Missing Config -- [private_key]');
        }
        if (!empty($options['debug'])) {
            $this->gateway = 'https://openapi-sandbox.dl.alipaydev.com/gateway.do?charset=utf-8';
        }
        $this->params = new DataArray([]);
        $this->config = new DataArray($options);
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

        // 证书模式读取证书
        $appCertPath = $this->config->get('app_cert_path');
        $aliRootPath = $this->config->get('alipay_root_path');
        if (!$this->config->get('app_cert') && !empty($appCertPath) && is_file($appCertPath)) {
            $this->config->set('app_cert', file_get_contents($appCertPath));
        }
        if (!$this->config->get('root_cert') && !empty($aliRootPath) && is_file($aliRootPath)) {
            $this->config->set('root_cert', file_get_contents($aliRootPath));
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
     * @param string $outTradeNo
     * @return array|boolean
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function query($outTradeNo = '')
    {
        $this->options->set('method', 'alipay.trade.query');
        return $this->getResult(['out_trade_no' => $outTradeNo]);
    }

    /**
     * 支付宝订单退款操作
     * @param array|string $options 退款参数或退款商户订单号
     * @param null $refundAmount 退款金额
     * @return array|boolean
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function refund($options, $refundAmount = null)
    {
        if (!is_array($options)) $options = ['out_trade_no' => $options, 'refund_amount' => $refundAmount];
        $this->options->set('method', 'alipay.trade.refund');
        return $this->getResult($options);
    }

    /**
     * 关闭支付宝进行中的订单
     * @param array|string $options
     * @return array|boolean
     * @throws \WeChat\Exceptions\InvalidResponseException
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
     *
     * @param boolean $needSignType 是否需要sign_type字段
     * @param array $parameters
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function notify($needSignType = false, array $parameters = [])
    {
        $data = empty($parameters) ? $_POST : $parameters;

        if (empty($data) || empty($data['sign'])) {
            throw new InvalidResponseException('Illegal push request.', 0, $data);
        }
        $string = $this->getSignContent($data, $needSignType);
        if (openssl_verify($string, base64_decode($data['sign']), $this->getAliPublicKey(), OPENSSL_ALGO_SHA256) !== 1) {
            throw new InvalidResponseException('Data signature verification failed.', 0, $data);
        }
        return $data;
    }

    /**
     * 验证接口返回的数据签名
     * @param array $data 通知数据
     * @param null|string $sign 数据签名
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    protected function verify($data, $sign)
    {
        unset($data['sign']);
        if ($this->options->get('sign_type') === 'RSA2') {
            if (openssl_verify(json_encode($data, 256), base64_decode($sign), $this->getAliPublicKey(), OPENSSL_ALGO_SHA256) !== 1) {
                throw new InvalidResponseException('Data signature verification failed by RSA2.');
            }
        } else {
            if (openssl_verify(json_encode($data, 256), base64_decode($sign), $this->getAliPublicKey(), OPENSSL_ALGO_SHA1) !== 1) {
                throw new InvalidResponseException('Data signature verification failed by RSA.');
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
        if ($this->options->get('sign_type') === 'RSA2') {
            openssl_sign($this->getSignContent($this->options->get(), true), $sign, $this->getAppPrivateKey(), OPENSSL_ALGO_SHA256);
        } else {
            openssl_sign($this->getSignContent($this->options->get(), true), $sign, $this->getAppPrivateKey(), OPENSSL_ALGO_SHA1);
        }
        return base64_encode($sign);
    }

    /**
     * 去除证书前后内容及空白
     * @param string $sign
     * @return string
     */
    protected function trimCert($sign)
    {
        return preg_replace(['/\s+/', '/-{5}.*?-{5}/'], '', $sign);
    }

    /**
     * 数据签名处理
     * @param array $data 需要进行签名数据
     * @param boolean $needSignType 是否需要sign_type字段
     * @return string
     */
    private function getSignContent(array $data, $needSignType = false)
    {
        list($attrs,) = [[], ksort($data)];
        if (isset($data['sign'])) unset($data['sign']);
        if (empty($needSignType)) unset($data['sign_type']);
        foreach ($data as $key => $value) {
            if ($value === '' || is_null($value)) continue;
            $attrs[] = "{$key}={$value}";
        }
        return join('&', $attrs);
    }

    /**
     * 数据包生成及数据签名
     * @param array $options
     */
    protected function applyData($options)
    {
        if ($this->config->get('app_cert') && $this->config->get('root_cert')) {
            $this->setAppCertSnAndRootCertSn();
        }
        $this->options->set('biz_content', json_encode($this->params->merge($options), 256));
        $this->options->set('sign', $this->getSign());
    }

    /**
     * 请求接口并验证访问数据
     * @param array $options
     * @return array|boolean
     * @throws \WeChat\Exceptions\InvalidResponseException
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
        // 返回结果签名检查
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
     * 获取应用私钥内容
     * @return string
     */
    private function getAppPrivateKey()
    {
        $content = wordwrap($this->trimCert($this->config->get('private_key')), 64, "\n", true);
        return "-----BEGIN RSA PRIVATE KEY-----\n{$content}\n-----END RSA PRIVATE KEY-----";
    }

    /**
     * 获取支付公钥内容
     * @return string
     */
    public function getAliPublicKey()
    {
        $cert = $this->config->get('public_key');
        if (strpos(trim($cert), '-----BEGIN CERTIFICATE-----') !== false) {
            $pkey = openssl_pkey_get_public($cert);
            $keyData = openssl_pkey_get_details($pkey);
            return trim($keyData['key']);
        } else {
            $content = wordwrap($this->trimCert($cert), 64, "\n", true);
            return "-----BEGIN PUBLIC KEY-----\n{$content}\n-----END PUBLIC KEY-----";
        }
    }

    /**
     * 新版 从证书中提取序列号
     * @param string $sign
     * @return string
     */
    private function getAppCertSN($sign)
    {
        $ssl = openssl_x509_parse($sign, true);
        return md5($this->_arr2str(array_reverse($ssl['issuer'])) . $ssl['serialNumber']);
    }

    /**
     * 新版 提取根证书序列号
     * @param string $sign
     * @return string|null
     */
    private function getRootCertSN($sign)
    {
        if (strlen($sign) < 500 && file_exists($sign)) {
            $sign = file_get_contents($sign);
        }
        $sn = null;
        $array = explode('-----END CERTIFICATE-----', $sign);
        for ($i = 0; $i < count($array) - 1; $i++) {
            $ssl[$i] = openssl_x509_parse($array[$i] . '-----END CERTIFICATE-----', true);
            if (strpos($ssl[$i]['serialNumber'], '0x') === 0) {
                $ssl[$i]['serialNumber'] = $this->_hex2dec($ssl[$i]['serialNumberHex']);
            }
            if ($ssl[$i]['signatureTypeLN'] == 'sha1WithRSAEncryption' || $ssl[$i]['signatureTypeLN'] == 'sha256WithRSAEncryption') {
                if ($sn == null) {
                    $sn = md5($this->_arr2str(array_reverse($ssl[$i]['issuer'])) . $ssl[$i]['serialNumber']);
                } else {
                    $sn = $sn . '_' . md5($this->_arr2str(array_reverse($ssl[$i]['issuer'])) . $ssl[$i]['serialNumber']);
                }
            }
        }
        return $sn;
    }

    /**
     * 新版 设置网关应用公钥证书SN、支付宝根证书SN
     */
    protected function setAppCertSnAndRootCertSn()
    {
        if (!($appCert = $this->config->get('app_cert'))) {
            throw new InvalidArgumentException('Missing Config -- [app_cert|app_cert_path]');
        }
        if (!($rootCert = $this->config->get('root_cert'))) {
            throw new InvalidArgumentException('Missing Config -- [root_cert|alipay_root_path]');
        }
        $this->options->set('app_cert_sn', $this->getAppCertSN($appCert));
        $this->options->set('alipay_root_cert_sn', $this->getRootCertSN($rootCert));
        if (!$this->options->get('app_cert_sn')) {
            throw new InvalidArgumentException('Missing options -- [app_cert_sn]');
        }
        if (!$this->options->get('alipay_root_cert_sn')) {
            throw new InvalidArgumentException('Missing options -- [alipay_root_cert_sn]');
        }
    }

    /**
     * 新版 数组转字符串
     * @param array $array
     * @return string
     */
    private function _arr2str($array)
    {
        $string = [];
        if ($array && is_array($array)) {
            foreach ($array as $key => $value) {
                $string[] = $key . '=' . $value;
            }
        }
        return join(',', $string);
    }

    /**
     * 新版 0x转高精度数字
     * @param string $hex
     * @return int|string
     */
    private function _hex2dec($hex)
    {
        list($dec, $len) = [0, strlen($hex)];
        for ($i = 1; $i <= $len; $i++) {
            $dec = bcadd($dec, bcmul(strval(hexdec($hex[$i - 1])), bcpow('16', strval($len - $i))));
        }
        return $dec;
    }

    /**
     * 应用数据操作
     * @param array $options
     * @return mixed
     */
    abstract public function apply($options);
}