<?php

namespace WeChatSDK\Tests;

use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use WeChatSDK\Offiaccount\CardManager;

class CardManagerTest extends TestCase
{
    public function testGetCardDetailsSuccess()
    {
        // 模拟成功的 API 响应
        $mock = new MockHandler([
            new Response(200, [], json_encode([
                'errcode' => 0,
                'errmsg' => 'ok',
                'card' => [
                    'card_type' => 'GROUPON',
                    'groupon' => [
                        'base_info' => [
                            'title' => '测试卡券',
                            'brand_name' => '微信SDK',
                            'status' => 'CARD_STATUS_VERIFY_OK',
                        ],
                    ],
                ],
            ])),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        // 替换 CardManager 中的 GuzzleHttp 客户端
        $cardId = 'mock_card_id';

        // 调用方法
        try {
            $result = CardManager::getCardDetails('mock_access_token', $cardId);
            // 验证返回结果
            $this->assertArrayHasKey('card', $result);
            $this->assertEquals('GROUPON', $result['card']['card_type']);
        } catch (GuzzleException $e) {
            echo $e->getMessage();
        }
    }

    public function testCreateCardSuccess()
    {
        // 模拟成功的 API 响应
        $mock = new MockHandler([
            new Response(200, [], json_encode(['errcode' => 0, 'errmsg' => 'ok', 'card_id' => 'mock_card_id']))
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        // 替换 CardManager 中的 GuzzleHttp 客户端
        $cardData = [
            'card' => [
                'card_type' => 'GROUPON',
                'groupon' => [
                    'base_info' => [
                        'logo_url' => 'http://mmbiz.qpic.cn/mmbiz/your_logo_url/0',
                        'brand_name' => '微信SDK测试',
                        'code_type' => 'CODE_TYPE_QRCODE',
                        'title' => '测试团购券',
                        'sub_title' => '测试副标题',
                        'color' => 'Color010',
                        'notice' => '请出示二维码核销',
                        'description' => '不可与其他优惠同享',
                        'date_info' => [
                            'type' => 'DATE_TYPE_FIX_TIME_RANGE',
                            'begin_timestamp' => strtotime('2025-02-01'),
                            'end_timestamp' => strtotime('2025-02-28')
                        ],
                        'sku' => [
                            'quantity' => 1000
                        ],
                        'get_limit' => 1,
                        'use_custom_code' => false,
                        'bind_openid' => false,
                        'can_share' => true,
                        'can_give_friend' => true
                    ],
                    'deal_detail' => '测试团购详情'
                ]
            ]
        ];

        // 调用方法
        try {
            $result = CardManager::createCard('mock_access_token', $cardData);
            // 验证返回结果
            $this->assertArrayHasKey('card_id', $result);
            $this->assertEquals('mock_card_id', $result['card_id']);
        } catch (GuzzleException $e) {
            var_dump("Error: ", $e->getMessage());
        }
    }

    public function testCreateCardError()
    {
        // 模拟失败的 API 响应
        $mock = new MockHandler([
            new Response(400, [], json_encode(['errcode' => 40013, 'errmsg' => 'invalid appid']))
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        // 替换 CardManager 中的 GuzzleHttp 客户端
        $cardData = [
            'card' => [
                'card_type' => 'GROUPON',
                'groupon' => [] // 缺少必要参数
            ]
        ];

        // 调用方法
        try {
            $result = CardManager::createCard('mock_access_token', $cardData);

            // 验证返回结果
            $this->assertArrayHasKey('errcode', $result);
            $this->assertEquals(40013, $result['errcode']);
            $this->assertEquals('invalid appid', $result['errmsg']);
        } catch (GuzzleException $e) {
            var_dump("Error: ", $e->getMessage());
        }
    }
}