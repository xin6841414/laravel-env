# LaravelEnv
一个方便后台编辑env文件的小工具

## 1、安装
```php
    composer require xin6841414/laravel-env -vvv
```
## 2、使用
### 2.1、查询列表
```php
$laravelEnv = app('laravel-env');
$list = $laravelEnv->getFormatData();

// [
//    ['APP_NAME'=>'shagncheng.shoumai365.com'],
//    ['APP_ENV'=>'local']
//        ...
// ]
```
### 2.2、获取单个env配置
```php
$num = 10;  //上一步获取到的索引数组的索引键值，
$key = 'APP_NAME'; //配置项键值，$num和$key 不能同时为null，
$value = $laravelEnv->getEnv($num, $key);

```

### 2.3、设置env配置

```php
$data = [
    'APP_NAME' => '项目名修改',
    'APP_NAME1' => '项目名追加'
];
$laravelEnv->setEnv($data);
```

## 贡献

You can contribute in one of three ways:

1. File bug reports using the [issue tracker](https://github.com/xin6841414/laravel-env/issues).
2. Answer questions or fix bugs on the [issue tracker](https://github.com/xin6841414/laravel-env/issues).
3. Contribute new features or update the wiki.

_The code contribution process is not very formal. You just need to make sure that you follow the PSR-0, PSR-1, and PSR-2 coding guidelines. Any new code contributions must be accompanied by unit tests where applicable._

## License

MIT
