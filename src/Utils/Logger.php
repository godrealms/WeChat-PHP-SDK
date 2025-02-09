<?php

namespace WeChatSDK\Utils;

class Logger
{
    private string $logDir;
    private string $logFile;

    public function __construct($logDir = __DIR__ . '/logs/')
    {
        $this->logDir = rtrim($logDir, '/') . '/';
        if (!is_dir($this->logDir)) {
            mkdir($this->logDir, 0755, true);
        }
        $this->logFile = $this->logDir . date('Y-m-d') . '.log';
    }

    public function info($message)
    {
        $this->writeLog('INFO', $message);
    }

    public function error($message)
    {
        $this->writeLog('ERROR', $message);
    }

    private function writeLog($level, $message)
    {
        $dateTime = date('Y-m-d H:i:s');
        $formattedMessage = sprintf("[%s] [%s]: %s\n", $dateTime, $level, $message);
        file_put_contents($this->logFile, $formattedMessage, FILE_APPEND);
    }
}
