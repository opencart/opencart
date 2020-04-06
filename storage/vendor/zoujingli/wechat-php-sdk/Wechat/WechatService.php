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

use Wechat\Lib\Cache;
use Wechat\Lib\Tools;

/**
 * 公众号第三方平台SDK
 *
 * @version 1.0
 * @author Anyon <zoujingli@qq.com>
 * @date 2016/10/18 00:35:55
 */
class WechatService
{

    const URL_PREFIX = 'https://api.weixin.qq.com/cgi-bin/component';
    // 获取服务access_token
    const COMPONENT_TOKEN_URL = '/api_component_token';
    // 获取（刷新）授权公众号的令牌
    const REFRESH_ACCESS_TOKEN = '/api_authorizer_token';
    // 获取预授权码
    const PREAUTH_CODE_URL = '/api_create_preauthcode';
    // 获取公众号的授权信息
    const QUERY_AUTH_URL = '/api_query_auth';
    // 获取授权方的账户信息
    const GET_AUTHORIZER_INFO_URL = '/api_get_authorizer_info';
    // 获取授权方的选项设置信息
    const GET_AUTHORIZER_OPTION_URL = '/api_get_authorizer_option';
    // 设置授权方的选项信息
    const SET_AUTHORIZER_OPTION_URL = '/api_set_authorizer_option';

    // 微信后台推送的ticket 每十分钟更新一次
    public $errCode;
    // 服务appid
    public $errMsg;
    // 服务appsecret
    protected $component_verify_ticket;
    // 公众号消息校验Token
    protected $component_appid;
    // 公众号消息加解密Key
    protected $component_appsecret;
    // 服务令牌
    protected $component_token;
    // 授权方appid
    protected $component_encodingaeskey;
    // 授权方令牌
    protected $component_access_token;
    // 刷新令牌
    protected $authorizer_appid;
    // JSON数据
    protected $pre_auth_code;
    // 错误消息
    protected $data;

    /**
     * WechatService constructor.
     * @param array $options
     */
    public function __construct($options = array())
    {
        $options = Loader::config($options);
        $this->component_encodingaeskey = !empty($options['component_encodingaeskey']) ? $options['component_encodingaeskey'] : '';
        $this->component_verify_ticket = !empty($options['component_verify_ticket']) ? $options['component_verify_ticket'] : '';
        $this->component_appsecret = !empty($options['component_appsecret']) ? $options['component_appsecret'] : '';
        $this->component_token = !empty($options['component_token']) ? $options['component_token'] : '';
        $this->component_appid = !empty($options['component_appid']) ? $options['component_appid'] : '';
    }

    /**
     * 接收公众平台推送的 Ticket
     * @return bool|array
     */
    public function getComonentTicket()
    {
        $receive = new WechatReceive(array(
            'appid'          => $this->component_appid,
            'appsecret'      => $this->component_appsecret,
            'encodingaeskey' => $this->component_encodingaeskey,
            'token'          => $this->component_token,
            'cachepath'      => Cache::$cachepath
        ));
        # 会话内容解密状态判断
        if (false === $receive->valid()) {
            $this->errCode = $receive->errCode;
            $this->errMsg = $receive->errMsg;
            Tools::log("Get Wechat Push ComponentVerifyTicket Faild. {$this->errMsg} [$this->errCode]", "ERR - {$this->authorizer_appid}");
            return false;
        }
        $data = $receive->getRev()->getRevData();
        if ($data['InfoType'] === 'component_verify_ticket' && !empty($data['ComponentVerifyTicket'])) {
            # 记录推送日志到微信SDK
            Tools::log("Wechat Push ComponentVerifyTicket Success. ");
            Tools::setCache('component_verify_ticket', $data['ComponentVerifyTicket']);
        }
        return $data;
    }

    /**
     * 获取（刷新）授权公众号的令牌
     * @注意1. 授权公众号访问access token2小时有效
     * @注意2. 一定保存好新的刷新令牌
     * @param string $authorizer_appid 授权方APPID
     * @param string $authorizer_refresh_token 授权方刷新令牌
     * @return bool|string
     */
    public function refreshAccessToken($authorizer_appid, $authorizer_refresh_token)
    {
        empty($this->component_access_token) && $this->getComponentAccessToken();
        if (empty($this->component_access_token)) {
            return false;
        }
        $data = array();
        $data['component_appid'] = $this->component_appid;
        $data['authorizer_appid'] = $authorizer_appid;
        $data['authorizer_refresh_token'] = $authorizer_refresh_token;
        $url = self::URL_PREFIX . self::REFRESH_ACCESS_TOKEN . "?component_access_token={$this->component_access_token}";
        $result = Tools::httpPost($url, Tools::json_encode($data));
        if (($result = $this->_decode($result)) === false) {
            Tools::log("Get getAuthorizerOption Faild. {$this->errMsg} [$this->errCode]", "ERR - {$this->authorizer_appid}");
        }
        return $result;
    }

    /**
     * 获取或刷新服务 AccessToken
     * @return bool|string
     */
    public function getComponentAccessToken()
    {
        $cacheKey = 'wechat_component_access_token';
        $this->component_access_token = Tools::getCache($cacheKey);
        if (empty($this->component_access_token)) {
            $data = array();
            $data['component_appid'] = $this->component_appid;
            $data['component_appsecret'] = $this->component_appsecret;
            $data['component_verify_ticket'] = $this->component_verify_ticket;
            $url = self::URL_PREFIX . self::COMPONENT_TOKEN_URL;
            $result = Tools::httpPost($url, Tools::json_encode($data));
            if (($this->component_access_token = $this->_decode($result, 'component_access_token')) === false) {
                Tools::log("Get getComponentAccessToken Faild. {$this->errMsg} [$this->errCode]", "ERR - {$this->authorizer_appid}");
                return false;
            }
            Tools::setCache($cacheKey, $this->component_access_token, 7200);
        }
        return $this->component_access_token;
    }

    /**
     * 解析JSON数据
     * @param string $result
     * @param string|null $field
     * @return bool|array
     */
    private function _decode($result, $field = null)
    {
        $this->data = json_decode($result, true);
        if (!empty($this->data['errcode'])) {
            $this->errCode = $this->data['errcode'];
            $this->errMsg = $this->data['errmsg'];
            return false;
        }
        if ($this->data && !is_null($field)) {
            if (isset($this->data[$field])) {
                return $this->data[$field];
            } else {
                return false;
            }
        }
        return $this->data;
    }

    /**
     * 获取公众号的授权信息
     *
     * @param string $authorization_code
     * @return bool|array
     */
    public function getAuthorizationInfo($authorization_code)
    {
        empty($this->component_access_token) && $this->getComponentAccessToken();
        if (empty($this->component_access_token)) {
            return false;
        }
        $data = array();
        $data['component_appid'] = $this->component_appid;
        $data['authorization_code'] = $authorization_code;
        $url = self::URL_PREFIX . self::QUERY_AUTH_URL . "?component_access_token={$this->component_access_token}";
        $result = Tools::httpPost($url, Tools::json_encode($data));
        $authorization_info = $this->_decode($result, 'authorization_info');
        if (empty($authorization_info)) {
            Tools::log("Get getAuthorizationInfo Faild. {$this->errMsg} [$this->errCode]", "ERR - {$this->authorizer_appid}");
            return false;
        }
        $authorization_info['func_info'] = $this->_parseFuncInfo($authorization_info['func_info']);
        return $authorization_info;
    }

    /**
     * 解析授权信息，返回以逗号分割的数据
     * @param array $func_info
     * @return string
     */
    private function _parseFuncInfo($func_info)
    {
        $authorization_list = array();
        foreach ($func_info as $func) {
            foreach ($func as $f) {
                isset($f['id']) && $authorization_list[] = $f['id'];
            }
        }
        return join($authorization_list, ',');
    }

    /**
     * 获取授权方的账户信息
     * @param string $authorizer_appid
     * @return bool
     */
    public function getWechatInfo($authorizer_appid)
    {
        empty($this->component_access_token) && $this->getComponentAccessToken();
        $data = array();
        $data['component_access_token'] = $this->component_access_token;
        $data['component_appid'] = $this->component_appid;
        $data['authorizer_appid'] = $authorizer_appid;
        $url = self::URL_PREFIX . self::GET_AUTHORIZER_INFO_URL . "?component_access_token={$this->component_access_token}";
        $result = Tools::httpPost($url, Tools::json_encode($data));
        $authorizer_info = $this->_decode($result, 'authorizer_info');
        if (empty($authorizer_info)) {
            Tools::log("Get WechatInfo Faild. {$this->errMsg} [$this->errCode]", "ERR - {$this->authorizer_appid}");
            return false;
        }
        $author_data = array_merge($authorizer_info, $this->data['authorization_info']);
        $author_data['service_type_info'] = $author_data['service_type_info']['id'];
        $author_data['verify_type_info'] = $author_data['verify_type_info']['id'];
        $author_data['func_info'] = $this->_parseFuncInfo($author_data['func_info']);
        $author_data['business_info'] = json_encode($author_data['business_info']);
        return $author_data;
    }

    /**
     * 获取授权方的选项设置信息
     * @param string $authorizer_appid
     * @param string $option_name
     * @return bool
     */
    public function getAuthorizerOption($authorizer_appid, $option_name)
    {
        empty($this->component_access_token) && $this->getComponentAccessToken();
        if (empty($this->authorizer_appid)) {
            return false;
        }
        $data = array();
        $data['component_appid'] = $this->component_appid;
        $data['authorizer_appid'] = $authorizer_appid;
        $data['option_name'] = $option_name;
        $url = self::URL_PREFIX . self::GET_AUTHORIZER_OPTION_URL . "?component_access_token={$this->component_access_token}";
        $result = Tools::httpPost($url, Tools::json_encode($data));
        if (($result = $this->_decode($result)) === false) {
            Tools::log("Get getAuthorizerOption Faild. {$this->errMsg} [$this->errCode]", "ERR - {$this->authorizer_appid}");
        }
        return $result;
    }

    /**
     * 设置授权方的选项信息
     * @param string $authorizer_appid
     * @param string $option_name
     * @param string $option_value
     * @return bool
     */
    public function setAuthorizerOption($authorizer_appid, $option_name, $option_value)
    {
        empty($this->component_access_token) && $this->getComponentAccessToken();
        if (empty($this->authorizer_appid)) {
            return false;
        }
        $data = array();
        $data['component_appid'] = $this->component_appid;
        $data['authorizer_appid'] = $authorizer_appid;
        $data['option_name'] = $option_name;
        $data['option_value'] = $option_value;
        $url = self::URL_PREFIX . self::SET_AUTHORIZER_OPTION_URL . "?component_access_token={$this->component_access_token}";
        $result = Tools::httpPost($url, Tools::json_encode($data));
        if (($result = $this->_decode($result)) === false) {
            Tools::log("Get setAuthorizerOption Faild. {$this->errMsg} [$this->errCode]", "ERR - {$this->authorizer_appid}");
        }
        return $result;
    }

    /**
     * 获取授权回跳地址
     * @param string $redirect_uri
     * @return bool
     */
    public function getAuthRedirect($redirect_uri)
    {
        empty($this->pre_auth_code) && $this->getPreauthCode();
        if (empty($this->pre_auth_code)) {
            return false;
        }
        return "https://mp.weixin.qq.com/cgi-bin/componentloginpage?component_appid={$this->component_appid}&pre_auth_code={$this->pre_auth_code}&redirect_uri={$redirect_uri}";
    }

    /**
     * 获取预授权码
     *
     * @return bool|string
     */
    public function getPreauthCode()
    {
        empty($this->component_access_token) && $this->getComponentAccessToken();
        if (empty($this->component_access_token)) {
            return false;
        }
        $data = array();
        $data['component_appid'] = $this->component_appid;
        $url = self::URL_PREFIX . self::PREAUTH_CODE_URL . "?component_access_token={$this->component_access_token}";
        $result = Tools::httpPost($url, Tools::json_encode($data));
        $this->pre_auth_code = $this->_decode($result, 'pre_auth_code');
        if (empty($this->pre_auth_code)) {
            Tools::log("Get getPreauthCode Faild. {$this->errMsg} [$this->errCode]", "ERR - {$this->authorizer_appid}");
        }
        return $this->pre_auth_code;
    }

    /**
     * oauth 授权跳转接口
     * @param string $appid
     * @param string $redirect_uri
     * @param string $scope snsapi_userinfo|snsapi_base
     * @return string
     */
    public function getOauthRedirect($appid, $redirect_uri, $scope = 'snsapi_userinfo')
    {
        return "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$appid}&redirect_uri=" . urlencode($redirect_uri)
            . "&response_type=code&scope={$scope}&state={$appid}&component_appid={$this->component_appid}#wechat_redirect";
    }

    /**
     * 通过code获取Access Token
     * @param string $appid
     * @return bool|array
     */
    public function getOauthAccessToken($appid)
    {
        $code = isset($_GET['code']) ? $_GET['code'] : '';
        if (empty($code)) {
            return false;
        }
        empty($this->component_access_token) && $this->getComponentAccessToken();
        if (empty($this->component_access_token)) {
            return false;
        }
        $url = "https://api.weixin.qq.com/sns/oauth2/component/access_token?appid={$appid}&code={$code}&grant_type=authorization_code&"
            . "component_appid={$this->component_appid}&component_access_token={$this->component_access_token}";
        $json = $this->parseJson(Tools::httpGet($url));
        if ($json !== false) {
            return $json;
        }
        return false;
    }

    /**
     * 解析JSON数据
     * @param string $result
     * @return bool
     */
    private function parseJson($result)
    {
        $json = json_decode($result, true);
        if (empty($json) || !empty($json['errcode'])) {
            $this->errCode = isset($json['errcode']) ? $json['errcode'] : '505';
            $this->errMsg = isset($json['errmsg']) ? $json['errmsg'] : '无法解析接口返回内容！';
            return false;
        }
        return $json;
    }

    /**
     * 获取关注者详细信息
     * @param string $openid
     * @param string $oauthAccessToken
     * @return bool|array {subscribe,openid,nickname,sex,city,province,country,language,headimgurl,subscribe_time,[unionid]}
     * 注意：unionid字段 只有在用户将公众号绑定到公众号第三方平台账号后，才会出现。建议调用前用isset()检测一下
     */
    public function getOauthUserInfo($openid, $oauthAccessToken)
    {
        $url = "https://api.weixin.qq.com/sns/userinfo?access_token={$oauthAccessToken}&openid={$openid}&lang=zh_CN";
        return $this->parseJson(Tools::httpGet($url));
    }


}
