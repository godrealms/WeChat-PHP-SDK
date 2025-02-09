# WeChat PHP SDK

一个用于集成微信公众平台和小程序的 PHP SDK。该库简化了与微信 API 的交互，包括 OAuth 授权、卡券管理、消息处理和支付功能。

## 功能特性

- **OAuth 授权**：生成授权 URL，获取访问令牌，并获取用户信息。
- **卡券管理**：创建、分发和兑换卡券。
- **消息处理**：处理和响应来自微信公众平台的消息。
- **支付模块**：支持微信支付的基础功能。
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

### 4. 消息处理

```php
use WeChatSDK\Message\MessageHandler;

$messageHandler = new MessageHandler($config);

// 处理接收到的消息
$messageHandler->onText(function ($message) {
    return '您发送的内容是：' . $message['Content'];
});

$messageHandler->onEvent(function ($message) {
    return '收到事件：' . $message['Event'];
});

$messageHandler->handle();
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