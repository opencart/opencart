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

namespace WeMini;

use WeChat\Contracts\BasicWeChat;

/**
 * 微信小程序数据接口
 * Class Total
 * @package WeMini
 */
class Total extends BasicWeChat
{
    /**
     * 数据分析接口
     * @param string $begin_date 开始日期
     * @param string $end_date 结束日期，限定查询1天数据，end_date允许设置的最大值为昨日
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function getWeanalysisAppidDailySummarytrend($begin_date, $end_date)
    {
        $url = 'https://api.weixin.qq.com/datacube/getweanalysisappiddailysummarytrend?access_token=ACCESS_TOKEN';
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->callPostApi($url, ['begin_date' => $begin_date, 'end_date' => $end_date], true);
    }

    /**
     * 访问分析
     * @param string $begin_date 开始日期
     * @param string $end_date 结束日期，限定查询1天数据，end_date允许设置的最大值为昨日
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function getWeanalysisAppidDailyVisittrend($begin_date, $end_date)
    {
        $url = 'https://api.weixin.qq.com/datacube/getweanalysisappiddailyvisittrend?access_token=ACCESS_TOKEN';
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->callPostApi($url, ['begin_date' => $begin_date, 'end_date' => $end_date], true);
    }

    /**
     * 周趋势
     * @param string $begin_date 开始日期，为周一日期
     * @param string $end_date 结束日期，为周日日期，限定查询一周数据
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function getWeanalysisAppidWeeklyVisittrend($begin_date, $end_date)
    {
        $url = 'https://api.weixin.qq.com/datacube/getweanalysisappidweeklyvisittrend?access_token=ACCESS_TOKEN';
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->callPostApi($url, ['begin_date' => $begin_date, 'end_date' => $end_date], true);
    }

    /**
     * 月趋势
     * @param string $begin_date 开始日期，为自然月第一天
     * @param string $end_date 结束日期，为自然月最后一天，限定查询一个月数据
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function getWeanalysisAppidMonthlyVisittrend($begin_date, $end_date)
    {
        $url = 'https://api.weixin.qq.com/datacube/getweanalysisappidmonthlyvisittrend?access_token=ACCESS_TOKEN';
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->callPostApi($url, ['begin_date' => $begin_date, 'end_date' => $end_date], true);
    }

    /**
     * 访问分布
     * @param string $begin_date 开始日期
     * @param string $end_date 结束日期，限定查询1天数据，end_date允许设置的最大值为昨日
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function getWeanalysisAppidVisitdistribution($begin_date, $end_date)
    {
        $url = 'https://api.weixin.qq.com/datacube/getweanalysisappidvisitdistribution?access_token=ACCESS_TOKEN';
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->callPostApi($url, ['begin_date' => $begin_date, 'end_date' => $end_date], true);
    }

    /**
     * 日留存
     * @param string $begin_date 开始日期
     * @param string $end_date 结束日期，限定查询1天数据，end_date允许设置的最大值为昨日
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function getWeanalysisAppidDailyRetaininfo($begin_date, $end_date)
    {
        $url = 'https://api.weixin.qq.com/datacube/getweanalysisappiddailyretaininfo?access_token=ACCESS_TOKEN';
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->callPostApi($url, ['begin_date' => $begin_date, 'end_date' => $end_date], true);
    }

    /**
     * 周留存
     * @param string $begin_date 开始日期，为周一日期
     * @param string $end_date 结束日期，为周日日期，限定查询一周数据
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function getWeanalysisAppidWeeklyRetaininfo($begin_date, $end_date)
    {
        $url = 'https://api.weixin.qq.com/datacube/getweanalysisappidweeklyretaininfo?access_token=ACCESS_TOKEN';
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->callPostApi($url, ['begin_date' => $begin_date, 'end_date' => $end_date], true);
    }

    /**
     * 月留存
     * @param string $begin_date 开始日期，为自然月第一天
     * @param string $end_date 结束日期，为自然月最后一天，限定查询一个月数据
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function getWeanalysisAppidMonthlyRetaininfo($begin_date, $end_date)
    {
        $url = 'https://api.weixin.qq.com/datacube/getweanalysisappidmonthlyretaininfo?access_token=ACCESS_TOKEN';
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->callPostApi($url, ['begin_date' => $begin_date, 'end_date' => $end_date], true);
    }

    /**
     * 访问页面
     * @param string $begin_date 开始日期
     * @param string $end_date 结束日期，限定查询1天数据，end_date允许设置的最大值为昨日
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function getWeanalysisAppidVisitPage($begin_date, $end_date)
    {
        $url = 'https://api.weixin.qq.com/datacube/getweanalysisappidvisitpage?access_token=ACCESS_TOKEN';
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->callPostApi($url, ['begin_date' => $begin_date, 'end_date' => $end_date], true);
    }

    /**
     * 用户画像
     * @param string $begin_date 开始日期
     * @param string $end_date 结束日期，开始日期与结束日期相差的天数限定为0/6/29，分别表示查询最近1/7/30天数据，end_date允许设置的最大值为昨日
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function getWeanalysisAppidUserportrait($begin_date, $end_date)
    {
        $url = 'https://api.weixin.qq.com/datacube/getweanalysisappiduserportrait?access_token=ACCESS_TOKEN';
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->callPostApi($url, ['begin_date' => $begin_date, 'end_date' => $end_date], true);
    }

}