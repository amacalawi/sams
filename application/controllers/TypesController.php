<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TypesController extends CI_Controller {

    private $Data = array();

    public function __construct()
    {
        parent::__construct();
        $this->validated();

        $this->load->model('Type', '', TRUE);
        $this->load->model('Contact', '', TRUE);

        $this->Data['Headers'] = get_page_headers();
        $this->Data['Headers']->CSS = '<link rel="stylesheet" href="'.base_url('assets/vendors/bootgrid/jquery.bootgrid.min.css').'">';
        // $this->Data['Headers']->CSS.= '<link rel="stylesheet" href="'.base_url('assets/vendors/chosen/chosen.min.css').'">';
        $this->Data['Headers']->CSS.= '<link rel="stylesheet" href="'.base_url('assets/vendors/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css').'">';

        $this->Data['Headers']->JS  = '<script src="'.base_url('assets/vendors/bootgrid/jquery.bootgrid.min.js').'"></script>';
        // $this->Data['Headers']->JS .= '<script src="'.base_url('assets/vendors/chosen/chosen.jquery.min.js').'"></script>';
        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/vendors/moment/min/moment.min.js').'"></script>';
        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/vendors/bootstrap-growl/bootstrap-growl.min.js').'"></script>';
        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/vendors/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js').'"></script>';
        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/vendors/autosize/dist/autosize.min.js').'"></script>';
        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/vendors/jquery.validate/dist/jquery.validate.min.js').'"></script>';

        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/js/specifics/types.js').'"></script>';
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
        $this->Data['types'] = $this->Type->all();
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
            $total        = $this->Type->get_all()->num_rows();

            if( null != $wildcard )
            {
                $types = $this->Type->like($wildcard, $start_from, $limit, $sort)->result_array();
                $total  = $this->Type->like($wildcard)->num_rows();
            }
            else
            {
                $types = $this->Type->get_all($start_from, $limit, $sort)->result_array();
            }

            foreach ($types as $key => $type) {
                $bootgrid_arr[] = array(
                    'count_id'           => $key + 1 + $start_from,
                    'types_id'          => $type['types_id'],
                    'types_name'        => $type['types_name'],
                    'types_description' => $type['types_description'],
                    'types_code'        => $type['types_code'],
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
            if( $this->Type->validate(true) )
            {
                /*
                | --------------------------------------
                | # Save
                | --------------------------------------
                */
                $type = array(
                    'types_name'    => $this->input->post('types_name'),
                    'types_description'   => $this->input->post('types_description'),
                    'types_code'     => $this->input->post('types_code')
                );
                $type_id = $this->Type->insert($type);
                /*
                | --------------------------------------
                | # Save the Contacts Types
                | --------------------------------------
                */
                if( null !== $this->input->post('types_contacts') && $contacts_ids = $this->input->post('types_contacts') )
                {
                    $types_contacts = explode(",", $contacts_ids);
                    foreach ($types_contacts as $contact_id) {
                        $contact = $this->Contact->find($contact_id);
                        $contact_type = explode( ",", $contact->contacts_type);

                        # Check if the value is already in the resource,
                        # add to array if not.
                        if( !in_array($type_id, $contact_type) ) {
                            $contact_type[] = $type_id;
                        } else {
                            $data = array(
                                'message' => 'Contact is already in this type',
                                'type' => 'danger',
                                // 'debug' => $contact_type,
                                // "input" => $this->input->post('value'),
                            );
                            echo json_encode( $data );
                            exit();
                        }

                        $contact_type = arraytoimplode($contact_type);
                        $this->Contact->update($contact_id, array('contacts_type'=> $contact_type));
                    }
                }

                /*
                | ----------------------------------------
                | # Response
                | ----------------------------------------
                */
                $data = array(
                    'message' => 'Type was successfully added',
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
            redirect( base_url('types') );
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
            $type = $this->Type->find( $id );
            echo json_encode( $type );
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
            // $data['message'] = $this->input->post('types_contacts');
            // $data['type'] = 'success';
            // echo json_encode($data); exit();
            /*
            | --------------------------------------
            | # Validation
            | --------------------------------------
            */
            if( $this->Type->validate(false, $id, $this->input->post('types_code')) )
            {
                /*
                | --------------------------------------
                | # Update
                | --------------------------------------
                */
                $type = array(
                    'types_name'    => $this->input->post('types_name'),
                    'types_description'   => $this->input->post('types_description'),
                    'types_code'     => $this->input->post('types_code')
                );
                $this->Type->update($id, $type);
                /*
                | --------------------------------------
                | # Update the Contacts Types
                | --------------------------------------
                */
                if( null !== $this->input->post('types_contacts') && $contacts_ids = $this->input->post('types_contacts') )
                {
                    $types_contacts = explode(",", $contacts_ids);

                    foreach ($types_contacts as $contact_id) {
                        $this->Contact->update($contact_id, array('contacts_type'=> $id));
                    }
                }

                $data = array(
                    'message' => 'Type was successfully updated',
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
                $ids = $this->input->post('types_ids');
                if( $this->Type->delete($ids) )
                {
                    $data['message'] = 'Types were successfully deleted';
                    $data['type'] = 'success';

                    /*
                    | --------------------------------
                    | # Update Many Contacts
                    | --------------------------------
                    | All Contacts with this Types ID
                    */
                    foreach ($ids as $contacts_id) {
                        $contacts = $this->Contact->where(['contacts_type'=>$contacts_id])->get()->result_array();
                        foreach ($contacts as $contact) {
                            $this->Contact->update($contact['contacts_id'], ['contacts_type'=>'']);
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
            if( $this->Type->delete($id) )
            {
                $data['message'] = 'Type was successfully deleted';
                $data['type'] = 'success';

                /*
                | --------------------------------
                | # Update Contacts
                | --------------------------------
                | All Contacts with this Types ID
                */
                $contacts = $this->Contact->where(['contacts_type'=>$id])->get()->result_array();
                foreach ($contacts as $contact) {
                    $this->Contact->update($contact['contacts_id'], ['contacts_type'=>'']);
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