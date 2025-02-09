<?php

use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use WeChatSDK\Offiaccount\CardDistribution;

class CardDistributionTest extends TestCase
{
    public function testCreateQRCodeSuccess()
    {
        // 模拟成功的 API 响应
        $mock = new MockHandler([
            new Response(200, [], json_encode([
                'errcode' => 0,
                'errmsg' => 'ok',
                'ticket' => 'mock_ticket',
                'expire_seconds' => 1800,
                'url' => 'https://mock-url.com/qrcode',
            ])),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        // 替换 CardDistribution 中的 GuzzleHttp 客户端
        $cardData = [
            'action_name' => 'QR_CARD',
            'expire_seconds' => 1800,
            'action_info' => [
                'card' => [
                    'card_id' => 'mock_card_id',
                    'is_unique_code' => false,
                    'outer_id' => '12345',
                ],
            ],
        ];

        // 调用方法
        try {
            $result = CardDistribution::createQRCode('mock_access_token', $cardData);
            // 验证返回结果
            $this->assertArrayHasKey('ticket', $result);
            $this->assertEquals('mock_ticket', $result['ticket']);
        } catch (GuzzleException $e) {
            echo $e->getMessage();
        }
    }

    public function testGenerateH5Link()
    {
        $cardId = 'mock_card_id';
        $outerId = '12345';

        $h5Link = CardDistribution::generateH5Link($cardId, $outerId);

        $this->assertStringContainsString($cardId, $h5Link);
        $this->assertStringContainsString($outerId, $h5Link);
    }
}
