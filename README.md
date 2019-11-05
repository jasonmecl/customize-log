## Description
* CustomLog is a package to extend and customize log
* Driver only support single & daily

## Require
* "php": ">=7.1.3",
* "monolog/monolog": "^1.0.0",
* "laravel/framework": "5.2.0 - 5.6.0"

## Installation
* Install package
```
composer require customize-log/customize-log dev-master
```
* Generate logging.php file
```
php artisan vendor:publish
```
## Operation
#### 1. Log Request
```php
CustomizeLog::request({"uniqueId"});

[2019-11-05 09:13:33] INFO: /apis/version/xxx 5dc0ccbd430b5 {"ip":"x.x.x.x","request":{""}} 

```
#### 2. Log Response
```php
CustomizeLog::response({"uniqueId"},{"status"},{"response"});

[2019-11-05 09:13:33] INFO: /apis/version/xxx 5dc0ccbd430b5 {"ip":"x.x.x.x","status":true,"response":""} 
```
#### 3. Log With Channel
```php
CustomizeLog::channel({"channel"})->log({"level"},{"message"}, {"context"});

[2019-11-05 09:29:05] INFO: 11 {"aa":"11"} 
```
#### 4. Log
```php
CustomizeLog::log({"level"},{"message"}, {"context"});

[2019-11-05 09:29:05] INFO: 22 {"aa":"22"} 
```

#### 5. Set Timezone
```php
CustomizeLog::setLoggerTimezone('Asia/Tokyo');
```

#### 6. Set Level
```php
CustomizeLog::setLoggerLevel('debug');
```
