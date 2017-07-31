<?php

namespace Wechat;

use Wechat\Lib\Common;
use Wechat\Lib\Tools;

class WechatHardware extends Common
{

    const DEVICE_AUTHORIZE_DEVICE = '/device/authorize_device?'; //设备设全
    const DEVICE_GETQRCODE = '/device/getqrcode?';               //设备授权新接口
    const DEVICE_CREATE_QRCODE = '/device/create_qrcode?';       //获取设备二维码
    const DEVICE_GET_STAT = '/device/get_stat?';                 //获取设备状态
    const DEVICE_TRANSMSG = '/device/transmsg?';                 //主动发送消息给设备
    const DEVICE_COMPEL_UNBINDHTTPS = '/device/compel_unbind?';  //强制解绑用户和设备

    /**
     * 强制解绑用户和设备
     * @param $data
     * @return bool|mixed
     */
    public function deviceCompelUnbindhttps($data)
    {
        if (!$this->access_token && !$this->getAccessToken()) {
            return false;
        }
        $result = Tools::httpPost(self::API_BASE_URL_PREFIX . self::DEVICE_COMPEL_UNBINDHTTPS . "access_token={$this->access_token}", Tools::json_encode($data));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return $this->checkRetry(__FUNCTION__, func_get_args());
            }
            return $json;
        }
        return false;
    }


    public function transmsg($data)
    {
        if (!$this->access_token && !$this->getAccessToken()) {
            return false;
        }
        $result = Tools::httpPost(self::API_BASE_URL_PREFIX . self::DEVICE_TRANSMSG . "access_token={$this->access_token}", Tools::json_encode($data));
        //dump($result);
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return $this->checkRetry(__FUNCTION__, func_get_args());
            }
            return $json;
        }
        return false;
    }

    public function getQrcode($product_id)
    {
        if (!$this->access_token && !$this->getAccessToken()) {
            return false;
        }
        $result = Tools::httpGet(self::API_BASE_URL_PREFIX . self::DEVICE_GETQRCODE . "access_token={$this->access_token}&product_id=$product_id");
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return $this->checkRetry(__FUNCTION__, func_get_args());
            }
            return $json;
        }
        return false;
    }

    /**
     * 设备授权
     * @param $data
     * @return bool|mixed
     */
    public function deviceAuthorize($data)
    {
        if (!$this->access_token && !$this->getAccessToken()) {
            return false;
        }
        $result = Tools::httpPost(self::API_BASE_URL_PREFIX . self::DEVICE_AUTHORIZE_DEVICE . "access_token={$this->access_token}", Tools::json_encode($data));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return $this->checkRetry(__FUNCTION__, func_get_args());
            }
            return $json;
        }
        return false;
    }

    /**
     * 获取设备二维码
     * @param $data
     * @return bool|mixed
     */
    public function getDeviceQrcode($data)
    {
        if (!$this->access_token && !$this->getAccessToken()) {
            return false;
        }
        $result = Tools::httpPost(self::API_BASE_URL_PREFIX . self::DEVICE_CREATE_QRCODE . "access_token={$this->access_token}", Tools::json_encode($data));
        //dump($result);
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return $this->checkRetry(__FUNCTION__, func_get_args());
            }
            return $json;
        }
        return false;
    }

    /**
     * 获取设备状态
     * @param $device_id
     * @return bool|mixed
     */
    public function getDeviceStat($device_id)
    {
        if (!$this->access_token && !$this->getAccessToken()) {
            return false;
        }
        $result = Tools::httpGet(self::API_BASE_URL_PREFIX . self::DEVICE_GET_STAT . "access_token={$this->access_token}&device_id=$device_id");
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return $this->checkRetry(__FUNCTION__, func_get_args());
            }
            return $json;
        }
        return false;
    }

}