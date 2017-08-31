<?php
/**
 * 自动读取
 * @param unknown $className
 */
/*function __autoload($className) {
	include_once($className.".php");
}*/

/**
 * 客户端类
 * @author clarkso
 *
 */
class JsonRpcClient {

	private $_rpcUrl;

	private $_curlCookie;

	/**
	 *
	 * @param unknown $rpcUrl
	 */
	public function __construct($rpcUrl) {
		$this->_rpcUrl = $rpcUrl;
		$this->_curlCookie = dirname(__FILE__).DIRECTORY_SEPARATOR.'curl_cookie';
	}

	/**
	 *
	 * @param unknown $name
	 * @param unknown $arguments
	 */
	public function __call($name, $arguments) {
	    //var_dump(implode(', ', $arguments));
	    //print_r($arguments[0]);
	    return $this->call(new RpcRequest($name,$arguments[0]));
	}

	/**
	 *
	 * @param unknown $rpcRequest
	 */
	public function call($rpcRequest) {
		if($rpcRequest instanceof RpcRequest) {
			return $this->httpRequest($rpcRequest->getRpcRequestObject());
		}
	}

	/**
	 *
	 * @param unknown $rpcRequestList
	 */
	public function callBatch($rpcRequestList) {
        $rpcBatchArray = array();
        foreach($rpcRequestList as $rpcRequest) {
            if($rpcRequest instanceof RpcRequest) {
                array_push($rpcBatchArray, $rpcRequest->getRpcRequestObject());
            }
        }
        return $this->httpRequest($rpcBatchArray);
	}

	/**
	 * http请求
	 * @param unknown $rpcBatchArray
	 * @throws Exception
	 * @return unknown
	 */
	private function httpRequest($rpcBatchArray) {
		$curlHandler = curl_init();
		$curlOptions = array(
			CURLOPT_URL => $this->_rpcUrl,
			// cookie stuff
			CURLOPT_COOKIE => true,
            CURLOPT_COOKIEFILE => $this->_curlCookie,
            CURLOPT_COOKIEJAR => $this->_curlCookie,
			// request specific stuff
			CURLINFO_CONTENT_TYPE => "application/json",
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => json_encode($rpcBatchArray),//'{"jsonrpc":"2.0","method":"getPersons","params":[],"id":1}',
			CURLOPT_RETURNTRANSFER => true

		);
		//print_r($this->_rpcUrl);
		curl_setopt_array($curlHandler,$curlOptions);

        $response = curl_exec($curlHandler);
        if(!$response) {
          throw new Exception('Curl error: ' . curl_error($curlHandler), curl_errno($curlHandler));
        }
        curl_close($curlHandler);
        $json_response = json_decode($response);
        if (json_last_error() != JSON_ERROR_NONE) {
            switch (json_last_error()) {
                case JSON_ERROR_DEPTH:
                    $message = 'The maximum stack depth has been exceeded';
                    break;
                case JSON_ERROR_STATE_MISMATCH:
                    $message = 'Invalid or malformed JSON';
                    break;
                case JSON_ERROR_CTRL_CHAR:
                    $message = 'Control character error, possibly incorrectly encoded';
                    break;
                case JSON_ERROR_SYNTAX:
                    $message = 'Syntax error';
                    break;
                case JSON_ERROR_UTF8:
                    $message = 'Malformed UTF-8 characters, possibly incorrectly encoded';
                    break;
                default:
                    $message = "Error decoding JSON string.";
                    break;
            }
            $message .= "\nMethod: " . $rpcBatchArray->method.
                        "\nParams: " . var_export($rpcBatchArray->params, TRUE).
                        "\nResponse: " . $response;
            throw new Exception($message, json_last_error());
        }
        return $json_response;
	}
}
