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

namespace WeChat;

use WeChat\Contracts\BasicWeChat;

/**
 * 二维码管理
 * Class Qrcode
 * @package WeChat
 */
class Qrcode extends BasicWeChat
{

    /**
     * 创建二维码ticket
     * @param string|integer $scene 场景
     * @param int $expire_seconds 有效时间
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function create($scene, $expire_seconds = 0)
    {
        if (is_integer($scene)) { // 二维码场景类型
            $data = ['action_info' => ['scene' => ['scene_id' => $scene]]];
        } else {
            $data = ['action_info' => ['scene' => ['scene_str' => $scene]]];
        }
        if ($expire_seconds > 0) { // 临时二维码
            $data['expire_seconds'] = $expire_seconds;
            $data['action_name'] = is_integer($scene) ? 'QR_SCENE' : 'QR_STR_SCENE';
        } else { // 永久二维码
            $data['action_name'] = is_integer($scene) ? 'QR_LIMIT_SCENE' : 'QR_LIMIT_STR_SCENE';
        }
        $url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, $data);
    }

    /**
     * 通过ticket换取二维码
     * @param string $ticket 获取的二维码ticket，凭借此ticket可以在有效时间内换取二维码。
     * @return string
     */
    public function url($ticket)
    {
        return "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket={$ticket}";
    }

    /**
     * 长链接转短链接接口
     * @param string $longUrl 需要转换的长链接
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function shortUrl($longUrl)
    {
        $url = "https://api.weixin.qq.com/cgi-bin/shorturl?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, ['action' => 'long2short', 'long_url' => $longUrl]);
    }

}