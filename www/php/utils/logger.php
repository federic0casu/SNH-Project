<?php
include_once 'db_manager.php';

class Logger {
    private static $instance;
    private $dbManager;

    const LOG_LEVEL_DEBUG    = 0;
    const LOG_LEVEL_INFO     = 1;
    const LOG_LEVEL_WARNING  = 2;
    const LOG_LEVEL_ERROR    = 3;
    const LOG_LEVEL_CRITICAL = 4;

    private function __construct() {
        $this->dbManager = DBManager::get_instance();
    }

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new Logger();
        }
        return self::$instance;
    }

    public function log($level, $message, $context = []) {
        $this->dbManager->exec_query(
            "INSERT",
            "INSERT INTO log_messages (level, message, context) VALUES (?, ?, ?)",
            [$level, $message, json_encode($context)],
            'iss'
        );
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

    public function critical($message, $context = []) {
        $this->log(self::LOG_LEVEL_CRITICAL, $message, $context);
    }
}

?>