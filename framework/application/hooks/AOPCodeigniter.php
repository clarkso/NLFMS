<?php

class AOPCodeigniter
{

    private $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
    }

    public function applyBeforeAspects()
    {
        $uriSegments = $this->CI->uri->segment_array();
        
        $controller = $uriSegments[1];
        $function = $uriSegments[2];
        
        $doc = new DOMDocument();
        $doc->load(NONPHARPATH."suites/config/aop.xml");
        
        $aopAspects = $doc->getElementsByTagName("aopPointcut");
        
        foreach ($aopAspects as $aspect) {
            $expr = $aspect->getAttribute("expression");
            preg_match('/[(.*?)]/s', $expr, $match);
            if (isset($match[1])) {
                $exprComponents = explode(".", $match[1]);
                $controllerExpr = "/^" . str_replace("*", "[a-zA-Z0-9]+", $exprComponents[0]) . "$/";
                $functionExpr = "/^" . str_replace("*", "[a-zA-Z0-9]+", $exprComponents[1]) . "$/";
                
                preg_match($controllerExpr, $controller, $controllerMatch);
                preg_match($functionExpr, $function, $functionMatch);
                
                if (count($controllerMatch) > 0 && count($functionMatch) > 0) {
                    $beforeAspects = $aspect->getElementsByTagName("aopBefore");
                    
                    foreach ($beforeAspects as $beforeAspect) {
                        $refClass = $beforeAspect->getAttribute("ref-class");
                        $refMethod = $beforeAspect->getAttribute("method");
                        
                        $classObject = new $refClass();
                        $classObject->$refMethod();
                    }
                }
            }
        }
    }
}