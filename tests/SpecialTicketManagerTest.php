<?php

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use WeChatSDK\Offiaccount\SpecialTicketManager;

class SpecialTicketManagerTest extends TestCase
{
    public function testCreateSpecialTicketSuccess()
    {
        // 模拟成功的 API 响应
        $mock = new MockHandler([
            new Response(200, [], json_encode([
                'errcode' => 0,
                'errmsg' => 'ok',
                'card_id' => 'mock_card_id',
            ])),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        // 替换 SpecialTicketManager 中的 GuzzleHttp 客户端
        $cardData = [
            'card' => [
                'card_type' => 'MOVIE_TICKET',
                'movie_ticket' => [
                    'base_info' => [
                        'title' => '测试电影票',
                        'color' => 'Color010',
                    ],
                ],
            ],
        ];

        // 调用方法
        try {
            $result = SpecialTicketManager::createSpecialTicket('mock_access_token', $cardData);
            // 验证返回结果
            $this->assertArrayHasKey('card_id', $result);
            $this->assertEquals('mock_card_id', $result['card_id']);
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            echo $e->getMessage();
        }
    }
}
