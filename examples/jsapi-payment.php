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
    // 1. 调用统一下单接口
    $unifiedOrderParams = [
        'body' => '测试商品',                           // 商品描述
        'out_trade_no' => date('YmdHis') . rand(1000, 9999), // 商户订单号
        'total_fee' => 1,                              // 总金额，单位为分
        'spbill_create_ip' => $_SERVER['REMOTE_ADDR'], // 终端IP
        'notify_url' => 'https://yourdomain.com/notify', // 异步通知地址
        'trade_type' => 'JSAPI',                       // 交易类型
        'openid' => 'user_openid',                     // 用户标识
    ];
    
    $result = $payment->unifiedOrder($unifiedOrderParams);
    
    if ($result['return_code'] === 'SUCCESS' && $result['result_code'] === 'SUCCESS') {
        // 2. 获取JSAPI支付参数
        $prepayId = $result['prepay_id'];
        $jsApiParams = $payment->getJsApiParameters($prepayId);
        
        echo "统一下单成功！\n";
        echo "预支付ID: " . $prepayId . "\n";
        echo "JSAPI支付参数:\n";
        print_r($jsApiParams);
        
        // 3. 在前端页面中调用WeixinJSBridge进行支付
        echo "\n前端JavaScript调用示例:\n";
        echo "<script>\n";
        echo "  function onBridgeReady() {\n";
        echo "    WeixinJSBridge.invoke('getBrandWCPayRequest', {\n";
        echo "      'appId': '" . $jsApiParams['appId'] . "',\n";
        echo "      'timeStamp': '" . $jsApiParams['timeStamp'] . "',\n";
        echo "      'nonceStr': '" . $jsApiParams['nonceStr'] . "',\n";
        echo "      'package': '" . $jsApiParams['package'] . "',\n";
        echo "      'signType': '" . $jsApiParams['signType'] . "',\n";
        echo "      'paySign': '" . $jsApiParams['paySign'] . "'\n";
        echo "    }, function(res) {\n";
        echo "      if (res.err_msg == 'get_brand_wcpay_request:ok') {\n";
        echo "        // 支付成功处理\n";
        echo "        alert('支付成功');\n";
        echo "      } else {\n";
        echo "        // 支付失败处理\n";
        echo "        alert('支付失败');\n";
        echo "      }\n";
        echo "    });\n";
        echo "  }\n";
        echo "\n";
        echo "  if (typeof WeixinJSBridge == 'undefined') {\n";
        echo "    if (document.addEventListener) {\n";
        echo "      document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);\n";
        echo "    } else if (document.attachEvent) {\n";
        echo "      document.attachEvent('WeixinJSBridgeReady', onBridgeReady);\n";
        echo "      document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);\n";
        echo "    }\n";
        echo "  } else {\n";
        echo "    onBridgeReady();\n";
        echo "  }\n";
        echo "</script>\n";
    } else {
        echo "统一下单失败:\n";
        print_r($result);
    }
} catch (Exception $e) {
    echo "支付过程中发生错误: " . $e->getMessage();
}