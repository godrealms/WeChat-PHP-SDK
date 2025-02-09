<?php

use GuzzleHttp\Exception\GuzzleException;
use WeChatSDK\Offiaccount\CardDistribution;

require 'vendor/autoload.php'; // 自动加载 Guzzle 和其他依赖

// 示例 1：生成卡券二维码

// 调用接口凭证（access_token）
$accessToken = 'your_access_token_here';

// 卡券的投放信息
$cardData = [
    'action_name' => 'QR_CARD',
    'expire_seconds' => 1800, // 二维码有效期（秒）
    'action_info' => [
        'card' => [
            'card_id' => 'your_card_id_here',
            'is_unique_code' => false,
            'outer_id' => '12345', // 自定义参数
        ],
    ],
];

// 调用生成二维码方法
try {
    $result = CardDistribution::createQRCode($accessToken, $cardData);
    // 检查结果
    if (isset($result['errcode']) && $result['errcode'] != 0) {
        echo "Error: " . $result['errmsg'] . "\n";
    } else {
        echo "QR Code created successfully.\n";
        echo "Ticket: " . $result['ticket'] . "\n";
        echo "QR Code URL: https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=" . urlencode($result['ticket']) . "\n";
    }
} catch (GuzzleException $e) {
    var_dump("Error:", $e->getMessage());
}

// 示例 2：生成卡券 H5 链接

// 卡券 ID
$cardId = 'your_card_id_here';

// 自定义参数
$outerId = '12345';

// 生成 H5 链接
$h5Link = CardDistribution::generateH5Link($cardId, $outerId);

// 输出 H5 链接
echo "H5 Link: {$h5Link}\n";
