# Customlog Package
## Description
* CustomLog is a package to extend and customize log
* Driver only support single & daily

## Require
* "php": ">=7.0.33",
* "monolog/monolog": "^1.0.0",
* "illuminate/log": "5.2.0 - 5.6.0"

## Installation
* Install package
```
composer require customize-log/customize-log dev-master
```
* Generate logging.php file to control log behavior
```
php artisan vendor:publish
```
## Operation
#### 1. Log Request
```php
CustomizeLog::request({"uniqueId"});
```
#### 2. Log Response
```php
CustomizeLog::response({"uniqueId"},{"status"},{"response"});
```
#### 3. Set Timezone
```php
CustomizeLog::setLoggerTimezone('Asia/Tokyo');
```
#### 4. Set Logger Level
```php
CustomizeLog::setLoggerLevel('debug');
```

## Result Test Report 
### Request
```php
Request Format 

[時間] 層級: route unique_id{底線}user_id ['ip' => '{ip}', 'request' => []] 
```
```php
Request Log 

[2019-11-13 10:40:35] INFO: /apis/route 5dcb6d2395fa6_5db149d91d41c85c4120e084 {"ip":"x.x.x.x","request":[]}
```
### Response
```php
Response Format 

[時間] 層級: route unique_id{底線}user_id ['ip' => '{ip}', 'response_status' => '{true/false}', 'response' => []]
```
```php
Response Log 

[2019-11-13 10:40:35] INFO: /apis/route 5dcb6d2395fa6_5db149d91d41c85c4120e084 {"ip":"x.x.x.x","status":true,"response":"{\"success\":true,\"retval\":{\"find\":1498459400,\"area\":1531276514,\"bikeTire\":1531276515,\"store\":1573585242,\"bikeBrand\":1531276514,\"iColorArea\":1493777191,\"disablePowerSavingHelp\":1493272324},\"message\":\"success\"}"}
```