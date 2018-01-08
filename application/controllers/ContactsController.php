<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ContactsController extends CI_Controller {

    private $Data = array();
    private $hidden;

    public function __construct()
    {
        parent::__construct();
        $this->validated();

        $this->hidden = rand();
        $this->load->model('Contact', '', TRUE);
        $this->load->model('Group', '', TRUE);
        $this->load->model('Level', '', TRUE);
        $this->load->model('Type', '', TRUE);

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

        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/js/specifics/contacts.js').'"></script>';
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
        $this->Data['contacts'] = $this->Contact->all();
        $this->Data['form']['groups_list'] = dropdown_list($this->Group->dropdown_list('groups_id, groups_name')->result_array(), ['groups_id', 'groups_name'], 'No Group');
        $this->Data['form']['levels_list'] = dropdown_list($this->Level->dropdown_list('levels_id, levels_name')->result_array(), ['levels_id', 'levels_name'], 'No Level');
        $this->Data['form']['types_list']  = dropdown_list($this->Type->dropdown_list('types_id, types_name')->result_array(), ['types_id', 'types_name'], 'No Type');
        $this->load->view('layouts/main', $this->Data);
    }

    public function listing()
    {
        /**
         * AJAX List of Data
         * Here we load the list of data in a table
         */
        if( $this->input->is_ajax_request() ) {
            $bootgrid_arr = [];
            $current      = $this->input->post('current');
            $limit        = $this->input->post('rowCount') == -1 ? 0 : $this->input->post('rowCount');
            $page         = $current !== null ? $current : 1;
            $start_from   = ($page-1) * $limit;
            $sort         = null != $this->input->post('sort') ? $this->input->post('sort') : null;
            $wildcard     = null != $this->input->post('searchPhrase') ? $this->input->post('searchPhrase') : null;
            $total        = $this->Contact->get_all()->num_rows();

            if( null != $wildcard )
            {
                $contacts = $this->Contact->like($wildcard, $start_from, $limit, $sort)->result_array();
                $total    = $this->Contact->like($wildcard)->num_rows();
            }
            else
            {
                $contacts = $this->Contact->get_all($start_from, $limit, $sort)->result_array();
            }

            foreach ($contacts as $key => $contact) {

                $group = null;
                if( null != $contact['contacts_group'] ) $group = $this->Group->find( explodetoarray($contact['contacts_group']) );
                $groups_name_arr = [];
                $groups_id_arr = [];
                if( is_array( $group ) )
                {
                    foreach ($group as $group_single) {
                        $groups_name_arr[] = $group_single->groups_name;
                        $groups_id_arr[] = $group_single->groups_id;
                    }
                }

                if( null !== $contact['contacts_level'] ) $level = $this->Level->find( explodetoarray($contact['contacts_level']) );
                $levels_name_arr = [];
                $levels_id_arr = [];
                if( is_array( $level ) )
                {
                    foreach ($level as $level_single) {
                        $levels_name_arr[] = $level_single->levels_name;
                        $levels_id_arr[] = $level_single->levels_id;
                    }
                }

                if( null !== $contact['contacts_type'] ) $type = $this->Type->find( explodetoarray($contact['contacts_type']) );
                $types_name_arr = [];
                $types_id_arr = [];
                if( is_array( $type ) )
                {
                    foreach ($type as $type_single) {
                        $types_name_arr[] = $type_single->types_name;
                        $types_id_arr[] = $type_single->types_id;
                    }
                }

                $bootgrid_arr[] = array(
                    'count_id'           => $key + 1 + $start_from,
                    'contacts_id'        => $contact['contacts_id'],
                    'contacts_firstname' => arraytostring([$contact['contacts_firstname'], $contact['contacts_middlename'] ? substr($contact['contacts_middlename'], 0,1) . '.' : '', $contact['contacts_lastname']], ' '),
                    'contacts_level'     => $levels_name_arr ? arraytostring($levels_name_arr, ", ") : '',
                    'levels_id'          => $levels_id_arr ? $levels_id_arr : '',
                    'contacts_type'      => $types_name_arr ? arraytostring($types_name_arr, ", ") : '',
                    'types_id'          => $types_id_arr ? $types_id_arr : '',
                    'contacts_address'   => arraytostring([$contact['contacts_blockno'], $contact['contacts_street'], $contact['contacts_brgy'], $contact['contacts_city'], $contact['contacts_zip']]),
                    'contacts_telephone' => $contact['contacts_telephone'],
                    'contacts_mobile'    => $contact['contacts_mobile'],
                    'contacts_email'     => $contact['contacts_email'],
                    'contacts_group'     => $groups_name_arr ? arraytostring($groups_name_arr, ", ") : '', //isset($group->groups_name) ? $group->groups_name : '',
                    'groups_id'          => $groups_id_arr ? $groups_id_arr : '',//isset($group->groups_id) ? $group->groups_id : '',
                );
            }
            $data = array(
                "current"       => intval($current),
                "rowCount"      => $limit,
                "searchPhrase"  => $wildcard,
                "total"         => intval( $total ),
                "rows"          => $bootgrid_arr,
                // "debug" => $contact['contacts_type'],
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
            | --------------------------------------
            | # Validation
            | --------------------------------------
            */
            if( $this->Contact->validate(true) )
            {
                /*
                | --------------------------------------
                | # Save
                | --------------------------------------
                */
                $contact = array(
                    'contacts_firstname'    => $this->input->post('contacts_firstname'),
                    'contacts_middlename'   => $this->input->post('contacts_middlename'),
                    'contacts_lastname'     => $this->input->post('contacts_lastname'),
                    'contacts_level'        => arraytoimplode($this->input->post('contacts_level')),
                    'contacts_type'         => arraytoimplode($this->input->post('contacts_type')),
                    'contacts_blockno'      => $this->input->post('contacts_blockno'),
                    'contacts_street'       => $this->input->post('contacts_street'),
                    'contacts_brgy'         => $this->input->post('contacts_brgy'),
                    'contacts_city'         => $this->input->post('contacts_city'),
                    'contacts_zip'          => $this->input->post('contacts_zip'),
                    'contacts_telephone'    => $this->input->post('contacts_telephone'),
                    'contacts_mobile'       => $this->input->post('contacts_mobile'),
                    'contacts_email'        => $this->input->post('contacts_email'),
                    'contacts_group'        => arraytoimplode($this->input->post('contacts_group')),
                );
                $this->Contact->insert($contact);

                /*
                | ----------------------------------------
                | # Response
                | ----------------------------------------
                */
                $data = array(
                    'message' => 'Contact was successfully added',
                    'type'    => 'success',
                    'debug'   => $this->input->post('contacts_group'),
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
            redirect( base_url('contacts') );
        }
    }

    /**
     * Retrieve the resource for editing
     * @param  int $id
     * @return AJAX
     */
    public function edit($id)
    {
        if( $this->input->is_ajax_request() )
        {
            $contact = $this->Contact->find( $id );
            echo json_encode( $contact );
            exit();
        }
    }

    public function update($id)
    {
        if( $this->input->is_ajax_request() )
        {
            /*
            | --------------------------------------
            | # Validation
            | --------------------------------------
            */
            if( null != $this->input->post('updating_group') )
            {
                $contact = $this->Contact->find($id);
                $contact_group = [];
                if( !empty($contact->contacts_group) ) $contact_group = explode(",", $contact->contacts_group);

                # If we're Adding a Group
                if( "add" == $this->input->post('action') ) {
                    # Check if the value is already in the resource,
                    # add to array if not.
                    if( !in_array($this->input->post('value'), $contact_group) ) {
                        $contact_group[] = $this->input->post('value');
                    } else {
                        $data = array(
                            'message' => 'Contact is already in this group',
                            'type' => 'danger',
                            // 'debug' => $contact_group,
                            // "input" => $this->input->post('value'),
                        );
                        echo json_encode( $data );
                        exit();
                    }
                }

                # If we're Removing a Group
                if( "remove" == $this->input->post('action') ) {
                    # Remove the value if in the resource
                    if( in_array($this->input->post('value'), $contact_group) ) {
                        $index = array_search($this->input->post('value'), $contact_group);
                        unset( $contact_group[$index] );
                    } else {
                        $data = array(
                            'message' => 'Contact is already not in this group',
                            'type' => 'danger',
                            // 'debug' => $contact_group,
                            // "input" => $this->input->post('value'),
                        );
                        echo json_encode( $data );
                        exit();
                    }
                }

                # Prepare data
                $contact_group = arraytoimplode($contact_group);   // stringify the array E.g. `array("1", "2")` will be `"1,2"`
                $contact_data = array(
                    $this->input->post('updating_group') => $contact_group,
                );

                 # Update
                $this->Contact->update($id, $contact_data);

                # Response
                $data = array(
                    'message' => 'Contact was successfully updated',
                    'type' => 'success',
                    // 'debug' => $contact_group,
                    // "input" => $this->input->post('value'),
                );
                echo json_encode( $data );
                exit();

            }

            if( null != $this->input->post('updating_level') )
            {
                $contact = $this->Contact->find($id);
                $contact_level = [];
                if( !empty($contact->contacts_level) )$contact_level = explode(",", $contact->contacts_level);

                # If we're Adding a Group
                if( "add" == $this->input->post('action') ) {
                    # Check if the value is already in the resource,
                    # add to array if not.
                    if( !in_array($this->input->post('value'), $contact_level) ) {
                        $contact_level[] = $this->input->post('value');
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
                }

                # If we're Removing a Group
                if( "remove" == $this->input->post('action') ) {
                    # Remove the value if in the resource
                    if( in_array($this->input->post('value'), $contact_level) ) {
                        $index = array_search($this->input->post('value'), $contact_level);
                        unset( $contact_level[$index] );
                    } else {
                        $data = array(
                            'message' => 'Contact is already not in this level',
                            'type' => 'danger',
                            // 'debug' => $contact_level,
                            // "input" => $this->input->post('value'),
                        );
                        echo json_encode( $data );
                        exit();
                    }
                }

                # Prepare data
                $contact_level = implode(",", $contact_level);   // stringify the array E.g. `array("1", "2")` will be `"1,2"`
                $contact_data = array(
                    $this->input->post('updating_level') => $contact_level,
                );

                 # Update
                $this->Contact->update($id, $contact_data);

                # Response
                $data = array(
                    'message' => 'Contact was successfully updated',
                    'type' => 'success',
                    // 'debug' => $contact_level,
                    // "input" => $this->input->post('value'),
                );
                echo json_encode( $data );
                exit();

            }

            if( null != $this->input->post('updating_type') )
            {
                $contact = $this->Contact->find($id);
                $contact_type = [];
                if( !empty($contact->contacts_type) ) $contact_type = explode(",", $contact->contacts_type);

                # If we're Adding a Type
                if( "add" == $this->input->post('action') ) {
                    # Check if the value is already in the resource,
                    # add to array if not.
                    if( !in_array($this->input->post('value'), $contact_type) ) {
                        $contact_type[] = $this->input->post('value');
                    } else {
                        $data = array(
                            'message' => 'Contact is already in this type',
                            'type' => 'danger',
                            'debug' => $contact_type,
                            // "input" => $this->input->post('value'),
                        );
                        echo json_encode( $data );
                        exit();
                    }
                }

                # If we're Removing a Type
                if( "remove" == $this->input->post('action') ) {
                    # Remove the value if in the resource
                    if( in_array($this->input->post('value'), $contact_type) ) {
                        $index = array_search($this->input->post('value'), $contact_type);
                        unset( $contact_type[$index] );
                    } else {
                        $data = array(
                            'message' => 'Contact is already not in this type',
                            'type' => 'danger',
                            // 'debug' => $contact_type,
                            // "input" => $this->input->post('value'),
                        );
                        echo json_encode( $data );
                        exit();
                    }
                }

                # Prepare data
                $contact_type = implode(",", $contact_type);   // stringify the array E.g. `array("1", "2")` will be `"1,2"`
                $contact_data = array(
                    $this->input->post('updating_type') => $contact_type,
                );

                 # Update
                $this->Contact->update($id, $contact_data);

                # Response
                $data = array(
                    'message' => 'Contact was successfully updated',
                    'type' => 'success',
                    // 'debug' => $contact_type,
                    // "input" => $this->input->post('value'),
                );
                echo json_encode( $data );
                exit();

            }

            if( $this->Contact->validate(false, $id, $this->input->post('contacts_email')) )
            {
                /*
                | --------------------------------------
                | # Update
                | --------------------------------------
                */
                $contact = array(
                    'contacts_firstname' => $this->input->post('contacts_firstname'),
                    'contacts_middlename' => $this->input->post('contacts_middlename'),
                    'contacts_lastname' => $this->input->post('contacts_lastname'),
                    'contacts_level' => arraytoimplode( $this->input->post('contacts_level') ),
                    'contacts_type' => arraytoimplode( $this->input->post('contacts_type') ),
                    'contacts_blockno' => $this->input->post('contacts_blockno'),
                    'contacts_street' => $this->input->post('contacts_street'),
                    'contacts_brgy' => $this->input->post('contacts_brgy'),
                    'contacts_city' => $this->input->post('contacts_city'),
                    'contacts_zip' => $this->input->post('contacts_zip'),
                    'contacts_telephone' => $this->input->post('contacts_telephone'),
                    'contacts_mobile' => $this->input->post('contacts_mobile'),
                    'contacts_email' => $this->input->post('contacts_email'),
                    'contacts_group' => arraytoimplode( $this->input->post('contacts_group') ),
                );
                $this->Contact->update($id, $contact);

                $data = array(
                    'message' => 'Contact was successfully updated',
                    'type' => 'success',
                    'debug'=>$contact,
                );
                echo json_encode( $data );
                exit();
            }
            else
            {
                echo json_encode(['message'=>$this->form_validation->toArray(), 'type'=>'error']); exit();
            }
        }
    }

    public function trash()
    {
        $this->load->view('layouts/main', $this->Data);
    }

    public function remove($id=null)
    {
        if( null === $id ) $id = $this->input->post('contacts_id');

        if( $this->Contact->remove($id) ) {
            $data['contact']['message'] = 'Contact was successfully removed';
            $data['contact']['type'] = 'success';
        } else {
            $data['message'] = 'An error occured while removing the resource';
            $data['type'] = 'error';
        }

        if( $this->input->is_ajax_request() ) {
            echo json_encode( $data );
            exit();
        } else {
            $this->session->set_userdata( $data );
            redirect('contacts');
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
                $ids = $this->input->post('contacts_id');
                if( $this->Contact->delete($ids) )
                {
                    $data['message'] = 'Contacts were successfully deleted';
                    $data['type'] = 'success';
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
            if( $this->Contact->delete($id) )
            {
                $data['message'] = 'Contact was successfully deleted';
                $data['type'] = 'success';
            }
            else
            {
                $data['message'] = 'An unhandled error occured. Record was not deleted';
                $data['type'] = 'error';
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

        if( $this->input->is_ajax_request() )
        {
            $this->load->library('upload', ['upload_path'=>'./uploads/', 'allowed_types'=>'csv']);

            if ( !$this->upload->do_upload('file'))
            {
                $data = array('message' => $this->upload->display_errors(), 'type'=>'error');
            }
            else
            {
                # Import
                $full_path = $this->upload->data()['full_path'];
                if( $this->Contact->import( $this->upload->data()['full_path'] ) )
                {
                    # Delete uploaded file
                    clean_upload_folder( $full_path );

                    # Response
                    $data = array('message' => 'Contacts successfully imported.', 'type'=>'success');

                } else {
                    $data = array('message' => 'Something went wrong importing the file', 'type'=>'error');
                }

            }
            echo json_encode( $data );
            exit();

        }

        $this->Data['Headers']->CSS.= '<link rel="stylesheet" href="'.base_url('assets/vendors/dropzone/dropzone.css').'"></link>';
        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/vendors/dropzone/dropzone.min.js').'"></script>';
        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/js/specifics/contactsImport.js').'"></script>';

        $this->load->view('layouts/main', $this->Data);

    }

    /**
     * Export Page for this controller
     * @return [type] [description]
     */
    public function export()
    {
        if( null != $this->input->post('export_start') )
        {
            $export = $this->Contact->export( false, date('Y-m-d', strtotime($this->input->post('export_start'))), date('Y-m-d', strtotime($this->input->post('export_end') . ' +1 day')), $this->input->post('export_level') );
            /*
            | ---------------------------------------------
            | # Validation
            | ---------------------------------------------
            */
            $result = $this->Contact->export( false, date('Y-m-d', strtotime($this->input->post('export_start'))), date('Y-m-d', strtotime($this->input->post('export_end') . ' +1 day')), $this->input->post('export_level') )->result();
            if( empty( $result ) ) {
                $this->Data['messages']['error'] = 'No Record was found in the Dates or Level specified';
            } else
            {
                # Export
                #// Load the DB Utility
                $this->load->dbutil();
                switch ( $this->input->post('export_format') ) {
                    case 'CSV':
                        $CSV =  $this->dbutil->csv_from_result( $export );
                        $csv_name = 'Contacts_' . date('Y-m-d-H-i') . '.export.csv';
                        force_download($csv_name, $CSV);
                        // $data = array('message' => 'Export was successful', 'type'=>'success');
                        break;

                    case 'SQL':
                        $SQL = $this->dbutil->backup(['tables'=>'{PRE}contacts']);
                        $sql_name = 'Contacts_' . date('Y-m-d-H-i') . '.export.zip';
                        force_download($sql_name, $SQL);
                        break;

                    case 'PDF':
                        die('PDF is not available on your user level');
                        break;
                    default:
                        break;
                }


                # Response
                # -- No response yet --
            }
        }

        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/js/specifics/contactsExport.js').'"></script>';
        $this->Data['form']['levels_list'] = dropdown_list($this->Level->dropdown_list('levels_id, levels_name')->result_array(), ['levels_id', 'levels_name'], 'No Level');
        $this->load->view('layouts/main', $this->Data);
    }

}