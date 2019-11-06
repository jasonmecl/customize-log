<?php
return [
    'default' => env('LOG_CHANNEL', 'default'),

    'channels' => [
        //自定义频道
        'default' => [
            // 日志驱动模式
            'driver' => 'single',
            // 日志存放路径
            'path' => storage_path('logs/default.log'),
            // 日誌周期
            'days' => 7,
            // 日誌時區
            'timezone' => 'Asia/Taipei'
        ],
    ],
];
