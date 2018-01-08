<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class APIController extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->validated();
        $this->user_id = $this->session->userdata('id');

        $this->load->model('Message', '', TRUE);
        $this->load->model('Outbox', '', TRUE);
        $this->load->model('Member', '', TRUE);
        $this->Data['Headers'] = get_page_headers();

        $this->Data['Headers']->JS  = '<script src="'.base_url('assets/vendors/bootgrid/jquery.bootgrid.min.js').'"></script>';
        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/vendors/bootstrap-growl/bootstrap-growl.min.js').'"></script>';
        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/vendors/moment/min/moment.min.js').'"></script>';
        $this->Data['Headers']->JS .= '<link rel="stylesheet" href="'.base_url('assets/vendors/selectize.js/dist/css/selectize.bootstrap3.css').'">';

        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/vendors/selectize.js/dist/js/standalone/selectize.min.js').'"></script>';

        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/js/specifics/messaging.js').'"></script>';
    }

    public function validated()
    {
        $this->session->set_flashdata('error', "You are not logged in");
        if(!$this->session->userdata('validated')) redirect('login');
    }

}