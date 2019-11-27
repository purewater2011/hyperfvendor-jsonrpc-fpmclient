<?php

require_once __DIR__."/vendor/autoload.php";
try {
    $rs = \HyperfVendor\Service::getInstance('CalculatorService')->call('add', [1, 2])->getResult();
    var_dump($rs);
} catch (\Exception $e) {
    echo $e->getMessage();
}

