<?php

namespace WeChatSDK\Utils;

class Signature
{
    /**
     * 生成签名
     * @param array $params 待签名参数
     * @param string $key 微信支付密钥（或其他签名密钥）
     * @return string 签名结果
     */
    public static function generate(array $params, string $key = ''): string
    {
        ksort($params); // 按字典序排序
        $string = urldecode(http_build_query($params));
        if ($key) {
            $string .= "&key=$key";
        }
        return strtoupper(md5($string));
    }

    /**
     * 校验签名
     * @param array $params 待校验参数
     * @param string $key 微信支付密钥（或其他签名密钥）
     * @param string $sign 待校验的签名
     * @return bool 签名是否正确
     */
    public static function verify(array $params, string $key, string $sign): bool
    {
        $generatedSign = self::generate($params, $key);
        return $generatedSign === $sign;
    }
}
