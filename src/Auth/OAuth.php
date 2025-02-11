<?php

namespace WeChatSDK\Auth;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use WeChatSDK\Config;

class OAuth
{
    private $config;

    /**
     * OAuth constructor.
     *
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * 获取微信授权 URL
     *
     * @param string $redirectUri 回调地址
     * @param string $scope 授权范围，默认 snsapi_userinfo
     * @param string $state 状态参数，默认 STATE
     * @return string 授权 URL
     */
    public function getAuthorizeUrl(string $redirectUri, string $scope = 'snsapi_userinfo', string $state = 'STATE'): string
    {
        $params = [
            'appid' => $this->config->getAppId(),
            'redirect_uri' => $redirectUri,
            'response_type' => 'code',
            'scope' => $scope,
            'state' => $state,
        ];

        return 'https://open.weixin.qq.com/connect/oauth2/authorize?' . http_build_query($params) . '#wechat_redirect';
    }

    /**
     * 获取 Access Token
     *
     * @param string $code 授权码
     * @return array 返回解析后的响应数据
     * @throws GuzzleException
     */
    public function getAccessTokenByCode(string $code): array
    {
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token';
        $params = [
            'appid' => $this->config->getAppId(),
            'secret' => $this->config->getAppSecret(),
            'code' => $code,
            'grant_type' => 'authorization_code',
        ];

        try {
            $client = new Client();
            $response = $client->get($url, ['query' => $params]);
            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            return [
                'errcode' => 500,
                'errmsg' => $e->getMessage(),
            ];
        }
    }
    /**
     * 获取 Access Token
     *
     * @return array 返回解析后的响应数据
     * @throws GuzzleException
     */
    public function getAccessToken(): array
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/token';
        $params = [
            'appid' => $this->config->getAppId(),
            'secret' => $this->config->getAppSecret(),
            'grant_type' => 'client_credential',
        ];

        try {
            $client = new Client();
            $response = $client->get($url, ['query' => $params]);
            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            return [
                'errcode' => 500,
                'errmsg' => $e->getMessage(),
            ];
        }
    }

    /**
     * 获取用户信息
     *
     * @param string $accessToken 调用接口凭证
     * @param string $openId 用户唯一标识
     * @param string $lang 返回语言，默认 zh-CN
     * @return array 返回解析后的用户信息
     * @throws GuzzleException
     */
    public function getUserInfo(string $accessToken, string $openId, string $lang = 'zh-CN'): array
    {
        $url = 'https://api.weixin.qq.com/sns/userinfo';
        $params = [
            'access_token' => $accessToken,
            'openid' => $openId,
            'lang' => $lang,
        ];

        try {
            $client = new Client();
            $response = $client->get($url, ['query' => $params]);
            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            return [
                'errcode' => 500,
                'errmsg' => $e->getMessage(),
            ];
        }
    }
}