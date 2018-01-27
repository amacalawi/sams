<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MessagingController extends CI_Controller {

    private $Data = array();
    private $user_id = 0;

    public function __construct()
    {
        parent::__construct();
        $this->validated();
        $this->user_id = $this->session->userdata('id');

        $this->load->model('Message', '', TRUE);
        $this->load->model('Outbox', '', TRUE);
        $this->load->model('Member', '', TRUE);
        $this->load->model('Group', '', TRUE);
        $this->load->model('GroupMember', '', TRUE);
        $this->load->model('Scheduler', '', TRUE);
        $this->load->model('PrivilegesLevel', '', TRUE);        
        $this->load->model('Module', '', TRUE);
        $this->Data['Headers'] = get_page_headers();


        $this->Data['Headers']->CSS = '<link rel="stylesheet" href="'.base_url('assets/vendors/bootgrid/jquery.bootgrid.min.css').'">';
        $this->Data['Headers']->JS  = '<script src="'.base_url('assets/vendors/bootgrid/jquery.bootgrid.min.js').'"></script>';
        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/vendors/bootstrap-growl/bootstrap-growl.min.js').'"></script>';
        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/vendors/moment/min/moment.min.js').'"></script>';
        $this->Data['Headers']->CSS .= '<link rel="stylesheet" href="'.base_url('assets/vendors/selectize.js/dist/css/selectize.bootstrap3.css').'">';

        $this->Data['Headers']->CSS.= '<link rel="stylesheet" href="'.base_url('assets/vendors/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css').'">';
        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/vendors/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js').'"></script>';

        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/vendors/selectize.js/dist/js/standalone/selectize.min.js').'"></script>';
        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/vendors/jquery.validate/dist/jquery.validate.min.js').'"></script>';

        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/js/specifics/messaging.js').'"></script>';
    }

    public function validated()
    {
        $this->session->set_flashdata('error', "You are not logged in");
        if(!$this->session->userdata('validated')) redirect('login');
    }

    /**
     * Index Page for this controller.
     *
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function index()
    {
        if ($this->input->is_ajax_request()) {
            // The list to return.
            // $list = [];
            // $contacts = $this->Member->all(false, 'id, CONCAT(firstname, " ", lastname) AS fullname, msisdn');
            // foreach ($contacts as $contact) {
            //     $numbers = explode(',', $contact->msisdn);
            //     foreach ($numbers as $number) {
            //         $list[] = array(
            //             'msisdn' => $number,
            //             'name' => "$contact->firstname $contact->lastname",
            //         );
            //     }
            // }
            // echo json_encode($list); exit();

            $contacts = $this->Member->all(false, 'id, CONCAT(firstname, " ", lastname) AS name, msisdn');
            $c = [];
            foreach ($contacts as $contact) {
                $c[$contact->msisdn][] = $contact;
            }
            $d = [];
            $i = 0;
            foreach ($c as $msisdn => $cc) {
                $d[$i]['id'] = $msisdn;
                $fullname = [];
                foreach ($cc as $ccc) {
                    $fullname[] = "$ccc->firstname $ccc->lastname";
                }
                $d[$i]['name'] = implode(",", $fullname);
                $d[$i]['msisdn'] = $msisdn;
                $i++;
            }
            echo json_encode($d); exit();
        }
        // $this->Data['contacts'] = $this->Contact->all();
        $this->Data['form']['contacts_list'] = dropdown_list($this->Member->dropdown_list('id, CONCAT(firstname, " ", lastname) AS fullname, msisdn')->result_array(), ['id', 'fullname'], 'No Contacts');
        $this->Data['form']['contacts_json'] = json_encode($this->Member->dropdown_list('id, CONCAT(firstname, " ", lastname) AS fullname, msisdn')->result_array());

        $this->Data['form']['groups_json'] = json_encode($this->Group->dropdown_list('groups_id, groups_name')->result_array());

        $this->Data['form']['templates'] = $this->db->query('SELECT * FROM message_templates')->result();

        $this->load->view('layouts/main', $this->Data);
    }

    public function groups()
    {
        echo json_encode($this->Group->dropdown_list('groups_id AS msisdn, groups_name AS name')->result_array()); exit();
    }

    public function bulk_send()
    { 
        if (is_array($this->input->post('msisdn'))) {
            
            $body = $this->input->post('body');
            $msisdns = $this->input->post('msisdn');

            $message = array(
                'message' => $body,
                'by' => $this->user_id,
            );

            $message_id = $this->Message->insert( $message ); 

            if ( array_key_exists('members', $msisdns) ) {
                foreach ($msisdns['members'] as $msisdn) {
                    $msisdns = explode(",", $msisdn);
                    foreach ($msisdns as $msisdn) {
                        $msisdn = trim($msisdn);

                        $group_msisdn = $this->Member->get_all_member_by_msisdn($msisdn);

                        if($group_msisdn > 0)
                        {
                            foreach ($group_msisdn as $single_msisdn) {
                                
                                $members = $this->Member->find_member_via_msisdn($msisdn);

                                $outbox_id = null;
                                if ($members > 0) {
                                    foreach ($members as $member) {
                                        $outbox = array(
                                            'message_id' => $message_id,
                                            'msisdn' => $single_msisdn->msisdn,
                                            'status' => 'pending',
                                            'member_id' => $single_msisdn->id,
                                            'smsc' => $this->Message->get_network($single_msisdn->msisdn),
                                            'created_by' => $this->user_id,
                                        );
                                        $outbox_id = $this->Outbox->insert( $outbox );
                                    }
                                } else {
                                    $outbox = array(
                                        'message_id' => $message_id,
                                        'msisdn' => $single_msisdn->msisdn,
                                        'status' => 'pending',
                                        'member_id' => NULL,
                                        'smsc' => $this->Message->get_network($single_msisdn->msisdn),
                                        'created_by' => $this->user_id,
                                    );
                                    $outbox_id = $this->Outbox->insert( $outbox );
                                }

                                # This is the Kannel SHIT
                                # This sends the shit out of the message to the kannel server
                                $this->Message->send($outbox_id, $single_msisdn->msisdn, $this->Message->get_network($single_msisdn->msisdn), $body);
                            }
                        } 
                        else
                        {
                            $outbox = array(
                                'message_id' => $message_id,
                                'msisdn' => $msisdn,
                                'status' => 'pending',  
                                'member_id' => NULL,
                                'smsc' => $this->Message->get_network($msisdn),
                                'created_by' => $this->user_id,
                            );
                            $outbox_id = $this->Outbox->insert( $outbox ); 

                            $this->Message->send($outbox_id, $msisdn, $this->Message->get_network($msisdn), $body);
                        }
                        // this ends the group fucking kanel shit out of the message to the kannel server
                    }
                } // endforeach
            }

            $msisdns1 = $this->input->post('msisdn');

            # Groups
            if (array_key_exists('groups', $msisdns1)) {
                $group_members = [];
                foreach ($msisdns1['groups'] as $group_id) {
                    $group_members = $this->GroupMember->lookup('group_id', $group_id)->result_array();
                    
                    foreach ($group_members as $member) {
                        $member = $this->Member->find($member['member_id']);
                        $msisdns = explode(",", $member->msisdn);
                        foreach ($msisdns as $msisdn) {
                            $msisdn = trim($msisdn);

                            $outbox = array(
                                'message_id' => $message_id,
                                'msisdn' => $msisdn,
                                'status' => 'pending',
                                'member_id' => $member->id,
                                'smsc' => $this->Message->get_network($msisdn),
                                'created_by' => $this->user_id,
                            );
                            $outbox_id = $this->Outbox->insert( $outbox );

                            # This is the Kannel SHIT
                            # This sends the shit out of the messagfe to the kannel server
                            $this->Message->send($outbox_id, $msisdn, $this->Message->get_network($msisdn), $body);
                        }
                    }
                }
            }

            $data = array(
                'type' => 'success',
                'message' => "Message successfully sent",
                'msisdns' => $msisdns,
            );
            echo json_encode($data); exit();
        }
        else {
            $body = $this->input->post('body');
            $msisdns = $this->input->post('msisdn');

            $message = array(
                'message' => $body,
                'by' => $this->user_id,
            );

            $message_id = $this->Message->insert( $message ); 

            $outbox = array(
                'message_id' => $message_id,
                'msisdn' => $msisdns,
                'status' => 'pending',
                'member_id' => NULL,
                'smsc' => $this->Message->get_network($single_msisdn->msisdn),
                'created_by' => $this->user_id,
            );
            $outbox_id = $this->Outbox->insert( $outbox );

            $this->Message->send($outbox_id, $msisdn, $this->Message->get_network($msisdn), $body);
        }
    }

    public function send()
    {
        $body = $this->input->post('body');
        $msisdn = $this->input->post('msisdn');

        $message = array(
            'message' => $body,
            'msisdn' => $msisdn,
            'by' => $this->user_id,
        );
        $message_id = $this->Message->insert( $message );

        $members = $this->Member->find_member_via_msisdn($msisdn);
        // echo json_encode($members); exit();

        $outbox_id = null;
        if ($members > 0) {
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
                $this->Message->send($outbox_id, trim($msisdn), $this->Message->get_network($msisdn), $body);
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
            $this->Message->send($outbox_id, $msisdn, $this->Message->get_network($msisdn), $body);
        }

        # This is the Kannel SHIT
        # This sends the shit out of the messagfe to the kannel server
        // $this->Message->send($outbox_id, $msisdn, $this->Message->get_network($msisdn), $body);

        $data = array(
            'type' => 'success',
            'body' => $body,
            'date' => date('m/d/Y \a\t h:ia'),
            'msisdn' => $msisdn,
        );
        echo json_encode($data); exit();
    }

    public function outbox()
    {
        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/js/specifics/outbox.js').'"></script>';
        $this->Data['trash']['count'] = $this->Outbox->get_all(0, 0, null, true)->num_rows();
        $this->load->view('layouts/main', $this->Data);
    }

    public function listing()
    {   
        if( $this->input->is_ajax_request() )
        {
            $bootgrid_arr = [];
            $current      = null != $this->input->post('current') ? $this->input->post('current') : 1;
            $limit        = $this->input->post('rowCount') == -1 ? 0 : $this->input->post('rowCount');
            $page         = $current !== null ? $current : 1;
            $start_from   = ($page-1) * $limit;
            $sort         = null != $this->input->post('sort') ? $this->input->post('sort') : null;
            $wildcard     = null != $this->input->post('searchPhrase') ? $this->input->post('searchPhrase') : null;
            

            if( isset($wildcard) )
            {
                $outboxes = $this->Outbox->like_outbox($wildcard, $start_from, $limit, $sort)->result_array();
                $total = $this->Outbox->likes_outbox($wildcard)->num_rows();

            }
            else
            {
                $outboxes = $this->Outbox->get_all_outbox($start_from, $limit, $sort)->result_array();
                $total = $this->Outbox->get_alls_outbox()->num_rows();
            }

            foreach ($outboxes as $key => $outbox) 
            {
                $bootgrid_arr[] = array(
                    'count_id' => $key + 1 + $start_from,
                    'id'       => $outbox['id'],
                    'member'   => $outbox['firstname'].' '.substr($outbox['middlename'], 0,1).'. '.$outbox['lastname'],
                    'message'  => $outbox['message'],
                    'msisdn'   => $outbox['msisdn'],
                    'smsc'     => $outbox['smsc'],
                    'status'   => $outbox['status'],
                );
            }

            $data = array(
                "current"       => intval($current),
                "rowCount"      => $limit,
                "searchPhrase"  => $wildcard,
                "total"         => intval( $total ),
                "rows"          => $bootgrid_arr,
            );

            echo json_encode( $data );
            exit();
        }

        /**
         * AJAX List of Data
         * Here we load the list of data in a table
         */
        // if ( $this->input->is_ajax_request() ) {
        //     $bootgrid_arr = [];
        //     $current      = $this->input->post('current');
        //     $limit        = $this->input->post('rowCount') == -1 ? 0 : $this->input->post('rowCount');
        //     $page         = $current !== null ? $current : 1;
        //     $start_from   = ($page-1) * $limit;
        //     $sort         = null != $this->input->post('sort') ? $this->input->post('sort') : null;
        //     $wildcard     = null != $this->input->post('searchPhrase') ? $this->input->post('searchPhrase') : null;
        //     $removed_only = null !== $this->input->post('removedOnly') ? $this->input->post('removedOnly') : false;
        //     $total        = $this->Outbox->get_all(0, 0, null, $removed_only)->num_rows();

        //     if( null != $wildcard ) {
        //         $outboxs = $this->Outbox->like($wildcard, $start_from, $limit, $sort, $removed_only)->result_array();
        //         $total   = $this->Outbox->like($wildcard, 0, 0, null, $removed_only)->num_rows();
        //     } else {
        //         $outboxs = $this->Outbox->get_all($start_from, $limit, $sort, $removed_only)->result_array();
        //     }

        //     foreach ($outboxs as $key => $outbox) {
        //         $students = $this->Member->find_member_via_msisdn($outbox['msisdn']);
        //         $student = [];
        //         foreach ($students as $s) {
        //             $student[] = $s->firstname . " " . $s->lastname;
        //         }

        //         $bootgrid_arr[] = array(
        //             'count_id'           => $key + 1 + $start_from,
        //             'id'        => $outbox['id'],
        //             'member'   => implode(",<br>", $student),
        //             'message' => $this->Message->find($outbox['message_id'])->message,
        //             'msisdn' => $outbox['msisdn'],
        //             'smsc' => $outbox['smsc'],
        //             'status' => $outbox['status'],
        //         );
        //     }

        //     $data = array(
        //         "current"       => intval($current),
        //         "rowCount"      => $limit,
        //         "searchPhrase"  => $wildcard,
        //         "total"         => intval( $total ),
        //         "rows"          => $bootgrid_arr,
        //         "trash"         => array(
        //             "count" => $this->Outbox->get_all(0, 0, null, true)->num_rows(),
        //         )
        //         // "debug" => $outbox['type'],
        //     );

        //     echo json_encode( $data );
        //     exit();
        // }
    }

    public function tracking()
    {
        $this->Data['scheduled'] = $this->Scheduler->all();
        $this->Data['pending'] = count($this->Scheduler->get("status", "pending")->result_array());
        $this->Data['failed'] = count($this->Scheduler->get("status", "failed")->result_array());
        $this->Data['success'] = count($this->Scheduler->get("status", "success")->result_array());
        $this->Data['rejected'] = count($this->Scheduler->get("status", "rejected")->result_array());
        $this->Data['buffered'] = count($this->Scheduler->get("status", "buffered")->result_array());

        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/js/specifics/messageTracking.js').'"></script>';
        $this->load->view('layouts/main', $this->Data);
    }

    public function tracking_listing_grouped()
    {
        $bootgrid_arr = [];
        $group_by     = 'message';
        $current      = $this->input->post('current');
        $limit        = $this->input->post('rowCount') == -1 ? 0 : $this->input->post('rowCount');
        $page         = $current !== null ? $current : 1;
        $start_from   = ($page-1) * $limit;
        $sort         = null != $this->input->post('sort') ? $this->input->post('sort') : null;
        $wildcard     = null != $this->input->post('searchPhrase') ? $this->input->post('searchPhrase') : null;
        $removed_only = null !== $this->input->post('removedOnly') ? $this->input->post('removedOnly') : false;
        $total        = $this->Outbox->tracking_all(0, 0, null, $removed_only)->num_rows();

        if( null != $wildcard ) {
            $tracking = $this->Outbox->tracking($wildcard, $start_from, $limit, $sort, $removed_only)->result();
            $total    = $this->Outbox->tracking($wildcard, 0, 0, null, $removed_only)->num_rows();
        } else {
            $tracking = $this->Outbox->tracking_all($start_from, $limit, $sort, $removed_only)->result();
        }

        foreach ($tracking as $key => $message) {

            $bootgrid_arr[] = array(
                'count_id'           => $key + 1 + $start_from,
                'id'        => $message->id,
                'message' => $message->message,
                'contacts' => $message->contacts,
                'pending' => $message->pending,
                'successful' => $message->successful,
                'rejected' => $message->rejected,
                'failure' => $message->failure,
                'buffered' => $message->buffered,
            );
        }

        $pending = $this->Outbox->tracking_status_count('pending')->row()->pending;
        $failed = $this->Outbox->tracking_status_count('failed')->row()->failed;
        $success = $this->Outbox->tracking_status_count('success')->row()->success;
        $rejected = $this->Outbox->tracking_status_count('rejected')->row()->rejected;
        $buffered = $this->Outbox->tracking_status_count('buffered')->row()->buffered;

        $data = array(
            "current"       => intval($current),
            "rowCount"      => $limit,
            "searchPhrase"  => $wildcard,
            "total"         => intval( $total ),
            "rows"          => $bootgrid_arr,
            "trash"         => array(
                "count" => $this->Scheduler->get_all(0, 0, null, true)->num_rows(),
            ),
            "contacts" => $total,
            "pending" => ($pending > 0) ? $pending : 0,
            "failed" => ($failed > 0) ? $failed : 0,
            "success" => ($success > 0) ? $success : 0,
            "rejected" => ($rejected > 0) ? $rejected : 0,
            "buffered" => ($buffered > 0) ? $buffered : 0,
            "sort"  => $sort
        );
        echo json_encode( $data );
        exit();

    }
    public function tracking_listing_grouped_disabled()
    {
        $bootgrid_arr = [];
        $group_by     = 'message';
        $current      = $this->input->post('current');
        $limit        = $this->input->post('rowCount') == -1 ? 0 : $this->input->post('rowCount');
        $page         = $current !== null ? $current : 1;
        $start_from   = ($page-1) * $limit;
        $sort         = null != $this->input->post('sort') ? $this->input->post('sort') : null;
        $wildcard     = null != $this->input->post('searchPhrase') ? $this->input->post('searchPhrase') : null;
        $removed_only = null !== $this->input->post('removedOnly') ? $this->input->post('removedOnly') : false;
        $total        = $this->Scheduler->get_all_grouped($group_by, 0, 0, null, $removed_only)->num_rows();

        if( null != $wildcard ) {
            $scheduled = $this->Scheduler->like_grouped($group_by, $wildcard, $start_from, $limit, $sort, $removed_only)->result_array();
            $total   = $this->Scheduler->like_grouped($group_by, $wildcard, 0, 0, null, $removed_only)->num_rows();

            $scheduled_full = $this->Scheduler->like($wildcard, $start_from, $limit, $sort, $removed_only)->result_array();
        } else {
            $scheduled = $this->Scheduler->get_all_grouped($group_by, $start_from, $limit, $sort, $removed_only)->result_array();

            $scheduled_full = $this->Scheduler->get_all($start_from, $limit, $sort, $removed_only)->result_array();
        }

        foreach ($scheduled as $key => $schedule) {

            $bootgrid_arr[] = array(
                'count_id'           => $key + 1 + $start_from,
                'id'        => $schedule['id'],
                'message' => $schedule['message'],
                'member_ids' => $schedule['member_ids'],
                // 'group_ids' => arraytostring($this->Group->find(explode(",", $schedule['group_ids'])), ", "),
                'smsc' => $schedule['smsc'],
                'msisdn' => $schedule['msisdn'],
                'status' => $schedule['status'],
                'send_at' => date("M d, Y \a\\t h:ia", strtotime($schedule['send_at'])),
            );
        }

        $pending = count($this->Scheduler->get("status", "pending")->result_array());
        $failed = count($this->Scheduler->get("status", "failed")->result_array());
        $success = count($this->Scheduler->get("status", "success")->result_array());
        $rejected = count($this->Scheduler->get("status", "rejected")->result_array());
        $buffered = count($this->Scheduler->get("status", "buffered")->result_array());

        $data = array(
            "current"       => intval($current),
            "rowCount"      => $limit,
            "searchPhrase"  => $wildcard,
            "total"         => intval( $total ),
            "rows"          => $bootgrid_arr,
            "trash"         => array(
                "count" => $this->Scheduler->get_all(0, 0, null, true)->num_rows(),
            ),
            "scheduled" => count($this->Scheduler->all()),
            "pending" => $pending,
            "failed" => $failed,
            "success" => $success,
            "rejected" => $rejected,
            "buffered" => $buffered,
            // "debug" => $outbox['type'],
        );
        echo json_encode( $data );
        exit();
    }

    public function tracking_listing()
    {
        /**
         * AJAX List of Data
         * Here we load the list of data in a table
         */
        if ( $this->input->is_ajax_request() ) {
            $bootgrid_arr = [];
            $current      = $this->input->post('current');
            $limit        = $this->input->post('rowCount') == -1 ? 0 : $this->input->post('rowCount');
            $page         = $current !== null ? $current : 1;
            $start_from   = ($page-1) * $limit;
            $sort         = null != $this->input->post('sort') ? $this->input->post('sort') : null;
            $wildcard     = null != $this->input->post('searchPhrase') ? $this->input->post('searchPhrase') : null;
            $removed_only = null !== $this->input->post('removedOnly') ? $this->input->post('removedOnly') : false;
            $total        = $this->Scheduler->get_all(0, 0, null, $removed_only)->num_rows();

            if( null != $wildcard ) {
                $scheduled = $this->Scheduler->like($wildcard, $start_from, $limit, $sort, $removed_only)->result_array();
                $total   = $this->Scheduler->like($wildcard, 0, 0, null, $removed_only)->num_rows();
            } else {
                $scheduled = $this->Scheduler->get_all($start_from, $limit, $sort, $removed_only)->result_array();
            }

            foreach ($scheduled as $key => $schedule) {

                // $members = $this->Member->find()

                $bootgrid_arr[] = array(
                    'count_id'           => $key + 1 + $start_from,
                    'id'        => $schedule['id'],
                    'message' => $schedule['message'],
                    'member_ids' => null != $this->Member->find($schedule['member_ids']) ? $this->Member->find($schedule['member_ids'], null, 'CONCAT(firstname, " ", lastname) as fullname')->fullname : "unregistered no.",
                    // 'group_ids' => arraytostring($this->Group->find(explode(",", $schedule['group_ids'])), ", "),
                    'smsc' => $schedule['smsc'],
                    'msisdn' => $schedule['msisdn'],
                    'status' => $schedule['status'],
                    'send_at' => date("M d, Y \a\\t h:ia", strtotime($schedule['send_at'])),
                );
            }

            $pending = count($this->Scheduler->get("status", "pending")->result_array());
            $failed = count($this->Scheduler->get("status", "failed")->result_array());
            $success = count($this->Scheduler->get("status", "success")->result_array());
            $rejected = count($this->Scheduler->get("status", "rejected")->result_array());
            $buffered = count($this->Scheduler->get("status", "buffered")->result_array());

            $data = array(
                "current"       => intval($current),
                "rowCount"      => $limit,
                "searchPhrase"  => $wildcard,
                "total"         => intval( $total ),
                "rows"          => $bootgrid_arr,
                "trash"         => array(
                    "count" => $this->Scheduler->get_all(0, 0, null, true)->num_rows(),
                ),
                "scheduled" => count($this->Scheduler->all()),
                "pending" => $pending ? $pending : 0,
                "failed" => $failed,
                "success" => $success,
                "rejected" => $rejected,
                "buffered" => $buffered,
                // "debug" => $outbox['type'],
            );
            echo json_encode( $data );
            exit();
        }
    }

    public function configuration()
    {
        $this->Data['form']['dtr_sending_config'] = $this->db->query("SELECT * FROM dtr_sending_config")->result();
        $this->load->view('layouts/main', $this->Data);
    }

    public function postConfiguration()
    {
        $config = $this->input->post('config');

       $this->db->query("UPDATE dtr_sending_config SET enabled=0");
//        if (0 != $config) $this->db->query("UPDATE dtr_sending_config SET enabled=1 WHERE config='$config'")
	if(isset($config))
	{
		$this->db->query("UPDATE dtr_sending_config SET enabled=1 WHERE config='".$config."'");
	}
        if ($this->db->affected_rows() > 0 || 0 == $config) {
            $this->session->set_flashdata('message', array('type'=>'success', 'message'=>"Configuration Saved." . $config));
            redirect(base_url('messaging/configuration'));
        } else {
            die("error");
        }
    }

    public function resend($id)
    {
        $data = array(
            'id'    => $id,
            'title' => 'Error',
            'type' => 'error',
            'message' => 'No message id found',
        );

        $message = $this->Message->find($id);
        if (empty($message->id)) 
        { 
            echo json_encode($data); exit(); 
        }
        else {
            // $outbox_id = $this->Outbox->find($id, 'message_id')->id;

            $outboxes = $this->Outbox->find_message_by_outbox($id);
            
            if($outboxes != 0)
            {   
                foreach ($outboxes as $outbox) {
                    $msisdn = $outbox->msisdn;
                    $body = $outbox->msg_body;
                    $outbox_id = $outbox->id;
                    $this->Message->send($outbox_id, $msisdn, $this->Message->get_network($msisdn), $body);
                } 
                
                $data = array(
                    'title' => 'Success',
                    'type' => 'info',
                    'message' => 'Message resending...',
                );

            } else {
                $data = array(
                    'id'    => $id,
                    'title' => 'Error',
                    'type' => 'error',
                    'message' => 'No message id found',
                );   
            }

            echo json_encode($data); exit();
        }
    }

    public function resend_all_message()
    {
        $outboxes = $this->Outbox->find_all_pending_message();
        
        if($outboxes != 0)
        {   
            foreach ($outboxes as $outbox) {
                $msisdn = $outbox->msisdn;
                $body = $outbox->msg_body;
                $outbox_id = $outbox->id;
                $this->Message->send($outbox_id, $msisdn, $this->Message->get_network($msisdn), $body);
            } 
            
            $data = array(
                'title' => 'Success',
                'type' => 'info',
                'message' => 'Message resending...',
            );

        } else {
            $data = array(
                'title' => 'Error',
                'type' => 'error',
                'message' => 'No message id found',
            );   
        }

        echo json_encode($data); exit();
    }

}
