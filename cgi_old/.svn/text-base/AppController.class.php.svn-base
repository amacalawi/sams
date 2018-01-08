<?php
class AppController
{
    var $db;
    function AppController($db){
        global $config;
        $this->db = $db;
    }

    function group_message($details){
        $sender_id = $this->db->get_sender_id($details['msisdn']);
        $sender_name = $this->db->get_sender_name($sender_id);
        if (preg_match('/(.+?),(.+)/i',$details['message'], $m)) {
            $members = $this->db->group_members(trim($m[1]));
            if (count($members) < 1) {
                $msg = str_replace('::group::',$m[1], $this->db->get_tmpl('group_does_not_exist'));
                $this->db->send_sms('',$details['msisdn'], $msg ,$details['smsc'] ); die();
                exit();
            }
            $dt['message']     = $m[2] ."-". $sender_name ."(". $sender_id . ")" ;
            $msg     = $m[2] ."-". $sender_name ."(". $sender_id . ")" ;
            $dt['by'] = "auto-response";
            $mid = $this->db->insert('messages', $dt);
            foreach ($members as $member){
                $this->db->send_group($member['msisdn'], $mid, $details['smsc'], $msg,$member['id']);
            }
            $msg = str_replace('::group::',$m[1], $this->db->get_tmpl('group_send_ok'));
            $this->db->send_sms($sender_id,$details['msisdn'], $msg ,$details['smsc'] ); die();
            exit();

        }
    }

    function balance($details){
        if ($student_no = trim($details['message'])) {
        } else {
            exit();
        }

        $enrollment_id = $this->db->enrollment_id_from_studno($student_no);
        $sender_id = $this->db->get_sender_id($details['msisdn']);
        if (!$enrollment_id) {
            $msg = str_replace('::student_no::',$student_no, $this->db->get_tmpl('stud_does_not_exist'));
            $this->db->send_sms($sender_id,$details['msisdn'], $msg, $details['smsc']);
            exit();
        }
        $balance = number_format($this->db->get_outstanding($enrollment_id),2);
        $this->db->send_sms($sender_id,$details['msisdn'], str_replace('::balance::', $balance, $this->db->get_tmpl('balance')),$details['smsc'] ); die();
    }

    function poll($details){
        //echo print_r($details);
      $reg_id = $this->db->check_if_existing($details['msisdn']);
        if (preg_match('/(.+?),(.+)/i',$details['message'], $m)) {
        
        $data = $this->db->verify_voter(trim($m[1]),$details['msisdn']);
          if($data){
                   $msg = $this->db->get_tmpl('vote_exsist');
                   $this->db->send_sms($reg_id,$details['msisdn'], $msg, $details['smsc']);
                   exit();
          }
            if ($po = $this->db->get_poll_details(trim($m[1]), trim($m[2]))){
               $poid=$this->db->get_poll_id(trim($m[1]));
               $pocode=$this->db->get_pollopt_code($po[0]);
                $dt['poll_id'] = $poid[0];
                $dt['poll_option_id'] = $po[0];
                $dt['msisdn'] = $details['msisdn'];
                $mid = $this->db->insert('poll_answers', $dt);
                   $msg = str_replace('::code::',$pocode[0], $this->db->get_tmpl('thank_u_4voting'));
                   $this->db->send_sms($reg_id,$details['msisdn'], $msg, $details['smsc']);
                   exit();
            }
        }        
                   $msg = $this->db->get_tmpl('invalid_voting_pattern');
                   $this->db->send_sms($reg_id,$details['msisdn'], $msg, $details['smsc']);
                   exit();
    }
    

    function reg($details){
        //echo print_r($details);
        $pattern = '/^(.+?),(.+),(.*),(.+),(.+)/i';
        if (preg_match($pattern, $details['message'], $m)) {
               $mem['stud_no']=strtoupper(trim($m[1]));
               $mem['firstname']=strtoupper(trim($m[2]));
               $mem['lastname']=strtoupper(trim($m[3]));
               $mem['middlename']=strtoupper(trim($m[4]));
               $mem['msisdn']=$details['msisdn'];
               if(trim($m[5])=='voters'){
               $mem['type']='voters';
//               }elseif(trim($m[4])=='panunuluyan2010'){
//               $mem['type']='panunuluyan2010';
               }else{
               $mem['type']='EMPLOYEE';
               }
               $mem['created_by']="system";

 
               $gid=$this->db->get_group_id(trim($m[5]));
               #Check if group code is existing 
               if ($gid){
               $reg_id = $this->db->check_if_existing($details['msisdn']);
                 #Check if number is already existing
                 if ($reg_id) {
                   $group = $this->db->get_members_group($reg_id);
                   $msg = str_replace('::group_name::',$group, $this->db->get_tmpl('member_exist'));
                   $this->db->send_sms($reg_id,$details['msisdn'], $msg, $details['smsc']);
                   exit();
                 }
                
               $mid=$this->db->insert('members',$mem);
               $gname=$this->db->get_group_name($gid);
               $gcode=$this->db->get_group_code($gid);
                 
               $gm['group_id']=$gid;
               $gm['member_id']=$mid;
               $this->db->insert('group_members',$gm);
             
               $msg = str_replace('::group_name::',$gname, $this->db->get_tmpl('registration'));
               $msg = str_replace('::group_code::',$gcode, $msg);
               $this->db->send_sms($memid,$details['msisdn'], $msg, $details['smsc']);
               exit();
               }
               #INVALID GROUP CODE
               $msg = str_replace('::group_name::',trim($m[5]), $this->db->get_tmpl('invalid_group'));
               $this->db->send_sms('',$details['msisdn'], $msg, $details['smsc']);
               exit();

        }       
           #INVALID PATTERN 
               $msg = $this->db->get_tmpl('invalid_pattern');
               $this->db->send_sms('',$details['msisdn'], $msg, $details['smsc']);
    }
   
    function help_group($details){
      $reg_id = $this->db->check_if_existing($details['msisdn']);
      if ($reg_id){
         $gid=$this->db->get_group_id($details['message']);
         if ($gid){
         $gname=$this->db->get_group_code($gid);
        
         $msg = str_replace('::group_name::',$gname, $this->db->get_tmpl('help_group'));
         $this->db->send_sms($reg_id,$details['msisdn'], $msg, $details['smsc']);
         exit();
         }
               #INVALID GROUP CODE
               $msg = str_replace('::group_name::',$details['message'], $this->db->get_tmpl('invalid_group'));
               $this->db->send_sms($reg_id,$details['msisdn'], $msg, $details['smsc']);
               exit();
      }
  
   }
    function help($details){
      $reg_id = $this->db->check_if_existing($details['msisdn']);
      if ($reg_id){
         $msg = $this->db->get_tmpl('help');
         $this->db->send_sms($reg_id,$details['msisdn'], $msg, $details['smsc']);
         exit();
         }
               $msg = $this->db->get_tmpl('help');
               $this->db->send_sms('',$details['msisdn'], $msg, $details['smsc']);
               exit();
   }

    function reboot($details){
      $reg_id = $this->db->check_if_existing($details['msisdn']);
      if ($reg_id){
         $msg = $this->db->get_tmpl('reboot');
         $this->db->send_sms($reg_id,$details['msisdn'], $msg, $details['smsc']);
         exit();
         }
               $msg = $this->db->get_tmpl('reboot');
               $this->db->send_sms('',$details['msisdn'], $msg, $details['smsc']);
               exit();
   }
/*    function rep($details){
      $reg_id = $this->db->check_if_existing($details['msisdn']);
        if (preg_match('/(.+?),(.+)/i',$details['message'], $m)) {
            $sender_id = trim($m[1]);
            $message = trim($m[2]);
            
            $recipient  = $this->db->get_sender_no($sender_id);

            if ($recipient){
            $this->db->send_sms($reg_id,$recipeint, $message, $details['smsc']);
             exit();
            }
              $msg = str_replace('::senderid::',$sender_id, $this->db->get_tmpl('sender_id_does_not_exist'));
              $this->db->send_sms('',$details['msisdn'], $msg ,$details['smsc'] ); die();
              exit();
        }
              $msg = str_replace('::senderid::',$sender_id, $this->db->get_tmpl('sender_id_does_not_exist'));
              $this->db->send_sms('',$details['msisdn'], $msg ,$details['smsc'] ); die();
              exit();
    }*/
/*    function rep($details){
        //echo print_r($details);
      $reg_id = $this->db->check_if_existing($details['msisdn']);
        $pattern = '/(.+?),(.+)/i';
        if (preg_match($pattern,$details['message'], $m)) {
            $sender_id = trim($m[1]);
            $message = trim($m[2]);
            
            $recipient  = $this->db->get_sender_no($sender_id);

            if ($recipient){
            $this->db->send_sms($reg_id,$recipeint, $message, $details['smsc']);
             exit();
            }
              $msg = str_replace('::senderid::',$sender_id, $this->db->get_tmpl('sender_id_does_not_exist'));
              $this->db->send_sms('',$details['msisdn'], $msg ,$details['smsc'] ); die();
              exit();
        
        }        
              $msg = str_replace('::senderid::',$sender_id, $this->db->get_tmpl('sender_id_does_not_exist'));
              $this->db->send_sms('',$details['msisdn'], $msg ,$details['smsc'] ); die();
              exit();
    }*/

    function rep($details){
        //echo print_r($details);
      $reg_id = $this->db->check_if_existing($details['msisdn']);
        $sender_name = $this->db->get_sender_name($reg_id);
        if (preg_match('/(.+?),(.+)/i',$details['message'], $m)) {
        
        $recipient = $this->db->get_sender_no(trim($m[1]));
          if($recipient){
                   $msg = trim($m[2]) ."-". $sender_name ."(". $reg_id . ")";
                   $this->db->send_sms($reg_id,$recipient, $msg, $details['smsc']);
                   exit();
          }
              $msg = str_replace('::senderid::',trim($m[1]), $this->db->get_tmpl('sender_id_does_not_exist'));
              $this->db->send_sms('',$details['msisdn'], $msg ,$details['smsc'] ); die();
              exit();
        }        
           #INVALID PATTERN 
               $msg = $this->db->get_tmpl('invalid_reply');
               $this->db->send_sms('',$details['msisdn'], $msg, $details['smsc']);
    }
    function helprep($details){
      $reg_id = $this->db->check_if_existing($details['msisdn']);
         
         $msg = $this->db->get_tmpl('helprep');
         $this->db->send_sms($reg_id,$details['msisdn'], $msg, $details['smsc']);
     exit();
  
   }
    function helpvote($details){
      $reg_id = $this->db->check_if_existing($details['msisdn']);
         
         $msg = $this->db->get_tmpl('help_vote');
         $this->db->send_sms($reg_id,$details['msisdn'], $msg, $details['smsc']);
     exit();
  
   }
   
   function updatenick($details){
      $reg_id = $this->db->check_if_existing($details['msisdn']);
      $id = $this->db->update_nick($reg_id,$details['message']);
      if($id){
         $msg = str_replace('::nick::',$details['message'],$this->db->get_tmpl('success_nick_update'));
         $this->db->send_sms($reg_id,$details['msisdn'], $msg, $details['smsc']);
         exit();
       }
exit();
   }
/*    function updatenick($details){
      $reg_id = $this->db->check_if_existing($details['msisdn']);
         
         $msg = $this->db->get_tmpl('success_nick_update');
         $this->db->send_sms($reg_id,$details['msisdn'], $msg, $details['smsc']);
     exit();
  
   }*/
    

}
?>
