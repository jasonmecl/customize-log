<?php
return [
    'default' => env('LOG_CHANNEL', 'default'),

    'channels' => [
        //自定义频道
        'default' => [
            'name' => 'default',
            // 日志驱动模式：
            'driver' => 'single',
            // 日志存放路径
            'path' => storage_path('logs/default.log'),
            // 日志等级：
            'level' => 'info',
            // 日志分片周期，多少天一个文件
            'days' => 1,
            // 日誌時區
            'timezone' => 'Asia/Taipei'
        ],
    ],
];
