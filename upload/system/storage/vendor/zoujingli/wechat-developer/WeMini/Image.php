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

namespace WeMini;

use WeChat\Contracts\BasicWeChat;
use WeChat\Exceptions\InvalidResponseException;

/**
 * 小程序图像处理
 * Class Image
 * @package WeMini
 */
class Image extends BasicWeChat
{

    /**
     * 本接口提供基于小程序的图片智能裁剪能力
     * @param string $img_url 要检测的图片 url，传这个则不用传 img 参数。
     * @param string $img form-data 中媒体文件标识，有filename、filelength、content-type等信息，传这个则不用穿 img_url
     * @return array
     * @throws InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function aiCrop($img_url, $img)
    {
        $url = "https://api.weixin.qq.com/cv/img/aicrop?access_token=ACCESS_TOCKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->callPostApi($url, ['img_url' => $img_url, 'img' => $img], true);
    }

    /**
     * 本接口提供基于小程序的条码/二维码识别的API
     * @param string $img_url 要检测的图片 url，传这个则不用传 img 参数。
     * @param string $img form-data 中媒体文件标识，有filename、filelength、content-type等信息，传这个则不用穿 img_url
     * @return array
     * @throws InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function scanQRCode($img_url, $img)
    {
        $url = "https://api.weixin.qq.com/cv/img/qrcode?img_url=ENCODE_URL&access_token=ACCESS_TOCKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->callPostApi($url, ['img_url' => $img_url, 'img' => $img], true);
    }

    /**
     * 本接口提供基于小程序的图片高清化能力
     * @param string $img_url 要检测的图片 url，传这个则不用传 img 参数
     * @param string $img form-data 中媒体文件标识，有filename、filelength、content-type等信息，传这个则不用穿 img_url
     * @return array
     * @throws InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function superresolution($img_url, $img)
    {
        $url = "https://api.weixin.qq.com/cv/img/qrcode?img_url=ENCODE_URL&access_token=ACCESS_TOCKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->callPostApi($url, ['img_url' => $img_url, 'img' => $img], true);
    }
}