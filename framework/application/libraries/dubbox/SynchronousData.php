<?php
class SynchronousData extends JsonRpcService {
    
    private $CI;
    public function __construct(){
        parent::__construct ();
        $this->CI = & get_instance();
        $this->CI->load->helper('usercenter_return');
    }
    
    /** @JsonRpcMethod*/
    public function to_user_base($id = 0){
        //TODO 同步到用户中心
         
    }
    
    /** @JsonRpcMethod*/
    public function to_user_info($id = 0){
        //TODO 同步到用户中心
         
    }
    
    /** @JsonRpcMethod*/
    public function from_user_base(){
    
        try
        {
            $this->CI->load->model('usercenter/API_user_mdl');
            $data = $this->CI->API_user_mdl->get_user_base();
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
    
    /** @JsonRpcMethod*/
    public function from_user_info(){
        try
        {
            $this->CI->load->model('usercenter/API_user_mdl');
            $data = $this->CI->API_user_mdl->get_user_info();
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
}
	

