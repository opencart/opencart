<?php

namespace WeChat\Prpcrypt;

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