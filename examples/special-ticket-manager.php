<?php
require 'vendor/autoload.php';

use GuzzleHttp\Exception\GuzzleException;
use WeChatSDK\Offiaccount\SpecialTicketManager;

// 调用接口凭证（access_token）
$accessToken = 'your_access_token_here';


// 调用创建特权票卡券方法
try {
    // 特权票卡券信息
    $cardData = [
        'card' => [
            'card_type' => 'MOVIE_TICKET',
            'movie_ticket' => [
                'base_info' => [
                    'logo_url' => 'https://example.com/logo.jpg',
                    'brand_name' => '影院名称',
                    'title' => '电影票标题',
                    'color' => 'Color010',
                    'code_type' => 'CODE_TYPE_QRCODE',
                    'notice' => '请携带此票入场',
                    'description' => '电影票描述信息',
                    'date_info' => [
                        'type' => 'DATE_TYPE_FIX_TIME_RANGE',
                        'begin_timestamp' => strtotime('2025-01-01'),
                        'end_timestamp' => strtotime('2025-12-31'),
                    ],
                    'sku' => [
                        'quantity' => 1000,
                    ],
                ],
            ],
        ],
    ];
    $result = SpecialTicketManager::createSpecialTicket($accessToken, $cardData);

    // 检查结果
    if (isset($result['errcode']) && $result['errcode'] != 0) {
        echo "Error: " . $result['errmsg'] . "\n";
    } else {
        echo "Special ticket card created successfully.\n";
        print_r($result);
    }
} catch (GuzzleException $e) {
    echo $e->getMessage();
}

// 调用更新用户票信息方法
try {
    // 更新的用户票信息
    $updateData = [
        'code' => 'user_code_here',
        'card_id' => 'your_card_id_here',
        'ticket_class' => 'VIP',
        'show_time' => strtotime('2025-03-01 19:00:00'),
        'duration' => 120,
        'screening_room' => 'IMAX厅',
        'seat_number' => 'A1, A2',
    ];
    $result = SpecialTicketManager::updateUserTicket($accessToken, $updateData);

    // 检查结果
    if (isset($result['errcode']) && $result['errcode'] != 0) {
        echo "Error: " . $result['errmsg'] . "\n";
    } else {
        echo "User ticket updated successfully.\n";
    }
} catch (GuzzleException $e) {
    echo $e->getMessage();
}