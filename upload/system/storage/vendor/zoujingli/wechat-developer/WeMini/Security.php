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

namespace WeMini;

use WeChat\Contracts\BasicWeChat;

/**
 * 小程序内容安全
 * Class Security
 * @package WeMini
 */
class Security extends BasicWeChat
{

    /**
     * 校验一张图片是否含有违法违规内容
     * @param string $media 要检测的图片文件，格式支持PNG、JPEG、JPG、GIF，图片尺寸不超过 750px x 1334px
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function imgSecCheck($media)
    {
        $url = 'https://api.weixin.qq.com/wxa/img_sec_check?access_token=ACCESS_TOKEN';
        return $this->callPostApi($url, ['media' => $media], false, ['headers' => ['Content-Type: application/octet-stream']]);
    }

    /**
     * 异步校验图片/音频是否含有违法违规内容
     * @param string $media_url
     * @param string $media_type
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function mediaCheckAsync($media_url, $media_type)
    {
        $url = 'https://api.weixin.qq.com/wxa/media_check_async?access_token=ACCESS_TOKEN';
        return $this->callPostApi($url, ['media_url' => $media_url, 'media_type' => $media_type], true);
    }

    /**
     * 检查一段文本是否含有违法违规内容
     * @param string $content
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function msgSecCheck($content)
    {
        $url = 'https://api.weixin.qq.com/wxa/msg_sec_check?access_token=ACCESS_TOKEN';
        return $this->callPostApi($url, ['content' => $content], true);
    }
}