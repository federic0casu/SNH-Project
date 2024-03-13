<?php

class Logger {
    private static $instance;
    private $file;
    private $level;

    const LOG_LEVEL_DEBUG    = 0;
    const LOG_LEVEL_INFO     = 1;
    const LOG_LEVEL_WARNING  = 2;
    const LOG_LEVEL_ERROR    = 3;
    const LOG_LEVEL_CRITICAL = 4;

    private function __construct($file = NULL, $level = self::LOG_LEVEL_INFO) {
        if (!isset($file)) {
            $this->file = '../../log/bookshop.log';
        } else {
            $this->file = $file;
        }
        $this->level = $level;
    }

    public static function getInstance($file = NULL, $level = self::LOG_LEVEL_INFO) {
        if (!isset(self::$instance)) {
            self::$instance = new Logger($file, $level);
        }
        return self::$instance;
    }

    public function log($level, $message, $context = []) {
        if ($level < $this->level) {
            return;
        }

        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[$timestamp][$level] $message";

        if (!empty($context)) {
            $logMessage .= ' ' . json_encode($context);
        }

        $logMessage .= PHP_EOL;
        file_put_contents($this->file, $logMessage, FILE_APPEND | LOCK_EX);
    }

    public function debug($message, $context = []) {
        $this->log(self::LOG_LEVEL_DEBUG, $message, $context);
    }

    public function info($message, $context = []) {
        $this->log(self::LOG_LEVEL_INFO, $message, $context);
    }

    public function warning($message, $context = []) {
        $this->log(self::LOG_LEVEL_WARNING, $message, $context);
    }

    public function error($message, $context = []) {
        $this->log(self::LOG_LEVEL_ERROR, $message, $context);
    }

    public function cirtical($message, $context = []) {
        $this->log(self::LOG_LEVEL_CRITICAL, $message, $context);
    }
}

// Example usage:
// $file = 'app.log';
// $logger = Logger::getInstance(NULL, Logger::LOG_LEVEL_DEBUG);
// $logger->info('User logged in.', ['user_id' => 123]);
// $logger->error('Database connection failed.', ['error' => 'connection timeout']);
