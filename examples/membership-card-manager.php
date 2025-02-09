<?php

// 调用接口凭证（access_token）
use GuzzleHttp\Exception\GuzzleException;
use WeChatSDK\Offiaccount\MembershipCardManager;

$accessToken = 'your_access_token_here';

// 调用创建会员卡方法
try {
    // 会员卡信息
    $cardData = [
        'card' => [
            'card_type' => 'MEMBER_CARD',
            'member_card' => [
                'base_info' => [
                    'logo_url' => 'https://example.com/logo.jpg',
                    'brand_name' => '商家名称',
                    'title' => '会员卡标题',
                    'color' => 'Color010',
                    'code_type' => 'CODE_TYPE_QRCODE',
                    'notice' => '使用时请出示此卡',
                    'description' => '会员卡描述信息',
                    'date_info' => [
                        'type' => 'DATE_TYPE_PERMANENT',
                    ],
                    'sku' => [
                        'quantity' => 50000000,
                    ],
                ],
                'prerogative' => '会员特权说明',
                'auto_activate' => true,
            ],
        ],
    ];

    $result = MembershipCardManager::createMembershipCard($accessToken, $cardData);

    // 检查结果
    if (isset($result['errcode']) && $result['errcode'] != 0) {
        echo "Error: " . $result['errmsg'] . "\n";
    } else {
        echo "Membership card created successfully.\n";
        print_r($result);
    }
} catch (GuzzleException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}



// 调用设置开卡字段方法
try {
    // 开卡字段信息
    $formData = [
        'card_id' => 'your_card_id_here',
        'required_form' => [
            'common_field_id_list' => ['USER_FORM_INFO_FLAG_MOBILE', 'USER_FORM_INFO_FLAG_NAME'],
        ],
    ];
    $result = MembershipCardManager::setActivateUserForm($accessToken, $formData);

    // 检查结果
    if (isset($result['errcode']) && $result['errcode'] != 0) {
        echo "Error: " . $result['errmsg'] . "\n";
    } else {
        echo "Activate user form set successfully.\n";
    }
} catch (GuzzleException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}



// 调用拉取会员信息方法
try {
    // 卡券 ID 和 Code
    $cardId = 'your_card_id_here';
    $code = 'user_code_here';

    $result = MembershipCardManager::getUserInfo($accessToken, $cardId, $code);

    // 检查结果
    if (isset($result['errcode']) && $result['errcode'] != 0) {
        echo "Error: " . $result['errmsg'] . "\n";
    } else {
        echo "User info retrieved successfully.\n";
        print_r($result);
    }
} catch (GuzzleException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}


// 调用更新会员信息方法
try {
    // 更新的会员信息
    $updateData = [
        'code' => 'user_code_here',
        'card_id' => 'your_card_id_here',
        'background_pic_url' => 'https://example.com/new_background.jpg',
        'bonus' => 100,
        'balance' => 200,
    ];
    $result = MembershipCardManager::updateUserInfo($accessToken, $updateData);

    // 检查结果
    if (isset($result['errcode']) && $result['errcode'] != 0) {
        echo "Error: " . $result['errmsg'] . "\n";
    } else {
        echo "User info updated successfully.\n";
    }
} catch (GuzzleException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}