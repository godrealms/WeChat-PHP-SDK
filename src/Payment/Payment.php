<?php

namespace WeChatSDK\Payment;

use WeChatSDK\Config;
use WeChatSDK\HttpClient;

class Payment
{
    private Config $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function unifiedOrder($params)
    {
        $url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
        $params['appid'] = $this->config->getAppId();
        $params['mch_id'] = $this->config->getMchId();
        $params['nonce_str'] = $this->generateNonceStr();
        $params['sign'] = $this->generateSign($params);

        return HttpClient::post($url, $params);
    }

    private function generateNonceStr($length = 16)
    {
        return substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, $length);
    }

    private function generateSign($params)
    {
        ksort($params);
        $string = urldecode(http_build_query($params)) . '&key=' . $this->config->getApiKey();
        return strtoupper(md5($string));
    }
}
