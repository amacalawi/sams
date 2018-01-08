<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MembersController extends CI_Controller {

    private $Data = array();
    private $user_id = 0;
    private $member_photo_url = "";

    public function __construct()
    {
        parent::__construct();
        $this->validated();

        $this->load->model('Member', '', TRUE);
        $this->load->model('Group', '', TRUE);
        $this->load->model('GroupMember', '', TRUE);
        $this->load->model('Level', '', TRUE);
        $this->load->model('Type', '', TRUE);
        $this->load->model('Schedule', '', TRUE);
        $this->load->model('Schoolyear', '', TRUE);
        $this->load->model('Enrollment', '', TRUE);

        $this->user_id = $this->session->userdata('id');
        // $this->member_photo_url = "";

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

        $this->Data['Headers']->CSS.= '<link rel="stylesheet" href="'.base_url('assets/vendors/dropzone/dropzone.css').'"></link>';
        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/vendors/dropzone/dropzone.min.js').'"></script>';

        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/js/functions/form.js').'"></script>';

        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/js/specifics/members.js?v=2').'"></script>';
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
        if( !$this->Auth->can(['members/listing']) ) {
            $this->Data['Headers']->Page = 'errors/403';
            $this->load->view('layouts/errors', $this->Data);
        }

        $this->Data['members'] = $this->Member->all();
        $this->Data['form']['studentsnumber_list'] = dropdown_list($this->Member->dropdown_list('stud_no, stud_no')->result_array(), ['stud_no', 'stud_no'], '', false);
        $this->Data['form']['groups_list'] = dropdown_list($this->Group->dropdown_list('groups_id, groups_name')->result_array(), ['groups_id', 'groups_name'], '', false);
        $this->Data['form']['levels_list'] = dropdown_list($this->Level->dropdown_list('levels_id, levels_name')->result_array(), ['levels_id', 'levels_name'], '', false);
        $this->Data['form']['types_list']  = dropdown_list($this->Type->dropdown_list('types_id, types_name')->result_array(), ['types_id', 'types_name'], '', false);
        $this->Data['trash']['count'] = $this->Member->get_all(0, 0, null, true)->num_rows();
        $this->Data['form']['schedules_list'] = dropdown_list($this->Schedule->dropdown_list('id, name')->result_array(), ['id', 'name'], '', false);

        $s = $this->Schoolyear->find('CURRENT', 'status');
        $s2 = $this->Schoolyear->find('OPEN', 'status');
        $schoolyearsList = [];
        if ($s) {
            $schoolyearsList[$s->id] = "[$s->status] $s->code";
        }
        if ($s2) {
            $schoolyearsList[$s2->id] = "[$s2->status] $s2->code";
        }
        $this->Data['schoolyears_list'] = $schoolyearsList ? $schoolyearsList : 'Non configured';

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
            $current      = null != $this->input->post('current') ? $this->input->post('current') : 1;
            $limit        = $this->input->post('rowCount') == -1 ? 0 : $this->input->post('rowCount');
            $page         = $current !== null ? $current : 1;
            $start_from   = ($page-1) * $limit;
            $sort         = null != $this->input->post('sort') ? $this->input->post('sort') : null;
            $wildcard     = null != $this->input->post('searchPhrase') ? $this->input->post('searchPhrase') : null;
            $removed_only = null !== $this->input->post('removedOnly') ? $this->input->post('removedOnly') : false;
            $total        = $this->Member->get_all(0, 0, null, $removed_only)->num_rows();

            if( null != $wildcard ) {
                $members = $this->Member->like($wildcard, $start_from, $limit, $sort, $removed_only)->result_array();
                $total   = $this->Member->like($wildcard, 0, 0, null, $removed_only)->num_rows();
            } else {
                $members = $this->Member->get_all($start_from, $limit, $sort, $removed_only)->result_array();
            }

            foreach ($members as $key => $member) {
                $group = null;
                // if( null != $member['groups'] ) $group = $this->Group->find( explodetoarray($member['groups']) );
                if( null != $member['groups'] ) 
                $group = $this->GroupMember->find_group_by_member_id( $member['id'] );
                $groups_name_arr = [];
                $groups_id_arr = [];
        
                // foreach ($group as $group_single) {
                //     $groups_name_arr[] = $group_single->groups_code;
                //     $groups_id_arr[] = $group_single->groups_id;
                // }

                $level='';
                if( null !== $member['level'] ) $level = $this->Level->find( explodetoarray($member['level']) );
                $levels_name_arr = [];
                $levels_id_arr = [];
                if( is_array( $level ) )
                {
                    foreach ($level as $level_single) {
                        $levels_name_arr[] = $level_single->levels_name;
                        $levels_id_arr[] = $level_single->levels_id;
                    }
                }

                $type ='';
                if( null !== $member['type'] ) $type = $this->Type->find( explodetoarray($member['type']) );
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
                    'count_id'  => $key + 1 + $start_from,
                    'id'        => $member['id'],
                    'avatar'    => '<img src=\''.$member['avatar'].'\' />',
        		    'stud_no'   => $member['stud_no'],
                    'fullname'  => arraytostring([$member['firstname'], $member['middlename'] ? substr($member['middlename'], 0,1) . '.' : '', $member['lastname']], ' '),
                    'level'     => $levels_name_arr ? arraytostring($levels_name_arr, ", ") : '',
                    'levels_id' => $levels_id_arr ? $levels_id_arr : '',
                    'type'      => $types_name_arr ? arraytostring($types_name_arr, ", ") : '',
                    'types_id'  => $types_id_arr ? $types_id_arr : '',
                    'address'   => arraytostring([$member['address_blockno'], $member['address_street'], $member['address_brgy'], $member['address_city'], $member['address_zip']]),
                    'telephone' => $member['telephone'],
                    'msisdn'    => $member['msisdn'],
                    'email'     => $member['email'],
                    'groups'    => $this->GroupMember->find_group_by_member_id( $member['id'] ),//$groups_name_arr ? arraytostring($groups_name_arr, ", ") : '', //isset($group->groups_name) ? $group->groups_name : '',
                    // 'groups_id' => $groups_id_arr ? $groups_id_arr : '',//isset($group->groups_id) ? $group->groups_id : '',
                );
            }

            $data = array(
                "current"       => intval($current),
                "rowCount"      => $limit,
                "searchPhrase"  => $wildcard,
                "removed_only"  => $removed_only,
                "total"         => intval( $total ),
                "rows"          => $bootgrid_arr,
                "trash"         => array(
                    "count" => $this->Member->get_all(0, 0, null, true)->num_rows(),
                )
                // "debug" => $member['type'],
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
    }

    public function add()
    {
        if( !$this->Auth->can(['members/add']) ) {
            $this->Data['Headers']->Page = 'errors/403';
            $this->load->view('layouts/errors', $this->Data);
            echo json_encode( [
                'title' => 'Access Denied',
                'message' => "You don't have permission to Remove this resource",
                'type' => 'error',
            ] ); exit();
        }
        
        $check_students = $this->Member->find_students_if_exist($this->input->post('stud_no'));

        if(!($check_students > 0)) {
            # Validation
            $en_status = $this->input->post('enrollment_status');
            $stud_no = $this->Member->find($this->input->post('stud_no'), 'stud_no');
            if ($en_status == 'NEW' && null == $stud_no) {
                $avatar = $this->session->members_photo ? $this->session->members_photo : "";

                if( $this->Member->validate(true) ) {
                    # Save
                    $member = array(
                        'stud_no' => $this->input->post('stud_no'),
                        'firstname'    => $this->input->post('firstname'),
                        'middlename'   => $this->input->post('middlename'),
                        'lastname'     => $this->input->post('lastname'),
                        'level'        => $this->input->post('level') ? arraytoimplode($this->input->post('level')) : NULL,
                        'type'         => arraytoimplode($this->input->post('type')),
                        'address_blockno'      => $this->input->post('address_blockno'),
                        'address_street'       => $this->input->post('address_street'),
                        'address_brgy'         => $this->input->post('address_brgy'),
                        'address_city'         => $this->input->post('address_city'),
                        'address_zip'          => $this->input->post('address_zip'),
                        'telephone'    => $this->input->post('telephone'),
                        'msisdn'       => $this->input->post('msisdn'),
                        'email'        => $this->input->post('email'),
                        'groups'        => arraytoimplode($this->input->post('groups')),
                        'avatar'    => $avatar,//$this->input->post('avatar'),
                        'schedule_id' => $this->input->post('schedule_id') != 0 ? $this->input->post('schedule_id') : NULL,
                        'created_by'            => $this->user_id,
                    );

                    $this->Member->insert($member);
                    $member_id = $this->db->insert_id();

                    # Save the Groups id in another table
                    if( null !== $this->input->post('groups') ) {
                        $groups = $this->input->post('groups');
                        $data['debug'] = $groups;
                        foreach ($groups as $group_id) {
                            $group_member = array(
                                'group_id' => $group_id,
                                'member_id' => $member_id,
                            );
                            $this->GroupMember->insert($group_member);
                        }
                    }

                    $member_id = $this->db->insert_id();
                    $member = $this->Member->find($this->input->post('stud_no'), 'stud_no');
                    $this->saveEnrollment($member->id, $this->input);

                    # Response
                    $data = array(
                        'message' => 'Member was successfully added',
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

            } else {
                $member = $this->Member->find($this->input->post('stud_no'), 'stud_no');
                $member_id = $this->db->insert_id();
                $member = $this->Member->find($this->input->post('stud_no'), 'stud_no');
                $this->saveEnrollment($member->id, $this->input);
                $this->update($member->id, $this->input);

            }

            if( $this->input->is_ajax_request() ) {
                echo json_encode( $data ); exit();
            } else {
                $this->session->set_flashdata('message', $data);
                redirect( base_url('members') );
            }
        } 
        else {
            
            $data = array(
                'title' => 'Error',
                'message' => 'The student number is already exist.',
                'type' => 'danger',
                'color' => 'danger'
            );

            echo json_encode( $data ); exit();
        }
    }

    public function upload_photo()
    {
        $config['upload_path'] = "./uploads/";
        $config['allowed_types'] = "gif|jpg|jpeg|png|tiff";
        $config['file_name'] = $_FILES["file"]['name'];

        //$this->input->post('stud_no') ? $this->input->post('stud_no') : slugify($_FILES["file"]['name']);

        $this->load->library('upload', $config);
        $avatar = "";
        if (!$this->upload->do_upload('file')) {
            echo json_encode([
                'title' => 'Errors',
                'message' => $this->upload->display_errors(),
                'type' => 'errors',
            ]); exit();
        } else {
            // echo "<pre>";
            //     var_dump( $this->upload->data() ); die();
            // echo "</pre>";
            $ud = $this->upload->data();
            $this->member_photo_url = base_url() .'uploads/'. $ud['file_name'];
            $this->session->set_userdata('members_photo', $this->member_photo_url);
            $this->session->set_userdata('members_photo_dirpath', $ud['full_path']);
            // move_uploaded_file($this->member_photo_url, "./uploads/images/");
            echo json_encode([
                'title' => 'Success',
                'message' => "Uploaded to " . $this->member_photo_url,
                'type' => 'success',
            ]); exit();
        }
    }

    /**
     * Retrieve the resource for editing
     * @param  int $id
     * @return AJAX
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

        $arr['member'] = $this->Member->find_member( $id );
        $arr['groups'] = $this->GroupMember->find_group_by_mem_id( $id );
        if( $this->input->is_ajax_request() ) {
            echo json_encode( $arr ); exit();
        } else {
            $this->Data['member'] = $arr['member'];
            $this->load->view('layouts/main', $this->Data);
        }
    }

    public function update($id, $input = null)
    {
        if (null !== $input) {
            $this->input = $input;
        }

        # Validation
        if( null != $this->input->post('updating_groups') ) {
            $member = $this->Member->find($id);
            $member_groups = [];
            if( !empty($member->groups) ) $member_groups = explode(",", $member->groups);

            # If we're Adding a Group
            if( "add" == $this->input->post('action') ) {
                # Check if the value is already in the resource,
                # add to array if not.
                if( !in_array($this->input->post('value'), $member_groups) ) {
                    $member_groups[] = $this->input->post('value');
                } else {
                    $data = array(
                        'message' => 'Member is already in this groups',
                        'type' => 'danger',
                        // 'debug' => $member_groups,
                        // "input" => $this->input->post('value'),
                    );
                    echo json_encode( $data );
                    exit();
                }
            }

            # If we're Removing a Group
            if( "remove" == $this->input->post('action') ) {
                # Remove the value if in the resource
                if( in_array($this->input->post('value'), $member_groups) ) {
                    $index = array_search($this->input->post('value'), $member_groups);
                    unset( $member_groups[$index] );
                } else {
                    $data = array(
                        'message' => 'Member is already not in this groups',
                        'type' => 'danger',
                        // 'debug' => $member_groups,
                        // "input" => $this->input->post('value'),
                    );
                    echo json_encode( $data );
                    exit();
                }
            }

            # Prepare data
            $member_groups = arraytoimplode($member_groups);   // stringify the array E.g. `array("1", "2")` will be `"1,2"`
            $member_data = array(
                $this->input->post('updating_groups') => $member_groups,
            );

            # Update
            $this->Member->update($id, $member_data);

            # Update the group_members
            $group_ids = explodetoarray($member_groups);
            $members_groups = $this->GroupMember->lookup('member_id', $id)->result_array();
            $this->GroupMember->delete_member($id);
            foreach ($group_ids as $group_id) {
                $this->GroupMember->insert( array('group_id' => $group_id, 'member_id' => $id ) );
            }

            # Response
            $data = array(
                'message' => 'Member was successfully updated',
                'type' => 'success',
                // 'debug' => $member_groups,
                // "input" => $this->input->post('value'),
            );
            echo json_encode( $data );
            exit();

        }

        if( null != $this->input->post('updating_level') ) {
            $member = $this->Member->find($id);
            $member_level = [];
            if( !empty($member->level) )$member_level = explode(",", $member->level);

            # If we're Adding a Group
            if( "add" == $this->input->post('action') ) {
                # Check if the value is already in the resource,
                # add to array if not.
                if( !in_array($this->input->post('value'), $member_level) ) {
                    $member_level[] = $this->input->post('value');
                } else {
                    $data = array(
                        'message' => 'Member is already in this level',
                        'type' => 'danger',
                        // 'debug' => $member_level,
                        // "input" => $this->input->post('value'),
                    );
                    echo json_encode( $data );
                    exit();
                }
            }

            # If we're Removing a Group
            if( "remove" == $this->input->post('action') ) {
                # Remove the value if in the resource
                if( in_array($this->input->post('value'), $member_level) ) {
                    $index = array_search($this->input->post('value'), $member_level);
                    unset( $member_level[$index] );
                } else {
                    $data = array(
                        'message' => 'Member is already not in this level',
                        'type' => 'danger',
                        // 'debug' => $member_level,
                        // "input" => $this->input->post('value'),
                    );
                    echo json_encode( $data );
                    exit();
                }
            }

            # Prepare data
            $member_level = implode(",", $member_level);   // stringify the array E.g. `array("1", "2")` will be `"1,2"`
            $member_data = array(
                $this->input->post('updating_level') => $member_level,
            );

             # Update
            $this->Member->update($id, $member_data);

            # Response
            $data = array(
                'message' => 'Member was successfully updated',
                'type' => 'success',
                // 'debug' => $member_level,
                // "input" => $this->input->post('value'),
            );
            echo json_encode( $data );
            exit();

        }

        if( null != $this->input->post('updating_type') ) {
            $member = $this->Member->find($id);
            $member_type = [];
            if( !empty($member->type) ) $member_type = explode(",", $member->type);

            # If we're Adding a Type
            if( "add" == $this->input->post('action') ) {
                # Check if the value is already in the resource,
                # add to array if not.
                if( !in_array($this->input->post('value'), $member_type) ) {
                    $member_type[] = $this->input->post('value');
                } else {
                    $data = array(
                        'message' => 'Member is already in this type',
                        'type' => 'danger',
                        'debug' => $member_type,
                        // "input" => $this->input->post('value'),
                    );
                    echo json_encode( $data );
                    exit();
                }
            }

            # If we're Removing a Type
            if( "remove" == $this->input->post('action') ) {
                # Remove the value if in the resource
                if( in_array($this->input->post('value'), $member_type) ) {
                    $index = array_search($this->input->post('value'), $member_type);
                    unset( $member_type[$index] );
                } else {
                    $data = array(
                        'message' => 'Member is already not in this type',
                        'type' => 'danger',
                        // 'debug' => $member_type,
                        // "input" => $this->input->post('value'),
                    );
                    echo json_encode( $data );
                    exit();
                }
            }

            # Prepare data
            $member_type = implode(",", $member_type);   // stringify the array E.g. `array("1", "2")` will be `"1,2"`
            $member_data = array(
                $this->input->post('updating_type') => $member_type,
            );

             # Update
            $this->Member->update($id, $member_data);

            # Response
            $data = array(
                'message' => 'Member was successfully updated',
                'type' => 'success',
                // 'debug' => $member_type,
                // "input" => $this->input->post('value'),
            );
            echo json_encode( $data );
            exit();

        }

        if( $this->Member->validate(false, $id, $this->input->post('email')) ) {
            $avatar = $this->session->members_photo ? $this->session->members_photo : "";

            // if(!empty($avatar)) {
            //     unlink( $this->Member->find($id)->avatar );
            // }
            # Update
            $member = array(
                'stud_no' => $this->input->post('stud_no'),
                'firstname' => $this->input->post('firstname'),
                'middlename' => $this->input->post('middlename'),
                'lastname' => $this->input->post('lastname'),
                'level' => $this->input->post('level') ? arraytoimplode( $this->input->post('level') ) : NULL,
                'type' => arraytoimplode( $this->input->post('type') ),
                'address_blockno' => $this->input->post('address_blockno'),
                'address_street' => $this->input->post('address_street'),
                'address_brgy' => $this->input->post('address_brgy'),
                'address_city' => $this->input->post('address_city'),
                'address_zip' => $this->input->post('address_zip'),
                'telephone' => $this->input->post('telephone'),
                'msisdn' => $this->input->post('msisdn'),
                'email' => $this->input->post('email'),
                'groups' => arraytoimplode( $this->input->post('groups') ),
                'updated_by' => $this->user_id,
                'avatar'    => $avatar,//$this->input->post('avatar'),
                'schedule_id' => $this->input->post('schedule_id'),
            );
            $this->Member->update($id, $member);

            # Update the group_members
            $group_ids = $this->input->post('groups');
            $members_groups = $this->GroupMember->lookup('member_id', $id)->result_array();
            $this->GroupMember->delete_member($id);
            if( null !== $group_ids ) {
                foreach ($group_ids as $group_id) {
                    $this->GroupMember->insert( array('group_id' => $group_id, 'member_id' => $id ) );
                }
            } else {
                $this->GroupMember->delete_member($id);
            }

            # Response
            $data = array(
                'message' => 'Member was successfully updated',
                'type' => 'success',
                'color' => 'success',
                'debug'=>$member,
            );
        } else {
            $data = array(
                'message'=>$this->form_validation->toArray(),
                'type'=>'error',
            );
        }

        if( $this->input->is_ajax_request() ) {
            echo json_encode( $data ); exit();
        } else {
            $this->session->set_flashdata('message', $data);
        }
    }

    public function trash()
    {
        if( !$this->Auth->can(['members/export']) ) {
            $this->Data['Headers']->Page = 'errors/403';
            $this->load->view('layouts/errors', $this->Data);
            return false;
        }

        $this->Data['members'] = $this->Member->all(true);

        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/js/specifics/membersTrash.js').'"></script>';
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

        if( $this->Member->remove($id) ) {
            # Also update the TABLE `groups_members`
            $this->GroupMember->delete_member($id);

            if( 1 == $remove_many ) {
                $data['member']['message'] = 'Members were successfully removed';
            } else {
                $data['member']['message'] = 'Member was successfully removed';
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

        if( $this->Member->restore($id) ) {
            # Also update the TABLE `group_members`
            $member = $this->Member->find($id);
            $groups = explodetoarray($member->groups);
            foreach ($groups as $group_id) {
                $this->GroupMember->insert( array(
                    'group_id' => $group_id,
                    'member_id' => $id,
                ) );
            }
            $data['message'] = 'Member was successfully restored';
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
                $ids = $this->input->post('id');
                if( $this->Member->delete($ids) )
                {
                    $data['message'] = 'Members were successfully deleted';
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
            if( $this->Member->delete($id) )
            {
                $data['message'] = 'Member was successfully deleted';
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
        if( !$this->Auth->can(['members/import']) ) {
            $this->Data['Headers']->Page = 'errors/403';
            $this->load->view('layouts/errors', $this->Data);
            return false;
        }

        if( $this->input->is_ajax_request() )
        { 
            foreach($_FILES as $file)
            {   
                $row = 0;
                if (($files = fopen($file['tmp_name'], "r")) !== FALSE) 
                {
                    while (($data = fgetcsv($files, 1000, ",")) !== FALSE) 
                    {
                        $num = count($data);
                        $row++;

                        if($row > 1) 
                        {
                            $exist = $this->Member->check_if_exist(trim($data[0]));

                            $members = array(
                                'stud_no' => trim($data[0]),
                                'firstname' => trim($data[1]),
                                'middlename' => trim($data[2]),
                                'lastname' => trim($data[3]),
                                'birthdate' => trim($data[4]),
                                'nick' => trim($data[5]),
                                'level' => trim($data[6]),
                                'type' => trim($data[7]),
                                'address_blockno' => trim($data[8]),
                                'address_street' => trim($data[9]),
                                'address_brgy' => trim($data[10]),
                                'address_city' => trim($data[11]),
                                'address_zip' => trim($data[12]),
                                'telephone' => trim($data[13]),
                                'msisdn' => "0".trim($data[14]),
                                'email' => trim($data[15]),
                                // 'groups' => trim($data[16]),
                                'schedule_id' => $this->Schedule->get_schedule_id(trim($data[17])),
                                'active' => 0
                            );

                            if($exist > 0)
                            {   
                                $members['updated_at'] = NULL;
                                $members['updated_by'] = $this->session->userdata('id');                                
                                $this->GroupMember->delete_members($exist);
                                $this->Member->update($exist, $members);
                            } else {
                                $members['id'] = NULL;
                                $members['created_by'] = $this->session->userdata('id');
                                $members['created_at'] = NULL;
                                $members_id = $this->Member->insert($members);
                            }

                            if( null !== $data[16])
                            {   
                                foreach(explode(", ", trim($data[16])) as $item)
                                {   
                                    $group_check = $this->Group->check_if_group_exist($item);

                                    if($group_check > 0)
                                    {
                                        $groups = array(
                                            'group_id' => $group_check,
                                            'member_id' => ($exist > 0) ? $exist : $members_id
                                        );
                                        
                                        $groups_id = $this->GroupMember->insert($groups);
                                    }
                                }
                            }
                        }                    
                    }
                    fclose($files);
                    $data = array('message' => 'Members successfully imported.', 'type'=>'success');
                }
                else {
                    $data = array('message' => 'Something went wrong importing the file', 'type'=>'error');
                }
            }

            echo json_encode( $data );
            exit();
        }

        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/js/specifics/membersImport.js').'"></script>';
        $this->load->view('layouts/main', $this->Data);
    }

    /**
     * Export Page for this controller
     * @return [type] [description]
     */
    public function export()
    {
        if( !$this->Auth->can(['members/export']) ) {
            $this->Data['Headers']->Page = 'errors/403';
            $this->load->view('layouts/errors', $this->Data);
            return false;
        }

        if( null != $this->input->post('export_start') )
        {
            $export = $this->Member->export( 
                false, 
                date('Y-m-d', strtotime($this->input->post('export_start'))), 
                date('Y-m-d', strtotime($this->input->post('export_end'))), 
                $this->input->post('export_level'), 
                $this->input->post('export_format'));
            // /*
            // | ---------------------------------------------
            // | # Validation
            // | ---------------------------------------------
            // */
            // $result = $this->Member->export( false, date('Y-m-d', strtotime($this->input->post('export_start'))), date('Y-m-d', strtotime($this->input->post('export_end')), $this->input->post('export_level'), $this->input->post('export_format'))->result();
            // if( empty( $export ) ) {
            //     $this->Data['messages']['error'] = 'No Record was found in the Dates or Level specified';
            // } else
            // {
                # Export
                #// Load the DB Utility
                
                switch ( $this->input->post('export_format') ) {
                    case 'CSV':
                        break;

                    case 'SQL':
                        $this->load->dbutil();
                        $SQL = $this->dbutil->backup(['tables'=>'members']);
                        $sql_name = 'Members_' . date('Y-m-d-H-i') . '.export.zip';
                        force_download($sql_name, $SQL);
                        break;

                    case 'PDF':
                        die('PDF is not available on your user level');
                        break;
                    default:
                        break;
                // }


                # Response
                # -- No response yet --
                $this->session->set_flashdata('message', array('type'=>'success', 'message'=>"Export completed"));
            }
        }

        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/js/specifics/membersExport.js').'"></script>';
        $this->Data['form']['levels_list'] = dropdown_list($this->Level->dropdown_list('levels_id, levels_name')->result_array(), ['levels_id', 'levels_name'], 'All Levels', "0");
        $this->load->view('layouts/main', $this->Data);
    }

    public function export_members()
    {
        if( !$this->Auth->can(['members/export']) ) {
            $this->Data['Headers']->Page = 'errors/403';
            $this->load->view('layouts/errors', $this->Data);
            return false;
        }

        $export = $this->Member->export( false, 
                date('Y-m-d', strtotime($this->input->post('export_start'))), 
                date('Y-m-d', strtotime($this->input->post('export_end'))), 
                $this->input->post('export_level'), 
                $this->input->post('export_format'));
    }

    public function search($value = "")
    {
        switch ($value) {
            case 'student':
                $stud_no = $this->input->get('stud_no');
                $return = $this->Member->find($stud_no, 'stud_no');
                break;

            default:
                $return = "asd";
                break;
        }

        if ($this->input->is_ajax_request()) {
            echo json_encode( $return ); exit();
        } else {
            echo "<pre>";
                var_dump( $return ); die();
            echo "</pre>";
        }
    }

    public function saveEnrollment($id, $input)
    {
        # Validation
        // if ($this->Enrollment->validate(true)) {
            # Save
            $schoolyear_id = $input->post('schoolyear_id');//->find('CURRENT', 'status');
            $enstat = $this->Enrollment->find($id, 'member_id');
            $enrollment = array(
                'member_id' => $id,
                'schoolyear_id' => $schoolyear_id,
                'enrollment_status' => null == $enstat ? 'NEW' : 'OLD',
            );

            $this->Enrollment->insert($enrollment);
            $enrollment_id = $this->db->insert_id();

            # Response
            $data = array(
                'title' => 'Success',
                'message' => 'Enrollment successfully added',
                'type'    => 'success',
                'color' => 'success',
                // 'debug'   => $this->input->post('groups'),
            );

        // } else {

        //     # Negative Response
        //     $message = count($this->form_validation->toArray()) . (count($this->form_validation->toArray()) > 1 ? ' Errors found while adding.' : ' Error found while adding.');
        //     $data = array(
        //         'title' => 'Error',
        //         'message' => $message,
        //         'errors' => $this->form_validation->toArray(),
        //         'type' => 'error',
        //         'color' => 'danger',
        //     );
        // }

        if ($this->input->is_ajax_request()) {
            echo json_encode( $data ); exit();
        } else {
            $this->session->set_flashdata('message', $data);
            redirect(base_url('enrollments'));
        }
    }

    public function current_listing()
    {
        if( $this->input->is_ajax_request() )
        {
            $bootgrid_arr = [];
            $current      = null != $this->input->post('current') ? $this->input->post('current') : 1;
            $limit        = $this->input->post('rowCount') == -1 ? 0 : $this->input->post('rowCount');
            $page         = $current !== null ? $current : 1;
            $start_from   = ($page-1) * $limit;
            $wildcard     = null != $this->input->post('searchPhrase') ? $this->input->post('searchPhrase') : null;
 
            if( isset($wildcard) )
            {
                $members = $this->Member->like_all_current_listing($wildcard, $start_from, $limit, $this->input->post('group'))->result_array();
                $total = $this->Member->like_all_current_listings($wildcard, $this->input->post('group'))->num_rows();
            }
            else
            {
                $members = $this->Member->get_all_current_listing($start_from, $limit, $this->input->post('group'))->result_array();
                $total = $this->Member->get_all_current_listings($this->input->post('group'))->num_rows();
            }

            foreach ($members as $key => $member) 
            {
                $bootgrid_arr[] = array(
                    'count_id' => $key + 1 + $start_from,
                    'id' => $member['id'],
                    'fullname' => ucfirst($member['firstname']).' '.ucfirst($member['middlename']).' '.ucfirst($member['lastname']),
                    'level' => ($member['level'] != NULL) ? $member['level'] : 'none',
                    'groups' => $this->GroupMember->find_group_by_member_id($member['id'])
                );
            }

            $data = array(
                "current"       => intval($current),
                "rowCount"      => $limit,
                "searchPhrase"  => $wildcard,
                "total"         => intval( $total ),
                "rows"          => $bootgrid_arr,
                'search-per-jo' => $this->input->post('search_per_jo'),
                'search-per-date' => $this->input->post('search_per_date')
            );

            echo json_encode( $data );
            exit();
        }
    }

    public function available_listing()
    {
        // if( $this->input->is_ajax_request() )
        // {
            $bootgrid_arr = [];
            $current      = null != $this->input->post('current') ? $this->input->post('current') : 1;
            $limit        = $this->input->post('rowCount') == -1 ? 0 : $this->input->post('rowCount');
            $page         = $current !== null ? $current : 1;
            $start_from   = ($page-1) * $limit;
            $wildcard     = null != $this->input->post('searchPhrase') ? $this->input->post('searchPhrase') : null;
 
            if( isset($wildcard) )
            {
                $members = $this->Member->like_all_available_listing($wildcard, $start_from, $limit, $this->input->post('groups'))->result_array();
                $total = $this->Member->like_all_available_listings($wildcard, $this->input->post('groups'))->num_rows();
            }
            else
            {
                $members = $this->Member->get_all_available_listing($start_from, $limit, $this->input->post('groups'))->result_array();
                $total = $this->Member->get_all_available_listings($this->input->post('groups'))->num_rows();
            }

            foreach ($members as $key => $member) 
            {
                $bootgrid_arr[] = array(
                    'count_id' => $key + 1 + $start_from,
                    'id' => $member['id'],
                    'fullname' => ucfirst($member['firstname']).' '.ucfirst($member['middlename']).' '.ucfirst($member['lastname']),
                    'level' => ($member['level'] != NULL) ? $member['level'] : 'none',
                    'groups' => $this->GroupMember->find_group_by_member_id($member['id'])
                );
            }

            $data = array(
                "current"       => intval($current),
                "rowCount"      => $limit,
                "searchPhrase"  => $wildcard,
                "total"         => intval( $total ),
                "rows"          => $bootgrid_arr,
                'search-per-jo' => $this->input->post('search_per_jo'),
                'search-per-date' => $this->input->post('search_per_date')
            );

            echo json_encode( $data );
            exit();
        // }
    }

    public function delete_members()
    {
        
    }
}
