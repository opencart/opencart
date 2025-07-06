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

namespace WePay;

use WeChat\Contracts\BasicWePay;
use WeChat\Contracts\Tools;
use WeChat\Exceptions\InvalidArgumentException;
use WeChat\Exceptions\InvalidDecryptException;
use WeChat\Exceptions\InvalidResponseException;

/**
 * 微信商户打款到银行卡
 * Class TransfersBank
 * @package WePay
 */
class TransfersBank extends BasicWePay
{

    /**
     * 企业付款到银行卡
     * @param array $options
     * @return array
     * @throws \WeChat\Exceptions\InvalidDecryptException
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function create(array $options)
    {
        if (!isset($options['partner_trade_no'])) {
            throw new InvalidArgumentException('Missing Options -- [partner_trade_no]');
        }
        if (!isset($options['enc_bank_no'])) {
            throw new InvalidArgumentException('Missing Options -- [enc_bank_no]');
        }
        if (!isset($options['enc_true_name'])) {
            throw new InvalidArgumentException('Missing Options -- [enc_true_name]');
        }
        if (!isset($options['bank_code'])) {
            throw new InvalidArgumentException('Missing Options -- [bank_code]');
        }
        if (!isset($options['amount'])) {
            throw new InvalidArgumentException('Missing Options -- [amount]');
        }
        $this->params->offsetUnset('appid');
        return $this->callPostApi('https://api.mch.weixin.qq.com/mmpaysptrans/pay_bank', [
            'amount'           => $options['amount'],
            'bank_code'        => $options['bank_code'],
            'partner_trade_no' => $options['partner_trade_no'],
            'enc_bank_no'      => $this->rsaEncode($options['enc_bank_no']),
            'enc_true_name'    => $this->rsaEncode($options['enc_true_name']),
            'desc'             => isset($options['desc']) ? $options['desc'] : '',
        ], true, 'MD5', false);
    }

    /**
     * 商户企业付款到银行卡操作进行结果查询
     * @param string $partnerTradeNo 商户订单号，需保持唯一
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function query($partnerTradeNo)
    {
        $this->params->offsetUnset('appid');
        $url = 'https://api.mch.weixin.qq.com/mmpaysptrans/query_bank';
        return $this->callPostApi($url, ['partner_trade_no' => $partnerTradeNo], true, 'MD5', false);
    }

    /**
     * RSA加密处理
     * @param string $string
     * @param string $encrypted
     * @return string
     * @throws \WeChat\Exceptions\InvalidDecryptException
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    private function rsaEncode($string, $encrypted = '')
    {
        $search = ['-----BEGIN RSA PUBLIC KEY-----', '-----END RSA PUBLIC KEY-----', "\n", "\r"];
        $pkc1 = str_replace($search, '', $this->getRsaContent());
        $publicKey = '-----BEGIN PUBLIC KEY-----' . PHP_EOL .
            wordwrap('MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8A' . $pkc1, 64, PHP_EOL, true) . PHP_EOL .
            '-----END PUBLIC KEY-----';
        if (!openssl_public_encrypt("{$string}", $encrypted, $publicKey, OPENSSL_PKCS1_OAEP_PADDING)) {
            throw new InvalidDecryptException('Rsa Encrypt Error.');
        }
        return base64_encode($encrypted);
    }

    /**
     * 获取签名文件内容
     * @return string
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    private function getRsaContent()
    {
        $cacheKey = "pub_ras_key_" . $this->config->get('mch_id');
        if (($pub_key = Tools::getCache($cacheKey))) {
            return $pub_key;
        }
        $data = $this->callPostApi('https://fraud.mch.weixin.qq.com/risk/getpublickey', [], true, 'MD5');
        if (!isset($data['return_code']) || $data['return_code'] !== 'SUCCESS' || $data['result_code'] !== 'SUCCESS') {
            $error = 'ResultError:' . $data['return_msg'];
            $error .= isset($data['err_code_des']) ? ' - ' . $data['err_code_des'] : '';
            throw new InvalidResponseException($error, 20000, $data);
        }
        Tools::setCache($cacheKey, $data['pub_key'], 600);
        return $data['pub_key'];
    }
}