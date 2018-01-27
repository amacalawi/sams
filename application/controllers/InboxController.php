<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class InboxController extends CI_Controller {
    private $Data = array();
    private $user_id = 0;

    public function __construct()
    {
        parent::__construct();
        $this->validated();

        $this->load->model('Inbox', '', TRUE);
        $this->load->model('Member', '', TRUE);
        $this->load->model('PrivilegesLevel', '', TRUE);
        $this->load->model('Module', '', TRUE);
        $this->user_id = $this->session->userdata('id');

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

        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/js/specifics/inbox.js').'"></script>';
    }

    public function validated()
    {
        $this->session->set_flashdata('error', "You are not logged in");
        if(!$this->session->userdata('validated')) redirect('login');
    }

    public function index($msisdn=null)
    {
        $msisdn = $msisdn ? $msisdn : "NULL";
        $contacts = $this->Inbox->contacts();

        $inbox = $this->Inbox->messages($msisdn);

        if ($this->input->is_ajax_request()) {
            echo json_encode( array('inbox'=>$inbox) ); exit();
        }

        $this->Data['inbox'] = $inbox;
        $this->Data['contacts'] = $contacts;
        $this->Data['Headers']->Page = 'messaging/inbox';
        $this->load->view('layouts/main', $this->Data);
    }

    public function updateStatus($msisdn = null)
    {
        $status = $this->input->post('status');
        $this->Inbox->updateColumn(['status' => $status], $msisdn);

        echo json_encode(['status' => 'OK']); exit();
    }
}
