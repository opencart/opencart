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
 * 微信网页授权
 * Class Oauth
 * @package WeChat
 */
class Oauth extends BasicWeChat
{

    /**
     * Oauth 授权跳转接口
     * @param string $redirect_url 授权回跳地址
     * @param string $state 为重定向后会带上state参数（填写a-zA-Z0-9的参数值，最多128字节）
     * @param string $scope 授权类类型(可选值snsapi_base|snsapi_userinfo)
     * @return string
     */
    public function getOauthRedirect($redirect_url, $state = '', $scope = 'snsapi_base')
    {
        $appid = $this->config->get('appid');
        $redirect_uri = urlencode($redirect_url);
        return "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$appid}&redirect_uri={$redirect_uri}&response_type=code&scope={$scope}&state={$state}#wechat_redirect";
    }

    /**
     * 通过 code 获取 AccessToken 和 openid
     * @param string $code 授权Code值，不传则取GET参数
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function getOauthAccessToken($code = '')
    {
        $appid = $this->config->get('appid');
        $appsecret = $this->config->get('appsecret');
        $code = $code ? $code : (isset($_GET['code']) ? $_GET['code'] : '');
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$appid}&secret={$appsecret}&code={$code}&grant_type=authorization_code";
        return $this->httpGetForJson($url);
    }

    /**
     * 刷新AccessToken并续期
     * @param string $refresh_token
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function getOauthRefreshToken($refresh_token)
    {
        $appid = $this->config->get('appid');
        $url = "https://api.weixin.qq.com/sns/oauth2/refresh_token?appid={$appid}&grant_type=refresh_token&refresh_token={$refresh_token}";
        return $this->httpGetForJson($url);
    }

    /**
     * 检验授权凭证（access_token）是否有效
     * @param string $accessToken 网页授权接口调用凭证,注意：此access_token与基础支持的access_token不同
     * @param string $openid 用户的唯一标识
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function checkOauthAccessToken($accessToken, $openid)
    {
        $url = "https://api.weixin.qq.com/sns/auth?access_token={$accessToken}&openid={$openid}";
        return $this->httpGetForJson($url);
    }

    /**
     * 拉取用户信息(需scope为 snsapi_userinfo)
     * @param string $accessToken 网页授权接口调用凭证,注意：此access_token与基础支持的access_token不同
     * @param string $openid 用户的唯一标识
     * @param string $lang 返回国家地区语言版本，zh_CN 简体，zh_TW 繁体，en 英语
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function getUserInfo($accessToken, $openid, $lang = 'zh_CN')
    {
        $url = "https://api.weixin.qq.com/sns/userinfo?access_token={$accessToken}&openid={$openid}&lang={$lang}";
        return $this->httpGetForJson($url);
    }
}
