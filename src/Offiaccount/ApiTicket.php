<?php

namespace WeChatSDK\Offiaccount;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;

class ApiTicket
{
    /**
     * @param $access_token
     * @return array|mixed
     * @throws GuzzleException
     */
    public static function GetTicket($access_token)
    {
        // API 地址
        $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token={$access_token}&type=wx_card";

        // 创建 GuzzleHttp 客户端
        $client = new Client();

        try {
            // 发送 POST 请求
            $response = $client->get($url);

            // 获取响应体并解析为数组
            $responseBody = $response->getBody()->getContents();
            return json_decode($responseBody, true);

        } catch (RequestException $e) {
            // 捕获请求异常并返回错误信息
            return [
                'errcode' => 500,
                'errmsg' => $e->getMessage(),
            ];
        }
    }
}