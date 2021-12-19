<?php
require_once __DIR__.'/../vendor/autoload.php';
$test=new \Cpkj\ClientX();
//单条API接口访问
$api="接口1";
//多条API接口并发访问
$api=[
    "https://apigx.cn/token/dsadsad/code/twbg28/rows/1.json",
    "https://apigx.cn/token/dsadsaq/code/twbg28/rows/1.json",
];
$test->getData($api,[
    'fulfilled'=>function($response,$index){
        //访问成功操作(下面是范例)
        $getJson2Arr=json_decode($response->getBody()->getContents(),true);//把JSON数据处理为数组
        if($getJson2Arr["rows"]>0){
            //判断数据正常时
            print_r($getJson2Arr);//打印获取的数组
        }else{
            //数据异常时。这里的数据异常主要出现为这几种情况：
            //1、token过期；
            //2、token的绑定IP冲突（1个IP1个程序1个token）；
            //3、token访问过于频繁（1个token访问频率最大值不能高于1秒）。如：1个token1秒访问5次，是不允许的行为，严重还会被防火墙误判为CC攻击。
            echo "API接口的数组位置".$index.",返回提示：".$getJson2Arr["info"];//输出提示语
        }
    },
    'rejected'=>function($reason,$index){
        //访问失败操作
        echo $index;//访问失败API接口数组的位置从0开始。
        print_r($reason);//响应的部分数据
        //开发建议：这里建议直接提示即可。因为不同的开发框架提示都会有差异。上述2个数据不一定存在。
    },
]);