<?php
$outbox_id = $_REQUEST['outbox_id'];
if (!$outbox_id) exit();

require_once('config.inc.php');
require_once('BaseModel.class.php');
$db = new BaseModel('recieved');

#  1: delivery success
#  2: delivery failure
#  4: message buffered
#  8: smsc submit
#  16: smsc reject

$status[1] = 'successful';
$status[2] = 'failure';
$status[4] = 'buffered';
$status[8] = 'success';
$status[16] = 'reject';

$type = $_REQUEST['type'];
$type = ($status[$type]) ? $status[$type] : $type;

$d['status'] = $type; 
//$d['modified_date'] = "NOW()"; 

$db->update('outbox', $d, "id=$outbox_id");

?>
