<?php

namespace WeChatSDK\Offiaccount;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;

class GiftCardManager
{
    /**
     * 创建礼品卡货架
     *
     * @param string $accessToken 调用接口凭证
     * @param array $pageData 礼品卡货架信息
     * @return array 返回接口的响应结果
     * @throws GuzzleException
     */
    public static function createGiftCardPage(string $accessToken, array $pageData): array
    {
        $url = "https://api.weixin.qq.com/card/giftcard/page/add?access_token={$accessToken}";

        return self::sendPostRequest($url, $pageData);
    }

    /**
     * 查询礼品卡货架信息
     *
     * @param string $accessToken 调用接口凭证
     * @param string $pageId 礼品卡货架 ID
     * @return array 返回接口的响应结果
     * @throws GuzzleException
     */
    public static function getGiftCardPage(string $accessToken, string $pageId): array
    {
        $url = "https://api.weixin.qq.com/card/giftcard/page/get?access_token={$accessToken}";

        $requestData = [
            'page_id' => $pageId,
        ];

        return self::sendPostRequest($url, $requestData);
    }

    /**
     * 更新礼品卡货架
     *
     * @param string $accessToken 调用接口凭证
     * @param array $pageData 更新的礼品卡货架信息
     * @return array 返回接口的响应结果
     * @throws GuzzleException
     */
    public static function updateGiftCardPage(string $accessToken, array $pageData): array
    {
        $url = "https://api.weixin.qq.com/card/giftcard/page/update?access_token={$accessToken}";

        return self::sendPostRequest($url, $pageData);
    }

    /**
     * 删除礼品卡货架
     *
     * @param string $accessToken 调用接口凭证
     * @param string $pageId 礼品卡货架 ID
     * @return array 返回接口的响应结果
     * @throws GuzzleException
     */
    public static function deleteGiftCardPage(string $accessToken, string $pageId): array
    {
        $url = "https://api.weixin.qq.com/card/giftcard/page/delete?access_token={$accessToken}";

        $requestData = [
            'page_id' => $pageId,
        ];

        return self::sendPostRequest($url, $requestData);
    }

    /**
     * 设置支付后开通礼品卡功能
     *
     * @param string $accessToken 调用接口凭证
     * @param array $whitelistData 白名单信息
     * @return array 返回接口的响应结果
     * @throws GuzzleException
     */
    public static function setPayWhitelist(string $accessToken, array $whitelistData): array
    {
        $url = "https://api.weixin.qq.com/card/giftcard/pay/whitelist/add?access_token={$accessToken}";

        return self::sendPostRequest($url, $whitelistData);
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
