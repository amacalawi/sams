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
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

/*
| ------------
| # Auth Route
| ------------
*/
$route['login']['get'] = 'AuthController/index';
$route['auth/login']['post'] = 'AuthController/login';
$route['auth/register']['post'] = 'AuthController/register';
$route['auth/register']['get'] = 'AuthController/register';
$route['logout']['get'] = 'AuthController/logout';

$route['dtr'] = 'DTRController/dtr';
$route['get-dtr'] = 'DTRController/get_dtr';
$route['cgi/(:any)'] = 'PageController/view';
$route['execute'] = 'DTRController/execute';
/*
|------------------
| # Users Route
|------------------
*/
$route['users']['get'] = 'UsersController/index';

$route['users/listing']['post']  = 'UsersController/listing';
$route['users/listing']['get']  = 'UsersController/listing';

$route['users/delete/(:num)']['delete']  = 'UsersController/delete/$1';
$route['users/delete']['post']  = 'UsersController/delete';

$route['users/add']['post']  = 'UsersController/add';

$route['users/edit/(:num)']['post']  = 'UsersController/edit/$1';
$route['users/update/(:num)']['post']  = 'UsersController/update/$1';

$route['users/import']['get']  = 'UsersController/import';
$route['users/import']['post']  = 'UsersController/import';

$route['users/export']['get']  = 'UsersController/export';
$route['users/export']['post']  = 'UsersController/export';

/*
| -------------------
| # Privileges Routes
| -------------------
*/
$route['privileges']['get'] = 'PrivilegesController/index';

$route['privileges/listing']['post']  = 'PrivilegesController/listing';
$route['privileges/listing']['get']  = 'PrivilegesController/listing';

$route['privileges/trash']['get']  = 'PrivilegesController/trash';
$route['privileges/remove']['post']  = 'PrivilegesController/remove';
$route['privileges/remove/(:num)']['post']  = 'PrivilegesController/remove/$1';
$route['privileges/restore/(:num)']['post']  = 'PrivilegesController/restore/$1';

$route['privileges/delete/(:num)']['delete']  = 'PrivilegesLevelsController/delete/$1';
$route['privileges/delete']['post']  = 'PrivilegesController/delete';

$route['privileges/add']['post']  = 'PrivilegesController/add';

$route['privileges/edit/(:num)']['post']  = 'PrivilegesController/edit/$1';
$route['privileges/update/(:num)']['post']  = 'PrivilegesController/update/$1';

/*
|---------------------------
| # Privileges Levels Routes
|---------------------------
*/
$route['privileges-levels']['get'] = 'PrivilegesLevelsController/index';

$route['privileges-levels/listing']['post']  = 'PrivilegesLevelsController/listing';
$route['privileges-levels/listing']['get']  = 'PrivilegesLevelsController/listing';

$route['privileges-levels/trash']['get']  = 'PrivilegesLevelsController/trash';
$route['privileges-levels/remove']['post']  = 'PrivilegesLevelsController/remove';
$route['privileges-levels/remove/(:num)']['post']  = 'PrivilegesLevelsController/remove/$1';
$route['privileges-levels/restore/(:num)']['post']  = 'PrivilegesLevelsController/restore/$1';

$route['privileges-levels/delete/(:num)']['delete']  = 'PrivilegesLevelsController/delete/$1';
$route['privileges-levels/delete']['post']  = 'PrivilegesLevelsController/delete';

$route['privileges-levels/add']['post']  = 'PrivilegesLevelsController/add';

$route['privileges-levels/edit/(:num)']['post']  = 'PrivilegesLevelsController/edit/$1';
$route['privileges-levels/update/(:num)']['post']  = 'PrivilegesLevelsController/update/$1';

/*
|------------------
| # Modules Routes
|------------------
*/
$route['modules']['get'] = 'ModulesController/index';
$route['modules/listing']['post']  = 'ModulesController/listing';
$route['modules/listing']['get']  = 'ModulesController/listing';

$route['modules/trash']['get']  = 'ModulesController/trash';
$route['modules/remove']['post']  = 'ModulesController/remove';
$route['modules/remove/(:num)']['post']  = 'ModulesController/remove/$1';
$route['modules/restore/(:num)']['post']  = 'ModulesController/restore/$1';

$route['modules/delete']['post']  = 'ModulesController/delete';
$route['modules/delete/(:num)']['delete']  = 'ModulesController/delete/$1';

$route['modules/add']['post']  = 'ModulesController/add';

$route['modules/edit/(:num)']['post']  = 'ModulesController/edit/$1';
$route['modules/update/(:num)']['post']  = 'ModulesController/update/$1';

$route['modules/import']['get']  = 'ModulesController/import';
$route['modules/import']['post']  = 'ModulesController/import';

$route['modules/export']['get']  = 'ModulesController/export';
$route['modules/export']['post']  = 'ModulesController/export';

/*
| -----------------
| # Dashboard Route
| -----------------
*/
$route['dashboard'] = 'PageController/index';

/*
|-----------------
| # Members Route
|-----------------
*/
$route['members']['get'] = 'MembersController/index';
$route['members/listing']['post']  = 'MembersController/listing';
$route['members/listing']['get']  = 'MembersController/listing';

$route['members/trash']['get']  = 'MembersController/trash';
$route['members/remove']['post']  = 'MembersController/remove';
$route['members/remove/(:num)']['post']  = 'MembersController/remove/$1';
$route['members/restore/(:num)']['post']  = 'MembersController/restore/$1';

$route['members/delete']['post']  = 'MembersController/delete';
$route['members/delete/(:num)']['delete']  = 'MembersController/delete/$1';

$route['members/add']['post']  = 'MembersController/add';
$route['members/add']['get']  = 'MembersController/add';

$route['members/edit/(:num)']['post']  = 'MembersController/edit/$1';
$route['members/update/(:num)']['post']  = 'MembersController/update/$1';

$route['members/import']['get']  = 'MembersController/import';
$route['members/import']['post']  = 'MembersController/import';

$route['members/export']['get']  = 'MembersController/export';
$route['members/export']['post']  = 'MembersController/export';

$route['members/check']['post']  = 'MembersController/check';

$route['members/upload_photo']['post']  = 'MembersController/upload_photo';

$route['members/search/(:any)']['get'] = 'MembersController/search/$1';

$route['members/export-members'] = 'MembersController/export_members';

$route['members/current-listing']['post']  = 'MembersController/current_listing';
$route['members/available-listing']['post']  = 'MembersController/available_listing';
$route['members/delete-members']['post']  = 'MembersController/delete_members';

/*
|-----------------
| # Group Members
|-----------------
*/
// $route['group-members/update/(:num)']['post']  = 'GroupMembersController/update/$1';

/*
| ----------------
| # Contacts Route
| ----------------
*/
$route['contacts']['get'] = 'ContactsController/index';

$route['contacts/listing']['post']  = 'ContactsController/listing';
$route['contacts/listing']['get']  = 'ContactsController/listing';

$route['contacts/trash']['get']  = 'ContactsController/trash';
$route['contacts/delete/(:num)']['delete']  = 'ContactsController/delete/$1';
$route['contacts/delete']['post']  = 'ContactsController/delete';
$route['contacts/remove']['post']  = 'ContactsController/remove';
$route['contacts/remove/(:num)']['post']  = 'ContactsController/remove/$1';

$route['contacts/add']['post']  = 'ContactsController/add';

$route['contacts/edit/(:num)']['post']  = 'ContactsController/edit/$1';
$route['contacts/update/(:num)']['post']  = 'ContactsController/update/$1';

$route['contacts/import']['get']  = 'ContactsController/import';
$route['contacts/import']['post']  = 'ContactsController/import';

$route['contacts/export']['get']  = 'ContactsController/export';
$route['contacts/export']['post']  = 'ContactsController/export';

/*
| ----------------
| # Groups Route
| ----------------
*/
$route['groups']  = 'GroupsController/index';

$route['groups/listing']['post']  = 'GroupsController/listing';

$route['groups/trash']['get']  = 'GroupsController/trash';
$route['groups/remove']['post']  = 'GroupsController/remove';
$route['groups/remove/(:num)']['post']  = 'GroupsController/remove/$1';
$route['groups/restore/(:num)']['post']  = 'GroupsController/restore/$1';

$route['groups/delete/(:num)']['delete']  = 'GroupsController/delete/$1';
$route['groups/delete']['post']  = 'GroupsController/delete';

$route['groups/add']['post']  = 'GroupsController/add';

$route['groups/edit/(:num)']['post']  = 'GroupsController/edit/$1';
$route['groups/update/(:num)']['post']  = 'GroupsController/update/$1';

$route['groups/import']['get']  = 'GroupsController/import';
$route['groups/export']['get']  = 'GroupsController/export';
$route['groups/import']['post']  = 'GroupsController/import';
$route['groups/export']['post']  = 'GroupsController/export';

$route['groups/check']['post']  = 'GroupsController/check';

$route['group-members/remove']['post']  = 'GroupsController/group_members_remove';

/*
|-----------------
| # Levels Route
|-----------------
*/
$route['levels']  = 'LevelsController/index';

$route['levels/listing']['post']  = 'LevelsController/listing';

$route['levels/delete/(:num)']['delete']  = 'LevelsController/delete/$1';
$route['levels/delete']['post']  = 'LevelsController/delete';

$route['levels/add']['post']  = 'LevelsController/add';

$route['levels/edit/(:num)']['post']  = 'LevelsController/edit/$1';
$route['levels/update/(:num)']['post']  = 'LevelsController/update/$1';

$route['levels/import']['get']  = 'LevelsController/import';
$route['levels/export']['get']  = 'LevelsController/export';

/*
|-----------------
| # Types Route
|-----------------
*/
$route['types']  = 'TypesController/index';

$route['types/listing']['post']  = 'TypesController/listing';

$route['types/delete/(:num)']['delete']  = 'TypesController/delete/$1';
$route['types/delete']['post']  = 'TypesController/delete';

$route['types/add']['post']  = 'TypesController/add';

$route['types/edit/(:num)']['post']  = 'TypesController/edit/$1';
$route['types/update/(:num)']['post']  = 'TypesController/update/$1';

$route['types/import']['get']  = 'TypesController/import';
$route['types/export']['get']  = 'TypesController/export';

/*
|----------------
| # Messaging
|----------------
*/
$route['messaging/listing']['post'] = 'MessagingController/listing';
// $route['messaging/listing']['get'] = 'MessagingController/listing';
$route['messaging/new']['get'] = 'MessagingController/index';
$route['messaging/send']['post'] = 'MessagingController/send';
$route['messaging/bulk-send']['post'] = 'MessagingController/bulk_send';
$route['messaging/bulk-send/later']['post'] = 'SchedulerController/schedule';
$route['messaging/bulk-send/later']['get'] = 'SchedulerController/schedule';
$route['messaging/send-scheduled-messages']['get'] = 'SchedulerController/send';
$route['messaging/send-scheduled-messages']['post'] = 'SchedulerController/send';
$route['messaging/tracking']['get'] = 'MessagingController/tracking';
// $route['messaging/tracking-debug']['get'] = 'MessagingController/tracking_listing_grouped';
$route['messages/tracking-listing']['post'] = 'MessagingController/tracking_listing';
$route['messages/tracking-listing_grouped']['post'] = 'MessagingController/tracking_listing_grouped';
$route['messages/tracking-listing_grouped']['get'] = 'MessagingController/tracking_listing_grouped';
$route['messaging/resend-message/(:num)']['post'] = 'MessagingController/resend/$1';
$route['messaging/resend-message/(:num)']['get'] = 'MessagingController/resend/$1';

// $route['messaging/bulk-send']['get'] = 'MessagingController/bulk_send';
$route['messaging/inbox']['get'] = 'InboxController/index';
$route['messaging/inbox/update-status/(:any)']['post'] = 'InboxController/updateStatus/$1';
$route['messaging/inbox/(:any)']['post'] = 'InboxController/index/$1';
$route['messaging/outbox']['get'] = 'MessagingController/outbox';
$route['messaging/groups']['get'] = 'MessagingController/groups';
$route['messaging/configuration']['get'] = 'MessagingController/configuration';
$route['messaging/post-configuration']['post'] = 'MessagingController/postConfiguration';


$route['messaging/resend-all-message']['post'] = 'MessagingController/resend_all_message';
/*
| --------------------
| # Message Template
| --------------------
*/
$route['messaging/templates']['get'] = 'MessageTemplatesController/index';
$route['messaging/templates/listing']['post'] = 'MessageTemplatesController/listing';
$route['messaging/templates/add']['post'] = 'MessageTemplatesController/add';
$route['messaging/templates/edit/(:num)']['post'] = 'MessageTemplatesController/edit/$1';
$route['messaging/templates/update/(:num)']['post'] = 'MessageTemplatesController/update/$1';
$route['messaging/templates/trash']['get'] = 'MessageTemplatesController/trash';
$route['messaging/templates/remove/(:num)']['post'] = 'MessageTemplatesController/remove/$1';
$route['messaging/templates/restore/(:num)']['post'] = 'MessageTemplatesController/restore/$1';

/*
| ---------------
| # Schedules
| ---------------
*/
$route['schedules']['get'] = 'SchedulesController/index';
$route['schedules/listing']['post'] = 'SchedulesController/listing';
$route['schedules/listing']['get'] = 'SchedulesController/listing';
$route['schedules/add']['post'] = 'SchedulesController/add';
$route['schedules/edit/(:num)']['post'] = 'SchedulesController/edit/$1';
$route['schedules/update/(:num)']['post']  = 'SchedulesController/update/$1';
// $route['schedules/update/(:num)']['get']  = 'SchedulesController/update/$1';

$route['schedules/trash']['get']  = 'SchedulesController/trash';
$route['schedules/remove']['post']  = 'SchedulesController/remove';
$route['schedules/remove/(:num)']['post']  = 'SchedulesController/remove/$1';
$route['schedules/restore/(:num)']['post']  = 'SchedulesController/restore/$1';

$route['schedules/preset-messages']['get'] = 'PresetMessagesController/index';
$route['schedules/preset-messages/listing']['post'] = 'PresetMessagesController/listing';
$route['schedules/preset-messages/add']['post'] = 'PresetMessagesController/add';
$route['schedules/preset-messages/edit/(:num)']['post'] = 'PresetMessagesController/edit/$1';
$route['schedules/preset-messages/update/(:num)']['post'] = 'PresetMessagesController/update/$1';
$route['schedules/preset-messages/trash']['get'] = 'PresetMessagesController/trash';
$route['schedules/preset-messages/remove/(:num)']['post'] = 'PresetMessagesController/remove/$1';
$route['schedules/preset-messages/restore/(:num)']['post'] = 'PresetMessagesController/restore/$1';

/*
| ---------------
| # School Year
| ---------------
|
*/
$route['schoolyears']['get'] = 'SchoolyearController/index';
$route['schoolyears/listing']['post'] = 'SchoolyearController/listing';
$route['schoolyears/listing']['get'] = 'SchoolyearController/listing';
$route['schoolyears/add']['post'] = 'SchoolyearController/add';
$route['schoolyears/edit/(:num)']['post'] = 'SchoolyearController/edit/$1';
$route['schoolyears/update/(:num)']['post']  = 'SchoolyearController/update/$1';
$route['schoolyears/trash']['get'] = 'SchoolyearController/trash';
$route['schoolyears/remove/(:num)']['post'] = 'SchoolyearController/remove/$1';
$route['schoolyears/restore/(:num)']['post'] = 'SchoolyearController/restore/$1';

/*
| ---------------
| # Enrollment
| ---------------
|
*/
$route['enrollments']['get'] = 'EnrollmentController/index';
$route['enrollments/listing']['post'] = 'EnrollmentController/listing';
$route['enrollments/listing']['get'] = 'EnrollmentController/listing';
$route['enrollments/add']['post'] = 'EnrollmentController/add';
$route['enrollments/edit/(:num)']['post'] = 'EnrollmentController/edit/$1';
$route['enrollments/update/(:num)']['post']  = 'EnrollmentController/update/$1';
$route['enrollments/trash']['get'] = 'EnrollmentController/trash';
$route['enrollments/remove/(:num)']['post'] = 'EnrollmentController/remove/$1';
$route['enrollments/restore/(:num)']['post'] = 'EnrollmentController/restore/$1';
$route['enrollments/export']['get']  = 'EnrollmentController/export';
$route['enrollments/export']['post']  = 'EnrollmentController/export';


/*
| ---------------
| # Migration
| ---------------
| This should be commented out in production mode
*/
$route['migrate']['get'] = 'Migrate/index';
$route['migrate/(:num)']['get'] = 'Migrate/index/$1';
// $route['migrate/(:any)']['get'] = 'migrate/$1';

/*
|----------------
| # Seeds
|----------------
*/
// $route['seed/users'] = 'UsersController/seed';
// $route['seed/modules'] = 'ModulesController/seed';
/*
|----------
| # Install
|----------
*/
$route['install']['get'] = 'PageController/install';
$route['seed']['get'] = 'PageController/seed';

/*
|----------
| # Monitor
|----------
*/
$route['monitor/gates']['get'] = 'GateController/index';
$route['monitor/gates/listing']['post'] = 'GateController/listing';
$route['monitor/gates/add']['post'] = 'GateController/add';
$route['monitor/gates/edit/(:num)']['post'] = 'GateController/edit/$1';
$route['monitor/gates/update/(:num)']['post'] = 'GateController/update/$1';
$route['monitor/gates/remove/(:num)']['post'] = 'GateController/remove/$1';

$route['monitor/devices']['get'] = 'DeviceController/index';
$route['monitor/devices/listing']['post'] = 'DeviceController/listing';
$route['monitor/devices/add']['post'] = 'DeviceController/add';
$route['monitor/devices/edit/(:num)']['post'] = 'DeviceController/edit/$1';
$route['monitor/devices/update/(:num)']['post'] = 'DeviceController/update/$1';
$route['monitor/devices/remove/(:num)']['post'] = 'DeviceController/remove/$1';

$route['monitor/(:any)']= 'MonitorController/$1';
$route['monitor/announcement_listing']['post']  = 'MonitorController/announcement_listing';
$route['monitor/add_announcement']['post'] = 'MonitorController/add_announcement';
$route['monitor/del_announcement/(:num)']  = 'MonitorController/del_announcement/$1';
$route['monitor/edit_announcement/(:num)']['post']  = 'MonitorController/edit_announcement/$1';
$route['monitor/update_announcement/(:num)']['post']  = 'MonitorController/update_announcement/$1';


$route['monitor/splash_listing']['post']  = 'MonitorController/splash_listing';
$route['monitor/add_splash']['post'] = 'MonitorController/add_splash';
$route['monitor/add_splash_source'] = 'MonitorController/add_splash_source';
$route['monitor/del_splash/(:num)']  = 'MonitorController/del_splash/$1';
$route['monitor/edit_splash/(:num)']['post']  = 'MonitorController/edit_splash/$1';
$route['monitor/update_splash_source/(:num)']['post']  = 'MonitorController/update_splash_source/$1';

// $route['(:any)'] = 'PageController/view/$1';

/*
|----------------
| # Debugs
|----------------
*/
$route['debug'] = 'PageController/debug_view';
$route['debug-action'] = 'PageController/debug';

$route['(:any)'] = '$1';


$route['default_controller'] = 'PageController/index';
$route['404_override'] = 'Error404Controller';
$route['translate_uri_dashes'] = FALSE;
