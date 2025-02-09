<?php

namespace WeChatSDK\Offiaccount;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;

class ThirdPartyCardManager
{
    /**
     * 代商户创建卡券
     *
     * @param string $accessToken 调用接口凭证
     * @param array $cardData 卡券信息
     * @return array 返回接口的响应结果
     * @throws GuzzleException
     */
    public static function createCard(string $accessToken, array $cardData): array
    {
        $url = "https://api.weixin.qq.com/card/create?access_token={$accessToken}";

        return self::sendPostRequest($url, $cardData);
    }

    /**
     * 设置测试白名单
     *
     * @param string $accessToken 调用接口凭证
     * @param array $openIds 测试用户的 OpenID 列表
     * @return array 返回接口的响应结果
     * @throws GuzzleException
     */
    public static function setTestWhiteList(string $accessToken, array $openIds): array
    {
        $url = "https://api.weixin.qq.com/card/testwhitelist/set?access_token={$accessToken}";

        $requestData = [
            'openid' => $openIds,
        ];

        return self::sendPostRequest($url, $requestData);
    }

    /**
     * 拉取卡券概况数据
     *
     * @param string $accessToken 调用接口凭证
     * @param string $beginDate 开始日期，格式为 YYYY-MM-DD
     * @param string $endDate 结束日期，格式为 YYYY-MM-DD
     * @return array 返回接口的响应结果
     * @throws GuzzleException
     */
    public static function getCardOverview(string $accessToken, string $beginDate, string $endDate): array
    {
        $url = "https://api.weixin.qq.com/datacube/getcardbizuininfo?access_token={$accessToken}";

        $requestData = [
            'begin_date' => $beginDate,
            'end_date' => $endDate,
        ];

        return self::sendPostRequest($url, $requestData);
    }

    /**
     * 拉取卡券详细数据
     *
     * @param string $accessToken 调用接口凭证
     * @param string $beginDate 开始日期，格式为 YYYY-MM-DD
     * @param string $endDate 结束日期，格式为 YYYY-MM-DD
     * @return array 返回接口的响应结果
     * @throws GuzzleException
     */
    public static function getCardDetail(string $accessToken, string $beginDate, string $endDate): array
    {
        $url = "https://api.weixin.qq.com/datacube/getcardcardinfo?access_token={$accessToken}";

        $requestData = [
            'begin_date' => $beginDate,
            'end_date' => $endDate,
        ];

        return self::sendPostRequest($url, $requestData);
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
