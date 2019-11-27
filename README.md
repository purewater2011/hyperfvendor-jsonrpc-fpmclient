#hyperfvendor-jsonrpc-fpmclient
# 介绍
本项目是在fpm环境下，调用hyperf的jsonrpc微服务。
使用方法：
```
require_once __DIR__."/vendor/autoload.php";
try {
    $rs = \HyperfVendor\Service::getInstance('CalculatorService')->call('add', [1, 2])->getResult();
    var_dump($rs);
} catch (\Exception $e) {
    echo $e->getMessage();
}
```
>注意:1.微服务配置，首先在跟目录下新建config/nodes.php,然后配置节点。
>2.根目录常量：需要定义BASE_PATH或者ROOT_PATH

nodes.php 配置格式： 
```
return [
    ['host' => '127.0.0.1', 'port' => 9503]
];
```

# Hyperf文档

[https://doc.hyperf.io/](https://doc.hyperf.io/)