<?php

class NLF_Router extends CI_Router {

	/**
	 * Current class name
	 *
	 * @var string
	 * @access public
	 */
	//var $suite = '';
	public function fetch_suite() {
		return $this->suite;
	}

	/**
	 * 重写验证请求(non-PHPdoc)
	 *
	 * @see CI_Router::_validate_request()
	 */
    protected function _validate_request($segments)
    {
        $c = count($segments);
        $suite = "";
        // Loop through our segments and return as soon as a controller
        // is found or when such a directory doesn't exist
        $i = 0;
        while ($c -- > 0) {
            $test = $this->directory . ucfirst($this->translate_uri_dashes === TRUE ? str_replace('-', '_', $segments[$i]) : $segments[$i]);
            
            if ($c == count($segments) - 1 && substr($segments[$i], 0, 1) == "_") {
                $suite = str_replace('_', '', $segments[$i]);
                $i ++;
                continue;
            }
            
            if ((! file_exists(APPPATH . 'controllers/' . $test . '.php') 
                && is_dir(APPPATH . 'controllers/' . $this->directory . $segments[$i])) 
                || (! file_exists(NONPHARPATH . "suites/modules/" . $suite . '/controllers/' . $test . '.php') 
                && is_dir(NONPHARPATH . "suites/modules/" . $suite . '/controllers/' . $this->directory . $segments[$i]))) {
                $this->set_directory($segments[$i], TRUE);
                $i ++;
                continue;
            }
            return $segments;
        }
        
        // This means that all segments were actually directories
        return $segments;
    }

	// --------------------------------------------------------------------

	/**
	 * 重写获取link request
	 *
	 * Takes an array of URI segments as input and sets the class/method
	 * to be called.
	 *
	 * @used-by	CI_Router::_parse_routes()
	 *
	 * @param array $segments
	 * @return void
	 */
    protected function _set_request($segments = array())
    {
        $segments = $this->_validate_request($segments);
        // If we don't have any segments left - try the default controller;
        // WARNING: Directories get shifted out of the segments array!
        if (empty($segments)) {
            $this->_set_default_controller();
            return;
        }
        
        if ($this->translate_uri_dashes === TRUE) {
            $segments[0] = str_replace('-', '_', $segments[0]);
            if (isset($segments[1])) {
                $segments[1] = str_replace('-', '_', $segments[1]);
            }
        }
        // 有suite
        $c = count($segments);
        if (substr($segments[0], 0, 1) == "_") {
            $this->set_suite(substr($segments[0], 1));
            $i = 0;
            for ($i = 1; $i < $c; $i ++) {
                $test = $this->directory . ucfirst($this->translate_uri_dashes === TRUE ? str_replace('-', '_', $segments[$i]) : $segments[$i]);
                // error_log(SUITEPATH . $this->suite . '/controllers/'. $test . '.php');
                if (file_exists(APPPATH . 'controllers/' . $test . '.php') || file_exists(NONPHARPATH . "suites/modules/" . $this->suite . '/controllers/' . $test . '.php')) {
                    // $this->set_directory($segments[$i], TRUE);
                    $this->set_class($segments[$i]);
                    break;
                }
            }
            $i ++;
            // $this->set_class($segments[1]);
            if (isset($segments[$i])) {
                $this->set_method($segments[$i]);
            } else {
                $segments[$i] = 'index';
            }
            array_unshift($segments, NULL);
            unset($segments[0]);
            unset($segments[1]);
            // unset($segments[2]);
            $cnt_dir = substr_count($this->directory, "/");
            for ($i = 2; $i < $cnt_dir + 2; $i ++) {
                unset($segments[$i]);
            }
        } else { // 冇suite
            
            for ($i = 0; $i < $c; $i ++) {
                if (file_exists(APPPATH . 'controllers/' . ucfirst($segments[$i]) . '.php')) {
                    $this->set_class(ucfirst($segments[$i]));
                    if (isset($segments[$i + 1])) {
                        $this->set_method($segments[$i + 1]);
                    } else {
                        $segments[$i + 1] = 'index';
                    }
                    break;    
                }
            }
            
            array_unshift($segments, NULL);
            unset($segments[0]);
        }
        $this->uri->rsegments = $segments;
    }

	// --------------------------------------------------------------------
	/**
	 * Set the suite name
	 *
	 * @access public
	 * @param
	 *        	string
	 * @return void
	 */
	function set_suite($suite) {
		$this->suite = str_replace ( array (
				'/',
				'.'
		), '', $suite );
	}
	

	// --------------------------------------------------------------------
	
	/**
	 * Get suite name
	 * 
	 * @return String suite name
	 */
	function get_suite(){
	    return $this->suite;
	}

}