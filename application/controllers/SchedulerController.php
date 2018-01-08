<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SchedulerController extends CI_Controller {

    private $Data = array();
    private $user_id = 0;

    public function __construct()
    {
        parent::__construct();
        // $this->validated();
        $this->user_id = $this->session->userdata('id');
        $this->load->model('Scheduler', '', TRUE);
        $this->load->model('Message', '', TRUE);
        $this->load->model('Outbox', '', TRUE);
        $this->load->model('Member', '', TRUE);
        $this->load->model('GroupMember', '', TRUE);
    }

    public function validated()
    {
        $this->session->set_flashdata('error', "You are not logged in");
        if(!$this->session->userdata('validated')) redirect('login');
    }

    public function schedule()
    {
        $msisdn = $this->input->post('msisdn');
        $body = $this->input->post('body');
        $date = str_replace('/', '-', $this->input->post('send_at_date'));
        $time = $this->input->post('send_at_time');
        $datetime = date("Y-m-d H:i:s", strtotime($date . " " . $time));

    	if (!$this->Scheduler->validate(true)) {
    	    echo json_encode(array(
    		'type' => 'danger',
    		'message' => $this->form_validation->toArray(),
    		'title' => 'Error',
    	    )); exit();
    	}

        if( $msisdn && array_key_exists("members", $msisdn) ) {
            foreach ($msisdn["members"] as $number) {
                $members = $this->Member->find_member_via_msisdn($number);
                if (null != $members) {
                    foreach ($members as $member) {
                        $data = array(
                            'message' => $body,
                            'member_ids' => $member->id,
                            'msisdn' => $member->msisdn,
                            'group_ids' => $member->groups,
                            "smsc" => $this->Message->get_network($number),
                            "created_by" => $this->user_id,
                            "status" => "pending",
                            "interval" => "",
                            "send_at" => $datetime,
                        );
                        $this->Scheduler->insert($data);
                    }
                } else {
                    $data = array(
                        'message' => $body,
                        'member_ids' => NULL,
                        'msisdn' => $number,
                        'group_ids' => NULL,
                        "smsc" => $this->Message->get_network($number),
                        "created_by" => $this->user_id,
                        "status" => "pending",
                        "interval" => "",
                        "send_at" => $datetime,
                    );
                    $this->Scheduler->insert($data);
                }
            }
        }
        if( $msisdn && array_key_exists("groups", $msisdn) ) {
            foreach ($msisdn["groups"] as $group_id) {
                $group_members = $this->GroupMember->lookup('group_id', $group_id)->result_array();
                foreach ($group_members as $member) {
                    $member = $this->Member->find($member['member_id']);
                    if (null != $member) {
                        $data = array(
                            'message' => $body,
                            'member_ids' => $member->id,
                            'msisdn' => $member->msisdn,
                            'group_ids' => $group_id,
                            "smsc" => $this->Message->get_network($member->msisdn),
                            "created_by" => $this->user_id,
                            "status" => "pending",
                            "interval" => "",
                            "send_at" => $datetime,
                        );
                        $this->Scheduler->insert($data);
                    }
                }

            }
        }
        if ($this->input->is_ajax_request()) {
            $data = array(
                'title' => 'Success',
                'type' => 'success',
                'message' => 'Message successfully scheduled',
            );
            echo json_encode($data); exit();
        }
    }

    public function send()
    {
        $scheduled = $this->Scheduler->get_scheduled();

        if ( null !== $scheduled ) {
            foreach ($scheduled as $schedule) {
                $msisdns = explode(",", $schedule->msisdn);
                foreach ($msisdns as $msisdn) {
                    $msisdn = trim($msisdn);

                    $schedule_d = array(
                        'message' => $schedule->message,
                        'msisdn' => $msisdn,
                        'by' => $this->user_id,
                    );
                    $message_id = $this->Message->insert( $schedule_d );

                    $members = $this->Member->find_member_via_msisdn($msisdn);

                    if (null != $members) {
                        foreach ($members as $member) {
                            $outbox = array(
                                'message_id' => $message_id,
                                'msisdn' => $msisdn,
                                'status' => 'pending',
                                'member_id' => $member->id,
                                'smsc' => $this->Message->get_network($msisdn),
                                'created_by' => $this->user_id,
                            );
                            $outbox_id = $this->Outbox->insert( $outbox );
                            $this->Message->send($outbox_id, $msisdn, $this->Message->get_network($msisdn), $schedule->message);
                            $this->Scheduler->update($schedule->id, array("status"=>"sent"));
                        }
                    } else {
                        $outbox = array(
                            'message_id' => $message_id,
                            'msisdn' => $msisdn,
                            'status' => 'pending',
                            'member_id' => NULL,
                            'smsc' =>  $this->Message->get_network($msisdn),
                            'created_by' => $this->user_id,
                        );
                        $outbox_id = $this->Outbox->insert( $outbox );
                        $this->Message->send($outbox_id, $msisdn, $this->Message->get_network($msisdn), $schedule->message);
                        $this->Scheduler->update($schedule->id, array("status"=>"sent"));
                    }

                    # This is the Kannel SHIT
                    # This sends the shit out of the messagfe to the kannel server
                    // $this->Message->send($outbox_id, $schedule->msisdn, $this->Message->get_network($schedule->msisdn), $body);
                }
            }
        }

        if ($this->input->is_ajax_request()) {
            echo json_encode([
                "title" => "Sending",
                "message" => "Pending messages are sending...",
                "type" => "inverse",
            ]); exit();
        } else {
            echo "Done";
        }
    }
}
