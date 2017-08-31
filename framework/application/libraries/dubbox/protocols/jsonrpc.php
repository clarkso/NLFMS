<?php

namespace dubbox\protocols;
require_once dirname(dirname(__FILE__))."/invoker.php";
require_once dirname(dirname(__FILE__))."/../jsonrpc/client/JsonRpcClient.php";
require_once dirname(dirname(__FILE__))."/../jsonrpc/client/RpcRequest.php";

use dubbox\Invoker;

/**
 * JSONRPC 实现类
 * @author clarkso
 *
 */
class jsonrpc extends Invoker{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 任何方法调用响应
     * @param string $name 方法名
     * @param unknown $arguments 参数
     * @throws \Exception
     */
    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
        if (!is_scalar($name)) {
            throw new \Exception('Method name has no scalar value');
        }

        // check
        if (is_array($arguments)) {
            // no keys
            $params = array_values($arguments);
        } else {
            throw new \Exception('Params must be given as array');
        }

        // sets notification or request task
        if ($this->notification) {
            $currentId = NULL;
        } else {
            $currentId = $this->id;
        }

        // prepares the request
        $request = array(
            'method' => $name,
            'params' => $params,
            'id' => $currentId
        );
        $request = json_encode($request);
        $listOfCalls = array();

        //print_r("URL:".$this->url);
        array_push($listOfCalls,new \RpcRequest($name,$arguments));
        $client = new \JsonRpcClient($this->url);
        //$client = new \JsonRpcClient("http://cecrm.9leaf.com/index.php/_api/test_jsonrpc/reg_provider");
        //var_dump($arguments);
        return $client->$name($arguments)->result;
        //$this->debug && $this->debug.='***** Request *****'."\n".$request."\n".'***** End Of request *****'."\n\n";
        /*
        // performs the HTTP POST
        $opts = array ('http' => array (
            'method'  => 'POST',
            'header'  => 'Content-type: application/json',
            'content' => $request
        ));
        $context  = stream_context_create($opts);
        if ($fp = fopen($this->url, 'r', false, $context)) {
            $response = '';
            while($row = fgets($fp)) {
                $response.= trim($row)."\n";
            }
            $this->debug && $this->debug.='***** Server response *****'."\n".$response.'***** End of server response *****'."\n";
            $response = json_decode($response,true);
        } else {
            throw new \Exception('Unable to connect to '.$this->url);
        }

        // debug output
        if ($this->debug) {
            //echo nl2br($debug);
        }

        // final checks and return
        if (!$this->notification) {
            // check
            if ($response['id'] != $currentId) {
                throw new \Exception('Incorrect response id (request id: '.$currentId.', response id: '.$response['id'].')');
            }
            if (!is_null($response['error'])) {
                throw new \Exception('Request error: '.$response['error']);
            }

            return $response['result'];

        } else {
            return true;
        }*/
    }

}


