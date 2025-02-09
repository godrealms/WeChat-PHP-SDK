<?php

namespace WeChatSDK\Utils;

class RandomString
{
    /**
     * 生成指定长度的随机字符串
     * @param int $length 字符串长度
     * @return string 随机字符串
     */
    public static function generate(int $length = 16): string
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= $chars[mt_rand(0, strlen($chars) - 1)];
        }
        return $str;
    }
}
