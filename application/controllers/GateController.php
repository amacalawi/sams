<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GateController extends CI_Controller
{
    private $Data = array();

    public function __construct()
    {
        parent::__construct();
        $this->Data['Headers'] = get_page_headers();
        $this->validated();

        $this->Data['Headers']->CSS = '<link rel="stylesheet" href="'.base_url('assets/vendors/bootgrid/jquery.bootgrid.min.css').'">';
        $this->Data['Headers']->CSS.= '<link rel="stylesheet" href="'.base_url('assets/vendors/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css').'">';
        $this->Data['Headers']->CSS.= '<link rel="stylesheet" href="'.base_url('assets/vendors/chosen/chosen.min.css').'">';

        $this->Data['Headers']->JS  = '<script src="'.base_url('assets/vendors/bootgrid/jquery.bootgrid.min.js').'"></script>';
        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/vendors/bootstrap-growl/bootstrap-growl.min.js').'"></script>';
        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/vendors/moment/min/moment.min.js').'"></script>';
        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/vendors/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js').'"></script>';
        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/vendors/jquery.validate/dist/jquery.validate.min.js').'"></script>';
        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/vendors/chosen/chosen.jquery.min.js').'"></script>';

        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/js/specifics/gates.js').'"></script>';
        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/js/crud/list.js').'"></script>';
        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/js/crud/add.js').'"></script>';
        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/js/crud/edit.js').'"></script>';
        $this->load->model('Gate', '', TRUE);

    }

    public function validated()
    {
        $this->session->set_flashdata('error', "You are not logged in");
        if (!$this->session->userdata('validated')) redirect('login');
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
            $total        = $this->Gate->get_all(0, 0, null, $removed_only)->num_rows();

            if( null != $wildcard ) {
                $gates = $this->Gate->like($wildcard, $start_from, $limit, $sort, $removed_only)->result_array();
                $total   = $this->Gate->like($wildcard, 0, 0, null, $removed_only)->num_rows();
            } else {
                $gates = $this->Gate->get_all($start_from, $limit, $sort, $removed_only)->result_array();
            }

            foreach ($gates as $key => $gate) {
                $bootgrid_arr[] = array(
                    'count_id'  => $key + 1 + $start_from,
                    'id'        => $gate['id'],
                    'name'  => $gate['name'] ? $gate['name'] : '',
                    'code'  => $gate['code'] ? $gate['code'] : '',
                    'description'    => $gate['description'],
                );
            }

            $data = array(
                "current"       => intval($current),
                "rowCount"      => $limit,
                "searchPhrase"  => $wildcard,
                "total"         => intval( $total ),
                "rows"          => $bootgrid_arr,
                "trash"         => array(
                    "count" => $this->Gate->get_all(0, 0, null, true)->num_rows(),
                )
                // "debug" => $member['type'],
            );

            echo json_encode( $data );
            exit();
        }
    }

    public function index()
    {
        // Validate
        if (!$this->Auth->can(['members/listing'])) {
            $this->Data['Headers']->Page = 'errors/403';
            $this->load->view('layouts/errors', $this->Data);
        }

        $this->Data['gates'] = $this->Gate->all();

        $this->load->view('layouts/main', $this->Data);
    }

    public function add()
    {
        # Validation
        if( $this->Gate->validate(true) ) {
            # Save
            $gate = array(
                'name' => $this->input->post('name'),
                'code' => $this->input->post('code'),
                'description' => $this->input->post('description'),
            );

            $this->Gate->insert($gate);

            # Response
            $data = array(
                'message' => 'Gate was successfully added',
                'type' => 'success',
                'color' => 'success',
                // 'debug'   => $this->input->post('groups'),
            );

        } else {

            # Negative Response
            $data = array(
                'message' => $this->form_validation->toArray(),
                'type' => 'danger',
                'color' => 'danger',
            );
        }

        if ($this->input->is_ajax_request()) {
            echo json_encode( $data ); exit();
        } else {
            $this->session->set_flashdata('message', $data);
            redirect(base_url('monitor/gates'));
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

        $gate = $this->Gate->find($id);

        if( $this->input->is_ajax_request() ) {
            echo json_encode( $gate ); exit();
        } else {
            $this->Data['gate'] = $gate;
            $this->load->view('layouts/main', $this->Data);
        }
    }

    public function update($id)
    {
        # Validation
        if ($this->Gate->validate(false, $id, $this->input->post('code'))) {
            # Update
            $gate = array(
                'name' => $this->input->post('name'),
                'code' => $this->input->post('code'),
                'description' => $this->input->post('description'),
            );
            $this->Gate->update($id, $gate);

            # Response
            $data = array(
                'message' => 'Gate was successfully updated',
                'type' => 'success',
                'debug' => $gate,
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

        if( $this->Gate->delete($id) ) {
            # Also update the TABLE `groups_members`
            // $this->GroupMember->delete_member($id);

            if( 1 == $remove_many ) {
                $data['member']['message'] = 'Gates were successfully removed';
            } else {
                $data['member']['message'] = 'Gate was successfully removed';
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
            redirect('monitor/gates');
        }
    }
}
