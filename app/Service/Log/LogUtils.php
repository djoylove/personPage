<?php
/**
 * Created by PhpStorm.
 * User: xiaod
 * Date: 2017/8/24
 * Time: 23:33
 */

namespace App\Service\Log;


class LogUtils
{
    private static $loggers;
    private static $defaultInstance;

    private function __construct()
    {
    }
    public static function setDefaultInstance($instance) {
        self::$defaultInstance = $instance;
    }

    public static function getInstance($instance='')
    {
        if(empty($instance)) {
            $instance = self::$defaultInstance;
        }
        if(empty($instance)) {
            throw new \Exception("Please set logger instance!");
        }
        if(is_null(self::$loggers)) self::$loggers = array();
        if(!isset(self::$loggers[$instance]) || !(self::$loggers[$instance] instanceof Logger)) {
            self::$loggers[$instance] = new Logger("/data/service_logs/services", $instance);
        }
        return self::$loggers[$instance];
    }
}



class Logger
{

    const DEBUG = 1; // Most Verbose
    const INFO = 2; // ...
    const WARN = 3; // ...
    const ERROR = 4; // ...
    const FATAL = 5; // Least Verbose

    const DEFAULT_ROW_SIZE = 3000;
    private $saveDir;
    private $category;

    public function __construct($saveDir, $category)
    {
        $this->saveDir = $saveDir;
        $this->category = $category;
    }

    /**
     * 记录一条Warning日志
     * @param[in] string message 日志的正文
     */
    public function logWarn($message, $backtrace = null)
    {
        $this->formatMessage($message);
        $message .= " " . self::formatExtInfo($backtrace);
        $this->log($message, self::WARN);
    }

    /**
     * 记录一条Error日志
     * @param[in] string message 日志的正文
     * @param $backtrace 自定义debug backtrace 传空则不记录
     * @param $aggKeyword 聚合关键词， cateye根据这个关键词进行分组
     */
    public function logError($message, $backtrace = null, $aggKeyword = '-')
    {
        $this->formatMessage($message, self::DEFAULT_ROW_SIZE, $aggKeyword);
        $message .= " " . self::formatExtInfo($backtrace);
        $this->log($message, self::ERROR);
    }

    /**
     * 记录一条Fatal日志
     * @param[in] string message 日志的正文
     */
    public function logFatal($message, $backtrace = null)
    {
        $this->formatMessage($message);
        $message .= " " . self::formatExtInfo($backtrace);
        $this->log($message, self::FATAL);
    }

    /**
     * 记录一条Info日志
     * @param[in] string message 日志的正文
     */
    public function logInfo($message)
    {
        $this->formatMessage($message);
        $this->log($message . " @[php@]", self::INFO);
    }

    /**
     * 记录一条Debug日志
     * @param[in] string message 日志的正文
     */
    public function logDebug($message)
    {
        $this->formatMessage($message);
        $this->log($message . " @[php@]", self::DEBUG);
    }

    /**
     * 调用第三方出错时 可以使用这个方法单独记录log
     * @param $message
     * @param $backtrace 自定义debug backtrace 传空则不记录
     * @param $aggKeyword 聚合关键词， cateye根据这个关键词进行分组
     */
    public function logExtError($message, $backtrace = null, $aggKeyword = '-')
    {
        $this->formatMessage($message, self::DEFAULT_ROW_SIZE, $aggKeyword);
        $message .= " " . self::formatExtInfo($backtrace);
        $this->log($message, self::ERROR, 'ext_error');
    }

    /**
     * 调用第三方单独记录log
     * @param $message
     */
    public function logExtInfo($message)
    {
        $this->formatMessage($message);
        $this->log($message . " @[php@]", self::INFO, 'ext_error');
    }

    /**
     * 实现日志输出的内部函数
     * 根据配置的不同，日志可以送给本机的Scribed服务，也可以送到临时目录下
     * @param[in] string messsage 日志的正文
     * @param[in] enum priority 日志输出的级别
     * @param[in] string category 日志的类别，格式 {产品线}.{模块1}.{子模块1}...
     */
    private function log($message, $priority, $category = '')
    {
        if (!is_dir($this->saveDir)) {
            mkdir($this->saveDir);
        }

        $logFileName = $this->saveDir;
        if (substr($logFileName, -1) != '/') {
            $logFileName .= '/';
        }
        $category = $category ? $category : $this->category;
        $logFileName .= $category . '.log_' . date('Y-m-d');
        file_put_contents($logFileName, date("Y-m-d H:i:s", time()) . " [$priority] $message\n", FILE_APPEND);
    }

    /**
     * @breif 格式化截取信息长度
     * @param $message
     * @param $maxLength 默认3k
     * @param $aggKeywords 聚合关键词
     */
    private function formatMessage(&$message, $maxLength = 3000, $aggKeywords = '-')
    {
        // 确保信息是string
        if (!is_string($message)) {
            $message = var_export($message, true);
        }
        $message = $aggKeywords . " " . $message;
        // 超过3k做消息内容的截取
        if (strlen($message) < $maxLength) {
            $message = str_replace("\n", ' ', $message); //防止换行
            return;
        }
        $message = str_replace("\n", ' ', substr($message, 0, $maxLength));
    }

    private function formatExtInfo($backtrace = null)
    {
        if ($backtrace === null) {
            // 在 5.3.6 之前，仅仅能使用的值是 TRUE 或者 FALSE，分别等于是否设置 DEBUG_BACKTRACE_PROVIDE_OBJECT 选项。
            $stacks = debug_backtrace(false);
            foreach ($stacks as $key => &$stack) {
                unset($stack['args']);
            }
            $backtrace = array_slice($stacks, 2); // remove myself and logXXX
        }

        $request = $_SERVER;
        unset($request['HTTP_COOKIE']);

        $info = array("callstack" => $backtrace, //$stack_clean,
            "request" => $request);
        return "@[php" . @json_encode($info) . "@]";
    }
}