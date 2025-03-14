<?php

namespace WeChatSDK;

class Config
{
    private $appId;
    private $appSecret;
    private $token;
    private $aesKey;
    private $mchId;

    public function __construct($appId="", $appSecret="", $token = "", $aesKey = "", $mchId = "")
    {
        $this->appId = $appId;
        $this->appSecret = $appSecret;
        $this->token = $token;
        $this->aesKey = $aesKey;
        $this->mchId = $mchId;
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
}
