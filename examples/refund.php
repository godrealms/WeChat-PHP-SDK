<?php

require_once __DIR__ . '/../vendor/autoload.php';

use WeChatSDK\Config;
use WeChatSDK\Payment\Refund;

// 初始化配置
$config = new Config(
    'your_app_id',      // AppID
    'your_app_secret',  // AppSecret
    '',                 // Token
    '',                 // AES Key
    'your_mch_id',      // MchID (商户ID)
    'your_api_key'      // API密钥
);

// 初始化退款类
$refund = new Refund($config);

try {
    // 退款参数
    $refundParams = [
        // 以下参数二选一
        // 'transaction_id' => '4007752501201407033233368018', // 微信订单号
        'out_trade_no' => '20150806125346', // 商户订单号
        
        'out_refund_no' => '20150806125346001', // 商户退款单号
        'total_fee' => 100,  // 订单金额，单位为分
        'refund_fee' => 100, // 退款金额，单位为分
        'refund_desc' => '商品已售完' // 退款原因
    ];

    // 发起退款申请
    $result = $refund->refund($refundParams);
    
    if ($result['return_code'] === 'SUCCESS' && $result['result_code'] === 'SUCCESS') {
        echo "退款申请成功！\n";
        echo "微信退款单号: " . $result['refund_id'] . "\n";
        echo "商户退款单号: " . $result['out_refund_no'] . "\n";
        echo "退款金额: " . $result['refund_fee'] . "分\n";
    } else {
        echo "退款申请失败:\n";
        print_r($result);
    }
} catch (Exception $e) {
    echo "退款过程中发生错误: " . $e->getMessage();
}