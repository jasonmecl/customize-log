## Description
* CustomLog is a package to extend and customize log
* Driver only support single & daily

## Installation
```
composer require customize-log/customize-log dev-master

php artisan vendor:publish
```
## Operation
#### Log Request
```php
CustomizeLog::request({"uniqueId"});

[2019-11-05 09:13:33] INFO: /apis/version/xxx 5dc0ccbd430b5 {"ip":"x.x.x.x","request":{""}} 

```
#### Log Response
```php
CustomizeLog::response({"uniqueId"},{"status"},{"response"});

[2019-11-05 09:13:33] INFO: /apis/version/xxx 5dc0ccbd430b5 {"ip":"x.x.x.x","status":true,"response":""} 
```
#### Log With Channel
```php
CustomizeLog::channel({"channel"})->log({"level"},{"message"}, {"context"});

[2019-11-05 09:29:05] INFO: 11 {"aa":"11"} 
```
#### Log
```php
CustomizeLog::log({"level"},{"message"}, {"context"});

[2019-11-05 09:29:05] INFO: 22 {"aa":"22"} 
```

#### setLoggerTimezone
```php
CustomizeLog::setLoggerTimezone('Asia/Tokyo');
```

#### setLoggerLevel
```php
CustomizeLog::setLoggerLevel('debug');
```
