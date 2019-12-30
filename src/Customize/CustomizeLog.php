<?php
namespace Customize;

use Illuminate\Log\Writer;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class CustomizeLog
{
    const DEFAULT_FORMAT = "[%datetime%] %level_name%: %message% %context% %extra%\n";

    protected $timezone;
    protected $infoLevel = 'info';
    protected $errorLevel = 'error';

    protected $levels = [
        'debug' => Logger::DEBUG,
        'info' => Logger::INFO,
        'notice' => Logger::NOTICE,
        'warning' => Logger::WARNING,
        'error' => Logger::ERROR,
        'critical' => Logger::CRITICAL,
        'alert' => Logger::ALERT,
        'emergency' => Logger::EMERGENCY,
    ];

    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * @description request
     * @param int $id
     * @return void
     */
    public function request($id)
    {
        $message = $_SERVER['REQUEST_URI'] ?? '';
        $context['ip'] = $_SERVER['REMOTE_ADDR'] ?? '';

        $this->log($this->infoLevel, "$message {$id}", $context);
    }

    /**
     * @description request
     * @param int $id
     * @param boolean $status
     * @param $response
     * @return void
     */
    public function response($id, $status, $content)
    {
        $message = $_SERVER['REQUEST_URI'] ?? '';
        $context['ip'] = $_SERVER['REMOTE_ADDR'] ?? '';
        $context['status'] = $status;

        if($status == true) {
            $this->log($this->infoLevel, "$message {$id}", $context);
        }else{
            $context['response'] = $content;
            $this->log($this->errorLevel, "$message {$id}", $context);
        }
    }

    /**
     * @description Log
     * @param string $level
     * @param string $message
     * @param string $context
     * @return Monolog->log()
     */
    public function log($level, $message, $context)
    {
        return $this->channel()->log($level, $message, $context);
    }

    /**
     * @description 選擇通道
     * @param string $channel
     * @return Illuminate\Log\Writer
     */
    public function channel($channel = null)
    {
        return $this->generateWriter($channel ?? $this->getDefaultChannel());
    }

    /**
     * @description 產生 Writer
     * @param string $channel
     * @return Illuminate\Log\Writer
     */
    protected function generateWriter($channel)
    {
        return with($this->resolve($channel), function ($logger) use ($channel) {
            return new Writer($logger, $this->app['events']);
        });
    }

    /**
     * @description 解析通道
     * @param string $channel
     * @return Illuminate\Log\Writer
     */
    protected function resolve($channel)
    {
        $config = $this->getConfiguration($channel);
        $driverMethod = 'generate' . ucfirst($config['driver']) . 'Driver';

        if (method_exists($this, $driverMethod)) {
            $logger = $this->{$driverMethod}($config);

            if (is_null($this->timezone) && isset($config['timezone'])) {
                $this->timezone = $config['timezone'];
            }

            if (!is_null($this->timezone)) {
                $logger::setTimezone(new \DateTimeZone($this->timezone));
            }

            return $logger;
        } else {
            throw new \InvalidArgumentException("Driver [{$config['driver']}] is not supported.");
        }
    }

    /**
     * @description 產生 DailyDriver
     * @param array $config
     * @return \Monolog\Logger
     */
    protected function generateDailyDriver(array $config)
    {
        return new Logger($config['name'] ?? 'default', [
            $this->formatHandler(new RotatingFileHandler(
                                     $config['path'], $config['days'] ?? 7, $this->levels[$config['level'] ?? 'debug'] ?? 'debug',
                                     $config['bubble'] ?? true, $config['permission'] ?? null, $config['locking'] ?? false
                                 )),
        ]);
    }

    /**
     * @description 產生 SingleDriver
     * @param array $config
     * @return \Monolog\Logger
     */
    protected function generateSingleDriver(array $config)
    {
        return new Logger($config['name'] ?? 'default', [$this->formatHandler(new StreamHandler($config['path'], $this->levels[$config['level'] ?? 'debug'] ?? 'debug', $config['bubble'] ?? true, $config['permission'] ?? null, $config['locking'] ?? false)),]);
    }

    /**
     * @description Format Handler
     * @param Monolog\Handler\HandlerInterface $handler
     * @param array $config
     * @return Monolog\Handler\HandlerInterface
     */
    protected function formatHandler(HandlerInterface $handler, array $config = [])
    {
        return $handler->setFormatter(new LineFormatter(self::DEFAULT_FORMAT, null, true, true));
    }

    /**
     * @description 取得設定
     * @return array $config
     */
    protected function getDefaultChannel()
    {
        return $this->app['config']['logging.default'];
    }

    /**
     * @description 取得設定
     * @return array $config
     */
    protected function getConfiguration($channel)
    {
        return $this->app['config']["logging.channels.{$channel}"];
    }

    /**
     * @description 設置時區
     * @return string $timezone
     */
    public function setLoggerTimezone(string $timezone)
    {
        $this->timezone = $timezone;
    }

    /**
     * @description 設置Log層級
     * @return string $level
     */
    public function setLoggerLevel(string $level)
    {
        $this->logLevel = $level;
    }
}
