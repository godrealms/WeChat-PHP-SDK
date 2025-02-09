<?php

namespace WeChatSDK\Message;

class MessageHandler
{
    /**
     * @param $xml
     * @return false|\SimpleXMLElement
     */
    public static function parseMessage($xml)
    {
        return simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
    }

    /**
     * @param $toUser
     * @param $fromUser
     * @param $content
     * @return string
     */
    public static function replyText($toUser, $fromUser, $content): string
    {
        $template = "<xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%d</CreateTime>
            <MsgType><![CDATA[text]]></MsgType>
            <Content><![CDATA[%s]]></Content>
        </xml>";
        return sprintf($template, $toUser, $fromUser, time(), $content);
    }
}
