<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MessageTemplatesController extends CI_Controller {

    private $Data = array();
    private $user_id = 0;

    public function __construct()
    {
        parent::__construct();
        $this->validated();
        $this->load->model('MessageTemplate', '', TRUE);

        $this->user_id = $this->session->userdata('id');

        $this->Data['Headers'] = get_page_headers();
        $this->Data['Headers']->CSS = '<link rel="stylesheet" href="'.base_url('assets/vendors/bootgrid/jquery.bootgrid.min.css').'">';
        $this->Data['Headers']->CSS.= '<link rel="stylesheet" href="'.base_url('assets/vendors/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css').'">';

        $this->Data['Headers']->CSS.= '<link rel="stylesheet" href="'.base_url('assets/vendors/chosen/chosen.min.css').'">';

        $this->Data['Headers']->JS  = '<script src="'.base_url('assets/vendors/bootgrid/jquery.bootgrid.min.js').'"></script>';
        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/vendors/moment/min/moment.min.js').'"></script>';
        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/vendors/bootstrap-growl/bootstrap-growl.min.js').'"></script>';
        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/vendors/chosen/chosen.jquery.min.js').'"></script>';
        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/vendors/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js').'"></script>';
        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/vendors/autosize/dist/autosize.min.js').'"></script>';
        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/vendors/jquery.validate/dist/jquery.validate.min.js').'"></script>';

        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/js/specifics/messageTemplates.js').'"></script>';
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
        $this->Data['Headers']->Page = 'message-templates/index';

        $this->Data['templates'] = $this->MessageTemplate->all();
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
            $removed_only = null !== $this->input->post('removedOnly') ? $this->input->post('removedOnly') : false;
            $total        = $this->MessageTemplate->get_all()->num_rows();

            if ( null != $wildcard ) {
                $templates = $this->MessageTemplate->like($wildcard, $start_from, $limit, $sort, $removed_only)->result_array();
                $total  = $this->MessageTemplate->like($wildcard, 0, 0, null, $removed_only)->num_rows();
            } else {
                $templates = $this->MessageTemplate->get_all($start_from, $limit, $sort, $removed_only)->result_array();
            }

            foreach ($templates as $key => $template) {
                $bootgrid_arr[] = array(
                    'count_id'           => $key + 1 + $start_from,
                    'id'          => $template['id'],
                    'name'        => $template['name'],
                    'code'        => $template['code'],
                    'type'        => $template['type'],
                );
            }
            $data = array(
                "current"       => intval($current),
                "rowCount"      => $limit,
                "searchPhrase"  => $wildcard,
                "total"         => intval( $total ),
                "rows"          => $bootgrid_arr,
                'trash'         => array(
                    'count' => $this->MessageTemplate->get_all(0, 0, null, true)->num_rows(),
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
        if( $this->MessageTemplate->validate(true) ) {
            /*
            | --------------------------------------
            | # Save
            | --------------------------------------
            */
            $template = array(
                'name'    => $this->input->post('name'),
                'code'     => $this->input->post('code'),
                'type' => $this->input->post('type'),
                'created_by' => $this->user_id,
            );
            $template_id = $this->MessageTemplate->insert($template);

            /*
            | ----------------------------------------
            | # Response
            | ----------------------------------------
            */
            $data = array(
                'message' => 'Template was successfully added',
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
        if( $this->input->is_ajax_request() )
        {
            $template = $this->MessageTemplate->find( $id );
            echo json_encode( $template );
            exit();
        }
    }

    public function update($id)
    {
        // if( $this->input->is_ajax_request() )
        // {
            /*
            | --------------------------------------
            | # Validation
            | --------------------------------------
            */
            if( $this->MessageTemplate->validate(false, $id, $this->input->post('code')) ) {
                /*
                | --------------------------------------
                | # Update
                | --------------------------------------
                */
                $template = array(
                    'name'    => $this->input->post('name'),
                    'code'     => $this->input->post('code'),
                    'type' => $this->input->post('type'),
                    'modified_by' => $this->user_id,
                );
                $this->MessageTemplate->update($id, $template);

                $data = array(
                    'message' => 'Template was successfully updated',
                    'type' => 'success'
                );
                echo json_encode( $data );
                exit();
            } else {
                echo json_encode(['message'=>$this->form_validation->toArray(), 'type'=>'danger']); exit();
            }
        // }
    }

    public function trash()
    {
        if( !$this->Auth->can(['messaging/templates/trash']) ) {
            $this->Data['Headers']->Page = 'errors/403';
            $this->load->view('layouts/errors', $this->Data);
            return false;
        }

        $this->Data['Headers']->Page = 'message-templates/trash';

        $this->Data['templates'] = $this->MessageTemplate->all(true);
        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/js/specifics/messageTemplatesTrash.js').'"></script>';
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

        if( $this->MessageTemplate->remove($id) ) {
            if( 1 == $remove_many ) {
                $data['message'] = 'Message Template were successfully removed';
            } else {
                $data['message'] = 'Message Template was successfully removed';
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

        if( $this->MessageTemplate->restore($id) ) {
            $data['message'] = 'Message Template was successfully restored';
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
                $ids = $this->input->post('templates_ids');
                if( $this->Template->delete($ids) )
                {
                    $data['message'] = 'Templates were successfully deleted';
                    $data['type'] = 'success';

                    /*
                    | --------------------------------
                    | # Update Many Contacts
                    | --------------------------------
                    | All Contacts with this Templates ID
                    */
                    foreach ($ids as $contacts_id) {
                        $contacts = $this->Contact->where(['contacts_template'=>$contacts_id])->get()->result_array();
                        foreach ($contacts as $contact) {
                            $this->Contact->update($contact['contacts_id'], ['contacts_template'=>'']);
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
            if( $this->Template->delete($id) )
            {
                $data['message'] = 'Template was successfully deleted';
                $data['type'] = 'success';

                /*
                | --------------------------------
                | # Update Contacts
                | --------------------------------
                | All Contacts with this Templates ID
                */
                $contacts = $this->Contact->where(['contacts_template'=>$id])->get()->result_array();
                foreach ($contacts as $contact) {
                    $this->Contact->update($contact['contacts_id'], ['contacts_template'=>'']);
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