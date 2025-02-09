<?php

namespace WeChatSDK\Tests;

use PHPUnit\Framework\TestCase;
use WeChatSDK\Offiaccount\Material;

class MaterialTest extends TestCase
{
    private string $validAccessToken = 'mock_access_token'; // 模拟的 access_token
    private string $validImagePath;
    private string $invalidImagePath;
    private string $largeImagePath;

    protected function setUp(): void
    {
        // 创建一个有效的图片文件（JPG 格式，大小 < 1MB）
        $this->validImagePath = __DIR__ . '/test_valid_image.jpg';
        file_put_contents($this->validImagePath, str_repeat('A', 500 * 1024)); // 500KB 文件

        // 创建一个无效的文件路径
        $this->invalidImagePath = __DIR__ . '/non_existent_image.jpg';

        // 创建一个超大图片文件（大小 > 1MB）
        $this->largeImagePath = __DIR__ . '/test_large_image.jpg';
        file_put_contents($this->largeImagePath, str_repeat('A', 2 * 1024 * 1024)); // 2MB 文件
    }

    protected function tearDown(): void
    {
        // 删除测试文件
        if (file_exists($this->validImagePath)) {
            unlink($this->validImagePath);
        }

        if (file_exists($this->largeImagePath)) {
            unlink($this->largeImagePath);
        }
    }

    public function testUploadValidImage()
    {
        // 模拟成功的 API 响应
        $mockResponse = json_encode(['url' => 'https://mock-url.com/image.jpg']);

        // 使用 CURL 模拟请求
        $this->mockCurl($mockResponse);

        $result = Material::uploadImage($this->validAccessToken, $this->validImagePath);

        // 验证返回结果
        $this->assertArrayHasKey('url', $result);
        $this->assertEquals('https://mock-url.com/image.jpg', $result['url']);
    }

    public function testUploadNonExistentImage()
    {
        $result = Material::uploadImage($this->validAccessToken, $this->invalidImagePath);

        // 验证错误码和错误信息
        $this->assertArrayHasKey('errcode', $result);
        $this->assertEquals(40010, $result['errcode']);
        $this->assertEquals('File not found: ' . $this->invalidImagePath, $result['errmsg']);
    }

    public function testUploadLargeImage()
    {
        $result = Material::uploadImage($this->validAccessToken, $this->largeImagePath);

        // 验证错误码和错误信息
        $this->assertArrayHasKey('errcode', $result);
        $this->assertEquals(40009, $result['errcode']);
        $this->assertEquals('Image size exceeds 1MB', $result['errmsg']);
    }

    public function testUploadInvalidFileFormat()
    {
        // 创建一个无效格式的文件（TXT 格式）
        $invalidFormatPath = __DIR__ . '/test_invalid_format.txt';
        file_put_contents($invalidFormatPath, 'This is a text file.');

        $result = Material::uploadImage($this->validAccessToken, $invalidFormatPath);

        // 验证错误码和错误信息
        $this->assertArrayHasKey('errcode', $result);
        $this->assertEquals(40011, $result['errcode']);
        $this->assertEquals('Invalid file format. Only JPG and PNG are allowed', $result['errmsg']);

        // 删除测试文件
        unlink($invalidFormatPath);
    }

    private function mockCurl($mockResponse)
    {
        // 使用 PHP 内置函数替换 CURL
        $GLOBALS['mock_curl_response'] = $mockResponse;

        function curl_init($url = null): string
        {
            return 'mock_curl_resource';
        }

        function curl_setopt($ch, $option, $value)
        {
            // 模拟设置 CURL 选项
        }

        function curl_exec($ch)
        {
            return $GLOBALS['mock_curl_response'];
        }

        function curl_close($ch)
        {
            // 模拟关闭 CURL
        }

        function curl_error($ch): string
        {
            return '';
        }
    }
}
