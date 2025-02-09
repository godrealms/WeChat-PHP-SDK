<?php

namespace WeChatSDK\Offiaccount;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;

class CardRedeem
{
    /**
     * 核销卡券
     *
     * @param string $accessToken 调用接口凭证
     * @param string $code 用户的卡券 Code
     * @param string|null $cardId 卡券 ID（可选）
     * @return array 返回接口的响应结果
     * @throws GuzzleException
     */
    public static function consumeCode(string $accessToken, string $code, ?string $cardId = null): array
    {
        // API 地址
        $url = "https://api.weixin.qq.com/card/code/consume?access_token={$accessToken}";

        // 请求数据
        $requestData = [
            'code' => $code,
        ];

        // 如果提供了 cardId，则添加到请求数据中
        if ($cardId) {
            $requestData['card_id'] = $cardId;
        }

        // 创建 GuzzleHttp 客户端
        $client = new Client();

        try {
            // 发送 POST 请求
            $response = $client->post($url, [
                'json' => $requestData, // 自动将数组编码为 JSON
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
            ]);

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
