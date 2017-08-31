<?php

/**
 * 套件类
 * @author clarkso
 *
 */
class Suite
{

    public $location;
    public $suite_name;

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
}

require_once BASEPATH . 'database/DB.php';

define("SUITEPATH", NONPHARPATH . "suites/modules/");

/**
 * 套件应用类
 * @author clarkso
 *
 */
class Suites
{

    public $suite_list;
    public $suite_name;

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

    // ---------------------------------------------------------------

    /**
     * 获取套件列表
     */
    public static function get_suite_list()
    {
        if (! DB()->table_exists('9thleaf_suite')) {
            // Create alias table
            DB()->query('
					CREATE TABLE `9thleaf_suite` (
					  `id` int NOT NULL AUTO_INCREMENT,
					  `suite_name` varchar(100) NOT NULL,
					  `suite_id` int NOT NULL,
					  `path` varchar(300) NOT NULL,
					  `type` enum("park", "redirect") NOT NULL DEFAULT "park",
					  PRIMARY KEY (`id`),
					  KEY `suite_name` (`suite_name`),
					  UNIQUE `unique` (`suite_name`)
					) ENGINE=Memory DEFAULT CHARSET=utf8; ');
        } else {
            $suite_list = DB()->get('9thleaf_suite')->result();
        }
    }

    // ---------------------------------------------------------------

    /**
     * 获取套件路径
     *
     * @param unknown $classname
     * @return string
     */
    public static function get_suite_path($suite_name, $classname)
    {
        // TODO:返回每个套件的路径，暂时写死测试

        $path = SUITEPATH . $suite_name . "/controllers/" . $classname . ".php";

        if (file_exists($path)) {
            return $path;
        }
        
        return APPPATH . 'controllers/' . $classname . '.php';
    }

    // ---------------------------------------------------------------

    /**
     * 判断组件是否存在
     *
     * @param unknown $suite_name
     */
    public static function suite_exists($suite_name)
    {
        $suite_path = SUITEPATH . $suite_name;
        
        return file_exists($suite_path);
    }
}

?>