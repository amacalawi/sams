#!/usr/bin/env php5
<?php
# $id$

require_once('config.inc.php');
require_once('DBAccessModel.class.php');
require_once('AppController.class.php');

$db = new DBAccess('received');
$controller = new AppController($db);

#%p the phone number of the sender of the SMS message
#%a all words of the SMS message
#%C message charset
#%i the smsc-id of the connection that received the message

# received.php %p %C %i %a
#               1 2  3  4


# insert to database received sms
$d['body']    = trim(urldecode($argv[4]));
$d['msisdn'] = $db->num_format(urldecode($argv[1]));
$smsc = urldecode($argv[3]);
$d['smsc']    = ($smsc=='auto') ? false : $smsc;
$d['charset'] = urldecode($argv[2]);
$d['created_on']= "NOW()";
$num=$d['msisdn'];
if ($num=="7210"){
  exit();
}else{
$db->save_messages($d);

$data = $db->get_member($d['msisdn']);

//echo $d['message'];
# pattern to check for possible command case insensitive
$pattern = '/^(.+?),(.*)/i';

if (preg_match($pattern, $d['body'], $m)) {
    $command = $db->get_pattern($m[1]); 
	$d['message'] = trim($m[2]);
    $db->log_debug($m,"pattern-> ");
} else {
          #$db->send_sms($d['msisdn'], $db->get_tmpl('invalid'), $d['smsc']);
    $command = $db->get_pattern($d['body']); 
     if($command){
       $db->log_debug($command,"pattern-> ");
       
       switch ($command)
       {
        case 'help reply':
        if (!$data) exit();
		$controller->helprep($d);
        break;
        case 'help':
        if (!$data) exit();
		$controller->help($d);
        break;
        case 'help vote':
        if (!$data) exit();
		$controller->helpvote($d);
        break;
        case 'reboot':
         shell_exec("sudo reboot");
        if (!$data) exit();
		$controller->reboot($d);
        break;
        default:
        break;
       }
     }
    exit();
}


$db->log_debug($command,"command -> ");
switch ($command)
{
    case 'grpmsg':
        if (!$data) exit();
        # only employee can send group message
        if ($data['0'] == 'EMPLOYEE') $controller->group_message($d);
    break;
    
    case 'balance':
        if (!$data) exit();
		$controller->balance($d);
	break;

    case 'poll':
        $controller->poll($d);
    break;
    
    case 'rep':
        $controller->rep($d);
    break;
    
    case 'reg':
        $controller->reg($d);
    break;

    case 'help':
        if (!$data) exit();
		$controller->help_group($d);
	break;
    case 'helprep':
        if (!$data) exit();
		$controller->helprep($d);
	break;
    case 'nick':
        if (!$data) exit();
		$controller->updatenick($d);
	break;
    default:
       #$db->send_sms($d['msisdn'], $db->get_tmpl('invalid'), $d['smsc']);
    break;
}
}
?>
