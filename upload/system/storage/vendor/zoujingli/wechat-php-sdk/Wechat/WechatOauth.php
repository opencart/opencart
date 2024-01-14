<?php

// +----------------------------------------------------------------------
// | wechat-php-sdk
// +----------------------------------------------------------------------
// | 版权所有 2014~2017 广州楚才信息科技有限公司 [ http://www.cuci.cc ]
// +----------------------------------------------------------------------
// | 官方文档: https://www.kancloud.cn/zoujingli/wechat-php-sdk
// +----------------------------------------------------------------------
// | 开源协议 ( https://mit-license.org )
// +----------------------------------------------------------------------
// | github开源项目：https://github.com/zoujingli/wechat-php-sdk
// +----------------------------------------------------------------------

namespace Wechat;

use Wechat\Lib\Common;
use Wechat\Lib\Tools;

/**
 * 微信网页授权
 */
class WechatOauth extends Common
{

    const OAUTH_PREFIX = 'https://open.weixin.qq.com/connect/oauth2';
    const OAUTH_AUTHORIZE_URL = '/authorize?';
    const OAUTH_TOKEN_URL = '/sns/oauth2/access_token?';
    const OAUTH_REFRESH_URL = '/sns/oauth2/refresh_token?';
    const OAUTH_USERINFO_URL = '/sns/userinfo?';
    const OAUTH_AUTH_URL = '/sns/auth?';

    /**
     * Oauth 授权跳转接口
     * @param string $callback 授权回跳地址
     * @param string $state 为重定向后会带上state参数（填写a-zA-Z0-9的参数值，最多128字节）
     * @param string $scope 授权类类型(可选值snsapi_base|snsapi_userinfo)
     * @return string
     */
    public function getOauthRedirect($callback, $state = '', $scope = 'snsapi_base')
    {
        $redirect_uri = urlencode($callback);
        return self::OAUTH_PREFIX . self::OAUTH_AUTHORIZE_URL . "appid={$this->appid}&redirect_uri={$redirect_uri}&response_type=code&scope={$scope}&state={$state}#wechat_redirect";
    }

    /**
     * 通过 code 获取 AccessToken 和 openid
     * @return bool|array
     */
    public function getOauthAccessToken()
    {
        $code = isset($_GET['code']) ? $_GET['code'] : '';
        if (empty($code)) {
            Tools::log("getOauthAccessToken Fail, Because there is no access to the code value in get.", "MSG - {$this->appid}");
            return false;
        }
        $result = Tools::httpGet(self::API_BASE_URL_PREFIX . self::OAUTH_TOKEN_URL . "appid={$this->appid}&secret={$this->appsecret}&code={$code}&grant_type=authorization_code");
        if ($result) {
            $json = json_decode($result, true);
            if (empty($json) || !empty($json['errcode'])) {
                $this->errCode = isset($json['errcode']) ? $json['errcode'] : '505';
                $this->errMsg = isset($json['errmsg']) ? $json['errmsg'] : '无法解析接口返回内容！';
                Tools::log("WechatOauth::getOauthAccessToken Fail.{$this->errMsg} [{$this->errCode}]", "ERR - {$this->appid}");
                return false;
            }
            return $json;
        }
        return false;
    }

    /**
     * 刷新access token并续期
     * @param string $refresh_token
     * @return bool|array
     */
    public function getOauthRefreshToken($refresh_token)
    {
        $result = Tools::httpGet(self::API_BASE_URL_PREFIX . self::OAUTH_REFRESH_URL . "appid={$this->appid}&grant_type=refresh_token&refresh_token={$refresh_token}");
        if ($result) {
            $json = json_decode($result, true);
            if (empty($json) || !empty($json['errcode'])) {
                $this->errCode = isset($json['errcode']) ? $json['errcode'] : '505';
                $this->errMsg = isset($json['errmsg']) ? $json['errmsg'] : '无法解析接口返回内容！';
                Tools::log("WechatOauth::getOauthRefreshToken Fail.{$this->errMsg} [{$this->errCode}]", "ERR - {$this->appid}");
                return false;
            }
            return $json;
        }
        return false;
    }

    /**
     * 获取授权后的用户资料
     * @param string $access_token
     * @param string $openid
     * @return bool|array {openid,nickname,sex,province,city,country,headimgurl,privilege,[unionid]}
     * 注意：unionid字段 只有在用户将公众号绑定到微信开放平台账号后，才会出现。建议调用前用isset()检测一下
     */
    public function getOauthUserInfo($access_token, $openid)
    {
        $result = Tools::httpGet(self::API_BASE_URL_PREFIX . self::OAUTH_USERINFO_URL . "access_token={$access_token}&openid={$openid}");
        if ($result) {
            $json = json_decode($result, true);
            if (empty($json) || !empty($json['errcode'])) {
                $this->errCode = isset($json['errcode']) ? $json['errcode'] : '505';
                $this->errMsg = isset($json['errmsg']) ? $json['errmsg'] : '无法解析接口返回内容！';
                Tools::log("WechatOauth::getOauthUserInfo Fail.{$this->errMsg} [{$this->errCode}]", "ERR - {$this->appid}");
                return false;
            }
            return $json;
        }
        return false;
    }

    /**
     * 检验授权凭证是否有效
     * @param string $access_token
     * @param string $openid
     * @return bool 是否有效
     */
    public function getOauthAuth($access_token, $openid)
    {
        $result = Tools::httpGet(self::API_BASE_URL_PREFIX . self::OAUTH_AUTH_URL . "access_token={$access_token}&openid={$openid}");
        if ($result) {
            $json = json_decode($result, true);
            if (empty($json) || !empty($json['errcode'])) {
                $this->errCode = isset($json['errcode']) ? $json['errcode'] : '505';
                $this->errMsg = isset($json['errmsg']) ? $json['errmsg'] : '无法解析接口返回内容！';
                Tools::log("WechatOauth::getOauthAuth Fail.{$this->errMsg} [{$this->errCode}]", "ERR - {$this->appid}");
                return false;
            } elseif (intval($json['errcode']) === 0) {
                return true;
            }
        }
        return false;
    }

}
