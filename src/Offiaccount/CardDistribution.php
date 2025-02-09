<?php

namespace WeChatSDK\Offiaccount;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;

class CardDistribution
{
    /**
     * 生成卡券二维码
     *
     * @param string $accessToken 调用接口凭证
     * @param array $cardData 卡券投放的详细信息
     * @return array 返回接口的响应结果
     * @throws GuzzleException
     */
    public static function createQRCode(string $accessToken, array $cardData): array
    {
        // API 地址
        $url = "https://api.weixin.qq.com/card/qrcode/create?access_token={$accessToken}";

        // 创建 GuzzleHttp 客户端
        $client = new Client();

        try {
            // 发送 POST 请求
            $response = $client->post($url, [
                'json' => $cardData, // 自动将数组编码为 JSON
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
     * 生成卡券 H5 链接
     *
     * @param string $cardId 卡券 ID
     * @param string $outerId 自定义参数（可选）
     * @return string 返回 H5 页面链接
     */
    public static function generateH5Link(string $cardId, string $outerId = ''): string
    {
        $baseUrl = "https://mp.weixin.qq.com/bizmall/malljump";
        $params = http_build_query([
            'action' => 'show',
            'biz' => 'MzA3MDM3NjE4OA==', // 商户的公众号 ID（需替换为实际值）
            'cardid' => $cardId,
            'outerid' => $outerId,
        ]);

        return "{$baseUrl}?{$params}";
    }
}
