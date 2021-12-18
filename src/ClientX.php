<?php
namespace Cpkj;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class ClientX{
    /**
     * 默认值
     * @var array|int[]
     */
    protected $config=[
        'timeout'  => 300,
    ];
    /**
     * 访问器
     * @var client
     */
    public $client;
    protected $uri="";
    /**
     * 初始化
     * ClientX constructor.
     * @param array $config
     */
    public function __construct(array $config = []){
        $this->config=array_merge($this->config,$config);
        $this->client=new Client();
    }

    /**
     * 设置获取
     * @param $uri
     */
    public function setData($uri){
        if(gettype($uri)=="string"){
            $this->uri=$uri;
        }else if(gettype($uri)=="array"){
            $this->uri=array_merge($this->uri,$uri);
        }
    }

    /**
     * 获取数据
     * @param $uri
     * @param $fn
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getData($uri,$fn){
        //处理数据
        if(gettype($uri)=="string"){
            $this->uri=$uri;
        }else if(gettype($uri)=="array"){
            $this->uri=array_merge($this->uri,$uri);
        }else{
            $this->uri="";
        }
        //获取数据
        if(gettype($uri)=="string"){
            try{
                $response = $this->client->request("get",$uri);
                $statusCode=$response->getStatusCode();
                if($statusCode==200){
                    try{
                        $arr=json_decode($response->getBody()->getContents(),true);
                        if($arr["rows"]==0){
                            return ["result"=>true,"data"=>$arr["data"],"rows"=>$arr["rows"],"code"=>$arr["code"],"msg"=>"获取成功"];
                        }else{
                            return ["result"=>false,"msg"=>$arr["info"]];
                        }
                    }catch(Exception $exception){
                        return ["result"=>false,"msg"=>$exception->getMessage()];
                    }
                }else{
                    return ["result"=>false,"msg"=>"返回代码不正确"];
                }
            }catch (RequestException $e){
                return ["result"=>false,"msg"=>$e->getMessage()];
            }

        }else if(gettype($uri)=="array"){
            $this->uri=array_merge($this->uri,$uri);
        }
    }
}