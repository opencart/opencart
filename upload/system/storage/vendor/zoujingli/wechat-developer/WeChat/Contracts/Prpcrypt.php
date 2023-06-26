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

/**
 * PKCS7算法 - 加解密
 * Class PKCS7Encoder
 */
class PKCS7Encoder
{

    public static $blockSize = 32;

    /**
     * 对需要加密的明文进行填充补位
     * @param string $text 需要进行填充补位操作的明文
     * @return string 补齐明文字符串
     */
    function encode($text)
    {
        $amount_to_pad = PKCS7Encoder::$blockSize - (strlen($text) % PKCS7Encoder::$blockSize);
        if ($amount_to_pad == 0) {
            $amount_to_pad = PKCS7Encoder::$blockSize;
        }
        list($pad_chr, $tmp) = [chr($amount_to_pad), ''];
        for ($index = 0; $index < $amount_to_pad; $index++) {
            $tmp .= $pad_chr;
        }
        return $text . $tmp;
    }

    /**
     * 对解密后的明文进行补位删除
     * @param string $text 解密后的明文
     * @return string 删除填充补位后的明文
     */
    function decode($text)
    {
        $pad = ord(substr($text, -1));
        if ($pad < 1 || $pad > PKCS7Encoder::$blockSize) {
            $pad = 0;
        }
        return substr($text, 0, strlen($text) - $pad);
    }

}

/**
 * 公众号消息 - 加解密
 * Class Prpcrypt
 */
class Prpcrypt
{

    public $key;

    /**
     * Prpcrypt constructor.
     * @param $key
     */
    function __construct($key)
    {
        $this->key = base64_decode("{$key}=");
    }

    /**
     * 对明文进行加密
     * @param string $text 需要加密的明文
     * @param string $appid 公众号APPID
     * @return array
     */
    public function encrypt($text, $appid)
    {
        try {
            $random = $this->getRandomStr();
            $iv = substr($this->key, 0, 16);
            $pkcEncoder = new PKCS7Encoder();
            $text = $pkcEncoder->encode($random . pack("N", strlen($text)) . $text . $appid);
            $encrypted = openssl_encrypt($text, 'AES-256-CBC', substr($this->key, 0, 32), OPENSSL_ZERO_PADDING, $iv);
            return [ErrorCode::$OK, $encrypted];
        } catch (Exception $e) {
            return [ErrorCode::$EncryptAESError, null];
        }
    }

    /**
     * 对密文进行解密
     * @param string $encrypted 需要解密的密文
     * @return array
     */
    public function decrypt($encrypted)
    {
        try {
            $iv = substr($this->key, 0, 16);
            $decrypted = openssl_decrypt($encrypted, 'AES-256-CBC', substr($this->key, 0, 32), OPENSSL_ZERO_PADDING, $iv);
        } catch (Exception $e) {
            return [ErrorCode::$DecryptAESError, null];
        }
        try {
            $pkcEncoder = new PKCS7Encoder();
            $result = $pkcEncoder->decode($decrypted);
            if (strlen($result) < 16) {
                return [ErrorCode::$DecryptAESError, null];
            }
            $content = substr($result, 16, strlen($result));
            $len_list = unpack("N", substr($content, 0, 4));
            $xml_len = $len_list[1];
            return [0, substr($content, 4, $xml_len), substr($content, $xml_len + 4)];
        } catch (Exception $e) {
            return [ErrorCode::$IllegalBuffer, null];
        }
    }

    /**
     * 随机生成16位字符串
     * @param string $str
     * @return string 生成的字符串
     */
    function getRandomStr($str = "")
    {
        $str_pol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($str_pol) - 1;
        for ($i = 0; $i < 16; $i++) {
            $str .= $str_pol[mt_rand(0, $max)];
        }
        return $str;
    }

}

/**
 * 仅用作类内部使用
 * 不用于官方API接口的errCode码
 * Class ErrorCode
 */
class ErrorCode
{

    public static $OK = 0;
    public static $ParseXmlError = 40002;
    public static $IllegalAesKey = 40004;
    public static $IllegalBuffer = 40008;
    public static $EncryptAESError = 40006;
    public static $DecryptAESError = 40007;
    public static $EncodeBase64Error = 40009;
    public static $DecodeBase64Error = 40010;
    public static $GenReturnXmlError = 40011;
    public static $ValidateAppidError = 40005;
    public static $ComputeSignatureError = 40003;
    public static $ValidateSignatureError = 40001;
    public static $errCode = [
        '0'     => '处理成功',
        '40001' => '校验签名失败',
        '40002' => '解析xml失败',
        '40003' => '计算签名失败',
        '40004' => '不合法的AESKey',
        '40005' => '校验AppID失败',
        '40006' => 'AES加密失败',
        '40007' => 'AES解密失败',
        '40008' => '公众平台发送的xml不合法',
        '40009' => 'Base64编码失败',
        '40010' => 'Base64解码失败',
        '40011' => '公众帐号生成回包xml失败',
    ];

    /**
     * 获取错误消息内容
     * @param string $code 错误代码
     * @return bool
     */
    public static function getErrText($code)
    {
        if (isset(self::$errCode[$code])) {
            return self::$errCode[$code];
        }
        return false;
    }

}
