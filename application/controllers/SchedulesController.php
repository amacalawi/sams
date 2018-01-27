<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SchedulesController extends CI_Controller {

    private $Data = array();
    private $user_id = 0;

    public function __construct()
    {
        parent::__construct();

        $this->user_id = $this->session->userdata('id');

        $this->load->model('Schedule', '', TRUE);
        $this->load->model('MessageTemplate', '', TRUE);
        $this->load->model('PrivilegesLevel', '', TRUE);
        $this->load->model('Module', '', TRUE);
        
        $this->Data['Headers'] = get_page_headers();
        $this->Data['Headers']->CSS = '<link rel="stylesheet" href="'.base_url('assets/vendors/bootgrid/jquery.bootgrid.min.css').'">';
        $this->Data['Headers']->CSS.= '<link rel="stylesheet" href="'.base_url('assets/vendors/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css').'">';
        $this->Data['Headers']->CSS.= '<link rel="stylesheet" href="'.base_url('assets/vendors/chosen/chosen.min.css').'">';

        $this->Data['Headers']->JS  = '<script src="'.base_url('assets/vendors/bootgrid/jquery.bootgrid.min.js').'"></script>';
        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/vendors/bootstrap-growl/bootstrap-growl.min.js').'"></script>';
        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/vendors/moment/min/moment.min.js').'"></script>';
        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/vendors/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js').'"></script>';
        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/vendors/jquery.validate/dist/jquery.validate.min.js').'"></script>';
        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/vendors/chosen/chosen.jquery.min.js').'"></script>';

        $this->Data['Headers']->CSS .= '<link rel="stylesheet" href="'.base_url('assets/vendors/selectize.js/dist/css/selectize.bootstrap3.css').'">';
        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/vendors/selectize.js/dist/js/standalone/selectize.min.js').'"></script>';

        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/js/specifics/schedules.js').'"></script>';
    }

    public function index()
    {
        $this->Data['trash']['count'] = $this->Schedule->get_all(0, 0, null, true)->num_rows();

        $this->Data['form']['preset_message'] = dropdown_list($this->db->query('SELECT id, name FROM preset_messages WHERE active = 1')->result_array(), ['id', 'name'], '', false);

        // $this->Data['form']['message_template_normal_out'] = dropdown_list($this->MessageTemplate->dropdown_list('id, name, code', 'type', 'NORMAL_OUT')->result_array(), ['id', 'code'], '', false);

        // $this->Data['form']['message_template_early_in'] = dropdown_list($this->MessageTemplate->dropdown_list('id, name, code', 'type', 'EARLY_IN')->result_array(), ['id', 'code'], '', false);
        // $this->Data['form']['message_template_early_out'] = dropdown_list($this->MessageTemplate->dropdown_list('id, name, code', 'type', 'EARLY_OUT')->result_array(), ['id', 'code'], '', false);

        // $this->Data['form']['message_template_late_in'] = dropdown_list($this->MessageTemplate->dropdown_list('id, name, code', 'type', 'LATE_IN')->result_array(), ['id', 'code'], '', false);
        // $this->Data['form']['message_template_late_out'] = dropdown_list($this->MessageTemplate->dropdown_list('id, name, code', 'type', 'LATE_OUT')->result_array(), ['id', 'code'], '', false);

        $this->load->view('layouts/main', $this->Data);
    }

    public function listing()
    {
        if ($this->input->is_ajax_request()) {
            $bootgrid_arr = [];
            $current      = $this->input->post('current');
            $limit        = $this->input->post('rowCount') == -1 ? 0 : $this->input->post('rowCount');
            $page         = $current !== null ? $current : 1;
            $start_from   = ($page-1) * $limit;
            $sort         = null != $this->input->post('sort') ? $this->input->post('sort') : null;
            $wildcard     = null != $this->input->post('searchPhrase') ? $this->input->post('searchPhrase') : null;
            $removed_only = null !== $this->input->post('removedOnly') ? $this->input->post('removedOnly') : false;
            $total        = $this->Schedule->get_all(0, 0, null, $removed_only)->num_rows();

            if( null != $wildcard ) {
                $schedules = $this->Schedule->like($wildcard, $start_from, $limit, $sort, $removed_only)->result_array();
                $total = $this->Schedule->like($wildcard, 0, 0, null, $removed_only)->num_rows();
            } else {
                $schedules = $this->Schedule->get_all($start_from, $limit, $sort, $removed_only)->result_array();
            }

            foreach ($schedules as $key => $schedule) {
                $bootgrid_arr[] = array(
                    'count_id'  => $key + 1 + $start_from,
                    'id'        => $schedule['id'],
                    'name'      => $schedule['name'],
                    'code'      => $schedule['code'],
                );
            }

            $data = array(
                "current"       => intval($current),
                "rowCount"      => $limit,
                "searchPhrase"  => $wildcard,
                "total"         => intval( $total ),
                "rows"          => $bootgrid_arr,
                "trash"         => array(
                    "count" => $this->Schedule->get_all(0, 0, null, true)->num_rows(),
                )
            );

            echo json_encode( $data );
            exit();

        }
    }

    public function add()
    {
        if ($this->Schedule->validate(true)) {
            $schedule = array(
                'name' => $this->input->post('name'),
                'code' => $this->input->post('code'),
                'created_by' => $this->user_id,
            );
            $schedule_id = $this->Schedule->insert($schedule);

            $data = array(
                'name' => 'LATE_IN',
                'time_from' => date("H:i:s", strtotime($this->input->post('late_in_from'))),
                'time_to' => date("H:i:s", strtotime($this->input->post('late_in_to'))),
                'mode' => '1',
                'presetmsg_id' => $this->input->post('preset_message_late_in_id') ? $this->input->post('preset_message_late_in_id') : 1,
                'schedule_id' => $schedule_id,
            );
            $this->db->insert('dtr_time_settings', $data);

            $data = array(
                'name' => 'LATE_OUT',
                'time_from' => date("H:i:s", strtotime($this->input->post('late_out_from'))),
                'time_to' => date("H:i:s", strtotime($this->input->post('late_out_to'))),
                'mode' => '0',
                'presetmsg_id' => $this->input->post('preset_message_late_out_id') ? $this->input->post('preset_message_late_out_id') : 2,
                'schedule_id' => $schedule_id,
            );
            $this->db->insert('dtr_time_settings', $data);

            $data = array(
                'name' => 'EARLY_IN',
                'time_from' => date("H:i:s", strtotime($this->input->post('early_in_from'))),
                'time_to' => date("H:i:s", strtotime($this->input->post('early_in_to'))),
                'mode' => '1',
                'presetmsg_id' => $this->input->post('preset_message_early_in_id') ? $this->input->post('preset_message_early_in_id') : 3,
                'schedule_id' => $schedule_id,
            );
            $this->db->insert('dtr_time_settings', $data);

            $data = array(
                'name' => 'EARLY_OUT',
                'time_from' => date("H:i:s", strtotime($this->input->post('early_out_from'))),
                'time_to' => date("H:i:s", strtotime($this->input->post('early_out_to'))),
                'mode' => '0',
                'presetmsg_id' => $this->input->post('preset_message_early_out_id') ? $this->input->post('preset_message_early_out_id') : 4,
                'schedule_id' => $schedule_id,
            );
            $this->db->insert('dtr_time_settings', $data);

            $data = array(
                'name' => 'NORMAL_IN',
                'time_from' => date("H:i:s", strtotime($this->input->post('normal_in_from'))),
                'time_to' => date("H:i:s", strtotime($this->input->post('normal_in_to'))),
                'mode' => '1',
                'presetmsg_id' => $this->input->post('preset_message_normal_in_id') ? $this->input->post('preset_message_normal_in_id') : 5,
                'schedule_id' => $schedule_id,
            );
            $this->db->insert('dtr_time_settings', $data);

            $data = array(
                'name' => 'NORMAL_OUT',
                'time_from' => date("H:i:s", strtotime($this->input->post('normal_out_from'))),
                'time_to' => date("H:i:s", strtotime($this->input->post('normal_out_to'))),
                'mode' => '0',
                'presetmsg_id' => $this->input->post('preset_message_normal_out_id') ? $this->input->post('preset_message_normal_out_id') : 6,
                'schedule_id' => $schedule_id,
            );
            $this->db->insert('dtr_time_settings', $data);

            # Response
            $data = array(
                'title'   => 'Success',
                'message' => 'Schedule was successfully added',
                'type'    => 'success',
                // 'debug'   => $this->input->post('groups'),
            );
        } else {
            # Negative Response
            $data = array(
                'title' => 'Error',
                'message'=>$this->form_validation->toArray(),
                'type'=>'error',
            );
        }

        if ($this->input->is_ajax_request()) {
            echo json_encode($data); exit();
        } else {
            $this->session->set_flashdata('message', $data);
            redirect( base_url('schedules') );
        }
    }

    public function edit($id)
    {
        $schedule = $this->Schedule->find( $id );
        $dtr_time_settings = $this->db->query("SELECT * FROM dtr_time_settings WHERE schedule_id = $schedule->id")->result_array();
	foreach ($dtr_time_settings as $settings) {
	   // $schedule->presetmsg_id = $settings['presetmsg_id'];
            switch ($settings['name']) {
                case 'LATE_IN':
                    $schedule->late_in_from = date("H:i:s", strtotime($settings['time_from']));
		    $schedule->late_in_to = date("H:i:s", strtotime($settings['time_to']));
                    $schedule->preset_message_late_in_id = $settings['presetmsg_id'];
                    break;
                case 'LATE_OUT':
                    $schedule->late_out_from = date("H:i:s", strtotime($settings['time_from']));
                    $schedule->late_out_to = date("H:i:s", strtotime($settings['time_to']));
                    $schedule->preset_message_late_out_id = $settings['presetmsg_id'];
                    break;
                case 'NORMAL_IN':
                    $schedule->normal_in_from = date("H:i:s", strtotime($settings['time_from']));
		    $schedule->normal_in_to = date("H:i:s", strtotime($settings['time_to']));
                    $schedule->preset_message_normal_in_id = $settings['presetmsg_id'];
                    break;
                case 'NORMAL_OUT':
                    $schedule->normal_out_from = date("H:i:s", strtotime($settings['time_from']));
		    $schedule->normal_out_to = date("H:i:s", strtotime($settings['time_to']));
                    $schedule->preset_message_normal_out_id = $settings['presetmsg_id'];
                    break;
                case 'EARLY_IN':
                    $schedule->early_in_from = date("H:i:s", strtotime($settings['time_from']));
		    $schedule->early_in_to = date("H:i:s", strtotime($settings['time_to']));
		    $schedule->preset_message_early_in_id = $settings['presetmsg_id'];
                    break;
                case 'EARLY_OUT':
                    $schedule->early_out_from = date("H:i:s", strtotime($settings['time_from']));
		    $schedule->early_out_to = date("H:i:s", strtotime($settings['time_to']));
		    $schedule->preset_message_early_out_id = $settings['presetmsg_id'];
                    break;

                default:
                    # code...
                    break;
            }
        }
	if( $this->input->is_ajax_request() ) {
//		echo "<pre>"; var_dump($schedule); die();
		echo json_encode( $schedule ); exit();
	} else {

            $this->Data['schedule'] = $schedule;
            $this->load->view('layouts/main', $this->Data);
        }
    }

    public function update($id)
    {
        if( $this->Schedule->validate(false, $id, $this->input->post('code')) ) {
            /*
            | --------------------------------------
            | # Update
            | --------------------------------------
            */
            $schedule = array(
                'name'    => $this->input->post('name'),
                'code'     => $this->input->post('code')
            );
            $this->Schedule->update($id, $schedule);
            /*
            | --------------------------------------
            | # Update the dtr_time_settings
            | --------------------------------------
            */
            $data = array(
                "time_from" => $this->input->post("late_in_from"),
                "time_to" => $this->input->post("late_in_to"),
                "presetmsg_id" => $this->input->post("preset_message_late_in_id"),
            );
            $this->db->where("schedule_id", $id)->where("name", "LATE_IN")->update("dtr_time_settings", $data);
            $data = array(
                "time_from" => $this->input->post("late_out_from"),
                "time_to" => $this->input->post("late_out_to"),
                "presetmsg_id" => $this->input->post("preset_message_late_out_id"),
            );
            $this->db->where("schedule_id", $id)->where("name", "LATE_OUT")->update("dtr_time_settings", $data);
            $data = array(
                "time_from" => $this->input->post("normal_in_from"),
                "time_to" => $this->input->post("normal_in_to"),
                "presetmsg_id" => $this->input->post("preset_message_normal_in_id"),
            );
            $this->db->where("schedule_id", $id)->where("name", "NORMAL_IN")->update("dtr_time_settings", $data);
            $data = array(
                "time_from" => $this->input->post("normal_out_from"),
                "time_to" => $this->input->post("normal_out_to"),
                "presetmsg_id" => $this->input->post("preset_message_normal_out_id"),
            );
            $this->db->where("schedule_id", $id)->where("name", "NORMAL_OUT")->update("dtr_time_settings", $data);
            $data = array(
                "time_from" => $this->input->post("early_in_from"),
                "time_to" => $this->input->post("early_in_to"),
                "presetmsg_id" => $this->input->post("preset_message_early_in_id"),
            );
            $this->db->where("schedule_id", $id)->where("name", "EARLY_IN")->update("dtr_time_settings", $data);
            $data = array(
                "time_from" => $this->input->post("early_out_from"),
                "time_to" => $this->input->post("early_out_to"),
                "presetmsg_id" => $this->input->post("preset_message_early_out_id"),
            );
            $this->db->where("schedule_id", $id)->where("name", "EARLY_OUT")->update("dtr_time_settings", $data);

            $data = array(
                'message' => 'Level was successfully updated',
                'type' => 'success'
            );
            echo json_encode( $data );
            exit();
        }
        else
        {
            echo json_encode(['message'=>$this->form_validation->toArray(), 'type'=>'danger']); exit();
        }
    }

    public function trash()
    {
        $this->Data['schedules'] = $this->Schedule->all(true);

        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/js/specifics/schedulesTrash.js').'"></script>';
        $this->load->view('layouts/main', $this->Data);
    }

    public function remove($id)
    {
        $remove_many = 0;
        if( null === $id ) $remove_many = 1;
        if( null === $id ) $id = $this->input->post('id');

        if( $this->Schedule->remove($id) ) {
            if( 1 == $remove_many ) {
                $data['message'] = 'Schedules were successfully removed';
            } else {
                $data['message'] = 'Schedule was successfully removed';
            }
            $data['member']['type'] = 'success';
        } else {
            $data['message'] = 'An error occured while removing the resource';
            $data['type'] = 'error';
        }

        if( $this->input->is_ajax_request() ) {
            echo json_encode( $data ); exit();
        } else {
            $this->session->set_flashdata('message', $data );
            redirect('members');
        }
    }

    public function restore($id=null)
    {
        if( null === $id ) $id = $this->input->post('id');

        if( $this->Schedule->restore($id) ) {
            $data['message'] = 'Schedule was successfully restored';
            $data['type'] = 'success';
        } else {
            $data['message'] = 'An error occured while trying to restore the resource';
            $data['type'] = 'error';
        }

        if( $this->input->is_ajax_request() ) {
            echo json_encode( $data ); exit();
        } else {
            $this->session->set_flashdata('message', $data );
            redirect('schedules');
        }
    }
}
