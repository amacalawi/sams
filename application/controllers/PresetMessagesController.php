<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PresetMessagesController extends CI_Controller {

    private $Data = array();
    private $user_id = 0;

    public function __construct()
    {
        parent::__construct();
        $this->validated();

        $this->load->model('PresetMessage', '', TRUE);

        $this->user_id = $this->session->userdata('id');

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

        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/js/specifics/presetMessages.js').'"></script>';
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
        $this->Data['Headers']->Page = 'preset-messages/index';
        $this->Data['presetMessages'] = $this->db->query("SELECT * FROM preset_messages WHERE active = 1")->result();
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
            $total        = $this->PresetMessage->get_all(0, 0, null, $removed_only)->num_rows();

            if ( null != $wildcard ) {
                $templates = $this->PresetMessage->like($wildcard, $start_from, $limit, $sort, $removed_only)->result_array();
                $total  = $this->PresetMessage->like($wildcard, 0, 0, null, $removed_only)->num_rows();
            } else {
                $templates = $this->PresetMessage->get_all($start_from, $limit, $sort, $removed_only)->result_array();
            }

            foreach ($templates as $key => $template) {
                $bootgrid_arr[] = array(
                    'count_id'           => $key + 1 + $start_from,
                    'id'          => $template['id'],
                    'name'        => $template['name'],
                );
            }
            $data = array(
                "current"       => intval($current),
                "rowCount"      => $limit,
                "searchPhrase"  => $wildcard,
                "total"         => intval( $total ),
                "rows"          => $bootgrid_arr,
                'trash'         => array(
                    'count' => $this->PresetMessage->get_all(0, 0, null, true)->num_rows(),
                ),
            );
            echo json_encode( $data );
            exit();
        }
    }

    public function add()
    {
        /*
        | --------------------------------------
        | # Validation
        | --------------------------------------
        */
        if( $this->PresetMessage->validate(true) ) {
            /*
            | --------------------------------------
            | # Save
            | --------------------------------------
            */
            $template = array(
                'name'    => $this->input->post('name'),
                'active'  => 1,
                'type' => $this->input->post('type'),
                'created_by' => $this->user_id,
            );
            $template_id = $this->PresetMessage->insert($template);

            /*
            | ----------------------------------------
            | # Response
            | ----------------------------------------
            */
            $data = array(
                'message' => 'Preset Message was successfully added',
                'type'    => 'success'
            );
            echo json_encode( $data ); exit();
        } else {
            echo json_encode(['message'=>$this->form_validation->toArray(), 'type'=>'danger']); exit();
        }
    }

    /**
     * Retrieve the resource for editing
     * @param  int $id
     * @return JSON
     */
    public function edit($id)
    {
        if( $this->input->is_ajax_request() ) {
            $presetMessage = $this->PresetMessage->find( $id );
            echo json_encode( $presetMessage );
            exit();
        }
    }

    public function update($id)
    {
        if( $this->PresetMessage->validate(false, $id, '') ) {
            # Update
            $preset = array(
                'name' => $this->input->post('name'),
                'modified_by' => $this->user_id,
            );
            $this->PresetMessage->update($id, $preset);

            # Response
            $data = array(
                'message' => 'Preset Message was successfully updated',
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
        // if( !$this->Auth->can(['schedules/preset-messages/trash']) ) {
        //     $this->Data['Headers']->Page = 'errors/403';
        //     $this->load->view('layouts/errors', $this->Data);
        //     return false;
        // }

        $this->Data['Headers']->Page = 'preset-messages/trash';

        $this->Data['templates'] = $this->PresetMessage->all(true);
        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/js/specifics/presetMessagesTrash.js').'"></script>';
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

        if( $this->PresetMessage->remove($id) ) {
            if( 1 == $remove_many ) {
                $data['message'] = 'Preset-messages were successfully removed';
            } else {
                $data['message'] = 'Preset-messages was successfully removed';
            }
            $data['title'] = 'Success';
            $data['type'] = 'success';
        } else {
            $data['title'] = 'Error';
            $data['message'] = 'An error occured while removing the resource';
            $data['type'] = 'error';
        }

        if( $this->input->is_ajax_request() ) {
            echo json_encode( $data ); exit();
        } else {
            $this->session->set_flashdata('message', $data );
            redirect('messaging/templates');
        }
    }

    public function restore($id=null)
    {
        if( null === $id ) $id = $this->input->post('id');

        if( $this->PresetMessage->restore($id) ) {
            $data['message'] = 'Preset-messages was successfully restored';
            $data['type'] = 'success';
        } else {
            $data['message'] = 'An error occured while trying to restore the resource';
            $data['type'] = 'error';
        }

        if( $this->input->is_ajax_request() ) {
            echo json_encode( $data ); exit();
        } else {
            $this->session->set_flashdata('message', $data );
            redirect('messaging/templates');
        }
    }
}