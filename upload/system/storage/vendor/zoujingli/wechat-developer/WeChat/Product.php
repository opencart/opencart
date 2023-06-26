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

namespace WeChat;

use WeChat\Contracts\BasicWeChat;

/**
 * 商店管理
 * Class Product
 * @package WeChat
 */
class Product extends BasicWeChat
{
    /**
     * 提交审核/取消发布商品
     * @param string $keystandard 商品编码标准
     * @param string $keystr 商品编码内容
     * @param string $status 设置发布状态。on为提交审核，off为取消发布
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function modStatus($keystandard, $keystr, $status = 'on')
    {
        $data = ['keystandard' => $keystandard, 'keystr' => $keystr, 'status' => $status];
        $url = "https://api.weixin.qq.com/scan/product/modstatus?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, $data);
    }

    /**
     * 设置测试人员白名单
     * @param array $openids 测试人员的openid列表
     * @param array $usernames 测试人员的微信号列表
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function setTestWhiteList(array $openids = [], array $usernames = [])
    {
        $data = ['openid' => $openids, 'username' => $usernames];
        $url = "https://api.weixin.qq.com/scan/testwhitelist/set?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, $data);
    }

    /**
     * 获取商品二维码
     * @param string $keystandard 商品编码标准
     * @param string $keystr 商品编码内容
     * @param integer $qrcode_size 二维码的尺寸（整型），数值代表边长像素数，不填写默认值为100
     * @param array $extinfo 由商户自定义传入，建议仅使用大小写字母、数字及-_().*这6个常用字符
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function getQrcode($keystandard, $keystr, $qrcode_size, $extinfo = [])
    {
        $data = ['keystandard' => $keystandard, 'keystr' => $keystr, 'qrcode_size' => $qrcode_size];
        empty($extinfo) || $data['extinfo'] = $extinfo;
        $url = "https://api.weixin.qq.com/scan/product/getqrcode?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, $data);
    }

    /**
     * 查询商品信息
     * @param string $keystandard 商品编码标准
     * @param string $keystr 商品编码内容
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function getProduct($keystandard, $keystr)
    {
        $data = ['keystandard' => $keystandard, 'keystr' => $keystr];
        empty($extinfo) || $data['extinfo'] = $extinfo;
        $url = "https://api.weixin.qq.com/scan/product/get?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, $data);
    }

    /**
     * 批量查询商品信息
     * @param integer $offset 批量查询的起始位置，从0开始，包含该起始位置
     * @param integer $limit 批量查询的数量
     * @param null|string $status 支持按状态拉取。on为发布状态，off为未发布状态，check为审核中状态，reject为审核未通过状态，all为所有状态
     * @param string $keystr 支持按部分编码内容拉取。填写该参数后，可将编码内容中包含所传参数的商品信息拉出。类似关键词搜索
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function getProductList($offset, $limit = 10, $status = null, $keystr = '')
    {
        $data = ['offset' => $offset, 'limit' => $limit];
        is_null($status) || $data['status'] = $status;
        empty($keystr) || $data['keystr'] = $keystr;
        $url = "https://api.weixin.qq.com/scan/product/get?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, $data);
    }


    /**
     * 更新商品信息
     * @param array $data
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function updateProduct(array $data)
    {
        $url = "https://api.weixin.qq.com/scan/product/update?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, $data);
    }

    /**
     * 清除商品信息
     * @param string $keystandard 商品编码标准
     * @param string $keystr 商品编码内容
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function clearProduct($keystandard, $keystr)
    {
        $url = "https://api.weixin.qq.com/scan/product/clear?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, ['keystandard' => $keystandard, 'keystr' => $keystr]);
    }


    /**
     * 检查wxticket参数
     * @param string $ticket
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function scanTicketCheck($ticket)
    {
        $url = "https://api.weixin.qq.com/scan/scanticket/check?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, ['ticket' => $ticket]);
    }

    /**
     * 清除扫码记录
     * @param string $keystandard 商品编码标准
     * @param string $keystr 商品编码内容
     * @param string $extinfo 调用“获取商品二维码接口”时传入的extinfo，为标识参数
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function clearScanticket($keystandard, $keystr, $extinfo)
    {
        $data = ['keystandard' => $keystandard, 'keystr' => $keystr, 'extinfo' => $extinfo];
        $url = "https://api.weixin.qq.com/scan/scanticket/check?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, $data);
    }

}