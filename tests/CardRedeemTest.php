<?php

use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use WeChatSDK\Offiaccount\CardRedeem;

class CardRedeemTest extends TestCase
{
    public function testConsumeCodeSuccess()
    {
        // 模拟成功的 API 响应
        $mock = new MockHandler([
            new Response(200, [], json_encode([
                'errcode' => 0,
                'errmsg' => 'ok',
                'openid' => 'oFS7Fjl0WsZ9AMZqrI80nbIq8xrA',
                'card' => [
                    'card_id' => 'mock_card_id',
                ],
            ])),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        // 替换 CardRedeem 中的 GuzzleHttp 客户端
        $code = 'mock_code';
        $cardId = 'mock_card_id';

        // 调用方法
        try {
            $result = CardRedeem::consumeCode('mock_access_token', $code, $cardId);
            // 验证返回结果
            $this->assertArrayHasKey('card', $result);
            $this->assertEquals('mock_card_id', $result['card']['card_id']);
        } catch (GuzzleException $e) {
            echo $e->getMessage();
        }
    }

    public function testConsumeCodeError()
    {
        // 模拟失败的 API 响应
        $mock = new MockHandler([
            new Response(400, [], json_encode([
                'errcode' => 40099,
                'errmsg' => 'invalid code',
            ])),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        // 替换 CardRedeem 中的 GuzzleHttp 客户端
        $code = 'invalid_code';

        // 调用方法
        try {
            $result = CardRedeem::consumeCode('mock_access_token', $code);
            // 验证返回结果
            $this->assertArrayHasKey('errcode', $result);
            $this->assertEquals(40099, $result['errcode']);
            $this->assertEquals('invalid code', $result['errmsg']);
        } catch (GuzzleException $e) {
            echo $e->getMessage();
        }
    }
}
