<?php
require 'vendor/autoload.php';

use GuzzleHttp\Exception\GuzzleException;
use WeChatSDK\Offiaccount\ThirdPartyCardManager;

// 调用接口凭证（access_token）
$accessToken = 'your_access_token_here';

// 调用代商户创建卡券方法
try {
    // 卡券信息
    $cardData = [
        'card' => [
            'card_type' => 'GROUPON',
            'groupon' => [
                'base_info' => [
                    'logo_url' => 'https://example.com/logo.jpg',
                    'brand_name' => '商户名称',
                    'title' => '团购券标题',
                    'color' => 'Color010',
                    'code_type' => 'CODE_TYPE_QRCODE',
                    'notice' => '请出示二维码核销',
                    'description' => '团购券描述信息',
                    'date_info' => [
                        'type' => 'DATE_TYPE_FIX_TIME_RANGE',
                        'begin_timestamp' => strtotime('2025-01-01'),
                        'end_timestamp' => strtotime('2025-12-31'),
                    ],
                    'sku' => [
                        'quantity' => 500,
                    ],
                ],
                'deal_detail' => '团购详情说明',
            ],
        ],
    ];

    $result = ThirdPartyCardManager::createCard($accessToken, $cardData);

    // 检查结果
    if (isset($result['errcode']) && $result['errcode'] != 0) {
        echo "Error: " . $result['errmsg'] . "\n";
    } else {
        echo "Card created successfully.\n";
        print_r($result);
    }
} catch (GuzzleException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}



// 调用设置测试白名单方法
try {
    // 调用接口凭证（access_token）
    $accessToken = 'your_access_token_here';

    // 测试用户的 OpenID 列表
    $openIds = ['openid1', 'openid2'];

    $result = ThirdPartyCardManager::setTestWhiteList($accessToken, $openIds);

    // 检查结果
    if (isset($result['errcode']) && $result['errcode'] != 0) {
        echo "Error: " . $result['errmsg'] . "\n";
    } else {
        echo "Test whitelist set successfully.\n";
    }
} catch (GuzzleException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}



// 调用拉取卡券概况数据方法
try {
    // 调用接口凭证（access_token）
    $accessToken = 'your_access_token_here';

    // 时间范围
    $beginDate = '2025-01-01';
    $endDate = '2025-01-31';

    $result = ThirdPartyCardManager::getCardOverview($accessToken, $beginDate, $endDate);

    // 检查结果
    if (isset($result['errcode']) && $result['errcode'] != 0) {
        echo "Error: " . $result['errmsg'] . "\n";
    } else {
        echo "Card overview data retrieved successfully.\n";
        print_r($result);
    }
} catch (GuzzleException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// 调用拉取卡券详细数据方法
try {
    // 调用接口凭证（access_token）
    $accessToken = 'your_access_token_here';

    // 时间范围
    $beginDate = '2025-01-01';
    $endDate = '2025-01-31';

    $result = ThirdPartyCardManager::getCardDetail($accessToken, $beginDate, $endDate);

    // 检查结果
    if (isset($result['errcode']) && $result['errcode'] != 0) {
        echo "Error: " . $result['errmsg'] . "\n";
    } else {
        echo "Card detail data retrieved successfully.\n";
        print_r($result);
    }
} catch (GuzzleException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
