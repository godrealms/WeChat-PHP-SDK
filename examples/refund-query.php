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
    // 查询退款参数（四选一）
    $queryParam = [
        // 使用微信订单号查询
        // 'transaction_id' => '1217752501201407033233368018',
        
        // 使用商户订单号查询
        // 'out_trade_no' => '20150806125346',
        
        // 使用商户退款单号查询
        'out_refund_no' => '20150806125346001',
        
        // 使用微信退款单号查询
        // 'refund_id' => '2007752501201407033233368018',
        
        // 偏移量，当部分退款次数超过10次时可使用
        // 'offset' => 0,
    ];
    
    // 查询退款
    $result = $refund->queryRefund($queryParam);
    
    if ($result['return_code'] === 'SUCCESS' && $result['result_code'] === 'SUCCESS') {
        echo "查询退款成功！\n";
        echo "订单总退款次数: " . (isset($result['total_refund_count']) ? $result['total_refund_count'] : 'N/A') . "\n";
        echo "微信订单号: " . $result['transaction_id'] . "\n";
        echo "商户订单号: " . $result['out_trade_no'] . "\n";
        echo "订单金额: " . $result['total_fee'] . "分\n";
        echo "退款笔数: " . $result['refund_count'] . "\n";
        
        // 显示退款详情（可能有多笔退款）
        for ($i = 0; $i < $result['refund_count']; $i++) {
            echo "\n--- 第" . ($i+1) . "笔退款信息 ---\n";
            echo "商户退款单号: " . $result["out_refund_no_$i"] . "\n";
            echo "微信退款单号: " . $result["refund_id_$i"] . "\n";
            echo "退款金额: " . $result["refund_fee_$i"] . "分\n";
            echo "退款状态: " . $result["refund_status_$i"] . "\n";
            echo "退款入账账户: " . $result["refund_recv_accout_$i"] . "\n";
        }
    } else {
        echo "查询退款失败:\n";
        print_r($result);
    }
} catch (Exception $e) {
    echo "查询退款过程中发生错误: " . $e->getMessage();
}