<?php

use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use WeChatSDK\Offiaccount\GiftCardManager;

class GiftCardManagerTest extends TestCase
{
    public function testCreateGiftCardPageSuccess()
    {
        // 模拟成功的 API 响应
        $mock = new MockHandler([
            new Response(200, [], json_encode([
                'errcode' => 0,
                'errmsg' => 'ok',
                'page_id' => 'mock_page_id',
            ])),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        // 替换 GiftCardManager 中的 GuzzleHttp 客户端
        $pageData = [
            'page_title' => '测试礼品卡货架',
            'theme_list' => [
                [
                    'card_id' => 'mock_card_id',
                    'title' => '礼品卡主题1',
                ],
            ],
        ];

        // 调用方法
        try {
            $result = GiftCardManager::createGiftCardPage('mock_access_token', $pageData);
            // 验证返回结果
            $this->assertArrayHasKey('page_id', $result);
            $this->assertEquals('mock_page_id', $result['page_id']);
        } catch (GuzzleException $e) {
            echo $e->getMessage();
        }

    }
}
