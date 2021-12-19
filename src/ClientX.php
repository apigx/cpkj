<?php
namespace Cpkj;
use GuzzleHttp\Client;
use GuzzleHttp\Pool;
class ClientX{
    /**
     * 默认值
     * @var array|int[]
     */
    protected $config=[];
    /**
     * 访问器
     * @var client
     */
    public $client;
    protected $uris=[];
    private $client_config=[];

    /**
     * 初始化
     * ClientX constructor.
     * @param array $client_config
     */
    public function __construct(array $client_config = ['timeout' => 300]){
        $this->client_config=array_merge($this->client_config,$client_config);
    }

    /**
     * 设置获取
     * @param $uris
     */
    public function setData($uris){
        if(gettype($uris)=="string"){
            $this->uris[]=$uris;
        }else if(gettype($uris)=="array"){
            $this->uris=array_merge($this->uris,$uris);
        }else{
            $this->uris=[];
        }
    }

    /**
     * 获取数据
     */
    public function getData($uris,$config=null){
        //处理数据
        if(gettype($uris)=="string"){
            $this->uris[]=$uris;
        }else if(gettype($uris)=="array"){
            $this->uris=array_merge($this->uris,$uris);
        }
        $client=new Client($this->client_config);
        $requests = function () use ($client) {
            foreach($this->uris as $uri){
                yield function() use ($client,$uri){
                    return $client->getAsync($uri);
                };
            }
        };
        //初始化默认值
        $this->config=[
            'concurrency' => 5,//配置并发异步请求数，每次同时创建多少个请求，默认为5，建议30以内。过大的话，服务器内存可能不够用。
            'fulfilled'   => function($response,$index){
                //访问成功操作
            },
            'rejected' => function($reason,$index){
                //访问失败操作
            },
        ];
        if($config)$this->config=array_merge((array)$this->config,(array)$config);
        // 开始发送请求
        $pool = new Pool($client,$requests(),$this->config);
        $promise = $pool->promise();
        $promise->wait();
    }
}