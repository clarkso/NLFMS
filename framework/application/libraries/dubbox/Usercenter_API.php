<?php
class Usercenter_API extends JsonRpcService {
    
    private $CI;
    public function __construct(){
        parent::__construct ();
        $this->CI = & get_instance();
        $this->CI->load->helper('usercenter_return');
    }
    
    //根据id获取用户信息
	/** @JsonRpcMethod*/
	public function get_account($id = 0){
	    //检查参数
	    $empty = check_array_not_empty(array('id'=>$id),array('id'));
	    if ($empty !== true){
	        return $empty;
	        exit;
	    }

	    try
	    {
		    $this->CI->load->model('usercenter/API_user_mdl');
    	    $data = $this->CI->API_user_mdl->get_detail($id);
    	    if (!empty($data)){
    	       return success($data);
    	    }else{
    	        return error(10001);
    	    }
	    }catch(Exception $e)
	    {
            return error_msg(1,'获取失败');
	    }
	    
	}
	
	/**
	 * 
	 * @Name login
	 * @Description 登录
	 * @Param @mobile string 用户账号，即手机号
	 * @Param @password string 密码sha1加密
	 */
	/** @JsonRpcMethod*/
	public function login($mobile = 0 ,$sha1password = 0,$md5password = 0,$returnUrl='',$token = ''){
	    $empty = check_array_not_empty(array('mobile'=>$mobile),array('mobile'));
	    if ($empty !== true){
	        return $empty;
	        exit;
	    }
	    
	    try
	    {
    	    $this->CI->load->model('usercenter/API_user_mdl');
    	    //检查用户是否存在
    	    $check = $this->CI->API_user_mdl->get_by_mobile($mobile);  
    	    
    	    if ($check){
        	    $data = $this->CI->API_user_mdl->login($mobile,$sha1password,$md5password);
        	    if ($data){
        	       $user_data = $this->CI->API_user_mdl->get_detail($data['UUID']);
        	       if ($returnUrl){
            	       //回调应用服务器记录状态
                       $this->sign_server(array('data'=>$user_data,'token'=>$token), $returnUrl);
        	       }
                   
        	       return success($user_data);
        	    }else{
        	        return error(1001);      //密码错误
        	    }
    	    }else{
    	           return error(1002);    //用户不存在
    	    }
	    }catch (Exception $e)
	    {
	        return error_msg(1,'获取失败');
	    }
	    
	}
	
	/*
	 * @Name update_account_info
	 * @Description 修改用户信息
	 * @Param @id string  用户UUID
	 * @Param @params string 
	 *         json字符串包括：
	 *         nickName:用户昵称;
	 *         introduce:个人简介;
	 *         avatar:用户头像;
	 *         sex:性别，1为男2为女0为未知
	 *         birthday:生日，格式 Y-m-d H:i:s
	 *         city:所在城市id，对应region表
	 *         
	 */
	/** @JsonRpcMethod*/
	public function update_account_info($id ,$params = '{}'){
	    $empty = check_array_not_empty(array('id'=>$id),array('id'));
	    if ($empty !== true){
	        return $empty;
	        exit;
	    }
	    
	    
	    try
	    {
	        $this->CI->load->model('usercenter/API_user_mdl');
	        //检查用户状态
	        $check = $this->CI->API_user_mdl->get_detail($id);
	        if ($check['isOn'] == 1){
	            $params = json_decode($params,true);
	            $change = array();
	            if (isset($params['nickName']))
	                $change['nickName'] = $params['nickName'];    //用户昵称
                if (isset($params['introduce']))
                    $change['introduce'] = $params['introduce'];    //个人简介
                if (isset($params['avatar']))
                    $change['avatar'] = $params['avatar'];  //头像
                if (isset($params['sex']))
                    $change['sex'] = $params['sex']; //性别
                if (isset($params['birthday']))
                    $change['birthday'] = $params['birthday'];  //生日
                if (isset($params['city']))
                    $change['city'] = $params['city'];      //所在城市
                
                //更新数据库
                if ($change){
                    $data = $this->CI->API_user_mdl->update_user_info($id,$change);
                    if($data){
                        return success($data);
                    }else{
                        return error(10002);
                    }
                }else {
                    return error(1013);
                }
	             
	             
	        }else{
	            return error(1003);
	        }
	    
	    }catch (Exception $e)
	    {
	        return error_msg(1,'更新失败');
	    }
	}
	
	/** @JsonRpcMethod*/
	public function update_value($id, $change_value = 0){
	    $empty = check_array_not_empty(array('id'=>$id),array('id'));
	    if ($empty !== true){
	        return $empty;
	        exit;
	    }
	    
	    try
	    {
	        $this->CI->load->model('usercenter/API_user_mdl');
	        //检查用户状态
	        $check = $this->CI->API_user_mdl->get_detail($id);
	        if ($check['isOn'] == 1){
	            $params = array(
	                '`value`' => "  `value` + {$change_value}"
	            );
	            $data = $this->CI->API_user_mdl->update_user_info_origin($id,$params);
	            
	            if($data){
	                return success($data);
	            }else{
	                return error(10002);
	            }
	            
	            
	            
	        }else{
	            return error(1003);      //用户被冻结
	        }
	        	
	    }catch (Exception $e)
	    {
	        return error_msg(1,'更新失败');
	    }
	}
	
	/** @JsonRpcMethod*/
	public function update_score($id ,$change_score = 0){
	    $empty = check_array_not_empty(array('id'=>$id),array('id'));
	    if ($empty !== true){
	        return $empty;
	        exit;
	    }
	    
	    try
	    {
	        $this->CI->load->model('usercenter/API_user_mdl');
	        //检查用户状态
	        $check = $this->CI->API_user_mdl->get_detail($id);
	        if ($check['isOn'] == 1){
	            $params = array(
	              '`score`' => "`score` + {$change_score}"
	            );
	            $data = $this->CI->API_user_mdl->update_user_info_origin($id,$params);
	            if($data){
	                return success($data);
	            }else{
	                return error(10002);
	            }
	            
	            
	        }else{
	            return error(1003);
	        }
	        	
	    }catch (Exception $e)
	    {
	        return error_msg(1,'更新失败');
	    }
	}
	
	/** @JsonRpcMethod */
	public function get_user_list($search = '{}', $page = 1, $perpage = 10){
	    try
	    {
	        $search = json_decode($search,true);
	        $this->CI->load->model('usercenter/API_user_mdl');
	        $data = $this->CI->API_user_mdl->get_user_list($search,$page,$perpage,1);
	        $count = $this->CI->API_user_mdl->get_user_list($search,$page,$perpage,0);
	        
	        return success_with_page($data, $count,$perpage,$page);
	    
	    }catch (Exception $e)
	    {
	        return error_msg(1,'获取失败');
	    }
	}
	
	/** @JsonRpcMethod */
	public function record_location($id,$longitude,$latitude){
	    $empty = check_array_not_empty(array('id'=>$id),array('id'));
	    if ($empty !== true){
	        return $empty;
	        exit;
	    }
	     
	    try
	    {
	    
	        $this->CI->load->model('usercenter/API_user_mdl');
	         
	        //检查用户状态
	        $check = $this->CI->API_user_mdl->get_detail($id);
	        if ($check['isOn'] == 1){
	            $params = array(
	              'latitude' => $latitude,
	                'longitude' => $longitude
	            );
                $data = $this->CI->API_user_mdl->update_user_info($id,$params);
                if($data){
                    return success($data);
                }else{
                    return error(10002);
                }
	    
	    
	        }else{
	            return error(1003);
	        }
	    
	    }catch (Exception $e)
	    {
	        return error_msg(1,'获取失败');
	    }
	}
	
	/** @JsonRpcMethod */
	public function register($mobile,$md5password,$sha1password,$returnUrl='',$mobile_verify,$token=''){
	    $empty = check_array_not_empty(
	        array('mobile'=>$mobile,'md5password'=>$md5password,'sha1password'=>$sha1password,'mobile_verify'=>$mobile_verify),
	        array('mobile','md5password','sha1password','mobile_verify')
        );
	    if ($empty !== true){
	        return $empty;
	        exit;
	    }
	    
	    try
	    {
	         
	        $this->CI->load->model('usercenter/API_user_mdl');
	        
	        //检验手机号码
	        if (!preg_match("/^((13[0-9])|(14[5,7])|(15[0-3,5-9])|(17[0,3,5-8])|(18[0-9])|(147))\d{8}$/",$mobile)){
	            return error('1009');
	            exit;
	        }
	        
	        // 检查mobile是否已被注册
	        if ($this->CI->API_user_mdl->get_by_mobile($mobile)){
	            return error(1008);
	            exit;
	        }
	        
	        //检查短信验证码
	        $this->CI->load->driver('cache', array('adapter' => 'redis'));
	        $verify = $this->CI->cache->get('mobile_register_mobile_verify_'.$mobile);
	        if (empty($verify)) {
	            return  error('1005');
	            exit();
	        }elseif ($mobile_verify != $verify) {
	            return  error('1006');
	            exit();
	        }

	        //生成UUID
	        $UUID = $this->createUUID();
	        
	        //根据UUID分别在user_base表和user_info表生成记录
	        $this->CI->API_user_mdl->create_user_base($UUID,$mobile , md5(strtoupper($md5password)),md5(strtoupper($sha1password)));
	        $this->CI->API_user_mdl->create_user_info($UUID,'L'.$this->getRandomString(6));
	        
	        $user_data = $this->CI->API_user_mdl->get_detail($UUID);
	        if ($returnUrl){
    	        //回调应用服务器记录状态
    	        $result = $this->sign_server(array('data'=>$user_data,'token'=>$token,'mobile_verify'=>$mobile_verify), $returnUrl);
                    
	        }
	        
	        
	        //成功返回
	        return success($user_data);
	        
	        
	         
	    }catch (Exception $e)
	    {
	        return error_msg(1,'创建成功失败');
	    }
	}
	
	/** @JsonRpcMethod */
	public function change_lock_statues($id,$status){
	    $empty = check_array_not_empty(array('id'=>$id),array('id'));
	    if ($empty !== true){
	        return $empty;
	        exit;
	    }
	    $status = ($status==1)?1:0;
	    
	    try
	    {
	    
	        $this->CI->load->model('usercenter/API_user_mdl');
	        
	        $params = array(
	            'isOn' => (int)$status
	        );
	        $data = $this->CI->API_user_mdl->update_user_info($id,$params);
	        if($data){
	            return success($data);
	        }else{
	            return error(10002);
	        }
	    
	    }catch (Exception $e)
	    {
	        return error_msg(1,'获取失败');
	    }
	}
	
	/** @JsonRpcMethod */
	public function update_password($mobile,$md5password,$sha1password){
	    $empty = check_array_not_empty(array('mobile'=>$mobile,'md5password'=>$md5password,'sha1password'=>$sha1password),array('mobile','md5password','sha1password'));
	    if ($empty !== true){
	        return $empty;
	        exit;
	    }
	    
	    try
	    {
	         
	        $this->CI->load->model('usercenter/API_user_mdl');
	        
	        //检查用户状态
	        $check = $this->CI->API_user_mdl->get_detail($id);
	        if ($check['isOn'] == 1){
    	        $params = array(
	            'password' => $sha1password,
	            'md5password' => $md5password
    	        );
    	        $data = $this->CI->API_user_mdl->update_user_base_mobile($mobile,$params);
    	        if($data){
    	            return success($data);
    	        }else{
    	            return error(10002);
    	        }
	             
	             
	        }else{
	            return error(1003);
	        }
	         
	    }catch (Exception $e)
	    {
	        return error_msg(1,'获取失败');
	    }
	}
	
	/** @JsonRpcMethod */
	public function bundling_third_party($id,$type,$num,$nick_name,$avatar){
	    $empty = check_array_not_empty(array('id'=>$id,'type'=>$type,'nick_name'=>$nick_name,'avatar'=>$avatar),array('id','nick_name','type','avatar'));
	    if ($empty !== true){
	        return $empty;
	        exit;
	    }
	    
	    try
	    {
	    
	        $this->CI->load->model('usercenter/API_user_mdl');
	        
	        //检查用户状态
	        $check = $this->CI->API_user_mdl->get_detail($id);
	        if ($check['isOn'] == 1){
	            switch ($type){
	                case 1:
	                    $params = array(
	                       'wechat_num' => $num,
	                       'wechat_nickname' => $nick_name
	                    );
	                    break;
	                case 2:
	                    $params = array(
	                        'qq_num' => $num,
	                        'qq_nickname' => $nick_name
	                    );
	                    break;
	                    
                    default:
                        $params = array(
                            'weibo_account' => $num,
                            'weibo_nickname' => $nick_name
                        );
	            }
	            
    	        $data = $this->CI->API_user_mdl->update_user_base_id($id,$params);
    	        if($data){
    	            return success($data);
    	        }else{
    	            return error(10002);
    	        }
	             
	             
	        }else{
	            return error(1003);
	        }
	    
	    }catch (Exception $e)
	    {
	        return error_msg(1,'获取失败');
	    }
	}
	
	/** @JsonRpcMethod */
	public function unbundling_third_party($id,$type){
	    $empty = check_array_not_empty(array('id'=>$id,'type'=>$type),array('id','type'));
	    if ($empty !== true){
	        return $empty;
	        exit;
	    }
	    
	    try
	    {
	         
	        $this->CI->load->model('usercenter/API_user_mdl');
	        
	        //检查用户状态
	        $check = $this->CI->API_user_mdl->get_detail($id);
	        if ($check['isOn'] == 1){
	        	            switch ($type){
	                case 1:
	                    $params = array(
	                       'wechat_num' => null,
	                       'wechat_nickname' => null
	                    );
	                    break;
	                case 2:
	                    $params = array(
	                        'qq_num' => null,
	                        'qq_nickname' => null
	                    );
	                    break;
	                    
                    default:
                        $params = array(
                            'weibo_account' => null,
                            'weibo_nickname' => null
                        );
	            }
	            
    	        $data = $this->CI->API_user_mdl->update_user_base_id($id,$params);
    	        if($data){
    	            return success($data);
    	        }else{
    	            return error(10002);
    	        }
	             
	             
	        }else{
	            return error(1003);
	        }
	         
	    }catch (Exception $e)
	    {
	        return error_msg(1,'获取失败');
	    }
	}
	
	/*
	 * 发送短信
	 */
	/** @JsonRpcMethod */
	public function send_sms($mobile = '',$status = 0){
	    //检验参数
	    $empty = check_array_not_empty(array('mobile'=>$mobile,'status'=>$status),array('mobile','status'));
	    if ($empty !== true){
	        return $empty;
	        exit;
	    }

	    //检验手机号码
	    if (!preg_match("/^((13[0-9])|(14[5,7])|(15[0-3,5-9])|(17[0,3,5-8])|(18[0-9])|(147))\d{8}$/",$mobile)){
	        return  error('1009');
	        exit;
	    }
	    
	    $this->CI->load->model('usercenter/API_user_mdl');
	    //检查用户是否存在
	    $check = $this->CI->API_user_mdl->get_by_mobile($mobile); 
	    
	    // 检查用户名是否存在
        if (isset($status) && ($status == 1 || $status == 2 || $status == 4) && (empty($check) || $check['isOn'] == 1)) {
            return  error('1002');
            exit;
	    }
	    if ($status == 3 && $check){
            return error('1008');
            exit();
	    }
	    
	    //不存在的情况
	    if ($status < 0 || $status >5){
	        return error_msg(1,'发送失败');
	        exit;
	    }

	    
	    $this->CI->load->library('sms/Short_Message_Factory', '', 'message');
	    //         $num = 123456;
	    $num = $this->CI->message->random(6);
	    
	    // 读取默认短信提供商
	    $this->CI->load->driver('cache', array('adapter' => 'redis'));
	    $this->CI->load->model("sms_supplier_mdl");
	    $supplier = $this->CI->sms_supplier_mdl->get_in_use();
	    $sms = $this->CI->message->get_message($supplier);
	    $date = date('Y-m-d H:i:s');
	    
	    if ($status == 1) {
	        $this->CI->cache->save('forgot_password_mobile_verify_'.$mobile, $num, 2*60);
	        $content = '亲爱的车友，您中经乐驾忘记密码的验证码为：'. $num .'，请在2分钟内完成验证。如非本人操作请忽略。';
	    } else if ($status == 2) {
	        $this->CI->cache->save('update_password_mobile_verify_'.$mobile, $num, 2*60);
	        $content = '亲爱的车友，您中经乐驾修改密码的验证码为：'. $num .'，请在2分钟内完成验证。如非本人操作请忽略。';
	    } else if ($status == 3) {
	        $this->CI->cache->save('mobile_register_mobile_verify_'.$mobile, $num, 2*60);
	        $content = '亲爱的车友，您中经乐驾注册的验证码为：'. $num .'，请在2分钟内完成验证。如非本人操作请忽略。';
	    } else if ($status == 4){
	        $this->CI->cache->save('third_bunding_verify_'.$mobile, $num, 2*60);
	        $content = '亲爱的车友，您中经乐驾绑定的验证码为：'. $num .'，请在2分钟内完成验证。如非本人操作请忽略。';
	    } elseif ($status == 5){
	        $this->CI->cache->save('htcard_bundling_verify_'.$mobile, $num, 2*60);
	        $content = '尊敬的客户，您中经乐驾绑定汇通卡的验证码为：'.$num.'，有效期2分钟。';
	    }
	    
	    $this->CI->load->model("shortmsg_log_mdl");
	    $id = $this->CI->shortmsg_log_mdl->create(array(
	        'mobile' => $mobile,
	        'content' => $content
	    ));
	    $msgs = $sms->send($mobile, $content); // 'sms&stat=100&message=发送成功';//
	    
	    //echo $msgs;
	    if ($msgs){
	        $msg = explode("&", $msgs);
	        $type = $msg[0];
	        $status = $msg[1]; // substr($msg[1], strpos($msg[1], "=") + 1);
	        $return_msg = $msg[2]; // substr($msg[2], strpos($msg[2], "=") + 1);
	        $log = array(
	            'id' => $id,
	            'msg_type' => $type,
	            'status' => $status,
	            'return_msg' => $return_msg
	        );
	        $this->CI->shortmsg_log_mdl->update($log);
	        return success($return_msg);
	    }else{
	        return error_msg(1,'发送失败');
	    }
	}
	
	
	
	/*
	 * 生成用户唯一标识UUID
	 */
	private function createUUID(){
	    if (function_exists('com_create_guid')){
	        return com_create_guid();
	    }else{
	        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
	        $charid = strtoupper(md5(uniqid(rand(), true)));
	        $hyphen = chr(45);// "-"
	        $uuid = chr(123)// "{"
	        .substr($charid, 0, 8).$hyphen
	        .substr($charid, 8, 4).$hyphen
	        .substr($charid,12, 4).$hyphen
	        .substr($charid,16, 4).$hyphen
	        .substr($charid,20,12)
	        .chr(125);// "}"
	        return $uuid;
	    }
	}
	
	//生成字母数字混合随机送
	private function getRandomString($len, $chars=null)
	{
	    if (is_null($chars)){
	        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	    }
	    mt_srand(10000000*(double)microtime());
	    for ($i = 0, $str = '', $lc = strlen($chars)-1; $i < $len; $i++){
	        $str .= $chars[mt_rand(0, $lc)];
	    }
	    return $str;
	}
	
	//回调到应用服务器记录状态
	private function sign_server($data,$url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SAFE_UPLOAD, true); //  PHP 5.6.0 后必须开启
        curl_setopt($ch, CURLOPT_POSTFIELDS, array('params'=>json_encode($data)));
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
         
        return curl_exec($ch);
	}
}
	

