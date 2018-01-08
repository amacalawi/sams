<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UsersController extends CI_Controller {

    private $Data = array();
    private $user_id = 0;

    public function __construct()
    {
        parent::__construct();
        $this->validated();

        $this->load->model('User', '', TRUE);
        $this->load->model('Privilege', '', TRUE);
        $this->load->model('PrivilegesLevel', '', TRUE);

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

        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/js/specifics/users.js').'"></script>';
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
        $this->Data['users'] = $this->User->all();
        $this->Data['form']['privileges_list'] = dropdown_list($this->Privilege->dropdown_list('id, name')->result_array(), ['id', 'name'], 'Select One');
        $this->Data['form']['privileges_levels_list'] = dropdown_list($this->PrivilegesLevel->dropdown_list('id, name')->result_array(), ['id', 'name'], 'Select One');
        $this->Data['trash']['count'] = $this->Privilege->get_all(0, 0, null, true)->num_rows();
        $this->load->view('layouts/main', $this->Data);
    }

    public function listing()
    {
        /**
         * AJAX List of Data
         * Here we load the list of data in a table
         */
        if( $this->input->is_ajax_request() )
        {
            $bootgrid_arr = [];
            $current      = null != $this->input->post('current') ? $this->input->post('current') : 1;
            $limit        = $this->input->post('rowCount') == -1 ? 0 : $this->input->post('rowCount');
            $page         = $current !== null ? $current : 1;
            $start_from   = ($page-1) * $limit;
            $sort         = null != $this->input->post('sort') ? $this->input->post('sort') : null;
            $wildcard     = null != $this->input->post('searchPhrase') ? $this->input->post('searchPhrase') : null;
            $removed_only = null != $this->input->post('removedOnly') ? $this->input->post('removedOnly') : false;
            $total        = $this->User->get_all(0, 0, null, $removed_only)->num_rows();

            if( null != $wildcard )
            {
                $users = $this->User->like($wildcard, $start_from, $limit, $sort, $removed_only)->result_array();
                $total  = $this->User->like($wildcard, 0, 0, null, $removed_only)->num_rows();
            }
            else
            {
                $users = $this->User->get_all($start_from, $limit, $sort, $removed_only)->result_array();
            }

            foreach ($users as $key => $user) {
                $bootgrid_arr[] = array(
                    'count_id'           => $key + 1 + $start_from,
                    'id'          => $user['id'],
                    'username'        => $user['username'],
                    'fullname' => arraytostring([
                        $user['firstname'],
                        $user['middlename'] ? substr($user['middlename'], 0,1) . '.' : '',
                        $user['lastname']],
                        ' '),
                    'email'       => $user['email'],
                    'privilege'        => $this->Privilege->find( $user['privilege'] )->name,
                    'privilege_level' => $this->PrivilegesLevel->find( $user['privilege_level'] )->name,
                );
            }
            $data = array(
                "current"       => intval($current),
                "rowCount"      => $limit,
                "searchPhrase"  => $wildcard,
                "total"         => intval( $total ),
                "rows"          => $bootgrid_arr,
                "trash"         => array(
                    "count" => $this->Privilege->get_all(0, 0, null, true)->num_rows(),
                ),
            );
            echo json_encode( $data );
            exit();
        }
    }

    public function add()
    {
        if( $this->input->is_ajax_request() ) {
            /*
            | -------------------------------------
            | # Debug
            | -------------------------------------
            */
            // $data['message'] = $this->input->post();
            // $data['type'] = 'success';
            // echo json_encode($data); exit();
            /*
            | --------------------------------------
            | # Validation
            | --------------------------------------
            */
            if( $this->User->validate(true) )
            {
                if( $this->input->post('password') != $this->input->post('retype_password') ) {
                    echo json_encode(['message'=>array('password'=>"Passwords did not match"), 'type'=>'danger']); exit();
                }
                /*
                | --------------------------------------
                | # Save
                | --------------------------------------
                */
                $user = array(
                    'username'    => $this->input->post('username'),
                    'password' => password_hash($this->input->post('password', TRUE), PASSWORD_BCRYPT),
                    'email' => $this->input->post('email'),
                    'firstname'     => $this->input->post('firstname'),
                    'middlename' => $this->input->post('middlename'),
                    'lastname' => $this->input->post('lastname'),
                    'privilege' => $this->input->post('privilege'),
                    'privilege_level' => $this->input->post('privilege_level'),
                    'created_by' => $this->user_id,
                );
                $user_id = $this->User->insert($user);

                /*
                | ----------------------------------------
                | # Response
                | ----------------------------------------
                */
                $data = array(
                    'message' => 'User was successfully added',
                    'type'    => 'success'
                );
                echo json_encode( $data ); exit();
            } else {
                echo json_encode(['message'=>$this->form_validation->toArray(), 'type'=>'danger']); exit();
            }

        }
        else
        {
            redirect( base_url('users') );
        }
    }

    /**
     * Retrieve the resource for editing
     * @param  int $id
     * @return JSON
     */
    public function edit($id)
    {
        if( $this->input->is_ajax_request() )
        {
            $user = $this->User->find( $id );
            echo json_encode( $user );
            exit();
        }
    }

    public function update($id)
    {
        if( $this->input->is_ajax_request() )
        {
            /*
            | -------------------------------------
            | # Debug
            | -------------------------------------
            */
            // $data['message'] = $this->input->post('users_contacts');
            // $data['type'] = 'success';
            // echo json_encode($data); exit();
            /*
            | --------------------------------------
            | # Validation
            | --------------------------------------
            */
            if( $this->User->validate(false, $id, $this->input->post('username'), $this->input->post('email')) )
            {
                if( $this->input->post('password') != $this->input->post('retype_password') ) {
                    echo json_encode(['message'=>array('password'=>"Passwords did not match"), 'type'=>'danger']); exit();
                }
                /*
                | --------------------------------------
                | # Update
                | --------------------------------------
                */
                $user = array(
                    'username'    => $this->input->post('username'),
                    'email'     => $this->input->post('email'),
                    'firstname' => $this->input->post('firstname'),
                    'middlename' => $this->input->post('middlename'),
                    'lastname' => $this->input->post('lastname'),
                    'privilege' => $this->input->post('privilege'),
                    'privilege_level' => $this->input->post('privilege_level'),
                    'updated_by' => $this->user_id,
                );
                if( null != $this->input->post('password') ) {
                    $user['password'] = password_hash($this->input->post('password'), PASSWORD_BCRYPT);
                }

                $this->User->update($id, $user);

                $data = array(
                    'message' => 'User was successfully updated',
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
    }

    public function trash()
    {
        $this->Data['users'] = $this->User->all(true);

        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/js/specifics/usersTrash.js').'"></script>';
        $this->load->view('layouts/main', $this->Data);
    }

    public function remove($id=null)
    {
        // if( !$this->Auth->can() ) {
        //     $this->Data['Headers']->Page = 'errors/403';
        //     $this->load->view('layouts/errors', $this->Data);
        //     echo json_encode( [
        //         'title' => 'Access Denied',
        //         'message' => "You don't have permission to Remove this resource",
        //         'type' => 'error',
        //     ] ); exit();
        // }

        $remove_many = 0;
        if( null === $id ) $remove_many = 1;
        if( null === $id ) $id = $this->input->post('id');

        if( $this->User->remove($id) ) {
            if( 1 == $remove_many ) {
                $data['message'] = 'Users were successfully removed';
            } else {
                $data['message'] = 'User was successfully removed';
            }
            $data['title'] = "Removed";
            $data['type'] = 'success';
        } else {
            $data['title'] = "Error";
            $data['message'] = 'An error occured while removing the resource';
            $data['type'] = 'error';
        }

        if( $this->input->is_ajax_request() ) {
            echo json_encode( $data ); exit();
        } else {
            $this->session->set_flashdata('message', $data );
            redirect('users');
        }
    }

    public function restore($id=null)
    {
        if( null === $id ) $id = $this->input->post('id');

        if( $this->User->restore($id) ) {
            $data['message'] = 'User was successfully restored';
            $data['type'] = 'success';
        } else {
            $data['message'] = 'An error occured while trying to restore the resource';
            $data['type'] = 'error';
        }

        if( $this->input->is_ajax_request() ) {
            echo json_encode( $data ); exit();
        } else {
            $this->session->set_flashdata('message', $data );
            redirect('users');
        }
    }

    public function delete($id=null)
    {
        if( $this->input->is_ajax_request() )
        {
            /**
             * If $id is null
             * then it's a POST request not DELETE.
             * POST will delete many records
             */
            if( null == $id || !isset($id) )
            {
                $ids = $this->input->post('users_ids');
                if( $this->User->delete($ids) )
                {
                    $data['message'] = 'Users were successfully deleted';
                    $data['type'] = 'success';

                    /*
                    | --------------------------------
                    | # Update Many Contacts
                    | --------------------------------
                    | All Contacts with this Users ID
                    */
                    foreach ($ids as $contacts_id) {
                        $contacts = $this->Contact->where(['contacts_user'=>$contacts_id])->get()->result_array();
                        foreach ($contacts as $contact) {
                            $this->Contact->update($contact['contacts_id'], ['contacts_user'=>'']);
                        }
                    }
                }
                else
                {
                    $data['message'] = 'An unhandled error occured. Record was not deleted';
                    $data['type'] = 'error';
                }
                echo json_encode( $data );
                exit();
            }

            $data = array();
            if( $this->User->delete($id) )
            {
                $data['message'] = 'User was successfully deleted';
                $data['type'] = 'success';

                /*
                | --------------------------------
                | # Update Contacts
                | --------------------------------
                | All Contacts with this Users ID
                */
                $contacts = $this->Contact->where(['contacts_user'=>$id])->get()->result_array();
                foreach ($contacts as $contact) {
                    $this->Contact->update($contact['contacts_id'], ['contacts_user'=>'']);
                }


            }
            else
            {
                $data['message'] = 'An unhandled error occured. Record was not deleted';
                $data['type'] = 'danger';
            }
            echo json_encode( $data );
            exit();
        }
    }

    /**
     * Import Page for this controller
     * @return [type] [description]
     */
    public function import()
    {
        $this->load->view('layouts/main', $this->Data);
    }

    /**
     * Export Page for this controller
     * @return [type] [description]
     */
    public function export()
    {
        $this->load->view('layouts/main', $this->Data);
    }

    public function seed()
    {
        $data = array(
            'username'    => 'admin',
            'password'   => password_hash('admin', PASSWORD_BCRYPT),
            'email'     => 'john.dionisio1@gmail.com',
            'firstname'        => 'John Lioneil',
            'middlename'         => 'Palanas',
            'lastname'      => 'Dionisio',
            'remember_token'       => 0,
        );
        $this->User->insert($data);
        echo "alright"; exit();
    }
}