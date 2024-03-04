<?php

/**
 * 对微信小程序用户加密数据的解密示例代码
 * Class WXBizDataCrypt
 * @copyright Copyright (c) 1998-2014 Tencent Inc.
 */
class WXBizDataCrypt
{
    private $appid;
    private $sessionKey;

    /**
     * 构造函数
     * @param $sessionKey string 用户在小程序登录后获取的会话密钥
     * @param $appid string 小程序的appid
     */
    public function __construct($appid, $sessionKey)
    {
        $this->appid = $appid;
        $this->sessionKey = $sessionKey;
        include_once __DIR__ . DIRECTORY_SEPARATOR . "errorCode.php";
    }

    /**
     * 检验数据的真实性，并且获取解密后的明文.
     * @param $encryptedData string 加密的用户数据
     * @param $iv string 与用户数据一同返回的初始向量
     * @param $data string 解密后的原文
     *
     * @return int 成功0，失败返回对应的错误码
     */
    public function decryptData($encryptedData, $iv, &$data)
    {
        if (strlen($this->sessionKey) != 24) {
            return ErrorCode::$IllegalAesKey;
        }
        $aesKey = base64_decode($this->sessionKey);
        if (strlen($iv) != 24) {
            return ErrorCode::$IllegalIv;
        }
        $aesIV = base64_decode($iv);
        $aesCipher = base64_decode($encryptedData);
        $result = openssl_decrypt($aesCipher, "AES-128-CBC", $aesKey, 1, $aesIV);
        $dataObj = json_decode($result);
        if ($dataObj == null) {
            return ErrorCode::$IllegalBuffer;
        }
        // 兼容新版本无 watermark 的情况
        if (isset($dataObj->watermark) && $dataObj->watermark->appid != $this->appid) {
            return ErrorCode::$IllegalBuffer;
        }
        $data = $result;
        return ErrorCode::$OK;
    }

}

