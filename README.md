<h1 align="center"> jingdong-sdk </h1>

<p align="center"> 京东宙斯 SDK.</p>


## Installing

```shell
$ composer require zacksleo/jingdong-sdk -vvv
```

## Usage

### 创建客户端

```php
$jingdong = new Jingdong([
    'key'    => 'key',
    'secret' => 'secret',
    'debug'  => false,
    'log'    => [
        'name'       => 'jingdong',
        'file'       => '/path/to/logs/jingdong.log'),
        'level'      => 'debug',
        'permission' => 0777,
    ],
]);
```

### 调用

#### 链式调用

```php
$res = $jingdong->pop->order->get([
    'order_state' => 'WAIT_SELLER_DELIVERY',
    'optional_fields' => 'venderId,orderType,payType',
    'order_id' => '67834311',
]);
```

#### 普通调用

```php

$res = $jingdong->request('pop.order.get', [
    'order_state' => 'WAIT_SELLER_DELIVERY',
    'optional_fields' => 'venderId,orderType,payType',
    'order_id' => '67834311',
]);

```

## Contributing

You can contribute in one of three ways:

1. File bug reports using the [issue tracker](https://github.com/zacksleo/jingdong-sdk/issues).
2. Answer questions or fix bugs on the [issue tracker](https://github.com/zacksleo/jingdong-sdk/issues).
3. Contribute new features or update the wiki.

_The code contribution process is not very formal. You just need to make sure that you follow the PSR-0, PSR-1, and PSR-2 coding guidelines. Any new code contributions must be accompanied by unit tests where applicable._

## License

MIT