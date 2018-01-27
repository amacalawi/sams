<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SchoolyearController extends CI_Controller
{
    private $Data;
    private $user_id = 0;

    public function __construct()
    {
        parent::__construct();
        $this->validated();

        $this->load->model('Schoolyear', '', TRUE);
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

        $this->Data['Headers']->CSS.= '<link rel="stylesheet" href="'.base_url('assets/vendors/dropzone/dropzone.css').'"></link>';

        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/js/crud/list.js').'"></script>';
        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/js/crud/add.js').'"></script>';
        // $this->Data['Headers']->JS .= '<script src="'.base_url('assets/js/crud/delete.js').'"></script>';
    }

    public function validated()
    {
        $this->session->set_flashdata('error', "You are not logged in");

        if (!$this->session->userdata('validated')) redirect('login');
    }

    /**
     * Index Page for this controller.
     *
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function index()
    {
        // schoolyears
        if( !$this->Auth->can(['members/listing']) ) {
            $this->Data['Headers']->Page = 'errors/403';
            return $this->load->view('layouts/errors', $this->Data);
        }

        $this->Data['schoolyears'] = $this->Schoolyear->all();
        $this->Data['form']['status_list'] = [
            'CLOSE' => 'CLOSE - Set to close',
            'CURRENT' => 'CURRENT - Set to current',
            'OPEN' => 'OPEN - Set to open',
        ];

        $year = date('Y');
        $endyear = date('Y', strtotime('+5 years'));
        $y = [];
        do {
            $y[$year] = $year;
            $year++;
        } while ($year != $endyear);


        $this->Data['form']['years_list'] = $y;
        $this->Data['trash']['count'] = $this->Schoolyear->get_all(0, 0, null, true)->num_rows();

        $this->load->view('layouts/main', $this->Data);
    }

    /**
     * List resource
     *
     * @return
     */
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
            $page         = $current != null ? $current : 1;
            $start_from   = ($page-1) * $limit;
            $sort         = null != $this->input->post('sort') ? $this->input->post('sort') : null;
            $wildcard     = null != $this->input->post('searchPhrase') ? $this->input->post('searchPhrase') : null;
            $removed_only = null !== $this->input->post('removedOnly') ? $this->input->post('removedOnly') : false;
            $total        = $this->Schoolyear->get_all(0, 0, null, $removed_only)->num_rows();

            if( null != $wildcard ) {
                $schoolyears = $this->Schoolyear->like($wildcard, $start_from, $limit, $sort, $removed_only)->result_array();
                $total   = $this->Schoolyear->like($wildcard, 0, 0, null, $removed_only)->num_rows();
            } else {
                $schoolyears = $this->Schoolyear->get_all($start_from, $limit, $sort, $removed_only)->result_array();
            }

            foreach ($schoolyears as $key => $schoolyear) {
                $bootgrid_arr[] = array(
                    'count_id'  => $key + 1 + $start_from,
                    'id'        => $schoolyear['id'],
                    'name'  => $schoolyear['name'] ? $schoolyear['name'] : '',
                    'code'  => $schoolyear['code'] ? $schoolyear['code'] : '',
                    'description'    => $schoolyear['description'],
                    'years'     => $schoolyear['years'],
                    'status'    => $schoolyear['status'],
                );
            }

            $data = array(
                "current"       => intval($current),
                "rowCount"      => $limit,
                "searchPhrase"  => $wildcard,
                "total"         => intval( $total ),
                "rows"          => $bootgrid_arr,
                "trash"         => array(
                    "count" => $this->Schoolyear->get_all(0, 0, null, true)->num_rows(),
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
                'message' => "You don't have permission to add to this resource",
                'type' => 'error',
            ] ); exit();
        }

        # Validation
        if ($this->Schoolyear->validate(true)) {
            # Save
            $start = $this->input->post('year_start');
            $end = $this->input->post('year_end');
            $years =  "{$start}-{$end}";

            $schoolyear = array(
                'name' => $this->input->post('name'),
                'code' => $this->input->post('code'),
                'description' => $this->input->post('description'),
                'year_start' => $this->input->post('year_start'),
                'year_end' => $this->input->post('year_end'),
                'years' => $years,
                'status' => $this->input->post('status') ? $this->input->post('status') : 'CLOSE',
                'created_by' => $this->user_id,
            );

            $this->Schoolyear->insert($schoolyear);
            $schoolyear_id = $this->db->insert_id();

            # Response
            $data = array(
                'title' => 'Success',
                'message' => 'School Year successfully added',
                'type'    => 'success',
                'color' => 'success',
                // 'debug'   => $this->input->post('groups'),
            );

        } else {

            # Negative Response
            $message = count($this->form_validation->toArray()) . (count($this->form_validation->toArray()) > 1 ? ' Errors found while adding.' : ' Error found while adding.');
            $data = array(
                'title' => 'Error',
                'message' => $message,
                'errors' => $this->form_validation->toArray(),
                'type' => 'error',
                'color' => 'danger',
            );
        }

        if ($this->input->is_ajax_request()) {
            echo json_encode( $data ); exit();
        } else {
            $this->session->set_flashdata('message', $data);
            redirect(base_url('schoolyears'));
        }
    }

    /**
     * Retrieve the resource for editing
     * @param  int $id
     * @return AJAX
     */
    public function edit($id)
    {
        if (!$this->Auth->can('members/edit')) {
            $this->Data['Headers']->Page = 'errors/403';
            $this->load->view('layouts/errors', $this->Data);
            echo json_encode( [
                'title' => 'Access Denied',
                'message' => "You don't have permission to Edit this resource",
                'type' => 'error',
            ] ); exit();
        }

        $schoolyear = $this->Schoolyear->find($id);

        if( $this->input->is_ajax_request() ) {
            echo json_encode( $schoolyear ); exit();
        } else {
            $this->Data['schoolyear'] = $schoolyear;
            $this->load->view('layouts/main', $this->Data);
        }
    }

    public function update($id)
    {
        # Validation
        if ($this->Schoolyear->validate(false, $id, $this->input->post('code'))) {
            # Update
            $start = $this->input->post('year_start');
            $end = $this->input->post('year_end');
            $years =  "{$start}-{$end}";

            // CLOSE All Other CURRENT
            $schoolyear = array(
                'status' => 'CLOSE'
            );

            $s = $this->Schoolyear->find($this->input->post('status'), 'status');
            if (null !== $s) {
                $this->Schoolyear->update($s->id, $schoolyear);
            }

            $schoolyear = array(
                'name' => $this->input->post('name'),
                'code' => $this->input->post('code'),
                'description' => $this->input->post('description'),
                'year_start' => $this->input->post('year_start'),
                'year_end' => $this->input->post('year_end'),
                'years' => $years,
                'status' => $this->input->post('status') ? $this->input->post('status') : 'CLOSE',
                'updated_by' => $this->user_id,
            );
            $this->Schoolyear->update($id, $schoolyear);

            # Response
            $data = array(
                'message' => 'School year was successfully updated',
                'type' => 'success',
                'debug' => $schoolyear,
                'color' => 'success',
            );
        } else {
            $message = count($this->form_validation->toArray()) . (count($this->form_validation->toArray()) > 1 ? ' Errors found while adding.' : ' Error found while adding.');
            $data = array(
                'title' => 'Error',
                'message' => $message,
                'errors' => $this->form_validation->toArray(),
                'type' => 'error',
                'color' => 'danger',
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

        $this->Data['schoolyears'] = $this->Schoolyear->all(true);

        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/js/crud/trash.js').'"></script>';
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

        if( $this->Schoolyear->remove($id) ) {
            # Also update the TABLE `groups_members`
            // $this->GroupMember->delete_member($id);

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

        if( $this->Schoolyear->restore($id) ) {
            $data['message'] = 'School Year was successfully restored';
            $data['type'] = 'success';
        } else {
            $data['message'] = 'An error occured while trying to restore the resource';
            $data['type'] = 'error';
        }

        if( $this->input->is_ajax_request() ) {
            echo json_encode( $data ); exit();
        } else {
            $this->session->set_flashdata('message', $data );
            redirect('schoolyears');
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
            $this->load->library('upload', ['upload_path'=>'./uploads/', 'allowed_types'=>'csv']);

            if ( !$this->upload->do_upload('file'))
            {
                $data = array('message' => $this->upload->display_errors(), 'type'=>'error');
            }
            else
            {
                # Import
                $full_path = $this->upload->data()['full_path'];
                if( $this->Member->import( $this->upload->data()['full_path'] ) )
                {
                    # Delete uploaded file
                    clean_upload_folder( $full_path );
                    clean_upload_folder( $full_path );

                    # Response
                    $data = array('message' => 'Members successfully imported.', 'type'=>'success');

                    # Also import on TABLE `group_members`
                    $members = $this->Member->all();
                    foreach ($members as $member) {

                        $group_ids = explodetoarray($member->groups);
                        $members_groups = $this->GroupMember->lookup('member_id', $member->id)->result_array();
                        $this->GroupMember->delete_member($member->id);
                        if($group_ids) {
                            foreach ($group_ids as $group_id) {
                                $this->GroupMember->insert( array('group_id' => $group_id, 'member_id' => $member->id ) );
                            }
                        }
                    }

                } else {
                    $data = array('message' => 'Something went wrong importing the file', 'type'=>'error');
                }

            }
            echo json_encode( $data );
            exit();

        }

        // $this->Data['Headers']->CSS.= '<link rel="stylesheet" href="'.base_url('assets/vendors/dropzone/dropzone.css').'"></link>';
        // $this->Data['Headers']->JS .= '<script src="'.base_url('assets/vendors/dropzone/dropzone.min.js').'"></script>';
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
            $export = $this->Member->export( false, date('Y-m-d', strtotime($this->input->post('export_start'))), date('Y-m-d', strtotime($this->input->post('export_end') . ' +1 day')), $this->input->post('export_level') );
            /*
            | ---------------------------------------------
            | # Validation
            | ---------------------------------------------
            */
            $result = $this->Member->export( false, date('Y-m-d', strtotime($this->input->post('export_start'))), date('Y-m-d', strtotime($this->input->post('export_end') . ' +1 day')), $this->input->post('export_level') )->result();
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
                        $csv_name = 'Members_' . date('Y-m-d-H-i') . '.export.csv';
                        force_download($csv_name, $CSV);
                        // $data = array('message' => 'Export was successful', 'type'=>'success');
                        break;

                    case 'SQL':
                        $SQL = $this->dbutil->backup(['tables'=>'members']);
                        $sql_name = 'Members_' . date('Y-m-d-H-i') . '.export.zip';
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

        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/js/specifics/membersExport.js').'"></script>';
        $this->Data['form']['levels_list'] = dropdown_list($this->Level->dropdown_list('levels_id, levels_name')->result_array(), ['levels_id', 'levels_name'], 'All Levels', "0");
        $this->load->view('layouts/main', $this->Data);
    }
}
