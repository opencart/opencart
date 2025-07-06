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
 * 小程序 URL-Scheme
 * Class Scheme
 * @package WeMini
 */
class Scheme extends BasicWeChat
{

    /**
     * 创建 URL-Scheme
     * @param array $data
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function create($data)
    {
        $url = 'https://api.weixin.qq.com/wxa/generatescheme?access_token=ACCESS_TOKEN';
        return $this->callPostApi($url, $data, true);
    }

    /**
     * 查询 URL-Scheme
     * @param string $scheme
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function query($scheme)
    {
        $url = 'https://api.weixin.qq.com/wxa/queryscheme?access_token=ACCESS_TOKEN';
        return $this->callPostApi($url, ['scheme' => $scheme], true);
    }

    /**
     * 创建 URL-Link
     * @param array $data
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function urlLink($data)
    {
        $url = "https://api.weixin.qq.com/wxa/generate_urllink?access_token=ACCESS_TOKEN";
        return $this->callPostApi($url, $data, true);
    }

    /**
     * 查询 URL-Link
     * @param string $urllink
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function urlQuery($urllink)
    {
        $url = 'https://api.weixin.qq.com/wxa/query_urllink?access_token=ACCESS_TOKEN';
        return $this->callPostApi($url, ['url_link' => $urllink], true);
    }
}