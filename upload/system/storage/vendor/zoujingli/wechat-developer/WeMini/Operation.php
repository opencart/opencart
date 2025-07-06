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
 * 小程序运维中心
 * Class Operation
 * @package WeMini
 */
class Operation extends BasicWeChat
{

    /**
     * 实时日志查询
     * @param array $data
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function realtimelogSearch($data)
    {
        $url = 'https://api.weixin.qq.com/wxaapi/userlog/userlog_search?access_token=ACCESS_TOKEN';
        return $this->callPostApi($url, $data, true);
    }

    /**
     * 获取 mediaId 图片
     * @param array $data
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function getFeedbackmedia($data)
    {
        $query = http_build_query($data);
        $url = 'https://api.weixin.qq.com/cgi-bin/media/getfeedbackmedia?'. $query .'&access_token=ACCESS_TOKEN';
        return $this->callGetApi($url);
    }


    /**
     * 实时日志查询
     * @param array $data
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function getFeedback($data)
    {
        $query = http_build_query($data);
        $url = 'https://api.weixin.qq.com/wxaapi/userlog/userlog_search?'.$query.'&access_token=ACCESS_TOKEN';
        return $this->callGetApi($url);
    }
}