<?php

namespace WeChatSDK\Utils;

class XmlHelper
{
    /**
     * 将数组转换为 XML 格式
     * @param array $data 待转换的数组
     * @return string XML 字符串
     */
    public static function arrayToXml(array $data): string
    {
        $xml = '<xml>';
        foreach ($data as $key => $value) {
            if (is_numeric($value)) {
                $xml .= "<$key>$value</$key>";
            } else {
                $xml .= "<$key><![CDATA[$value]]></$key>";
            }
        }
        $xml .= '</xml>';
        return $xml;
    }

    /**
     * 将 XML 转换为数组
     * @param string $xml XML 字符串
     * @return array 转换后的数组
     */
    public static function xmlToArray(string $xml): array
    {
        $data = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        return json_decode(json_encode($data), true);
    }
}
