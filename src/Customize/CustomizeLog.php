<?php

namespace Customize;

use Illuminate\Events\Dispatcher;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Log\Writer;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class CustomizeLog
{
    const DEFAULT_FORMAT = "[%datetime%] %level_name%: %message% %context% %extra%\n";

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
        $message = $_SERVER['REQUEST_URI'];
        $context['ip'] = request()->ip();
        $context['request'] = $_REQUEST;

        $this->log('info', "$message {$id}", $context);
    }

    /**
     * @description request
     * @param int $id
     * @return void
     */
    public function response($id, $status, $response)
    {
        $message = $_SERVER['REQUEST_URI'];
        $context['ip'] = request()->ip();
        $context['status'] = $status;
        $context['response'] = $response->content();

        $this->log('info', "$message {$id}", $context);
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
     * @description 產生Writer
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
            $logger::setTimezone(new \DateTimeZone($this->app['config']['logging.channels.' . $channel . '.timezone']));

            return $logger;
        } else {
            throw new \InvalidArgumentException("Driver [{$config['driver']}] is not supported.");
        }
    }

    /**
     * @description 產生 DailyDriver
     * @param array $config
     * @return Monolog\Handler\RotatingFileHandler
     */
    protected function generateDailyDriver(array $config)
    {
        return new Logger($config['name'], [
            $this->prepareHandler(new RotatingFileHandler(
                $config['path'], $config['days'] ?? 7, $this->levels[$config['level']] ?? 'debug',
                $config['bubble'] ?? true, $config['permission'] ?? null, $config['locking'] ?? false
            )),
        ]);
    }

    /**
     * @description 產生 SingleDriver
     * @param array $config
     * @return Monolog\Handler\StreamHandler
     */
    protected function generateSingleDriver(array $config)
    {
        return new Logger($config['name'], [$this->prepareHandler(new StreamHandler($config['path'])),]);
    }

    /**
     * @description Format Handler
     * @param Monolog\Handler\HandlerInterface $handler
     * @param array $config
     * @return Monolog\Handler\HandlerInterface
     */
    protected function prepareHandler(HandlerInterface $handler, array $config = [])
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
}
