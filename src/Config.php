<?php

namespace WeChatSDK;

class Config
{
    private $appId;
    private $appSecret;
    private $token;
    private $aesKey;
    private $mchId;
    private $apiKey;

    public function __construct($appId="", $appSecret="", $token = "", $aesKey = "", $mchId = "", $apiKey = "")
    {
        $this->appId = $appId;
        $this->appSecret = $appSecret;
        $this->token = $token;
        $this->aesKey = $aesKey;
        $this->mchId = $mchId;
        $this->apiKey = $apiKey;
    }

    public function getAppId(): string
    {
        return $this->appId;
    }

    public function getAppSecret(): string
    {
        return $this->appSecret;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getAesKey(): string
    {
        return $this->aesKey;
    }

    public function getMchId(): string
    {
        return $this->mchId;
    }

    public function getApiKey(): string
    {
        return $this->apiKey;
    }
}
