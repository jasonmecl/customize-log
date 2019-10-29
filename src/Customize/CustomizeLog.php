<?php

namespace Customize;

use Illuminate\Events\Dispatcher;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class CustomizeLog
{
    protected $config;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function logger($channel = 'default')
    {
        $logger = new \Illuminate\Log\Logger(new Logger($channel),new Dispatcher());
        $streamHander = new StreamHandler($this->app['config']['logging.channels.'.$channel.'.path']);
        $streamHander->setFormatter(new LineFormatter(null, null, false, true));
        $logger->pushHandler($streamHander);

        return $logger;
    }

    public function channel($channel)
    {
        return $this->logger($channel ?? $this->getDefaultChannel());
    }

    public function log($level, $message, array $context = array())
    {
        return $this->logger()->log($level, $message, $context);
    }

    public function getDefaultChannel()
    {
        return $this->app['config']['logging.default'];
    }

    public function request($uniqueId)
    {

    }

    public function response($uniqueId, $content)
    {

    }
    
    public function response2($uniqueId, $status)
    {

    }
}

