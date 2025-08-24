<?php

namespace WeChatSDK\Payment;

use WeChatSDK\Config;

class Refund
{
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * 申请退款接口
     *
     * @param array $params 退款参数
     * @return array
     * @throws \Exception
     */
    public function refund(array $params): array
    {
        // 验证必需参数
        $this->validateRefundParams($params);

        $url = 'https://api.mch.weixin.qq.com/secapi/pay/refund';

        // 设置必要的参数
        $params['appid'] = $this->config->getAppId();
        $params['mch_id'] = $this->config->getMchId();
        $params['nonce_str'] = $this->generateNonceStr();

        // 生成签名（签名必须在最后添加）
        $params['sign'] = $this->generateSign($params);

        // 发送请求（需要客户端证书）
        $response = $this->sendRequestWithCert($url, $params);

        return $this->parseResponse($response);
    }

    /**
     * 查询退款接口
     *
     * @param array $params 查询参数，必须包含以下参数中的一个：transaction_id, out_trade_no, out_refund_no, refund_id
     * @return array
     * @throws \Exception
     */
    public function queryRefund(array $params): array
    {
        // 验证查询参数
        $this->validateQueryParams($params);

        $url = 'https://api.mch.weixin.qq.com/pay/refundquery';

        // 设置必要的参数
        $params['appid'] = $this->config->getAppId();
        $params['mch_id'] = $this->config->getMchId();
        $params['nonce_str'] = $this->generateNonceStr();
        $params['sign'] = $this->generateSign($params);

        // 发送请求（不需要证书）
        $response = $this->sendRequestWithoutCert($url, $params);

        return $this->parseResponse($response);
    }

    /**
     * 验证退款参数
     *
     * @param array $params
     * @throws \Exception
     */
    private function validateRefundParams(array $params): void
    {
        // 必须包含订单号（二选一）
        if (!isset($params['transaction_id']) && !isset($params['out_trade_no'])) {
            throw new \Exception('transaction_id 和 out_trade_no 必须提供其中一个');
        }

        // 必需参数验证
        $required = ['out_refund_no', 'total_fee', 'refund_fee'];
        foreach ($required as $field) {
            if (!isset($params[$field]) || $params[$field] === '') {
                throw new \Exception("缺少必需参数: {$field}");
            }
        }

        // 金额验证
        if (!is_numeric($params['total_fee']) || $params['total_fee'] <= 0) {
            throw new \Exception('total_fee 必须是大于0的数字');
        }

        if (!is_numeric($params['refund_fee']) || $params['refund_fee'] <= 0) {
            throw new \Exception('refund_fee 必须是大于0的数字');
        }

        if ($params['refund_fee'] > $params['total_fee']) {
            throw new \Exception('退款金额不能大于订单总金额');
        }
    }

    /**
     * 验证查询参数
     *
     * @param array $params
     * @throws \Exception
     */
    private function validateQueryParams(array $params): void
    {
        $identifiers = ['transaction_id', 'out_trade_no', 'out_refund_no', 'refund_id'];
        $hasIdentifier = false;

        foreach ($identifiers as $identifier) {
            if (isset($params[$identifier]) && $params[$identifier] !== '') {
                $hasIdentifier = true;
                break;
            }
        }

        if (!$hasIdentifier) {
            throw new \Exception('必须提供以下参数中的一个: ' . implode(', ', $identifiers));
        }
    }

    /**
     * 生成随机字符串
     *
     * @param int $length 字符串长度
     * @return string
     */
    private function generateNonceStr(int $length = 32): string
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    /**
     * 生成签名
     *
     * @param array $params 参数数组
     * @return string
     */
    private function generateSign(array $params): string
    {
        // 过滤空值和sign参数
        $filteredParams = [];
        foreach ($params as $key => $value) {
            if ($key !== 'sign' && $value !== '' && $value !== null) {
                $filteredParams[$key] = $value;
            }
        }

        // 按键名排序
        ksort($filteredParams);

        // 构建签名字符串
        $stringToBeSigned = '';
        foreach ($filteredParams as $key => $value) {
            $stringToBeSigned .= $key . '=' . $value . '&';
        }
        $stringToBeSigned .= 'key=' . $this->config->getApiKey();

        // 根据签名类型生成签名
        $signType = $this->config->getSignType() ?? 'MD5';

        if (strtoupper($signType) === 'HMAC-SHA256') {
            return strtoupper(hash_hmac('sha256', $stringToBeSigned, $this->config->getApiKey()));
        } else {
            return strtoupper(md5($stringToBeSigned));
        }
    }

    /**
     * 发送请求(需要客户端证书) - 用于退款接口
     *
     * @param string $url 请求地址
     * @param array $params 请求参数
     * @return string
     * @throws \Exception
     */
    private function sendRequestWithCert(string $url, array $params): string
    {
        $xml = $this->arrayToXml($params);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: text/xml; charset=utf-8',
            'Content-Length: ' . strlen($xml)
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);

        // 设置客户端证书
        $certPath = $this->config->getCertPath();
        $keyPath = $this->config->getKeyPath();

        if (!$certPath || !$keyPath) {
            throw new \Exception('退款接口需要配置客户端证书路径');
        }

        if (!file_exists($certPath)) {
            throw new \Exception("客户端证书文件不存在: {$certPath}");
        }

        if (!file_exists($keyPath)) {
            throw new \Exception("客户端私钥文件不存在: {$keyPath}");
        }

        curl_setopt($ch, CURLOPT_SSLCERT, $certPath);
        curl_setopt($ch, CURLOPT_SSLKEY, $keyPath);

        // 如果有证书密码
        $certPassword = $this->config->getCertPassword();
        if ($certPassword) {
            curl_setopt($ch, CURLOPT_SSLCERTPASSWD, $certPassword);
            curl_setopt($ch, CURLOPT_SSLKEYPASSWD, $certPassword);
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);

        curl_close($ch);

        if ($response === false) {
            throw new \Exception('CURL请求失败: ' . $error);
        }

        if ($httpCode !== 200) {
            throw new \Exception("HTTP请求失败，状态码: {$httpCode}");
        }

        return $response;
    }

    /**
     * 发送请求(不需要证书) - 用于查询接口
     *
     * @param string $url 请求地址
     * @param array $params 请求参数
     * @return string
     * @throws \Exception
     */
    private function sendRequestWithoutCert(string $url, array $params): string
    {
        $xml = $this->arrayToXml($params);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: text/xml; charset=utf-8',
            'Content-Length: ' . strlen($xml)
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);

        curl_close($ch);

        if ($response === false) {
            throw new \Exception('CURL请求失败: ' . $error);
        }

        if ($httpCode !== 200) {
            throw new \Exception("HTTP请求失败，状态码: {$httpCode}");
        }

        return $response;
    }

    /**
     * 将数组转换为XML
     *
     * @param array $array 数组
     * @return string
     */
    private function arrayToXml(array $array): string
    {
        $xml = '<xml>';
        foreach ($array as $key => $value) {
            if (is_numeric($value)) {
                $xml .= "<{$key}>{$value}</{$key}>";
            } else {
                $xml .= "<{$key}><![CDATA[{$value}]]></{$key}>";
            }
        }
        $xml .= '</xml>';
        return $xml;
    }

    /**
     * 解析XML响应
     *
     * @param string $xml XML字符串
     * @return array
     * @throws \Exception
     */
    private function parseResponse(string $xml): array
    {
        if (empty($xml)) {
            throw new \Exception('响应内容为空');
        }

        // 禁用外部实体加载，防止XXE攻击
        $previousValue = libxml_disable_entity_loader(true);

        try {
            $xmlObject = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);

            if ($xmlObject === false) {
                throw new \Exception('XML解析失败');
            }

            $data = [];
            foreach ($xmlObject as $key => $value) {
                $data[(string) $key] = (string) $value;
            }

            // 验证响应签名
            if (isset($data['sign'])) {
                $responseSign = $data['sign'];
                unset($data['sign']);
                $calculatedSign = $this->generateSign($data);

                if ($responseSign !== $calculatedSign) {
                    throw new \Exception('响应签名验证失败');
                }

                // 重新添加签名到返回数据
                $data['sign'] = $responseSign;
            }

            return $data;

        } finally {
            libxml_disable_entity_loader($previousValue);
        }
    }
}