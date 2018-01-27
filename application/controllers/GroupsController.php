<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GroupsController extends CI_Controller {

    private $Data = array();
    private $user_id = 0;

    public function __construct()
    {
        parent::__construct();
        $this->validated();


        $this->load->model('Group', '', TRUE);
        $this->load->model('Member', '', TRUE);
        $this->load->model('GroupMember', '', TRUE);
        $this->load->model('PrivilegesLevel', '', TRUE);
        $this->load->model('Module', '', TRUE);
        $this->user_id = $this->session->userdata('id');
        $this->session->set_userdata('referred_from', current_url());

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

        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/js/specifics/groups.js').'"></script>';
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
        if( $this->Auth->can(['groups', 'groups/listing']) ) {
            $this->Data['groups'] = $this->Group->all();
            $this->load->view('layouts/main', $this->Data);
        } else {
            $this->Data['Headers']->Page = 'errors/403';
            $this->load->view('layouts/errors', $this->Data);
        }
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
            $total        = $this->Group->get_all(0, 0, null, $removed_only)->num_rows();

            if( null != $wildcard )
            {
                $groups = $this->Group->like($wildcard, $start_from, $limit, $sort, $removed_only)->result_array();
                $total  = $this->Group->like($wildcard, 0, 0, null, $removed_only)->num_rows();
            }
            else
            {
                $groups = $this->Group->get_all($start_from, $limit, $sort, $removed_only)->result_array();
            }

            foreach ($groups as $key => $group) {
                $bootgrid_arr[] = array(
                    'count_id'           => $key + 1 + $start_from,
                    'groups_id'          => $group['groups_id'],
                    'groups_name'        => $group['groups_name'],
                    'groups_description' => $group['groups_description'],
                    'groups_code'        => $group['groups_code'],
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

    public function check($can=null)
    {
        $can = (null == $can) ? $this->input->post('can') : $can;
        if( !$this->Auth->can($can) ) {
            if ($this->input->is_ajax_request()) {
                echo json_encode( [
                    'title' => 'Access Denied',
                    'message' => "You don't have permission to Add to this resource",
                    'type' => 'error',
                ] ); exit();
            } else {
                $this->Data['Headers']->Page = 'errors/403';
                $this->load->view('layouts/errors', $this->Data);
                return false;
            }
        }
        if ($this->input->is_ajax_request()) {
            echo json_encode( [
                'title' => 'Access Granted',
                'message' => "Please proceed",
                'type' => 'success',
            ] ); exit();
        } else {
            return false;
        }
    }

    public function add()
    {
        if( !$this->Auth->can(['groups/add']) ) {
            $this->Data['Headers']->Page = 'errors/403';
            $this->load->view('layouts/errors', $this->Data);
            echo json_encode( [
                'title' => 'Access Denied',
                'message' => "You don't have permission to Remove this resource",
                'type' => 'error',
            ] ); exit();
        }

        if( $this->input->is_ajax_request() ) {
            /*
            | --------------------------------------
            | # Validation
            | --------------------------------------
            */
            if( $this->Group->validate(true) ) {
                /*
                | --------------------------------------
                | # Save
                | --------------------------------------
                */
                $group = array(
                    'groups_name'           => $this->input->post('groups_name'),
                    'groups_description'    => $this->input->post('groups_description'),
                    'groups_code'           => $this->input->post('groups_code'),
                    'created_by'            => $this->user_id,
                );
                $this->Group->insert($group);
                $group_id = $this->db->insert_id();
                /*
                | --------------------------------------
                | # Save the Members Groups
                | --------------------------------------
                */
                if( null !== $this->input->post('groups_members') && $members_ids = $this->input->post('groups_members') )
                {
                    $groups_members = explode(",", $members_ids);
                    foreach ($groups_members as $member_id) {
                        $member = $this->Member->find($member_id);
                        $member_group = explode( ",", $member->groups);

                        # Check if the value is already in the resource,
                        # add to array if not.
                        if( !in_array($group_id, $member_group) ) {
                            $member_group[] = $group_id;
                        } else {
                            $data = array(
                                'message' => 'Member is already in this group',
                                'type' => 'danger',
                                // 'debug' => $member_group,
                                // "input" => $this->input->post('value'),
                            );
                            echo json_encode( $data );
                            exit();
                        }

                        $member_group = arraytoimplode($member_group);
                        $this->Member->update($member_id, array('groups'=> $member_group));

                    }

                    # Add data to group_members
                    foreach ($groups_members as $member_id) {
                        $this->GroupMember->insert( array(
                            'group_id' => $group_id,
                            'member_id' => $member_id,
                        ) );
                    }
                }

                /*
                | ----------------------------------------
                | # Response
                | ----------------------------------------
                */
                $data = array(
                    'message' => 'Group was successfully added',
                    'type'    => 'success'
                );
                echo json_encode( $data ); exit();
            } else {
                echo json_encode(['message'=>$this->form_validation->toArray(), 'type'=>'danger']); exit();
            }

        } else {
            redirect( base_url('groups') );
        }
    }

    /**
     * Retrieve the resource for editing
     * @param  int $id
     * @return JSON
     */
    public function edit($id)
    {
        if( !$this->Auth->can() ) {
            $this->Data['Headers']->Page = 'errors/403';
            $this->load->view('layouts/errors', $this->Data);
            echo json_encode( [
                'title' => 'Access Denied',
                'message' => "You don't have permission to Edit this resource",
                'type' => 'error',
            ] ); exit();
        }

        if( $this->input->is_ajax_request() ) {
            $group = $this->Group->find( $id );
            echo json_encode( $group );
            exit();
        }
    }

    public function update($id)
    {
        if( $this->input->is_ajax_request() ) {
            /*
            | --------------------------------------
            | # Validation
            | --------------------------------------
            */
            if( $this->Group->validate(false, $id, $this->input->post('groups_code')) ) {
                /*
                | --------------------------------------
                | # Update
                | --------------------------------------
                */
                $group = array(
                    'groups_name'    => $this->input->post('groups_name'),
                    'groups_description'   => $this->input->post('groups_description'),
                    'groups_code'     => $this->input->post('groups_code'),
                    'updated_by' => $this->user_id,
                );
                $this->Group->update($id, $group);

                # Look up any member that shouldn't be in the group anymore
                $old_members_of_this_group = $this->GroupMember->lookup('group_id', $id)->result_array();
                foreach ($old_members_of_this_group as $group_members) {
                    
                    $old_member = $this->Member->find( $group_members['member_id'] );
                    
                    $old_member_groups = [];
                    
                    if(!empty($old_member->groups)) $old_member_groups = explode(",", $old_member->groups);
                    
                    if( !in_array($old_member->id, explodetoarray($this->input->post('groups_members'))) ) 
                    {
                        $index = array_search($group_members['group_id'], $old_member_groups);
                        unset( $old_member_groups[ $index ] );
                    }

                    $old_member_groups = arraytoimplode($old_member_groups); // implode to string
                    
                    # Update the member
                    $this->Member->update($old_member->id, array(
                        'groups' => $old_member_groups,
                        'updated_by' => $this->user_id,
                    ));
                }

                # Update the members.groups
                $members = $this->Member->find( explodetoarray($this->input->post('groups_members')) );
                // $this->GroupMember->delete($id);
                
                foreach ($members->result_array() as $member) {
                    
                    $member_groups = [];
                    
                    if(!empty($member['groups'])) $member_groups = explode(",", $member['groups']);

                    if( !in_array($id, $member_groups) ) {
                        $member_groups[] = $id;
                    }

                    $member_groups = arraytoimplode($member_groups); // implode to string

                    # Update the member
                    $this->Member->update($member['id'], array(
                        'groups' => $member_groups,
                        'updated_by' => $this->user_id,
                    ));

                    # Update the group_members
                    $group_ids = explodetoarray($member_groups);
                    $this->GroupMember->delete_member($member['id'], $id);
                    foreach ($group_ids as $group_id) {
                        $exist = $this->GroupMember->check_if_exist($member['id'], $group_id);
                        
                        if(!($exist > 0)) {
                            $this->GroupMember->insert( array('group_id' => $group_id, 'member_id' => $member['id'] ) );
                        }
                    }
                }


                $data = array(
                    'message' => 'Group was successfully updated',
                    'type' => 'success',
                    'debug-2' => $old_members_of_this_group,
                );

                echo json_encode( $data );

                exit();

            } else {
                echo json_encode(['message'=>$this->form_validation->toArray(), 'type'=>'danger']); exit();
            }
        }
    }

    public function trash()
    {
        if( !$this->Auth->can(['groups/trash']) ) {
            $this->Data['Headers']->Page = 'errors/403';
            $this->load->view('layouts/errors', $this->Data);
            return false;
        }

        $this->Data['members'] = $this->Group->all(true);
        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/js/specifics/groupsTrash.js').'"></script>';
        $this->load->view('layouts/main', $this->Data);
    }

    public function remove($id=null)
    {
        if( !$this->Auth->can() ) {
            $this->Data['Headers']->Page = 'errors/403';
            $this->load->view('layouts/errors', $this->Data);
            echo json_encode( [
                'title' => 'Access Denied',
                'message' => "You don't have permission to Remove this resource",
                'type' => 'error',
            ] ); exit();
        }

        $remove_many = 0;
        if( null === $id ) $remove_many = 1;
        if( null === $id ) $id = $this->input->post('id');

        if( $this->Group->remove($id) ) {
            if( 1 == $remove_many ) {
                $data['message'] = 'Groups were successfully removed';
            } else {
                $data['message'] = 'Group was successfully removed';
            }
            $data['type'] = 'success';
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

    public function group_members_remove($id=null)
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

        foreach ($this->input->post('id') as $member) {
            $this->GroupMember->remove_member($member, $this->input->post('group'));
        }

        if( $remove_many > 0) {
            $data['message'] = 'Members were successfully removed';
            $data['type'] = 'success';
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

        if( $this->Group->restore($id) ) {
            $data['message'] = 'Group was successfully restored';
            $data['type'] = 'success';
        } else {
            $data['message'] = 'An error occured while trying to restore the resource';
            $data['type'] = 'error';
        }

        if( $this->input->is_ajax_request() ) {
            echo json_encode( $data ); exit();
        } else {
            $this->session->set_flashdata('message', $data );
            redirect('members');
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
                $ids = $this->input->post('groups_ids');
                if( $this->Group->delete($ids) )
                {
                    $data['message'] = 'Groups were successfully deleted';
                    $data['type'] = 'success';

                    /*
                    | --------------------------------
                    | # Update Many Members
                    | --------------------------------
                    | All Members with this Groups ID
                    */
                    foreach ($ids as $members_id) {
                        $members = $this->Member->where(['groups'=>$members_id])->get()->result_array();
                        foreach ($members as $member) {
                            $this->Member->update($member['members_id'], ['groups'=>'']);
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
            if( $this->Group->delete($id) )
            {
                $data['message'] = 'Group was successfully deleted';
                $data['type'] = 'success';

                /*
                | --------------------------------
                | # Update Members
                | --------------------------------
                | All Members with this Groups ID
                */
                $members = $this->Member->where(['groups'=>$id])->get()->result_array();
                foreach ($members as $member) {
                    $this->Member->update($member['members_id'], ['groups'=>'']);
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
        if( !$this->Auth->can(['groups/import']) ) {
            $this->Data['Headers']->Page = 'errors/403';
            $this->load->view('layouts/errors', $this->Data);
            return false;
        }

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
                $full_path = $this->upload->data();
                if( $this->Group->import( $this->upload->data()['full_path'] ) )
                {
                    # Delete uploaded file
                    clean_upload_folder( $full_path['full_path'] );

                    # Response
                    $data = array('message' => 'Groups successfully imported.', 'type'=>'success');

                } else {
                    $data = array('message' => 'Something went wrong importing the file', 'type'=>'error');
                }

            }
            echo json_encode( $data );
            exit();

        }

        $this->Data['Headers']->CSS.= '<link rel="stylesheet" href="'.base_url('assets/vendors/dropzone/dropzone.css').'"></link>';
        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/vendors/dropzone/dropzone.min.js').'"></script>';
        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/js/specifics/groupsImport.js').'"></script>';

        $this->load->view('layouts/main', $this->Data);
    }

    /**
     * Export Page for this controller
     * @return [type] [description]
     */
    public function export()
    {
        if( !$this->Auth->can(['groups/export']) ) {
            $this->Data['Headers']->Page = 'errors/403';
            $this->load->view('layouts/errors', $this->Data);
            return false;
        }

        if( null != $this->input->post('export_start') )
        {
            $export = $this->Group->export( false, date('Y-m-d', strtotime($this->input->post('export_start'))), date('Y-m-d', strtotime($this->input->post('export_end') . ' +1 day')), $this->input->post('export_level') );
            /*
            | ---------------------------------------------
            | # Validation
            | ---------------------------------------------
            */
            $result = $this->Group->export( false, date('Y-m-d', strtotime($this->input->post('export_start'))), date('Y-m-d', strtotime($this->input->post('export_end') . ' +1 day')), $this->input->post('export_level') )->result();
            if( empty( $result ) ) {
                $this->Data['messages']['error'] = 'No Record was found in the Dates specified';
            } else
            {
                # Export
                #// Load the DB Utility
                $this->load->dbutil();
                switch ( $this->input->post('export_format') ) {
                    case 'CSV':
                        $CSV =  $this->dbutil->csv_from_result( $export );
                        $csv_name = 'Groups_' . date('Y-m-d-H-i') . '.export.csv';
                        force_download($csv_name, $CSV);
                        // $data = array('message' => 'Export was successful', 'type'=>'success');
                        break;

                    case 'SQL':
                        $SQL = $this->dbutil->backup(['tables'=>'{PRE}members']);
                        $sql_name = 'Groups_' . date('Y-m-d-H-i') . '.export.zip';
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
                $this->session->set_flashdata('message', array('type'=>'success', 'message'=>"Export completed"));
            }
        }

        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/js/specifics/groupsExport.js').'"></script>';
        $this->load->view('layouts/main', $this->Data);
    }
}