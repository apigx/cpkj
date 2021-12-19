<?php
require_once __DIR__.'/../vendor/autoload.php';
$test=new \Cpkj\ClientX();
$api="https://apigx.cn/token/3ba3a7ca151511ecab09c43772bcfd94/code/twbg28/rows/1.json";
$test->getData($api,[
    'concurrency' => 5,
    'fulfilled'   => function($response,$index){
        //是失败后
    },
    'rejected' => function($response,$index){
        //成功后
    },
]);