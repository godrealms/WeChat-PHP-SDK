<?php

namespace WeChatSDK\Payment;

use WeChatSDK\Config;

class Payment
{
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * 统一下单接口
     * 
     * @param array $params 下单参数
     * @return array
     */
    public function unifiedOrder(array $params): array
    {
        $url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
        
        // 设置必要的参数
        $params['appid'] = $this->config->getAppId();
        $params['mch_id'] = $this->config->getMchId();
        $params['nonce_str'] = $this->generateNonceStr();
        $params['sign'] = $this->generateSign($params);
        
        // 发送请求
        $response = $this->sendRequest($url, $params);
        
        return $this->parseResponse($response);
    }

    /**
     * 生成JSAPI支付参数
     * 
     * @param string $prepayId 预支付交易会话标识
     * @return array
     */
    public function getJsApiParameters(string $prepayId): array
    {
        $params = [
            'appId' => $this->config->getAppId(),
            'timeStamp' => time(),
            'nonceStr' => $this->generateNonceStr(),
            'package' => 'prepay_id=' . $prepayId,
            'signType' => 'MD5',
        ];
        
        $params['paySign'] = $this->generateSign($params);
        
        return $params;
    }

    /**
     * 生成随机字符串
     * 
     * @param int $length 字符串长度
     * @return string
     */
    private function generateNonceStr(int $length = 32): string
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    /**
     * 生成签名
     * 
     * @param array $params 参数数组
     * @return string
     */
    private function generateSign(array $params): string
    {
        ksort($params);
        $string = urldecode(http_build_query($params)) . '&key=' . $this->config->getApiKey();
        return strtoupper(md5($string));
    }

    /**
     * 发送请求
     * 
     * @param string $url 请求地址
     * @param array $params 请求参数
     * @return string
     */
    private function sendRequest(string $url, array $params): string
    {
        $xml = $this->arrayToXml($params);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: text/xml']);
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        return $response;
    }

    /**
     * 将数组转换为XML
     * 
     * @param array $array 数组
     * @return string
     */
    private function arrayToXml(array $array): string
    {
        $xml = '<xml>';
        foreach ($array as $key => $value) {
            if (is_numeric($value)) {
                $xml .= "<{$key}>{$value}</{$key}>";
            } else {
                $xml .= "<{$key}><![CDATA[{$value}]]></{$key}>";
            }
        }
        $xml .= '</xml>';
        return $xml;
    }

    /**
     * 解析XML响应
     * 
     * @param string $xml XML字符串
     * @return array
     */
    private function parseResponse(string $xml): array
    {
        $data = [];
        $xmlObject = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        
        if ($xmlObject) {
            foreach ($xmlObject as $key => $value) {
                $data[(string) $key] = (string) $value;
            }
        }
        
        return $data;
    }
}