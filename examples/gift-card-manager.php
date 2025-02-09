<?php

use GuzzleHttp\Exception\GuzzleException;
use WeChatSDK\Offiaccount\GiftCardManager;

require 'vendor/autoload.php';


// 调用接口凭证（access_token）
$accessToken = 'your_access_token_here';

// 调用创建礼品卡货架方法
try {
    // 礼品卡货架信息
    $pageData = [
        'page_title' => '测试礼品卡货架',
        'theme_list' => [
            [
                'card_id' => 'your_card_id_here',
                'title' => '礼品卡主题1',
            ],
        ],
        'background_pic_url' => 'https://example.com/background.jpg',
    ];

    $result = GiftCardManager::createGiftCardPage($accessToken, $pageData);
    // 检查结果
    if (isset($result['errcode']) && $result['errcode'] != 0) {
        echo "Error: " . $result['errmsg'] . "\n";
    } else {
        echo "Gift card page created successfully.\n";
        print_r($result);
    }
} catch (GuzzleException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}


// 调用查询礼品卡货架方法
try {
    // 礼品卡货架 ID
    $pageId = 'your_page_id_here';

    $result = GiftCardManager::getGiftCardPage($accessToken, $pageId);
    // 检查结果
    if (isset($result['errcode']) && $result['errcode'] != 0) {
        echo "Error: " . $result['errmsg'] . "\n";
    } else {
        echo "Gift card page details retrieved successfully.\n";
        print_r($result);
    }
} catch (GuzzleException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// 调用设置支付后开通礼品卡功能方法
try {
    // 白名单信息
    $whitelistData = [
        'openid' => ['openid1', 'openid2'], // 或者使用 username
    ];
    $result = GiftCardManager::setPayWhitelist($accessToken, $whitelistData);

    // 检查结果
    if (isset($result['errcode']) && $result['errcode'] != 0) {
        echo "Error: " . $result['errmsg'] . "\n";
    } else {
        echo "Pay whitelist set successfully.\n";
    }
} catch (GuzzleException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

