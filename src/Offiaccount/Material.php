<?php

namespace WeChatSDK\Offiaccount;

class Material
{
    /**
     * 上传图片到微信服务器
     *
     * @param string $accessToken 调用接口凭证
     * @param string $filePath 图片文件的本地路径
     * @return array 返回接口的响应结果
     */
    public static function UploadImage(string $accessToken, string $filePath): array
    {
        // 检查文件是否存在
        if (!file_exists($filePath)) {
            return [
                'errcode' => 40010,
                'errmsg' => 'File not found: ' . $filePath
            ];
        }

        // 检查文件大小是否超过1MB
        if (filesize($filePath) > 1024 * 1024) {
            return [
                'errcode' => 40009,
                'errmsg' => 'Image size exceeds 1MB'
            ];
        }

        // 检查文件格式是否符合要求（JPG/PNG）
        $fileInfo = pathinfo($filePath);
        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        if (!in_array(strtolower($fileInfo['extension']), $allowedExtensions)) {
            return [
                'errcode' => 40011,
                'errmsg' => 'Invalid file format. Only JPG and PNG are allowed'
            ];
        }

        // API URL
        $url = "https://api.weixin.qq.com/cgi-bin/media/uploadimg?access_token={$accessToken}";

        // 使用 CURL 上传文件
        $curl = curl_init();
        $file = new \CURLFile(realpath($filePath)); // PHP >= 5.5 使用 CURLFile
        $postData = ['buffer' => $file];

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);

        if ($error) {
            return [
                'errcode' => 500,
                'errmsg' => 'CURL Error: ' . $error
            ];
        }

        // 返回解析的 JSON 数据
        return json_decode($response, true);
    }
}