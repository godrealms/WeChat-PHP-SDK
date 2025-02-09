<?php

require 'vendor/autoload.php'; // 自动加载 Guzzle 和其他依赖

use GuzzleHttp\Exception\GuzzleException;
use WeChatSDK\Offiaccount\CardManager;

// 调用接口凭证（access_token）
$accessToken = 'your_access_token_here';


// 卡券 ID
$cardId = 'your_card_id_here';

// 调用查询卡券详情方法
try {
    $result = CardManager::getCardDetails($accessToken, $cardId);
    // 检查结果
    if (isset($result['errcode']) && $result['errcode'] != 0) {
        echo "Error: " . $result['errmsg'] . "\n";
    } else {
        echo "Card details retrieved successfully.\n";
        print_r($result);
    }
} catch (GuzzleException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// 调用创建卡券方法
try {
    // 卡券的详细信息
    $cardData = [
        'card' => [
            'card_type' => 'GROUPON',
            'groupon' => [
                'base_info' => [
                    'logo_url' => 'https://mmbiz.qpic.cn/mmbiz/your_logo_url/0',
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
    $result = CardManager::createCard($accessToken, $cardData);

    // 检查结果
    if (isset($result['errcode']) && $result['errcode'] != 0) {
        echo "Error: " . $result['errmsg'] . "\n";
    } else {
        echo "Card created successfully. Card ID: " . $result['card_id'] . "\n";
    }
} catch (GuzzleException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// 调用更改卡券信息方法
try {
    // 更改的卡券信息
    $updateData = [
        'base_info' => [
            'title' => '新标题',
            'color' => 'Color010', // 更改颜色
        ],
    ];
    $result = CardManager::updateCard($accessToken, $cardId, $updateData);
    // 检查结果
    if (isset($result['errcode']) && $result['errcode'] != 0) {
        echo "Error: " . $result['errmsg'] . "\n";
    } else {
        echo "Card updated successfully.\n";
        print_r($result);
    }
} catch (GuzzleException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}


// 调用删除卡券方法
try {
    $result = CardManager::deleteCard($accessToken, $cardId);
    // 检查结果
    if (isset($result['errcode']) && $result['errcode'] != 0) {
        echo "Error: " . $result['errmsg'] . "\n";
    } else {
        echo "Card deleted successfully.\n";
    }
} catch (GuzzleException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}