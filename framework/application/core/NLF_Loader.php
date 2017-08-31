<?php

if (! defined('BASEPATH'))
    exit('No direct script access allowed');

    // ------------------------------------------------------------------------

/**
 * Loader 扩展CI_Loader
 *
 * 用于支持多皮肤
 *
 * @package 9thleaf
 * @subpackage core
 * @category core
 * @author Clark So
 *
 * @link
 *
 */
class NLF_Loader extends CI_Loader
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
    }

    // ------------------------------------------------------------------------

    /**
     * 切换视图路径
     *
     * @access public
     * @return void
     */
    public function switch_theme($theme = 'default')
    {
        $this->_ci_view_paths = array(
            FCPATH . 'templates/' . $theme . '/' => TRUE,
            NONPHARPATH."suites/modules/".SUITENAME."/views/"
        );
    }

    // ------------------------------------------------------------------------
    
    /**
     * 检查model是否存在
     */
    public function has_model($model, $name = '', $db_conn = FALSE){
        foreach ($this->_ci_model_paths as $mod_path)
        {
            if ( ! file_exists($mod_path.'models/'.$path.$model.'.php'))
            {
                continue;
            }
        
            require_once($mod_path.'models/'.$path.$model.'.php');
            if ( class_exists($model, FALSE))
            {
                return true;
            }
            break;
        }
        return false;
    }
}

/* End of file My_Loader.php */
/* Location: ./application/core/My_Loader.php */