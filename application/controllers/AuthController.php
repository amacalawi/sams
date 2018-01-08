<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AuthController extends CI_Controller {

    private $Data = array();
    private $hidden;

    public function __construct()
    {
        parent::__construct();

        $this->load->model('User', '', TRUE);

        $this->Data['Headers'] = get_page_headers();
        $this->Data['Headers']->CSS = '<link rel="stylesheet" href="'.base_url('assets/vendors/bootgrid/jquery.bootgrid.min.css').'">';

        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/vendors/bootstrap-growl/bootstrap-growl.min.js').'"></script>';
        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/vendors/jquery.validate/dist/jquery.validate.min.js').'"></script>';
        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/js/specifics/login.js').'"></script>';
        $this->Data['Headers']->bodyClass = 'login-content';
    }

    /**
     * GET Login controller.
     *
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function index($message = null)
    {
        if ($this->session->userdata('validated')) redirect('dashboard');

        $this->Data['message'] = $this->session->flashdata('message');
        return $this->load->view('auth/login', $this->Data);
    }

    /**
     * POST perform login
     *
     * @return
     */
    public function login()
    {
        $username = $this->security->xss_clean($this->input->post('username'));
        $password = $this->security->xss_clean($this->input->post('password'));
        $remember_me = $this->input->post('remember_me');

        // $q = $this->Auth->find(1);

        // echo "<pre>";
        //         var_dump( password_verify('admin', $q->password) ); die();
        // echo "</pre>";

        if( $this->Auth->check($username, $password, $remember_me) ) {
            if( null !== $this->session->userdata('referred_from') ) {
                redirect($this->session->userdata('referred_from'), 'refresh');
            } else {
                redirect('dashboard');
            }
        }

        $this->session->set_flashdata('message', array('type'=>"danger", "message"=>"Invalid credentials"));
        redirect('login');
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('login');
    }

    public function register()
    {
        if( $this->Auth->validate( array( 'email' => $this->input->post('email'), 'username' => $this->input->post('username') ) )) {

            if( $this->input->post('password') != $this->input->post('retype_password') ) {
                echo json_encode(['message'=>array('retype_password'=>"Passwords did not match"), 'type'=>'danger']); exit();
            }

            $username = $this->security->xss_clean($this->input->post('username'));
            $password = $this->security->xss_clean($this->input->post('password'));
            $user = array(
                'username' => $username,
                'password' => password_hash( $password, PASSWORD_BCRYPT ),
                'email' => $this->input->post('email'),
                'firstname' => $username,
                'middlename' => '',
                'lastname' => '',
                'privilege' => 1,
                'privilege_level' => 1,
                'remember_token' => 0,
                'created_by' => 0,
            );

            $this->User->insert($user);

            $data = array(
                'title' => 'Success',
                'message' => 'User was successfully Registered. Please Log in.',
                'type'    => 'success',
            );

        } else {
            # Negative Response
            $data = array(
                'title' => "Errors",
                'message' => $this->form_validation->toArray(),
                'type'=>'danger',
            );
        }

        if( $this->input->is_ajax_request() ) {
            echo json_encode( $data ); exit();
        } else {
            $data['debug'] = 'l';
            $this->session->set_flashdata('message', $data);
            redirect( base_url('login') );
        }
    }
}