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
    private $CertPath;
    private $KeyPath;

    public function __construct($appId="", $appSecret="", $token = "", $aesKey = "", $mchId = "", $apiKey = "",$CertPath="",$KeyPath="")
    {
        $this->appId = $appId;
        $this->appSecret = $appSecret;
        $this->token = $token;
        $this->aesKey = $aesKey;
        $this->mchId = $mchId;
        $this->apiKey = $apiKey;
        $this->CertPath = $CertPath;
        $this->KeyPath = $KeyPath;
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

    public function getCertPath()
    {
        return $this->CertPath;
    }

    public function getKeyPath()
    {
        return $this->KeyPath;
    }

    public function getCertPassword()
    {
        return null;
    }
}
