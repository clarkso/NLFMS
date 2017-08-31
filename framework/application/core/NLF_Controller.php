<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * 前台控制器基类
 *
 * @package 9thleaf
 *         
 * @subpackage core
 * @category core
 * @author Clark So
 *        
 * @link 全称Ninth Leaf Frontend Controller
 */
abstract class NLF_Controller extends CI_Controller
{

    /**
     * 构造函数
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->load->library('session');
        $app_info = array();
        
        // 判断是否第一次进入
        if (config_item('lock_domains')) {
            $this->load->database("default", TRUE);
            if ($this->session->userdata('app_info') == null || $this->session->userdata('app_info')['site_url'] !== base_url()) {
                // if ($this->load->database("default", TRUE)) {
                // Redirect("_install/install/database");
                // exit();
                // }
                $this->load->model('app_info_mdl');
                $countapp = $this->app_info_mdl->count_app_info();
                // 检测是否未安装
                if ($countapp == 0) {
                    // 判断有否安装组件
                    if (Suites::suite_exists("INSTALL")) {
                        redirect("_install/install");
                        exit();
                    } else {
                        redirect("contact_us");
                        exit();
                    }
                }
                $app_info = $this->app_info_mdl->get_app_info(base_url());
                
                // 如果没有找到分站，直接进入原始首页
                if (count($app_info) == 0) {
                    $app_info = $this->app_info_mdl->load(0);
                    redirect($app_info['site_url']);
                    return;
                }
                
                $app_id = $app_info['id'];
                $this->session->set_userdata('app_info', $app_info);
            }
        }
    }

    /**
     * 弹出窗
     *
     * @param unknown $msg            
     * @param string $returnurl            
     * @param string $auto            
     * @param string $type            
     */
    public function showMessage($msg, $returnurl = '', $auto = TRUE, $type = TRUE)
    {
        // 判断是否有返回路径
        if ($returnurl == '') {
            $returnurl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : site_url();
        } else {
            if (strpos($returnurl, 'history.') === false) {
                $returnurl = strpos($returnurl, 'http') !== false ? $returnurl : site_url($returnurl);
            }
        }
        $this->load->view('message', array(
            'msg' => $msg,
            'returnurl' => $returnurl,
            'auto' => $auto,
            'type' => $type
        ));
        // echo $this->output->get_output();
        // exit();
    }
}

abstract class Api_Controller extends CI_Controller
{

    var $t;

    var $s;

    var $p;
    // 參數
    var $n;
    // 分頁信息
    var $return;

    /**
     * 构造函数
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->load->database();
        
        date_default_timezone_set("Asia/Shanghai");
        if (config_item('lock_domains')) {
            if ($this->session->userdata('app_info') == null || $this->session->userdata('app_info')['site_url'] !== base_url()) {
                
                $this->load->model('app_info_mdl');
                $app_info = $this->app_info_mdl->get_app_info(base_url());
                if (count($app_info) == 0) {
                    $app_info = $this->app_info_mdl->load(0);
                    redirect($app_info['site_url']);
                    return;
                }
                
                $app_id = $app_info['id'];
                $this->session->set_userdata('app_info', $app_info);
                // exit();
            }
        }
        
        // 定义返回值格式
        $this->return['responseMessage'] = array(
            'messageType' => null,
            'errorType' => null,
            'errorMessage' => null
        );
        
        // 获取客户端提交参数
        $this->t = $this->input->post('token', 0);
        $this->s = $this->input->get_post('d', 0);
        $this->p = json_decode($this->input->get_post('params', 0), true);
        $page = json_decode($this->input->get_post('pageinfo', 0), true);
        
        // print_r($page);
        
        if (isset($page['perPage'])) {
            if (! $page['perPage'])
                $page['perPage'] = 0;
        } else {
            $page['perPage'] = 0;
        }
        
        if (isset($page['currPage'])) {
            if (! $page['currPage']) {
                $page['currPage'] = 1;
            } else {
                $page['currPage'] = $page['currPage'] + 0;
            }
        } else {
            $page['currPage'] = 1;
        }
        
        if (isset($page['orderBy'])) {
            if (! $page['orderBy'])
                $page['orderBy'] = '';
        } else {
            $page['orderBy'] = '';
        }
        
        $this->n = $page;
        
        $this->_check_token();
    }
    
    // token验证
    private function _check_token()
    {
        $this->return['responseMessage'] = array(
            'messageType' => 'success',
            'errorType' => null,
            'errorMessage' => null
        );
    }
    
    // session验证
    private function _check_session()
    {
        $this->return['responseMessage'] = array(
            'messageType' => 'success',
            'errorType' => null,
            'errorMessage' => null
        );
    }
    
    // 检验参数
    public function _check_prams($params, $needparams)
    {
        if (! $params || is_int($params)) {
            $return['responseMessage'] = array(
                'messageType' => 'error',
                'errorType' => '2003',
                'errorMessage' => '缺少参数'
            );
            print_r(json_encode($return));
            exit();
        }
        if (! is_array($params)) {
            $params[] = $params;
        }
        
        if (! is_array($needparams)) {
            $needparams[] = $needparams;
        }
        foreach ($needparams as $v) {
            
            if (! in_array($v, array_keys($params))) {
                $return = $this->return;
                $return['responseMessage'] = array(
                    'messageType' => 'error',
                    'errorType' => '2003',
                    'errorMessage' => '缺少参数'
                );
                print_r(json_encode($return));
                exit();
            }
        }
    }
    
    // 检查登录状态
    public function check_login()
    {
        if ($this->session->userdata("account_id") == null || $this->session->userdata("account_id") == "") {
            $return['responseMessage'] = array(
                'messageType' => 'error',
                'errorType' => '1004',
                'errorMessage' => '用户未登录'
            );
            print_r(json_encode($return));
            exit();
        }
    }
}

?>