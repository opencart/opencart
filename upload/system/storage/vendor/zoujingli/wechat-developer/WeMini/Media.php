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
use WeChat\Contracts\Tools;

/**
 * 小程序素材操作
 * Class Media
 * @package WeMini
 */
class Media extends BasicWeChat
{

    /**
     * 获取客服消息内的临时素材
     * @param array $data
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function get($media_id)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/media/get?access_token=ACCESS_TOKEN&media_id=' . $media_id;
        return $this->callGetApi($url);
    }

    /**
     * 新增图片素材
     * @param array $data
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function upload($filename)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/media/upload?access_token=ACCESS_TOKEN&type=image';
        return $this->callPostApi($url, ['media' => Tools::createCurlFile($filename)], false);
    }
}