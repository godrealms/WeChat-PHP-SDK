<?php

namespace WeChatSDK\Offiaccount;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;

class MembershipCardManager
{
    /**
     * 创建会员卡
     *
     * @param string $accessToken 调用接口凭证
     * @param array $cardData 会员卡信息
     * @return array 返回接口的响应结果
     * @throws GuzzleException
     */
    public static function createMembershipCard(string $accessToken, array $cardData): array
    {
        $url = "https://api.weixin.qq.com/card/create?access_token={$accessToken}";

        return self::sendPostRequest($url, $cardData);
    }

    /**
     * 设置开卡字段
     *
     * @param string $accessToken 调用接口凭证
     * @param array $formData 开卡字段信息
     * @return array 返回接口的响应结果
     * @throws GuzzleException
     */
    public static function setActivateUserForm(string $accessToken, array $formData): array
    {
        $url = "https://api.weixin.qq.com/card/membercard/activateuserform/set?access_token={$accessToken}";

        return self::sendPostRequest($url, $formData);
    }

    /**
     * 拉取会员信息
     *
     * @param string $accessToken 调用接口凭证
     * @param string $cardId 卡券 ID
     * @param string $code 用户领取的 Code
     * @return array 返回接口的响应结果
     * @throws GuzzleException
     */
    public static function getUserInfo(string $accessToken, string $cardId, string $code): array
    {
        $url = "https://api.weixin.qq.com/card/membercard/userinfo/get?access_token={$accessToken}";

        $requestData = [
            'card_id' => $cardId,
            'code' => $code,
        ];

        return self::sendPostRequest($url, $requestData);
    }

    /**
     * 更新会员信息
     *
     * @param string $accessToken 调用接口凭证
     * @param array $updateData 更新的会员信息
     * @return array 返回接口的响应结果
     * @throws GuzzleException
     */
    public static function updateUserInfo(string $accessToken, array $updateData): array
    {
        $url = "https://api.weixin.qq.com/card/membercard/updateuser?access_token={$accessToken}";

        return self::sendPostRequest($url, $updateData);
    }


    /**
     * 激活会员卡
     * @param string $accessToken
     * @param array $data
     * @return array
     * @throws GuzzleException
     */
    public static function activateMembershipCard(string $accessToken, array $data): array
    {
        $url = "https://api.weixin.qq.com/card/membercard/activate?access_token={$accessToken}";

        return self::sendPostRequest($url, $data);
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
