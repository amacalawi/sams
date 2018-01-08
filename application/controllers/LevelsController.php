<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LevelsController extends CI_Controller {

    private $Data = array();

    public function __construct()
    {
        parent::__construct();
        $this->validated();
        $this->load->model('Level', '', TRUE);
        $this->load->model('Contact', '', TRUE);

        $this->Data['Headers'] = get_page_headers();
        $this->Data['Headers']->CSS = '<link rel="stylesheet" href="'.base_url('assets/vendors/bootgrid/jquery.bootgrid.min.css').'">';
        $this->Data['Headers']->CSS.= '<link rel="stylesheet" href="'.base_url('assets/vendors/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css').'">';

        $this->Data['Headers']->JS  = '<script src="'.base_url('assets/vendors/bootgrid/jquery.bootgrid.min.js').'"></script>';
        // $this->Data['Headers']->JS .= '<script src="'.base_url('assets/vendors/chosen/chosen.jquery.min.js').'"></script>';
        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/vendors/moment/min/moment.min.js').'"></script>';
        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/vendors/bootstrap-growl/bootstrap-growl.min.js').'"></script>';
        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/vendors/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js').'"></script>';
        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/vendors/autosize/dist/autosize.min.js').'"></script>';
        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/vendors/jquery.validate/dist/jquery.validate.min.js').'"></script>';

        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/js/specifics/levels.js').'"></script>';
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
        $this->Data['levels'] = $this->Level->all();
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
            $total        = $this->Level->get_all()->num_rows();

            if( null != $wildcard )
            {
                $levels = $this->Level->like($wildcard, $start_from, $limit, $sort)->result_array();
                $total  = $this->Level->like($wildcard)->num_rows();
            }
            else
            {
                $levels = $this->Level->get_all($start_from, $limit, $sort)->result_array();
            }

            foreach ($levels as $key => $level) {
                $bootgrid_arr[] = array(
                    'count_id'           => $key + 1 + $start_from,
                    'levels_id'          => $level['levels_id'],
                    'levels_name'        => $level['levels_name'],
                    'levels_description' => $level['levels_description'],
                    'levels_code'        => $level['levels_code'],
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
    }

    public function add()
    {
        if( $this->input->is_ajax_request() )
        {
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
            if( $this->Level->validate(true) )
            {
                /*
                | --------------------------------------
                | # Save
                | --------------------------------------
                */
                $level = array(
                    'levels_name'    => $this->input->post('levels_name'),
                    'levels_description'   => $this->input->post('levels_description'),
                    'levels_code'     => $this->input->post('levels_code')
                );
                $level_id = $this->Level->insert($level);
                /*
                | --------------------------------------
                | # Save the Contacts Levels
                | --------------------------------------
                */
                if( null !== $this->input->post('levels_contacts') && $contacts_ids = $this->input->post('levels_contacts') )
                {
                    $levels_contacts = explode(",", $contacts_ids);
                    foreach ($levels_contacts as $contact_id) {
                        $contact = $this->Contact->find($contact_id);
                        $contact_level = explode( ",", $contact->contacts_level);

                        # Check if the value is already in the resource,
                        # add to array if not.
                        if( !in_array($level_id, $contact_level) ) {
                            $contact_level[] = $level_id;
                        } else {
                            $data = array(
                                'message' => 'Contact is already in this level',
                                'type' => 'danger',
                                // 'debug' => $contact_level,
                                // "input" => $this->input->post('value'),
                            );
                            echo json_encode( $data );
                            exit();
                        }

                        $contact_level = arraytoimplode($contact_level);
                        $this->Contact->update($contact_id, array('contacts_level'=> $contact_level));
                    }
                }

                /*
                | ----------------------------------------
                | # Response
                | ----------------------------------------
                */
                $data = array(
                    'message' => 'Level was successfully added',
                    'type'    => 'success'
                );
                echo json_encode( $data ); exit();
            }
            else
            {
                echo json_encode(['message'=>$this->form_validation->toArray(), 'type'=>'danger']); exit();
            }

        }
        else
        {
            redirect( base_url('levels') );
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
            $level = $this->Level->find( $id );
            echo json_encode( $level );
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
            // $data['message'] = $this->input->post('levels_contacts');
            // $data['type'] = 'success';
            // echo json_encode($data); exit();
            /*
            | --------------------------------------
            | # Validation
            | --------------------------------------
            */
            if( $this->Level->validate(false, $id, $this->input->post('levels_code')) )
            {
                /*
                | --------------------------------------
                | # Update
                | --------------------------------------
                */
                $level = array(
                    'levels_name'    => $this->input->post('levels_name'),
                    'levels_description'   => $this->input->post('levels_description'),
                    'levels_code'     => $this->input->post('levels_code')
                );
                $this->Level->update($id, $level);
                /*
                | --------------------------------------
                | # Update the Contacts Levels
                | --------------------------------------
                */
                if( null !== $this->input->post('levels_contacts') && $contacts_ids = $this->input->post('levels_contacts') )
                {
                    $levels_contacts = explode(",", $contacts_ids);

                    foreach ($levels_contacts as $contact_id) {
                        $this->Contact->update($contact_id, array('contacts_level'=> $id));
                    }
                }

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
                $ids = $this->input->post('levels_ids');
                if( $this->Level->delete($ids) )
                {
                    $data['message'] = 'Levels were successfully deleted';
                    $data['type'] = 'success';

                    /*
                    | --------------------------------
                    | # Update Many Contacts
                    | --------------------------------
                    | All Contacts with this Levels ID
                    */
                    foreach ($ids as $contacts_id) {
                        $contacts = $this->Contact->where(['contacts_level'=>$contacts_id])->get()->result_array();
                        foreach ($contacts as $contact) {
                            $this->Contact->update($contact['contacts_id'], ['contacts_level'=>'']);
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
            if( $this->Level->delete($id) )
            {
                $data['message'] = 'Level was successfully deleted';
                $data['type'] = 'success';

                /*
                | --------------------------------
                | # Update Contacts
                | --------------------------------
                | All Contacts with this Levels ID
                */
                $contacts = $this->Contact->where(['contacts_level'=>$id])->get()->result_array();
                foreach ($contacts as $contact) {
                    $this->Contact->update($contact['contacts_id'], ['contacts_level'=>'']);
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
}