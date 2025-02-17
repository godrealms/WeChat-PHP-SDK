<?php

namespace WeChatSDK\Offiaccount;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;

class CardManager
{
    /**
     * 查询卡券详情
     *
     * @param string $accessToken 调用接口凭证
     * @param string $cardId 卡券 ID
     * @return array 返回接口的响应结果
     * @throws GuzzleException
     */
    public static function getCardDetails(string $accessToken, string $cardId): array
    {
        // API 地址
        $url = "https://api.weixin.qq.com/card/get?access_token={$accessToken}";

        // 请求数据
        $requestData = [
            'card_id' => $cardId,
        ];

        // 创建 GuzzleHttp 客户端
        $client = new Client();

        try {
            // 发送 POST 请求
            $response = $client->post($url, [
                'json' => $requestData,
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
            ]);

            // 获取响应体并解析为数组
            $responseBody = $response->getBody()->getContents();
            return json_decode($responseBody, true);

        } catch (RequestException $e) {
            return [
                'errcode' => 500,
                'errmsg' => $e->getMessage(),
            ];
        }
    }

    /**
     * 创建卡券
     *
     * @param string $accessToken 调用接口凭证
     * @param array $cardData 卡券的详细信息
     * @return array 返回接口的响应结果
     * @throws GuzzleException
     */
    public static function createCard(string $accessToken, array $cardData): array
    {
        // API 地址
        $url = "https://api.weixin.qq.com/card/create?access_token={$accessToken}";

        // 创建 GuzzleHttp 客户端
        $client = new Client();

        try {
            // 发送 POST 请求
            $response = $client->post($url, [
                'body' => json_encode($cardData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), // 自动将数组编码为 JSON
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


    /**
     * 更改卡券信息
     *
     * @param string $accessToken 调用接口凭证
     * @param string $cardId 卡券 ID
     * @param array $updateData 更改的卡券信息
     * @return array 返回接口的响应结果
     * @throws GuzzleException
     */
    public static function updateCard(string $accessToken, string $cardId, array $updateData): array
    {
        // API 地址
        $url = "https://api.weixin.qq.com/card/update?access_token={$accessToken}";

        // 请求数据
        $requestData = [
            'card_id' => $cardId,
            'member_card' => $updateData, // 根据卡券类型，替换字段
        ];

        // 创建 GuzzleHttp 客户端
        $client = new Client();

        try {
            // 发送 POST 请求
            $response = $client->post($url, [
                'json' => $requestData,
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
            ]);

            // 获取响应体并解析为数组
            $responseBody = $response->getBody()->getContents();
            return json_decode($responseBody, true);

        } catch (RequestException $e) {
            return [
                'errcode' => 500,
                'errmsg' => $e->getMessage(),
            ];
        }
    }

    /**
     * 删除卡券
     *
     * @param string $accessToken 调用接口凭证
     * @param string $cardId 卡券 ID
     * @return array 返回接口的响应结果
     * @throws GuzzleException
     */
    public static function deleteCard(string $accessToken, string $cardId): array
    {
        // API 地址
        $url = "https://api.weixin.qq.com/card/delete?access_token={$accessToken}";

        // 请求数据
        $requestData = [
            'card_id' => $cardId,
        ];

        // 创建 GuzzleHttp 客户端
        $client = new Client();

        try {
            // 发送 POST 请求
            $response = $client->post($url, [
                'json' => $requestData,
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
            ]);

            // 获取响应体并解析为数组
            $responseBody = $response->getBody()->getContents();
            return json_decode($responseBody, true);

        } catch (RequestException $e) {
            return [
                'errcode' => 500,
                'errmsg' => $e->getMessage(),
            ];
        }
    }
    /**
     * 删除卡券
     *
     * @param string $accessToken 调用接口凭证
     * @param string $cardId 卡券 ID
     * @return array 返回接口的响应结果
     * @throws GuzzleException
     */
    public static function GetCardUrl(string $accessToken, string $cardId,string $outer_str = "h5"): array
    {
        // API 地址
        $url = "https://api.weixin.qq.com/card/membercard/activate/geturl?access_token={$accessToken}";

        // 请求数据
        $requestData = [
            'card_id' => $cardId,
            'outer_str'=>$outer_str
        ];

        // 创建 GuzzleHttp 客户端
        $client = new Client();

        try {
            // 发送 POST 请求
            $response = $client->post($url, [
                'json' => $requestData,
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
            ]);

            // 获取响应体并解析为数组
            $responseBody = $response->getBody()->getContents();
            return json_decode($responseBody, true);

        } catch (RequestException $e) {
            return [
                'errcode' => 500,
                'errmsg' => $e->getMessage(),
            ];
        }
    }
}
