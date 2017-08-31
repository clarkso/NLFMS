<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Qy_weixin_api {
	public $corpid = '';
	public $secret = '';
	public $access_token = '';
	public $request;
	public $debug = TRUE;
	public $CI;

	
	/**
	 */
	public function __construct() {
		//parent::__construct();
		log_message ( 'debug', "Weixinapi Class Initialized." );
		$this->CI = &get_instance ();
		$app_info = $this->CI->session->userdata ( 'app_info' );
		// $app_info = $this->app_info;
		$this->corpid = "wx35a152703e76c11a";//$app_info ['wechat_appid'];
		$this->secret = "w_tj9f6medy2zjR6a8C4fyKNcsxkS-zJKEhaC4Km1S3uYl0ZhZdyjuOHvYvu2-iN";//$app_info ['wechat_appsecret'];
		//$this->valid ();
	}
	
	// 用于接入验证
	public function Valid() {
		$signature = $this->CI->input->get ( 'signature' );
		$timestamp = $this->CI->input->get ( 'timestamp' );
		$nonce = $this->CI->input->get ( 'nonce' );
		
		$tmp_arr = array (
				$this->token,
				$timestamp,
				$nonce 
		);
		sort ( $tmp_arr );
		$tmp_str = implode ( $tmp_arr );
		$tmp_str = sha1 ( $tmp_str );
		
		if ($tmp_str == $signature) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * 获取access_token
	 *
	 * @return string
	 */
	public function Access_token() {
		$app_info = $this->CI->session->userdata ( 'app_info' );
		$now =  date ( 'Y-m-d H:i:s' ) ;
		// 如果未访问过，或者超时，重新获取
		//if (! isset ( $app_info ['wechat_token_stamp'] ) || $app_info ['wechat_token_stamp'] == 0 || ($now - $app_info ['wechat_token_stamp'] > 7190)) {
			$url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=" . $this->corpid . "&corpsecret=" . $this->secret;
			//echo $url;
			
			$res = $this->Https_request ( $url );
			$result = json_decode ( $res, true );
			//print_r($result);
			$this->CI->load->model ( 'app_info_mdl' );
			$this->CI->app_info_mdl->update_access_token ( $app_info ['id'], $result ['access_token'], $now );
			
		// error_log("res:". $res);
			
			// error_log("access_token:". $result ['access_token']);
			if ($result ['access_token']) {
				$this->access_token = $result ['access_token'];
				return $result ['access_token'];
			} else {
				return NULL;
			}
		//} else { // 否则直接返回token
		//	return $app_info ['wechat_access_token'];
		//}
	}
	
	// ------------------------------------------------------------
	
	/**
	 * https请求（支持GET和POST）
	 * 
	 * @param string $url        	
	 * @param string $data        	
	 * @return mixed
	 */
	public function Https_request($url, $data = null) {
		$curl = curl_init ();
		curl_setopt ( $curl, CURLOPT_URL, $url );
		curl_setopt ( $curl, CURLOPT_SSL_VERIFYPEER, FALSE );
		curl_setopt ( $curl, CURLOPT_SSL_VERIFYHOST, FALSE );
		if (! empty ( $data )) {
			curl_setopt ( $curl, CURLOPT_POST, 1 );
			curl_setopt ( $curl, CURLOPT_POSTFIELDS, $data );
		}
		curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 );
		$output = curl_exec ( $curl );
		// error_log("url:".$url." output: ".$output);
		curl_close ( $curl );
		return $output;
	}
	
	// ------------------------------------------------------------
	
	/**
	 */
	public function Wechatplugin() {
		$code = $this->CI->input->get ( "code" );
		$state = $this->CI->input->get ( "state" );
		
		$account_info = $this->CI->session->userdata ( 'account_info' );
		
		if (! isset ( $account_info ) || $account_info == "") {
			
			if ($this->accesstoken == "") {
				$this->_access_token ();
			}
			
			$url = "https://qyapi.weixin.qq.com/cgi-bin/user/getuserinfo?access_token=" . $this->accesstoken . "&code=" . $code . "&agentid=0";
			
			$json = $this->httpget ( $url );
			$accountInfo = "";
			
			if (! isset ( $json ["errcode"] )) {
				$userid = $json ["UserId"];
				$url = "https://qyapi.weixin.qq.com/cgi-bin/user/get?access_token=" . $this->accesstoken . "&userid=" . $userid;
				$json2 = $this->httpget ( $url );
				$wechatnum = $json2 ["weixinid"];
				/*$this->load->model ( "wechat_parents_mdl" );
				$account_infoList = $this->wechat_parents_mdl->getByIdForWeChat2 ( $wechatnum );
				
				if (count ( $account_infoList ) >= 1) {
					// 模拟登录
					$accountInfo = $account_infoList [0];
					// if(count($account_infoList)>1)
					// {
					
					// $accountInfo["accountList"] = $wechatnum;
					// $this->session->set_userdata("account_infoList",$account_infoList);
					
					// }
				} else {
					$this->load->view ( 'wechat_error_view' );
					return;
				}*/
				
			}
			
			$this->session->set_userdata ( 'account_info', $accountInfo );
		}
		
		if ($state == "bbs") {
			$this->session->set_userdata ( 'iswechat', "Y" );
			redirect ( site_url ( 'clazz/main' ) );
		} else if ($state == "kidphotos") {
			$this->session->set_userdata ( 'iswechat', "Y" );
			redirect ( site_url ( 'clazz/capture' ) );
		} else {
			$this->kidstatus ();
		}
	}
	
	// ------------------------------------------------------------
	
	/**
	 * 
	 * @param unknown $url
	 * @return mixed
	 */
	public function Httpget($url) {
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_HEADER, 0 );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 10 );
		$res = curl_exec ( $ch );
		curl_close ( $ch );
		$json_obj = json_decode ( $res, true );
		return $json_obj;
	}
	
	// ------------------------------------------------------------
	
	/**
	 * 
	 * @param unknown $url
	 * @param unknown $data
	 * @return mixed
	 */
	public function Httppost($url, $data) {

		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_HEADER, 0 );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 10 );
		curl_setopt ( $ch, CURLOPT_POST, 1 );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data ); // Post提交的数据包
		curl_setopt ( $ch, CURLOPT_HTTPHEADER, array (
				'Content-Type: application/json',
				'Content-Length: ' . strlen ( $data ) 
		) );
		$res = $this->post($url,$data);  
		$res = curl_exec ( $ch );
		
		curl_close ( $ch );
		$json_obj = json_decode ( $res, true );
		
		return $json_obj;

		
	}



	/**
	 * 取得JSSDK的签名
	 * @param unknown $url
	 * @param unknown $data
	 * @return mixed
	 */
  public function GetSignPackage() {
   
    $jsapiTicket = $this->GetJsApiTicket();

    // 注意 URL 一定要动态获取，不能 hardcode.
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

    $timestamp = time();
    $nonceStr = $this->createNonceStr();

    // 这里参数的顺序要按照 key 值 ASCII 码升序排序
    $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

    $signature = sha1($string);


    $signPackage = array(
      "appId"     => $this->corpid,
      "nonceStr"  => $nonceStr,
      "timestamp" => $timestamp,
      "url"       => $url,
      "signature" => $signature,
      "rawString" => $string
    );
    return $signPackage; 
  }


   private function CreateNonceStr($length = 16) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $str = "";
    for ($i = 0; $i < $length; $i++) {
      $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
    }
    return $str;
  }


    /**
	 * 取jsAPITicket
	 */
  private function GetJsApiTicket() {
    // jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例
    $data = json_decode(file_get_contents("./uploads/jsapi_ticket.json"));
    if ($data->expire_time < time()) {
      $accessToken = $this->Access_token();
      // 如果是企业号用以下 URL 获取 ticket
       $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";
      //$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";

      $res = json_decode($this->Https_request($url));

      $ticket = $res->ticket;
      if ($ticket) {
        $data->expire_time = time() + 7000;
        $data->jsapi_ticket = $ticket;
        $fp = fopen("./uploads/jsapi_ticket.json", "w");
        fwrite($fp, json_encode($data));
        fclose($fp);
      }
    } else {
      $ticket = $data->jsapi_ticket;
    }

    return $ticket;
  }

	/**
	 * 下载图片
	 * @param unknown $serviceId
	 * @param unknown $path
	 * @param unknown $imgname
	 * @return null
	 */
	public function DownloadTempImage($serviceId,$path)
	{
		$accessToken = $this->Access_token();
		$url = "https://qyapi.weixin.qq.com/cgi-bin/media/get?access_token=".$accessToken."&media_id=".$serviceId;
        $content = file_get_contents($url);
		/*if($imgname == "")
		{
			$imgname = ((float)$usec + (float)$sec).'.jpg';
		}*/
		//error_log("content:".$content);
        file_put_contents($path, $content);//把图片保存服务器
	}

}