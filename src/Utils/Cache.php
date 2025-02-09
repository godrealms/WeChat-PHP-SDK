<?php

namespace WeChatSDK\Utils;

class Cache
{
    private string $cacheDir;

    public function __construct($cacheDir = __DIR__ . '/cache/')
    {
        $this->cacheDir = rtrim($cacheDir, '/') . '/';
        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0755, true);
        }
    }

    public function set($key, $value, $ttl = 3600)
    {
        $data = [
            'value' => $value,
            'expire' => time() + $ttl
        ];
        $file = $this->cacheDir . md5($key) . '.cache';
        file_put_contents($file, json_encode($data));
    }

    public function get($key)
    {
        $file = $this->cacheDir . md5($key) . '.cache';
        if (!file_exists($file)) {
            return null;
        }

        $data = json_decode(file_get_contents($file), true);
        if (time() > $data['expire']) {
            unlink($file);
            return null;
        }

        return $data['value'];
    }

    public function delete($key)
    {
        $file = $this->cacheDir . md5($key) . '.cache';
        if (file_exists($file)) {
            unlink($file);
        }
    }
}
