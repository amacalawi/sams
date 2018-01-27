<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModulesController extends CI_Controller {
    private $Data = array();
    private $user_id = 0;

    public function __construct()
    {
        parent::__construct();
        $this->validated();

        $this->load->model('Module', '', TRUE);
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
        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/vendors/fileinput/fileinput.min.js').'"></script>';

        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/js/specifics/modules.js').'"></script>';
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
        // if( !$this->Auth->can(['modules', 'modules/', 'modules/listing']) ) {
        //     $this->Data['Headers']->Page = 'errors/403';
        //     $this->load->view('layouts/errors', $this->Data);
        //     return false;
        // }

        # Override the default layout, which was `users/modules` based on the route
        $this->Data['modules'] = $this->Module->all();
        // $this->Data['form']['groups_list'] = dropdown_list($this->Group->dropdown_list('groups_id, groups_name')->result_array(), ['groups_id', 'groups_name'], '', false);
        $this->Data['form']['modules_list'] = dropdown_list($this->Module->dropdown_list('id, name')->result_array(), ['id', 'name'], '', false);
        // $this->Data['form']['types_list']  = dropdown_list($this->Type->dropdown_list('types_id, types_name')->result_array(), ['types_id', 'types_name'], '', false);
        $this->Data['trash']['count'] = $this->Module->get_all(0, 0, null, true)->num_rows();
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
            $removed_only = null != $this->input->post('removedOnly') ? $this->input->post('removedOnly') : false;
            $total        = $this->Module->get_all(0, 0, null, $removed_only)->num_rows();

            if( null != $wildcard ) {
                $modules = $this->Module->like($wildcard, $start_from, $limit, $sort, $removed_only)->result_array();
                $total   = $this->Module->like($wildcard, 0, 0, null, $removed_only)->num_rows();
            } else {
                $modules = $this->Module->get_all($start_from, $limit, $sort, $removed_only)->result_array();
            }

            foreach ($modules as $key => $module) {
                $bootgrid_arr[] = array(
                    'count_id'  => $key + 1 + $start_from,
                    'id'        => $module['id'],
                    'name'      => $module['name'],
                    'slug'      => $module['slug'],
                    'description' => $module['description'],
                );
            }

            $data = array(
                "current"       => intval($current),
                "rowCount"      => $limit,
                "searchPhrase"  => $wildcard,
                "total"         => intval( $total ),
                "rows"          => $bootgrid_arr,
                "trash"         => array(
                    "count" => $this->Module->get_all(0, 0, null, true)->num_rows(),
                ),
            );

            echo json_encode( $data );
            exit();
        }
    }

    public function add()
    {
        if( !$this->Auth->can('modules/add') ) {
            echo json_encode( [
                'title' => 'Access Denied',
                'message' => "You don't have permission to Add to this resource",
                'type' => 'error',
            ] ); exit();
        }

        # Validation
        if( $this->Module->validate(true) ) {

            # Save
            $module = array(
                'name'    => $this->input->post('name'),
                'slug'   => $this->input->post('slug'),
                'description'     => $this->input->post('description'),
                'created_by'     => $this->user_id,
            );

            $this->Module->insert($module);

            # Response
            $data = array(
                'message' => 'Module was successfully added',
                'type'    => 'success',
                // 'debug'   => $this->input->post('groups'),
            );

        } else {

            # Negative Response
            $data = array(
                'message'=>$this->form_validation->toArray(),
                'type'=>'danger',
            );
        }

        if( $this->input->is_ajax_request() ) {
            echo json_encode( $data ); exit();
        } else {
            $this->session->set_flashdata('message', $data);
            redirect( base_url('modules') );
        }
    }

    /**
     * Retrieve the resource for editing
     * @param  int $id
     * @return AJAX
     */
    public function edit($id)
    {
        $module = $this->Module->find( $id );
        if( $this->input->is_ajax_request() ) {
            echo json_encode( $module ); exit();
        } else {
            $this->Data['module'] = $module;
            $this->load->view('layouts/main', $this->Data);
        }
    }

    /**
     * Updates the resource
     *
     * @param  INT $id
     * @return JSON or Redirect
     */
    public function update($id)
    {
        if( $this->Module->validate(false, $id, $this->input->post('slug')) ) {
            # Update
            $module = array(
                'name' => $this->input->post('name'),
                'slug' => $this->input->post('slug'),
                'description' => $this->input->post('description'),
                'updated_by' => $this->user_id,
            );
            $this->Module->update($id, $module);

            # Response
            $data = array(
                'message' => 'Module was successfully updated',
                'type' => 'success',
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
        if( !$this->Auth->can(['modules', 'modules/trash']) ) {
            $this->Data['Headers']->Page = 'errors/403';
            $this->load->view('layouts/errors', $this->Data);
            return false;
        }

        $this->Data['modules'] = $this->Module->all(true);

        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/js/specifics/modulesTrash.js').'"></script>';
        $this->load->view('layouts/main', $this->Data);
    }

    public function remove($id=null)
    {
        $remove_many = 0;
        if( null == $id ) $remove_many = 1;
        if( null == $id ) $id = $this->input->post('id');

        if( $this->Module->remove($id) ) {
            if( 1 == $remove_many ) {
                $data['message'] = 'Modules were successfully removed';
            } else {
                $data['message'] = 'Module was successfully removed';
            }
            $data['title'] = "Deleted";
            $data['type'] = 'success';
        } else {
            $data['title'] = "Oops!";
            $data['message'] = 'An error occured while removing the resource';
            $data['type'] = 'error';
            $data['debug'] = $id;
        }

        if( $this->input->is_ajax_request() ) {
            echo json_encode( $data ); exit();
        } else {
            $this->session->set_flashdata('message', $data );
            redirect('modules');
        }
    }

    public function restore($id=null)
    {
        if( null === $id ) $id = $this->input->post('id');

        if( $this->Module->restore($id) ) {
            $data['message'] = 'Module was successfully restored';
            $data['type'] = 'success';
        } else {
            $data['message'] = 'An error occured while trying to restore the resource';
            $data['type'] = 'error';
        }

        if( $this->input->is_ajax_request() ) {
            echo json_encode( $data ); exit();
        } else {
            $this->session->set_flashdata('message', $data );
            redirect('modules');
        }
    }

    public function seed()
    {
        $modules = array();
        $modules[] = array(
            'name' => 'List Members',
            'description' => 'Members list function',
            'slug' => 'members/listing',
        );
        $modules[] = array(
            'name' => 'Add Members',
            'description' => 'Members add function',
            'slug' => 'members/add',
        );
        $modules[] = array(
            'name' => 'Edit Members',
            'description' => 'Members edit function',
            'slug' => 'members/edit',
        );
        $modules[] = array(
            'name' => 'Update Members',
            'description' => 'Members update function',
            'slug' => 'members/update',
        );
        $modules[] = array(
            'name' => 'Remove Members',
            'description' => 'Members remove function',
            'slug' => 'members/remove',
        );
        $modules[] = array(
            'name' => 'Restore Members',
            'description' => 'Members restore function',
            'slug' => 'members/restore',
        );
        $modules[] = array(
            'name' => 'Export Members',
            'description' => 'Members export function',
            'slug' => 'members/export',
        );
        $modules[] = array(
            'name' => 'Import Members',
            'description' => 'Members import function',
            'slug' => 'members/import',
        );

        /*
        | -----------------
        | # Groups
        | -----------------
        */
        $modules[] = array(
            'name' => 'List Groups',
            'description' => 'Groups list function',
            'slug' => 'groups/listing',
        );
        $modules[] = array(
            'name' => 'Add Groups',
            'description' => 'Groups add function',
            'slug' => 'groups/add',
        );
        $modules[] = array(
            'name' => 'Edit Groups',
            'description' => 'Groups edit function',
            'slug' => 'groups/edit',
        );
        $modules[] = array(
            'name' => 'Update Groups',
            'description' => 'Groups update function',
            'slug' => 'groups/update',
        );
        $modules[] = array(
            'name' => 'Remove Groups',
            'description' => 'Groups remove function',
            'slug' => 'groups/remove',
        );
        $modules[] = array(
            'name' => 'Restore Groups',
            'description' => 'Groups restore function',
            'slug' => 'groups/restore',
        );
        $modules[] = array(
            'name' => 'Export Groups',
            'description' => 'Groups export function',
            'slug' => 'groups/export',
        );
        $modules[] = array(
            'name' => 'Import Groups',
            'description' => 'Groups import function',
            'slug' => 'groups/import',
        );

        /*
        | -----------------
        | # Types
        | -----------------
        */
        $modules[] = array(
            'name' => 'List Types',
            'description' => 'Types list function',
            'slug' => 'groups/listing',
        );
        $modules[] = array(
            'name' => 'Add Types',
            'description' => 'Types add function',
            'slug' => 'types/add',
        );
        $modules[] = array(
            'name' => 'Edit Types',
            'description' => 'Types edit function',
            'slug' => 'types/edit',
        );
        $modules[] = array(
            'name' => 'Update Types',
            'description' => 'Types update function',
            'slug' => 'types/update',
        );
        $modules[] = array(
            'name' => 'Remove Types',
            'description' => 'Types remove function',
            'slug' => 'types/remove',
        );
        $modules[] = array(
            'name' => 'Restore Types',
            'description' => 'Types restore function',
            'slug' => 'types/restore',
        );
        $modules[] = array(
            'name' => 'Export Types',
            'description' => 'Types export function',
            'slug' => 'types/export',
        );
        $modules[] = array(
            'name' => 'Import Types',
            'description' => 'Types import function',
            'slug' => 'types/import',
        );

        /*
        | -----------------
        | # Levels
        | -----------------
        */
        $modules[] = array(
            'name' => 'List Levels',
            'description' => 'Levels list function',
            'slug' => 'levels/listing',
        );
        $modules[] = array(
            'name' => 'Add Levels',
            'description' => 'Levels add function',
            'slug' => 'levels/add',
        );
        $modules[] = array(
            'name' => 'Edit Levels',
            'description' => 'Levels edit function',
            'slug' => 'levels/edit',
        );
        $modules[] = array(
            'name' => 'Update Levels',
            'description' => 'Levels update function',
            'slug' => 'levels/update',
        );
        $modules[] = array(
            'name' => 'Remove Levels',
            'description' => 'Levels remove function',
            'slug' => 'levels/remove',
        );
        $modules[] = array(
            'name' => 'Restore Levels',
            'description' => 'Levels restore function',
            'slug' => 'levels/restore',
        );
        $modules[] = array(
            'name' => 'Export Levels',
            'description' => 'Levels export function',
            'slug' => 'levels/export',
        );
        $modules[] = array(
            'name' => 'Import Levels',
            'description' => 'Levels import function',
            'slug' => 'levels/import',
        );

        /*
        | -----------------
        | # Messaging
        | -----------------
        */
        $modules[] = array(
            'name' => 'List Messaging',
            'description' => 'Messaging list function',
            'slug' => 'messaging/listing',
        );
        $modules[] = array(
            'name' => 'Add Messaging',
            'description' => 'Messaging add function',
            'slug' => 'messaging/add',
        );
        $modules[] = array(
            'name' => 'Edit Messaging',
            'description' => 'Messaging edit function',
            'slug' => 'messaging/edit',
        );
        $modules[] = array(
            'name' => 'Update Messaging',
            'description' => 'Messaging update function',
            'slug' => 'messaging/update',
        );
        $modules[] = array(
            'name' => 'Remove Messaging',
            'description' => 'Messaging remove function',
            'slug' => 'messaging/remove',
        );
        $modules[] = array(
            'name' => 'Restore Messaging',
            'description' => 'Messaging restore function',
            'slug' => 'messaging/restore',
        );
        $modules[] = array(
            'name' => 'Export Messaging',
            'description' => 'Messaging export function',
            'slug' => 'messaging/export',
        );
        $modules[] = array(
            'name' => 'Import Messaging',
            'description' => 'Messaging import function',
            'slug' => 'messaging/import',
        );

        /*
        | -----------------
        | # Privileges
        | -----------------
        */
        $modules[] = array(
            'name' => 'List Privilege',
            'description' => 'Privilege list function',
            'slug' => 'privileges/listing',
        );
        $modules[] = array(
            'name' => 'Add Privilege',
            'description' => 'Privilege add function',
            'slug' => 'privileges/add',
        );
        $modules[] = array(
            'name' => 'Edit Privilege',
            'description' => 'Privilege edit function',
            'slug' => 'privileges/edit',
        );
        $modules[] = array(
            'name' => 'Update Privilege',
            'description' => 'Privilege update function',
            'slug' => 'privileges/update',
        );
        $modules[] = array(
            'name' => 'Remove Privilege',
            'description' => 'Privilege remove function',
            'slug' => 'privileges/remove',
        );
        $modules[] = array(
            'name' => 'Restore Privilege',
            'description' => 'Privilege restore function',
            'slug' => 'privileges/restore',
        );
        $modules[] = array(
            'name' => 'Export Privilege',
            'description' => 'Privilege export function',
            'slug' => 'privileges/export',
        );
        $modules[] = array(
            'name' => 'Import Privilege',
            'description' => 'Privilege import function',
            'slug' => 'privileges/import',
        );

        /*
        | -----------------
        | # Privileges Levels
        | -----------------
        */
        $modules[] = array(
            'name' => 'List Privileges Level',
            'description' => 'Privileges Level list function',
            'slug' => 'privileges-levels/listing',
        );
        $modules[] = array(
            'name' => 'Add Privileges Level',
            'description' => 'Privileges Level add function',
            'slug' => 'privileges-levels/add',
        );
        $modules[] = array(
            'name' => 'Edit Privileges Level',
            'description' => 'Privileges Level edit function',
            'slug' => 'privileges-levels/edit',
        );
        $modules[] = array(
            'name' => 'Update Privileges Level',
            'description' => 'Privileges Level update function',
            'slug' => 'privileges-levels/update',
        );
        $modules[] = array(
            'name' => 'Remove Privileges Level',
            'description' => 'Privileges Level remove function',
            'slug' => 'privileges-levels/remove',
        );
        $modules[] = array(
            'name' => 'Restore Privileges Level',
            'description' => 'Privileges Level restore function',
            'slug' => 'privileges-levels/restore',
        );
        $modules[] = array(
            'name' => 'Export Privileges Level',
            'description' => 'Privileges Level export function',
            'slug' => 'privileges-levels/export',
        );
        $modules[] = array(
            'name' => 'Import Privileges Level',
            'description' => 'Privileges Level import function',
            'slug' => 'privileges-levels/import',
        );

        foreach ($modules as $module) {
            $this->Module->insert($module);
            echo "success" . "<br>";
        }
    }
}