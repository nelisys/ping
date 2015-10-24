# ping
PHP Class for fping command

Requires: fping

Installation with Composer
--------------------------

For RedHat/CentOS 6, 7

```shell
[root@centos ~]# yum install epel-release
[root@centos ~]# yum install fping
```

```shell
$ composer require nelisys/ping
```

Usage
-----

Example php file.

```php
// test-ping.php
require 'vendor/autoload.php';

use Nelisys\Ping;

$host = new Ping('127.0.0.1');

var_dump($host->ping());
```

Test run php file.

```shell
$ php test-ping.php
Array
(
    [127.0.0.1] => 0.14
)
```
