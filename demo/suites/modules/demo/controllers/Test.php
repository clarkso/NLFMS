<?php
// if (! defined ( 'BASEPATH' ))
// 	exit ( 'No direct script access allowed' );

// 	require_once (dirname ( __FILE__ ) . '/../config/configurations.php');
/**
 * CRM进入控制器类
 *
 * @package
 *
 * @subpackage core
 * @category core
 * @author
 *
 * @link
 *
 */
class Test extends Api_Controller {
	/**
	 * 构造函数
	 *
	 * @access public
	 * @return void
	 */


	public function __construct() {
		parent::__construct ();

	}
	
	
	
	/**
	 * 登錄
	 */
	public function Index() {
	   //$this->load->view('apitest');
	   echo "testing";
	}
	
    public function admin(){
        $this->load->view('usercenter/index.html');
    }
    
    public function test_pageinfo(){
        $this->load->library('pagination');
        
        $config['base_url'] = 'http://example.com/index.php/test/page/';
        $config['total_rows'] = 200;
        $config['per_page'] = 20;
        
        $this->pagination->initialize($config);
        
        echo $this->pagination->create_links();
    }
    function uploadAttach()
        {/*{{{*/
        $ret = array();
        $ret['errorcode'] = 0;
        $ret['errormsg'] = '';
        if(!$_FILES || false == isset($_FILES["file"]))
        {
            $ret['errorcode'] = 1;
            $ret['errormsg'] = "ERROR:upFile is not set";
            return $ret;
        }
        
        $file = $_FILES["file"];
        if (false == isset($file['tmp_name']) || false == is_file($file['tmp_name']))
        {
            $ret['errorcode'] = 2;
            $ret['errormsg'] = "tmp_name is not file";
            return $ret;
        }
        if (0 == filesize($file['tmp_name']))
        {
            $ret['errorcode'] = 3;
            $ret['errormsg'] = "tmp_name filesize is 0";
            return $ret;
        }
        
        $curlFile = new CurlFile($file['tmp_name'], $file['type'], $file['name']);
        $fileSuffix = $this->getSuffix($curlFile->getPostFilename());
        
        $ret['file'] = $file;
        $ret['fileId'] = $this->uploadToFastdfs($curlFile, $fileSuffix);
        return $ret;
        }/*}}}*/
    
    //获取后缀
    function getSuffix($fileName)
    {/*{{{*/
    preg_match('/\.(\w+)?$/', $fileName, $matchs);
    return isset($matchs[1])?$matchs[1]:'';
    }/*}}}*/
    
    //上传文件到fastdfs
    function uploadToFastdfs(CurlFile $file, $fileSuffix)
    {/*{{{*/
        $fdfs = new FastDFS();
        $tracker = $fdfs->tracker_get_connection();
        $fileId = $fdfs->storage_upload_by_filebuff1(file_get_contents($file->getFilename()), $fileSuffix)."errno: " . $fdfs->get_last_error_no() . ", error info: " . $fdfs->get_last_error_info();;
        $fdfs->tracker_close_all_connections();
        return $fileId;
    }/*}}}*/
    
    function start()
    {
        $ret = $this->uploadAttach();
        echo json_encode($ret);
    }
    
    public function test_fastdfs(){
        $fdfs = new FastDFS();
        $tracker = $fdfs->tracker_get_connection();
        //         echo __FILE__;
        if(!$fdfs->active_test($tracker))
        {
            $data = "errno: " . $fdfs->get_last_error_no() . ", error info: " . $fdfs->get_last_error_info();
            echo json_encode($data);
            exit(1);
        }
        $storage = $fdfs->tracker_query_storage_store();
    
        if(!$storage)
        {
            $data = "errno: " . $fdfs->get_last_error_no() . ", error info: " . $fdfs->get_last_error_info();
            echo json_encode($data);
            exit(1);
        }
    
        $original_file1 = $_SERVER["DOCUMENT_ROOT"].'/'.$_GET['file1'];
        $original_file2 = $_SERVER["DOCUMENT_ROOT"].'/'.$_GET['file2'];
    
        $original_uploaded_info = $fdfs->storage_upload_by_filename($original_file1, null, array(), null, $tracker, $storage);
        //         var_dump($fdfs->get_last_error_info());
        //         var_dump($original_uploaded_info);
        $data[] = $original_uploaded_info;
    
        if($original_uploaded_info)
        {
            $group_name = $original_uploaded_info['group_name'];
            $remote_filename = $original_uploaded_info['filename'];
    
            $thumbnail_info = $fdfs->storage_upload_slave_by_filename($original_file2, $group_name, $remote_filename, '_200X200');
    
            $data[] = $thumbnail_info;
        }
        $fdfs->tracker_close_all_connections();
        echo json_encode($data);
    
    }
    
    public function del_fastdfs(){
        $fdfs = new FastDFS();
        $tracker = $fdfs->tracker_get_connection();
        //         echo __FILE__;
        if(!$fdfs->active_test($tracker))
        {
            $data = "errno: " . $fdfs->get_last_error_no() . ", error info: " . $fdfs->get_last_error_info();
            echo json_encode($data);
            exit(1);
        }
        $filename = $_GET['file'];
        $group = $_GET['group'];
        //         $storage = fastdfs_tracker_query_storage_store();
        //         if(!$storage)
            //         {
            //             $data = "errno: " . $fdfs->get_last_error_no() . ", error info: " . $fdfs->get_last_error_info();
            //             echo json_encode($data);
            //             exit(1);
            //         }
    
            $data = $fdfs->storage_delete_file($group,$filename);
    
    
            $fdfs->tracker_close_all_connections();
            echo json_encode($data);
    }
    
    public function upload_test(){
        $this->load->library('CecrmFastDFS.php','','cecrmFastDFS');
        $data = $this->cecrmFastDFS->upload_fastdfs($_GET['file']);
        //         echo json_encode($data);
        //         exit;
        $return_data[] = $this->cecrmFastDFS->upload_thumb($data,$_GET['file2']);
        //         echo json_encode($return_data);
        $return_data[] = $this->cecrmFastDFS->delete_fastdfs($data['group_name'].'/'.$data['filename']);
        $return_data[] = $this->cecrmFastDFS->get_fastdfs_file($data['group_name'].'/'.$data['filename']);
        echo json_encode($return_data);
    }
    
    public function thumb(){
        $this->load->library('CecrmFastDFS.php','','cecrmFastDFS');
        $data = $this->cecrmFastDFS->img_thumb($_GET['file'],$_GET['width'],$_GET['height']);
        //         echo json_encode($data);
        //         exit;
        //         echo json_encode($return_data);
        echo $data;
    }
    
    public function check_exists(){
        $this->load->library('CecrmFastDFS.php','','cecrmFastDFS');
    
        $return_data[] = $this->cecrmFastDFS->get_fastdfs_file($_GET['file']);
        echo json_encode($return_data);
    }
    
    public function delete(){
        $this->load->library('CecrmFastDFS.php','','cecrmFastDFS');
    
        $return_data[] = $this->cecrmFastDFS->delete_fastdfs($_GET['file']);
        echo json_encode($return_data);
    }
    
    public function check_redis(){
        $id = $this->input->get_post("id");
        $this->load->driver('cache', array('adapter' => 'redis'));
        
        echo $this->cache->get($id);
     
    }
    
    public function abc(){
        $mobile = $this->input->get_post("mobile");
       $headers = array(
            "Authorization:  Bearer 232edd01a58e1cfab5f61518fdbe3d756d6913d1"
        );
        
        
        
        // $interface = 'http://cecrm.9-leaf.com/index.php/_api/usercenter/login';
        $interface = 'http://192.168.10.139/cecrm/index.php/_api/usercenter/login';
        $data = array(
            'mobile' => $mobile,
            'password' => '7c4a8d09ca3762af61e59520943dc26494f8941b',
            'returnUrl' => 'http://192.168.10.139/cecrm/index.php/_api/account/record_login_status'
        );
        
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $interface);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);  //设置头信息的地方
        curl_setopt($ch, CURLOPT_HEADER, false);    //不取得返回头信息
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SAFE_UPLOAD, true); //  PHP 5.6.0 后必须开启01
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
//         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
    }
    
    private function sign_server($data,$url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SAFE_UPLOAD, true); //  PHP 5.6.0 后必须开启
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        // 	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
         
        curl_exec($ch);
    }
    
    public function set_redis(){
        $id = $this->input->get_post("account_data");
        $this->load->driver('cache');
        $this->cache->memcached->is_supported();
        $this->cache->save('account_data','asdas',60*60*2);
    }
    
    
    public function get_paygateway(){
        echo date('Y-m-d H:i:s');
        exit;
        $data1 = $_GET;
        $data2 = $_POST;
        $this->load->database();
        $this->db->set('img_url',json_encode($data1));
        $this->db->set('url',json_encode($data2));
        $this->db->insert('ad_info');
    }
    
    public function test_redis(){
        $this->load->driver('cache', array('adapter' => 'redis'));
     
        if ( ! $foo = $this->cache->get('foo'))
        {
             echo 'Saving to the cache!<br />';
             $foo = 'foobarbaz!';
         
             // Save into the cache for 5 minutes
             $this->cache->save('foo', $foo, 5*60);
        }else{
            $foo = $this->cache->get('foo');
            echo $foo;
        }
    }
}
?>