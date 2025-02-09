<?php

namespace WeChatSDK\Offiaccount;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;

class SpecialTicketManager
{
    /**
     * 创建特权票卡券
     *
     * @param string $accessToken 调用接口凭证
     * @param array $cardData 特权票卡券信息
     * @return array 返回接口的响应结果
     * @throws GuzzleException
     */
    public static function createSpecialTicket(string $accessToken, array $cardData): array
    {
        $url = "https://api.weixin.qq.com/card/create?access_token={$accessToken}";

        return self::sendPostRequest($url, $cardData);
    }

    /**
     * 更新用户票信息
     *
     * @param string $accessToken 调用接口凭证
     * @param array $updateData 更新的用户票信息
     * @return array 返回接口的响应结果
     * @throws GuzzleException
     */
    public static function updateUserTicket(string $accessToken, array $updateData): array
    {
        $url = "https://api.weixin.qq.com/card/moviecard/updateuser?access_token={$accessToken}";

        return self::sendPostRequest($url, $updateData);
    }

    /**
     * 发送 POST 请求的通用方法
     *
     * @param string $url 请求地址
     * @param array $data 请求数据
     * @return array 返回接口响应
     * @throws GuzzleException
     */
    private static function sendPostRequest(string $url, array $data): array
    {
        $client = new Client();

        try {
            $response = $client->post($url, [
                'json' => $data,
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
            ]);

            return json_decode($response->getBody()->getContents(), true);

        } catch (RequestException $e) {
            return [
                'errcode' => 500,
                'errmsg' => $e->getMessage(),
            ];
        }
    }
}
