<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/*
 * 使用fastdfs保存图片
 */
class CecrmFastDFS
{
    private $fdfs;
    private $tracker;
    private $storage;
    
    function __construct(){
        $this->fdfs = new FastDFS();
        $this->tracker = $this->fdfs->tracker_get_connection();
        if(!$this->fdfs->active_test($this->tracker))
        {
            $data = "errno: " . $this->fdfs->get_last_error_no() . ", error info: " . $this->fdfs->get_last_error_info();
            return $data;
            exit(1);
        }
        $this->storage = $this->fdfs->tracker_query_storage_store();
        
        if(!$this->storage)
        {
            $data = "errno: " . $this->fdfs->get_last_error_no() . ", error info: " . $this->fdfs->get_last_error_info();
            return $data;
            exit(1);
        }
    }
    
    function __destruct(){
        $this->fdfs->tracker_close_all_connections();
    }
    
    //上传到fastdfs
    public function upload_fastdfs($filename1){
        if(!$this->storage)
        {
            $data = "errno: " . $this->fdfs->get_last_error_no() . ", error info: " . $this->fdfs->get_last_error_info();
            return $data;
            exit(1);
        }
        
        $original_file1 = $_SERVER["DOCUMENT_ROOT"].'/'.$filename1;
        
        $original_uploaded_info = $this->fdfs->storage_upload_by_filename($original_file1, null, array(), null, $this->tracker, $this->storage);
        $data = $original_uploaded_info;
        return $data;
    }
    
    //批量上传
    public function upoad_batch($filename_arr){
        if(!$this->storage)
        {
            $data = "errno: " . $this->fdfs->get_last_error_no() . ", error info: " . $this->fdfs->get_last_error_info();
            return $data;
            exit(1);
        }
        if (!is_array($filename_arr)){
            $data = '不是array';
            return $data;
            exit(1);
        }
        foreach ($filename_arr as $re){
            $original_file1 = $_SERVER["DOCUMENT_ROOT"].'/'.$re;
            $original_uploaded_info = $this->fdfs->storage_upload_by_filename($original_file1, null, array(), null, $this->tracker, $this->storage);
            $data[] = $original_uploaded_info;
        }
        return $data;
    }
    
    //上传关联缩略图
    public function upload_thumb($original_uploaded_info,$filename2,$size='_thumb'){
        if($original_uploaded_info)
        {
            $group_name = $original_uploaded_info['group_name'];
            $remote_filename = $original_uploaded_info['filename'];
            $original_file2 = $_SERVER["DOCUMENT_ROOT"].'/'.$filename2;
            $thumbnail_info = $this->fdfs->storage_upload_slave_by_filename($original_file2, $group_name, $remote_filename, $size);
        
            $data = $thumbnail_info;
        }else{
            $data = false;
        }
        return $data;
    }
    
    //删除图片
    public function delete_fastdfs($file_id){
        $data = $this->fdfs->storage_delete_file1($file_id);
        return $data;
    }
    
    //获取文件情况
    public function get_fastdfs_file($file_id){
        $data = $this->fdfs->get_file_info1($file_id);
        return $data;
        
    }
    
    //生成缩略图
    public function img_thumb($file,$width,$hegiht){
        require_once (dirname(__FILE__).'/../../system/libraries/Image_lib.php');
        
        //生成缩略图
        $config['image_library'] = 'gd2';
        $config['source_image'] = $file;
        $config['create_thumb'] = TRUE;
        $config['maintain_ratio'] = TRUE;
        
        $image_lib = new CI_Image_lib($config);
        
        $img_info = $image_lib->get_image_properties($file,true);
        //                 var_dump($img_info);
        //                 exit;
        $new_size = $this->getNewSize($width, $hegiht, $img_info);
        
        
        $image_lib->width = $new_size['width'];
        $image_lib->height = $new_size['height'];
        $image_lib->quality = '100';
        
        
        $image_lib->resize();
        
        return $this->get_thumb_name($file);
    }
    
    //获取缩略图名称
    public function get_thumb_name($file){
        $thumb = trim(strstr($file, ".",true),'.').'_thumb'.strstr($file, ".");
        return $thumb;
    }
    
    /* 内部使用的私有方法，返回等比例缩放的图片宽度和高度，如果原图比缩放后的还小保持不变 */
    private function getNewSize($width, $height, $imgInfo){
        $size["width"] = $imgInfo["width"];          //原图片的宽度
        $size["height"] = $imgInfo["height"];        //原图片的高度
         
        if($width < $imgInfo["width"]){
            $size["width"]=$width;             		 //缩放的宽度如果比原图小才重新设置宽度
        }
        if($height < $imgInfo["height"]){
            $size["height"] = $height;            	 //缩放的高度如果比原图小才重新设置高度
        }
        /* 等比例缩放的算法 */
        if($imgInfo["width"]*$size["width"] < $imgInfo["height"] * $size["height"]){
            $size["height"] = round($imgInfo["height"]*$size["width"]/$imgInfo["width"]);
        }else{
            $size["width"] = round($imgInfo["width"]*$size["height"]/$imgInfo["height"]);
        }
    
        return $size;
    }
}
?>
