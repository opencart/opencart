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

namespace WePayV3;

use WeChat\Exceptions\InvalidResponseException;
use WePayV3\Contracts\BasicWePay;
use WePayV3\Contracts\DecryptAes;

/**
 * 平台证书管理
 * Class Cert
 * @package WePayV3
 */
class Cert extends BasicWePay
{

    /**
     * 自动配置平台证书
     * @var bool
     */
    protected $autoCert = false;

    /**
     * 商户平台下载证书
     * @return void
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function download()
    {
        try {
            $certs = [];
            $result = $this->doRequest('GET', '/v3/certificates');
            if (empty($result['data']) && !empty($result['message'])) {
                throw new InvalidResponseException($result['message']);
            }
            $decrypt = new DecryptAes($this->config['mch_v3_key']);
            foreach ($result['data'] as $vo) {
                $certs[$vo['serial_no']] = [
                    'expire'  => strtotime($vo['expire_time']),
                    'serial'  => $vo['serial_no'],
                    'content' => $decrypt->decryptToString(
                        $vo['encrypt_certificate']['associated_data'],
                        $vo['encrypt_certificate']['nonce'],
                        $vo['encrypt_certificate']['ciphertext']
                    )
                ];
            }
            $this->tmpFile("{$this->config['mch_id']}_certs", $certs);
        } catch (\Exception $exception) {
            throw new InvalidResponseException($exception->getMessage(), $exception->getCode());
        }
    }
}