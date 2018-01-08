<?php
# $Id: DBAccessModel.class.php,v 1.4 2007/05/03 09:31:42 altair Exp $
require_once('BaseModel.class.php');
class DBAccess extends BaseModel
{
    public $smsc;
    function save_messages($detail){
        return $this->insert('inbox', $detail);
    }
    function enrollment_db(){
        global $config;
        $this->set_db($config['enrollment_db']);
    }
    function sms_db(){
        global $config;
        $this->set_db($config['mysql_db']);
    }

    function log_debug($data,$message=''){
        global $config;
        if ($config['debug'] === true) {
            echo $message;
            var_dump($data);
            echo "\n\r";
        }
    }

    function get_tmpl($type){
        return $this->return_one("SELECT template from templates WHERE name LIKE '$type' AND activate=1");
    }

    function get_pattern($pattern){
        $pattern = trim($pattern);
        return $this->return_one(" SELECT name FROM  `codes` 
                                   JOIN patterns ON codes.pattern_id = patterns.id
                                   WHERE code LIKE  '$pattern'");
    }

	function validate_msisdn($msisdn){
		$msisdn = $this->txt2num($msisdn);
        if (preg_match("/^(\+63|63|0)([0-9]{1,12})/" ,$msisdn, $matches)) return "0".$matches[2];
		return false;
	}


    # convert text to its numeric equivalent and remove other character returning just numeric value
    function txt2num($i){
        $pattern = array('/i/i','/v/i','/l/i','/o/i','/[^\d]/');
        $replace = array(1,5,1,0,'');
        return preg_replace($pattern, $replace, $i);
    }
    # convert numeric value to its equivalent aphacharacter (zero to letter O and number one to letter L)
    function num2txt($i){
        $pattern = array('/1/i','/0/i');
        $replace = array('l','o');
        return preg_replace($pattern, $replace, $i);
    }
    # this just remove the country code
    function num_format($msisdn){
        $pattern = array('/^(\+63|63)/');
        $replace = array('0');
        $msisdn = preg_replace($pattern, $replace, trim($msisdn));
        return $msisdn;
    }
    function clean_msisdn($msisdn){
        $pattern = array('/i/i','/l/i','/o/i','/[^\d]/','/^(\+63|63)/');
        $replace = array(1,1,0,'','0');
        $msisdn = preg_replace($pattern, $replace, trim($msisdn));
        return $msisdn;
    }
    function get_network($msisdn){
        $msisdn = $this->num_format($msisdn);
        $prefix = str_split($msisdn,4);
        $sql = "SELECT network FROM prefixes where access LIKE '%{$prefix[0]}%' LIMIT 1";
        $res = $this->select_one($sql);
        return ($res) ? $res[0] : '';
    }
    function send_sms($memid='',$msisdn, $body, $smsc='',$username='') {
        $msisdn = $this->num_format($msisdn);
        $smsc = ($smsc) ? $smsc : $this->get_network($msisdn); 
        if ($msisdn && $body) {
            $d['message']     = $body;
            $d['by'] = ($username) ? $username : 'auto-response';
            $id = $this->insert('messages', $d);
            $this->send($id,$msisdn,$smsc,$body,$memid);
        }
    }

    function send_group($msisdn,$mid,$smsc,$body,$memid=''){
        $msisdn = $this->num_format($msisdn);
        $smsc = ($smsc) ? $smsc : $this->get_network($msisdn); 
        if ($msisdn && $mid) {
            $this->send($mid,$msisdn,$smsc,$body,$memid);
        }
    }

    function send($id,$msisdn,$smsc,$body,$memid=''){
        $d = Array();
        $d['message_id'] = $id;
        $d['member_id'] = $memid;
        $d['msisdn']     = $msisdn;
        $d['smsc']       = ($smsc) ? $smsc : 'globe';
        $d['created_on'] ="NOW()";
        $d['status'] ='pending';
        $id = $this->insert('outbox', $d);

        $dlr = DLR_URL . '&outbox_id=' . $id;
        $url = SEND_URL . '&to=' . $msisdn . '&text=' . urlencode($body) . '&smsc=' . $smsc . '&dlr-url=' . urlencode($dlr);

        $ch = curl_init ($url);
        ob_start();
        curl_exec($ch);
        $str = ob_get_contents();
        ob_end_clean();
        curl_close ($ch);
        $this->query("update outbox set extra = '$str' where id='$id'");
    }

    function group_members($group){
        $sql = "SELECT m.msisdn,m.id
                FROM `groups` g
                JOIN group_members gm ON g.id = gm.group_id
                JOIN members m ON m.id = gm.member_id WHERE g.description='$group' GROUP BY m.msisdn";
        return $this->select_all($sql);
    }

    function get_outstanding($enrollment_id){
        $this->enrollment_db();
        $sql = "SELECT SUM(outstanding) FROM `assessment_details` WHERE `enrollment_id` = '$enrollment_id'";
        $res = $this->return_one($sql);
        $this->sms_db();
        return $res;
    }

    function enrollment_id_from_studno($studno){
        $this->enrollment_db();
        $sql = "SELECT e.id FROM `students` s JOIN enrollments e on e.student_id=s.id
                where s.stud_no = '$studno' order by e.id desc limit 1";
        $res = $this->return_one($sql);
        $this->sms_db();
        return $res;
    }

    function get_member($msisdn){
        $sql = "SELECT type FROM `members` WHERE `msisdn` = '$msisdn' AND msisdn != '';";
        return $this->select_one($sql);
    }
    function get_poll_details($pcode, $pocode){
        $sql = " SELECT po.id FROM  `polls` p JOIN poll_options po where p.code='$pcode'  and po.code = '$pocode'";
        return $this->select_one($sql);
    }
    function get_poll_id($pcode){
        $sql = " SELECT p.id FROM  `polls` p where p.code='$pcode'";
        return $this->select_one($sql);
    }
    function get_pollopt_code($id){
        $sql = " SELECT po.code FROM  `poll_options` po where po.id='$id'";
        return $this->select_one($sql);
    }
    function check_if_existing($msisdn){
        $sql = "SELECT mem.id FROM `members` mem where mem.msisdn = '$msisdn'";
        $res = $this->return_one($sql);
        return $res;
    }

    function get_group_id($code){
        $sql = "SELECT g.id FROM `groups` g where g.name = '$code'";
        $res = $this->return_one($sql);
        return $res;
    }
    function get_group_name($id){
        $sql = "SELECT g.name FROM `groups` g where g.id = '$id'";
        $res = $this->return_one($sql);
        return $res;
    }
    function get_group_code($id){
        $sql = "SELECT g.description FROM `groups` g where g.id = '$id'";
        $res = $this->return_one($sql);
        return $res;
    }
    function get_members_group($memid){
        $sql = "SELECT g.name FROM `members` mem 
                JOIN `group_members` gm ON gm.member_id = mem.id 
                LEFT JOIN `groups` g ON g.id = gm.group_id where mem.id = '$memid'";
        $res = $this->return_one($sql);
        return $res;
    }
    function get_sender_id($msisdn){
        $sql = "SELECT m.id FROM `members` m where m.msisdn = '$msisdn'";
        $res = $this->return_one($sql);
        return $res;
    }
    function get_sender_name($id){
        $sql = "SELECT m.nick FROM `members` m where m.id = '$id'";
        $res = $this->return_one($sql);
        return $res;
    }
    function get_sender_no($sender_id){
        $sql = "SELECT m.msisdn FROM `members` m where m.id = '$sender_id'";
        $res = $this->return_one($sql);
        return $res;
    }
    function verify_voter($code,$msisdn){
        $sql = "SELECT p.code FROM `polls` p JOIN `poll_answers` poa ON poa.poll_id = p.id WHERE p.code = '$code' AND poa.msisdn ='$msisdn'";
        $res = $this->return_one($sql);
        return $res;
    }
    function update_nick($id,$nick){
        $d['nick']=$nick; 
        return $this->update('members', $d, "id = '$id'");
    }
}
?>
