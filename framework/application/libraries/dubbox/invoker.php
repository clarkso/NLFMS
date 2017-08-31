<?php

namespace dubbox;
use \dubbox\Cluster;

/**
 * 调用抽象类
 * @author clarkso
 *
 */
abstract class Invoker{
    protected $invokerDesc;
    protected $url;
    protected $id;
    protected $debug;
    protected $notification = false;
    protected $cluster;

    /**
     * 构造函数
     * @param unknown $url
     * @param string $debug
     */
    public function __construct($url=null, $debug=false) {
        // server URL
        $this->url = $url;
        $this->id = 1;
        $this->debug;
        $this->cluster = Cluster::getInstance();
    }

    /**
     * 设置RPC通知
     * @param unknown $notification
     */
    public function setRPCNotification($notification) {
        empty($notification) ?
            $this->notification = false
            :
            $this->notification = true;
    }

    /**
     * 获取集群
     */
    public function getCluster(){
        return $this->cluster;
    }

    /**
     * 设置Host
     * @param unknown $url
     */
    public function setHost($url){
        $this->url = $url;
    }

    /**
     * 获取dubbox的url
     * @param unknown $host
     * @param unknown $invokerDesc
     * @return string
     */
    public static function genDubboUrl($host,$invokerDesc){
        return $host;//.'/'.$invokerDesc->getService();
    }

    /**
     * 字符串化
     * @return string 返回序列化类
     */
    public function toString(){
        return  __CLASS__;
    }

    /**
     * 回调函数
     * @param string $name
     * @param string $arguments
     */
    abstract public function __call($name,$arguments);
}

