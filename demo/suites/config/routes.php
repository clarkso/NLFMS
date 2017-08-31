<?php  defined('BASEPATH') or exit('No direct script access allowed');

/**
 * DEMO module
 *
 */
// public
$route['demo/sitemap.xml'] = 'sitemap/xml';
$route['demo/demo(/:any)?'] = 'api/load$1';
$route['demo(/:any)?'] = 'Suites/modules/demo/controllers/api/index$1';
?>