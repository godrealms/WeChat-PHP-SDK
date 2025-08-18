# WeChat PHP SDK

一个用于集成微信公众平台和小程序的 PHP SDK。该库简化了与微信 API 的交互，包括 OAuth 授权、卡券管理、消息处理和支付功能。

## 功能特性

- **OAuth 授权**：生成授权 URL，获取访问令牌，并获取用户信息。
- **卡券管理**：创建、分发和兑换卡券。
- **消息处理**：处理和响应来自微信公众平台的消息。
- **支付模块**：支持微信支付的基础功能，包括JSAPI支付。
- **工具类**：包括缓存、日志、签名生成和 XML 处理工具。

## 环境要求

- PHP >= 7.4
- `ext-curl` 和 `ext-json` 扩展
- 用于 HTTP 请求的 [Guzzle](https://github.com/guzzle/guzzle)
- [PSR-4 自动加载](https://www.php-fig.org/psr/psr-4/)

## 安装

使用 Composer 安装 SDK：

```bash
composer require godrealms/wechat-php-sdk
```

## 项目结构

```
├── examples/                      # 示例代码目录
│   ├── card-distribution.php      # 卡券分发示例
│   ├── card-manager.php           # 卡券管理示例
│   ├── card-redeem.php            # 卡券兑换示例
│   ├── gift-card-manager.php      # 礼品卡管理示例
│   ├── jsapi-payment.php          # JSAPI支付示例
│   ├── membership-card-manager.php# 会员卡管理示例
│   ├── special-ticket-manager.php # 特殊票券管理示例
│   └── third-party-card-manager.php # 第三方卡券管理示例
│
├── src/                           # SDK 核心代码
│   ├── Auth/
│   │   └── OAuth.php              # OAuth 授权模块
│   ├── Message/
│   │   └── MessageHandler.php     # 消息处理模块
│   ├── Offiaccount/               # 微信公众平台相关功能
│   │   ├── CardDistribution.php   # 卡券分发管理
│   │   ├── CardManager.php        # 卡券管理
│   │   ├── CardRedeem.php         # 卡券兑换
│   │   ├── GiftCardManager.php    # 礼品卡管理
│   │   ├── Material.php           # 素材管理
│   │   ├── MembershipCardManager.php # 会员卡管理
│   │   ├── SpecialTicketManager.php  # 特殊票券管理
│   │   └── ThirdPartyCardManager.php # 第三方卡券管理
│   ├── Payment/
│   │   └── Payment.php            # 支付模块
│   ├── Utils/
│   │   ├── Cache.php              # 缓存工具
│   │   ├── Logger.php             # 日志工具
│   │   ├── RandomString.php       # 随机字符串生成工具
│   │   ├── Signature.php          # 签名生成工具
│   │   ├── XmlHelper.php          # XML 工具
│   │   └── Config.php             # 配置管理
│
├── tests/                         # 单元测试
│   ├── CardDistributionTest.php   # 卡券分发测试
│   ├── CardManagerTest.php        # 卡券管理测试
│   ├── CardRedeemTest.php         # 卡券兑换测试
│   ├── GiftCardManagerTest.php    # 礼品卡管理测试
│   ├── MediaUploaderTest.php      # 素材上传测试
│   └── SpecialTicketManagerTest.php # 特殊票券测试
│
├── vendor/                        # Composer 依赖目录
├── .gitignore                     # Git 忽略文件
├── composer.json                  # Composer 配置文件
├── composer.lock                  # Composer 锁定文件
├── LICENSE                        # 开源协议
└── README.md                      # 项目文档
```

## 使用方法

### 1. 初始化配置

```php
use WeChatSDK\Config;

$config = new Config('your_app_id', 'your_app_secret');
```

### 2. OAuth 授权

```php
use WeChatSDK\Auth\OAuth;

$oauth = new OAuth($config);

// 生成授权 URL
$redirectUri = 'https://yourdomain.com/callback';
$scope = 'snsapi_userinfo'; // 或 'snsapi_base'
$state = 'custom_state';

$authUrl = $oauth->getAuthorizeUrl($redirectUri, $scope, $state);
echo $authUrl;

// 获取访问令牌
$code = 'authorization_code_from_wechat';
$accessToken = $oauth->getAccessToken($code);
print_r($accessToken);

// 获取用户信息
$userInfo = $oauth->getUserInfo($accessToken['access_token'], $accessToken['openid']);
print_r($userInfo);
```

### 3. 卡券管理

```php
use WeChatSDK\Offiaccount\CardManager;

$cardManager = new CardManager($config);

// 创建卡券
$cardData = [
    'card_type' => 'GROUPON',
    'groupon' => [
        'base_info' => [
            'logo_url' => 'https://example.com/logo.png',
            'brand_name' => 'Brand',
            'title' => 'Groupon Title',
            'color' => 'Color010',
            'notice' => 'Use this card at checkout',
            'description' => 'Card description',
            // 其他参数...
        ],
        'deal_detail' => 'Groupon details...',
    ],
];
$response = $cardManager->createCard($cardData);
print_r($response);
```

### 4. JSAPI支付

```php
use WeChatSDK\Config;
use WeChatSDK\Payment\Payment;

// 初始化支付配置
$config = new Config(
    'your_app_id',      // AppID
    'your_app_secret',  // AppSecret
    '',                 // Token (支付用不到)
    '',                 // AES Key (支付用不到)
    'your_mch_id',      // MchID (商户ID)
    'your_api_key'      // API密钥
);

// 初始化支付类
$payment = new Payment($config);

// 调用统一下单接口
$unifiedOrderParams = [
    'body' => '测试商品',                            // 商品描述
    'out_trade_no' => date('YmdHis') . rand(1000, 9999), // 商户订单号
    'total_fee' => 1,                               // 总金额，单位为分
    'spbill_create_ip' => $_SERVER['REMOTE_ADDR'], // 终端IP
    'notify_url' => 'https://yourdomain.com/notify', // 异步通知地址
    'trade_type' => 'JSAPI',                        // 交易类型
    'openid' => 'user_openid',                      // 用户标识
];

$result = $payment->unifiedOrder($unifiedOrderParams);

if ($result['return_code'] === 'SUCCESS' && $result['result_code'] === 'SUCCESS') {
    // 获取JSAPI支付参数
    $prepayId = $result['prepay_id'];
    $jsApiParams = $payment->getJsApiParameters($prepayId);
    
    // 将$jsApiParams传递给前端页面，用于调起微信支付
    echo json_encode($jsApiParams);
}
```

在前端页面中使用JSAPI支付参数调起微信支付：

```html
<script>
function onBridgeReady() {
  WeixinJSBridge.invoke('getBrandWCPayRequest', {
    'appId': '<?= $jsApiParams['appId'] ?>',
    'timeStamp': '<?= $jsApiParams['timeStamp'] ?>',
    'nonceStr': '<?= $jsApiParams['nonceStr'] ?>',
    'package': '<?= $jsApiParams['package'] ?>',
    'signType': '<?= $jsApiParams['signType'] ?>',
    'paySign': '<?= $jsApiParams['paySign'] ?>'
  }, function(res) {
    if (res.err_msg == "get_brand_wcpay_request:ok") {
      // 支付成功
      alert('支付成功');
    } else {
      // 支付失败
      alert('支付失败');
    }
  });
}

if (typeof WeixinJSBridge == "undefined") {
  if (document.addEventListener) {
    document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
  } else if (document.attachEvent) {
    document.attachEvent('WeixinJSBridgeReady', onBridgeReady);
    document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
  }
} else {
  onBridgeReady();
}
</script>
```

## 测试

使用 PHPUnit 运行单元测试：

```bash
composer test
```

或直接运行：

```bash
phpunit --bootstrap vendor/autoload.php tests
```

## 贡献

欢迎提交 Issue 或 Pull Request 来改进本项目。请确保代码符合 PSR-12 规范。

## 开源协议

本项目基于 [MIT License](./LICENSE) 开源。

## 作者

由 [Endorphins](https://endorphins.cn) 开发。如有任何疑问，请通过 [wujie@endorphins.cn](mailto:wujie@endorphins.cn) 联系我们。

## 链接

- GitHub: [godrealms/WeChat-PHP-SDK](https://github.com/godrealms/WeChat-PHP-SDK)
- 文档: [微信官方文档](https://developers.weixin.qq.com/)