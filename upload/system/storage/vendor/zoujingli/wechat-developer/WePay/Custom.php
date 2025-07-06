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

namespace WePay;

use WeChat\Contracts\BasicWePay;

/**
 * 微信扩展上报海关
 * Class Custom
 * @package WePay
 */
class Custom extends BasicWePay
{

    /**
     * 订单附加信息提交接口
     * @param array $options
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function add(array $options = [])
    {
        $url = 'https://api.mch.weixin.qq.com/cgi-bin/mch/customs/customdeclareorder';
        return $this->callPostApi($url, $options, false, 'MD5', false, false);
    }

    /**
     * 订单附加信息查询接口
     * @param array $options
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function get(array $options = [])
    {
        $url = 'https://api.mch.weixin.qq.com/cgi-bin/mch/customs/customdeclarequery';
        return $this->callPostApi($url, $options, false, 'MD5', true, false);
    }


    /**
     * 订单附加信息重推接口
     * @param array $options
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function reset(array $options = [])
    {
        $url = 'https://api.mch.weixin.qq.com/cgi-bin/mch/newcustoms/customdeclareredeclare';
        return $this->callPostApi($url, $options, false, 'MD5', true, false);
    }

}