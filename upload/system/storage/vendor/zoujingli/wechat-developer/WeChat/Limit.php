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

namespace WeChat;

use WeChat\Contracts\BasicWeChat;

/**
 * 接口调用频次限制
 * Class Limit
 * @package WeChat
 */
class Limit extends BasicWeChat
{

    /**
     * 公众号调用或第三方平台帮公众号调用对公众号的所有api调用（包括第三方帮其调用）次数进行清零
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function clearQuota()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/clear_quota?access_token=ACCESS_TOKEN';
        return $this->callPostApi($url, ['appid' => $this->config->get('appid')]);
    }

    /**
     * 网络检测
     * @param string $action 执行的检测动作
     * @param string $operator 指定平台从某个运营商进行检测
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function ping($action = 'all', $operator = 'DEFAULT')
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/callback/check?access_token=ACCESS_TOKEN';
        return $this->callPostApi($url, ['action' => $action, 'check_operator' => $operator]);
    }

    /**
     * 获取微信服务器IP地址
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function getCallbackIp()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token=ACCESS_TOKEN';
        return $this->callGetApi($url);
    }
}