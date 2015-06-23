<?php

defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'welcome';
$route['404_override'] = 'findcontent';
$route['translate_uri_dashes'] = TRUE;

$route['admin'] = 'admin/dashboard';

/* End of file routes.php */
/* Location: ./application/config/routes.php */
