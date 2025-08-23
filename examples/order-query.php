<?php

require_once __DIR__ . '/../vendor/autoload.php';

use WeChatSDK\Config;
use WeChatSDK\Payment\Payment;

// 初始化配置
$config = new Config(
    'your_app_id',      // AppID
    'your_app_secret',  // AppSecret
    '',                 // Token
    '',                 // AES Key
    'your_mch_id',      // MchID (商户ID)
    'your_api_key'      // API密钥
);

// 初始化支付类
$payment = new Payment($config);

try {
    // 查询订单参数（transaction_id 和 out_trade_no 二选一）
    $queryParam = [
        // 使用微信订单号查询
        // 'transaction_id' => '1009660380201506130728806387',
        
        // 使用商户订单号查询
        'out_trade_no' => '20150806125346',
    ];
    
    // 查询订单
    $result = $payment->orderQuery($queryParam);
    
    if ($result['return_code'] === 'SUCCESS' && $result['result_code'] === 'SUCCESS') {
        echo "查询订单成功！\n";
        echo "交易状态: " . $result['trade_state'] . "\n";
        echo "交易状态描述: " . $result['trade_state_desc'] . "\n";
        echo "微信支付订单号: " . $result['transaction_id'] . "\n";
        echo "商户订单号: " . $result['out_trade_no'] . "\n";
        echo "订单总金额: " . $result['total_fee'] . "分\n";
        echo "支付完成时间: " . $result['time_end'] . "\n";
    } else {
        echo "查询订单失败:\n";
        print_r($result);
    }
} catch (Exception $e) {
    echo "查询订单过程中发生错误: " . $e->getMessage();
}