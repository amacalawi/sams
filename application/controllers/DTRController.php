<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DTRController extends CI_Controller {
    private $Data = array();
    private $user_id = 0;

    public function __construct()
    {
	date_default_timezone_set('Asia/Manila');
        parent::__construct();
        // $this->validated();

        $this->load->model('DTRLog', '', TRUE);
        $this->load->model('Member', '', TRUE);
        $this->load->model('Monitor_New', '', TRUE);
        $this->load->model('Message', '', TRUE);
		$this->load->model('DTR', '', TRUE);
		$this->load->model('Outbox', '', TRUE);
		$this->load->model('PrivilegesLevel', '', TRUE);
		$this->load->model('Module', '', TRUE);
        $this->user_id = $this->session->userdata('id');

        $this->Data['Headers'] = get_page_headers();
    }

    public function validated()
    {
        $this->session->set_flashdata('error', "You are not logged in");
        if(!$this->session->userdata('validated')) redirect('login');
    }

    public function dtr() {
		$stud_no = $this->input->get('stud_no');
		$e_date = $this->input->get('e_date');
		$e_time = $this->input->get('e_time');
		$e_mode = $this->input->get('e_mode');
		$e_tid = $this->input->get('e_tid') ? $this->input->get('e_tid') : '';
		$timelog = date("Y-m-d H:i:s", strtotime($e_date . " " . $e_time));

		$member = $this->Member->find2($stud_no, "stud_no");
		
		if (!$member) { 
			echo "**!! STUDENT No. IS NOT REGISTERED !!**"; exit();
		}
		else { 
			echo "-- STUDENT IS REGISTERED --"; 
			echo "\n<br>";
		
			$status = $this->DTRLog->check_schedule($stud_no, date("H:i:s", strtotime($e_time)), $e_mode);

			$data = array(
			   "member_id" => $member->id,//$stud_no,
			   "timelog" => $timelog,
			   "mode" => $e_mode,
			   "device_id" => $e_tid,
			   "status"	=> $status
			);

			$this->DTRLog->insert($data);

			# DTR
			$dtr_data = $this->DTR->find_mode($member->id, $e_mode);

			$is_timein=false;
			$is_timeout=false;
			if ($dtr_data > 0) {
			    $dateout = date("Y-m-d", strtotime($e_date));
			    $timeout = date("H:i:s", strtotime($e_time));

			    $totallate = '00:00:00';
			    $default_time = strtotime('00:00:00');

			    $in = $this->Monitor_New->get_dtrlog_in_2($stud_no, $dateout);
			    if($in != 0)
			    {
				    $time_in_hr = Date("H",strtotime($in))* 60 ;
	                $time_in_min  = Date("i",strtotime($in));
	                $reg_in_hr = Date("H",strtotime($this->Monitor_New->get_late_in_by_member_id($member->id)))* 60 ;
	                $reg_in_min  = (Date("i",strtotime($this->Monitor_New->get_late_in_by_member_id($member->id))) - 1);

	                if($time_in_hr > $reg_in_hr)
	                {
	                    $minutes = ($time_in_hr + $time_in_min) - ($reg_in_hr + $reg_in_min);
	                    $totallate = $totallate + $minutes;

	                } 
	                else if (($time_in_hr == ($reg_in_hr)) && $time_in_min > 0)
	                {
	                    $totallate = $totallate + $time_in_min;
	                }	
	            }

                $dtr_datas = array(
                	'dateout' => $dateout,
                	'timeout' => $timeout,
                	'total_late' => date("H:i", strtotime( ( $totallate > 0) ? date("H:i", strtotime('+'.$totallate.' minutes', $default_time)) : '00:00:00' )) 
                );

			    $this->DTR->update( $dtr_data, $dtr_datas);
			    $is_timeout = true;
			} else {

				if($dtr_data != -1)
				{
					$is_timein = true;

				    $member_id = $member->id;
				    $datein = date("Y-m-d", strtotime($e_date));
				    $timein = date("H:i:s", strtotime($e_time));
				    $data = array(
				    	"member_id" => $member_id,
				    	"datein" => $datein,
				        "timein" => $timein,
				    );
				    $this->DTR->insert($data);
				}
			}

			$mobile_nums = explode(', ',$this->Member->find($stud_no, "stud_no", "msisdn")->msisdn);
				
			foreach ($mobile_nums as $mobile_num) {
				# Send
				$send_data = array(
				    "stud_no" => $stud_no,
				    "stud_name" => $this->Member->find($stud_no, "stud_no", "CONCAT(firstname, ' ', lastname) AS fullname")->fullname,
				    "mode" => $e_mode,
				    "date" => date("M-d-y", strtotime($e_date)),
				    "time" => date("H:i:s", strtotime($e_time)),
				    "msisdn" => $mobile_num,
				    "is_timein" => $is_timein,
				    "is_timeout" => $is_timeout,
				    "schedule_id" => $this->Member->find($stud_no, "stud_no", "schedule_id")->schedule_id,
				);
				echo $this->execute($send_data);
			}
		}
    }

    public function curl_to_monitor($url)
    {
		$sock = @fopen($url, 'r');
		if ($sock) {
		    $str = fread($sock, 4096);
		    fclose($sock);
		    return $str;
		}

		$ch = curl_init($url);
		ob_start();
		curl_exec($ch);
		$str = ob_get_contents();
		ob_end_clean();
		curl_close($ch);
		return $str;
	}

	public function get_dtr()
	{
		echo $this->DTRLog->get_config();
    }

    /**
     * Execute
     *
     *
     * @return [type] [description]
     */
    public function execute($data=null)
    {
		$e_time = $data['time'];
		$e_mode = $data['mode'];
		$schedule_id = (null != $data['schedule_id'] && 0 != $data['schedule_id']) ? $data['schedule_id'] : 1;

		if ($e_mode == 1) {
			// Time in
			$times = $this->db->query("SELECT * FROM dtr_time_settings WHERE mode = '$e_mode' AND name LIKE '%IN%' AND schedule_id = $schedule_id AND '$e_time' BETWEEN time_from AND time_to")->row();
			//var_dump("asdasd------------", $e_time); die();
		} else {
			// Time out
			$times = $this->db->query("SELECT * FROM dtr_time_settings WHERE mode = '$e_mode' AND name LIKE '%OUT%' AND schedule_id = $schedule_id AND '$e_time' BETWEEN time_from AND time_to")->row();
		}

		if (empty($times)) {
			echo "WARNING | Out too Early | Must have been a double tap. No Message will be sent."; exit();
		}

		$template = $this->db->query("SELECT * FROM preset_messages WHERE id='$times->presetmsg_id'")->row();

		$detokenized = $this->Message->detokenize($template->name, $data);

		$body = $detokenized;
		// $msisdns = $data['msisdn'];
		$msisdns = explode(',', $data['msisdn']);

		foreach ($msisdns as $msisdn) 
		{
			# Check table str_sending_config which config is enabled
			# for sending
			$sending = $this->db->select('config')->where('enabled', 1)->limit(1)->get('dtr_sending_config')->row();

			echo isset($sending->config) ? "$sending->config | $times->name | $body\n<br>" : 'NO MODE |';
			// echo "$sending->config | $times->name | $body";
			$message_id = null;

			if ($sending && !empty($sending->config)) {
				# Compose message
				$message = array(
				    "message" => $body,
				    "msisdn" => $msisdn,
				    "by" => 0,
				);
				switch ($sending->config) {
					case 'A':
						$message_id = $this->Message->insert($message);
						echo "MODE A | A message will be sent\n<br>";
						break;

					case 'B':
						$today_is_a_weekend = in_array(date('D'), array('Sat', 'Sun')) ? 1 : 0;
						$time_inLate_or_outEarly = in_array($times->name, array('LATE_IN', 'LATE_OUT', 'EARLY_OUT')) ? 1 : 0;
						if (1 == $today_is_a_weekend || 1 == $time_inLate_or_outEarly) {
							$message_id = $this->Message->insert($message);
							echo "MODE B was used. A message will be sent...\n<br>";
						} else {
							echo "MODE B | NORMAL_IN/OUT so we did not send any messages\n<br>";
						}
						break;

					case 'C':
						echo "Mode C | All card tap are sent.\n<br>";
						$message_id = $this->Message->insert($message);
						break;

					# default here is actually useless & just for completion
					# since we've checked that $sending->config
					# should not be empty.
					default:
						echo "No Mode\n<br>";
						exit();
						break;
				}
			} else {
				echo "No Mode/config was found/specified. No messages will be sent upon tapping card.\n<br>";
				exit();
			}

			# Send to $msisdn if we have a message
			if (null != $message_id) {
				//$members = $this->Member->find_member_via_msisdn($msisdn);
				$members = $this->Member->find_member_via_msisdn2($msisdn, $data['stud_no']);
				foreach ($members as $member) {
				    $outbox = array(
				    	'message_id' => $message_id,
						'msisdn' => $msisdn,
						'status' => 'pending',
						'member_id' => $member->id,
						'smsc' => $this->Message->get_network($msisdn),
						'created_by' => 0
			   	    );
				    $outbox_id = $this->Outbox->insert($outbox);
				    $this->Message->send($outbox_id, $msisdn, $this->Message->get_network($msisdn), $body);
				    echo "Message was sent to $msisdn\n<br>";
				}
			}
		}


    }

}
