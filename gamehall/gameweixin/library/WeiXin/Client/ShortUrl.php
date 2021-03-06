<?php
if (!defined('BASE_PATH')) exit('Access Denied!');
/**
 * 短链接转换接口
 */
class WeiXin_Client_ShortUrl extends WeiXin_Client_Base
{
    function __construct()
    {
        //设置接口链接
        $this->url = "https://api.mch.weixin.qq.com/tools/shorturl";
        //设置curl超时时间
        $this->curl_timeout = WeiXin_Config::CURL_TIMEOUT;
    }

    /**
     * 生成接口参数xml
     */
    function createXml()
    {
        try
        {
            if($this->parameters["long_url"] == null )
            {
                throw new WeiXin_Error("短链接转换接口中，缺少必填参数long_url！"."<br>");
            }
            $this->parameters["appid"] = WeiXin_Config::getAppID();//公众账号ID
            $this->parameters["mch_id"] = WeiXin_Config::MCHID;//商户号
            $this->parameters["nonce_str"] = $this->createNoncestr();//随机字符串
            $this->parameters["sign"] = $this->getSign($this->parameters);//签名
            return  $this->arrayToXml($this->parameters);
        }catch (WeiXin_Error $e)
        {
            Common::log($e->errorMessage(), "weixin.log");
            return false;
        }
    }

    /**
     * 获取prepay_id
     */
    function getShortUrl()
    {
        $this->postXml();
        $prepay_id = $this->result["short_url"];
        return $prepay_id;
    }

}