<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|   example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|   http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|   $route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|   $route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|   $route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples: my-controller/index -> my_controller/index
|       my-controller/my-method -> my_controller/my_method
*/

$route['default_controller'] = 'clients';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['admin']  = "admin/dashboard";
$route['artem']  = "admin/dashboard_artem";
// Misc controller rewrites
$route['admin/access_denied']  = "admin/misc/access_denied";
$route['admin/not_found']  = "admin/misc/not_found";

// Staff rewrites
$route['admin/profile']  = "admin/staff/profile";
$route['admin/profile/(:num)']  = "admin/staff/profile/$1";
$route['admin/tasks/view/(:any)']  = "admin/tasks/index/$1";

// Items search rewrite
$route['admin/items/search'] = 'admin/invoice_items/search';

/* Clients links and routes */
// // In case if client access directly to url without the arguments redirect to clients url
$route['/']  = "clients";

// Deprecated
$route['viewinvoice/(:num)/(:any)']  = "invoice/index/$1/$2";

// New url from version 2.0.
$route['invoice/(:num)/(:any)']  = "invoice/index/$1/$2";

// Deprecated
$route['viewestimate/(:num)/(:any)']  = "estimate/index/$1/$2";

// New url from version 2.0
$route['estimate/(:num)/(:any)']  = "estimate/index/$1/$2";

$route['subscription/(:any)']  = "subscription/index/$1";

// Deprecated
$route['viewproposal/(:num)/(:any)']  = "proposal/index/$1/$2";
// New url from version 2.0
$route['proposal/(:num)/(:any)']  = "proposal/index/$1/$2";

// Available from version 2.0
$route['contract/(:num)/(:any)']  = "contract/index/$1/$2";
$route['survey/(:num)/(:any)']  = "survey/index/$1/$2";

// Deprecated
//$route['knowledge_base']  = "knowledge_base/index";
//$route['knowledge_base/(:any)']  = "knowledge_base/index/$1";

// Available from version 2.0
$route['knowledge-base']  = "knowledge_base/index";
$route['knowledge-base/search']  = "knowledge_base/search";
$route['knowledge-base/article']  = "knowledge_base/index";
$route['knowledge-base/article/(:any)']  = "knowledge_base/article/$1";
$route['knowledge-base/category']  = "knowledge_base/index";
$route['knowledge-base/category/(:any)']  = "knowledge_base/category/$1";

// Deprecated
if(strpos($_SERVER['REQUEST_URI'],'add_kb_answer') === false) {
    $route['knowledge-base/(:any)']  = "knowledge_base/article/$1";
    $route['knowledge_base/(:any)']  = "knowledge_base/article/$1";
    $route['clients/knowledge_base/(:any)']  = "knowledge_base/article/$1";
    $route['clients/knowledge-base/(:any)']  = "knowledge_base/article/$1";
}
// $route['knowledge-base/(:any)']  = "knowledge_base/index/$1";
$route['terms-and-conditions']  = "clients/terms_and_conditions";
$route['privacy-policy']  = "clients/privacy_policy";

if(file_exists(APPPATH.'config/my_routes.php')){
    include_once(APPPATH.'config/my_routes.php');
}
