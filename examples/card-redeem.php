<?php

use GuzzleHttp\Exception\GuzzleException;
use WeChatSDK\Offiaccount\CardRedeem;

require 'vendor/autoload.php'; // 自动加载 Guzzle 和其他依赖


// 调用接口凭证（access_token）
$accessToken = 'your_access_token_here';

// 用户的卡券 Code
$code = 'user_card_code_here';

// 可选的卡券 ID
$cardId = 'your_card_id_here'; // 如果不需要可以设为 null

// 调用核销卡券方法
try {
    $result = CardRedeem::consumeCode($accessToken, $code, $cardId);
    // 检查结果
    if (isset($result['errcode']) && $result['errcode'] != 0) {
        echo "Error: " . $result['errmsg'] . "\n";
    } else {
        echo "Card redeemed successfully.\n";
        echo "Card ID: " . $result['card']['card_id'] . "\n";
        echo "OpenID: " . $result['openid'] . "\n";
    }
} catch (GuzzleException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
