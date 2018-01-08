<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MonitorController extends CI_Controller {

    private $Data = array();
    private $user_id = 0;

    public function __construct()
    {
        parent::__construct();
        $this->validated();

        $this->load->model('Monitor', '', TRUE);

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
        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/js/specifics/monitor.js').'"></script>';
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
    
    }
    public function announcement_listing($add = null)
    {     
        $data['announcements'] = $this->Monitor->all_announcements();

        if( $this->input->is_ajax_request() )
        {
            $bootgrid_arr = [];
            $current      = null != $this->input->post('current') ? $this->input->post('current') : 1;
            $limit        = $this->input->post('rowCount') == -1 ? 0 : $this->input->post('rowCount');
            $page         = $current !== null ? $current : 1;
            $start_from   = ($page-1) * $limit;
            $wildcard     = null != $this->input->post('searchPhrase') ? $this->input->post('searchPhrase') : null;
            

            if( null != $wildcard )
            {
                $announcements = $this->Monitor->like($wildcard, $start_from, $limit)->result_array();
                $total = $this->Monitor->likes($wildcard)->num_rows();
            }
            else
            {
                $announcements = $this->Monitor->get_all($start_from,  $limit)->result_array();
                $total = $this->Monitor->get_alls()->num_rows();
            }

            foreach ($announcements as $key => $announcement) {

                $bootgrid_arr[] = array(
                    'count_id'           => $key + 1 + $start_from,
                    'announcement_id'        => $announcement['announcement_id'],
                    'announcement_name'      => $announcement['announcement_name'],
                    'announcement_text'     => $announcement['announcement_text']
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
            //echo "<script> console.log('".$data."');</script>";
            exit();
        }
    }

    public function announcement()
    {
        $this->Data['Headers']->Page = 'monitor/announcement';
        $this->load->view('layouts/main', $this->Data);
    }

    public function dtr()
    {   
        $this->Data['Headers']->Page = 'monitor/dtr';
        $this->load->view('layouts/main', $this->Data);
    }

    public function fetch_contact($generate = null)
    {   
        $data['results'] = $this->Monitor->all_members();
        $this->load->view('monitor/fetch_contact', $data);
    }
    public function fetch_level($generate = null)
    {    
        $data['results'] = $this->Monitor->all_levels();
        $this->load->view('monitor/fetch_level', $data);
    }
    public function fetch_group($generate = null)
    {   
        $data['results'] = $this->Monitor->all_groups();
        $this->load->view('monitor/fetch_group', $data);
    }
    public function fetch_csv($generate = null)
    {   
        $this->load->helper('download');
        $row  = $this->Monitor->generate_csv($_GET['date_from'],$_GET['date_to'],$_GET['category'],$_GET['category_level'],$_GET['type'],$_GET['type_order'],$_GET['time_from'],$_GET['time_to']);
        $filename = strtotime("now").'.csv';
        force_download($filename,$row);
    }

    public function generate($generate = null)
    {   
        $this->Data['results'] = $this->Monitor->generate_dtr($_GET['date_from'],$_GET['date_to'],$_GET['category'],$_GET['category_level'],$_GET['type'],$_GET['type_order'],$_GET['time_from'],$_GET['time_to']);
        $this->load->view('layouts/main', $this->Data);
    }

    public function add_announcement($generate = null)
    {
        if( $this->input->is_ajax_request() ) {
           
                /*
                | --------------------------------------
                | # Save
                | --------------------------------------
                */
                $announcement = array(
                    'announcement_name'    => $this->input->post('announcement_name'),
                    'announcement_text' => $this->input->post('announcement_text')
                );
                $announcement_id = $this->Monitor->insert_announcement($announcement);

                /*
                | ----------------------------------------
                | # Response
                | ----------------------------------------
                */
                $data = array(
                    'message' => 'Announcement was successfully added',
                    'type'    => 'success'
                );
                echo json_encode( $data ); exit();
            

        }
        else
        {
            redirect( base_url('monitor') );
        }
    }


    public function del_announcement($id=null)
    {
   
            if( null == $id || !isset($id) )
            {
                $ids = $_GET['id'];
                if( $this->Monitor->del_announcement($ids) )
                {
                    $data['message'] = 'Announcement were successfully deleted';
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
            if( $this->Monitor->del_announcement($id) )
            {
                $data['message'] = 'Announcement was successfully deleted';
                $data['type'] = 'success';

            }
            else
            {
                $data['message'] = 'An unhandled error occured. Record was not deleted';
                $data['type'] = 'danger';
            }
            echo json_encode( $data );
            exit();
    }

    public function edit_announcement($id)
    {
        if( $this->input->is_ajax_request() )
        {
            $announcement = $this->Monitor->find( $id );
            echo json_encode( $announcement );
            exit();
        }
    }


    public function update_announcement($id)
    {
            /*
                | --------------------------------------
                | # Update
                | --------------------------------------
                */
                $announcement = array(
                    'announcement_name'    => $this->input->post('announcement_name'),
                    'announcement_text' => $this->input->post('announcement_text')
                );

                $this->Monitor->update_announcement($id, $announcement);

                $data = array(
                    'message' => 'Announcement was successfully updated',
                    'type' => 'success'
                );
                echo json_encode( $data );
                exit();
    
    }


    public function splash_listing($add = null)
    {     
        $data['splashs'] = $this->Monitor->all_splashs();

        if( $this->input->is_ajax_request() )
        {
            $bootgrid_arr = [];
            $current      = null != $this->input->post('current') ? $this->input->post('current') : 1;
            $limit        = $this->input->post('rowCount') == -1 ? 0 : $this->input->post('rowCount');
            $page         = $current !== null ? $current : 1;
            $start_from   = ($page-1) * $limit;
            $wildcard     = null != $this->input->post('searchPhrase') ? $this->input->post('searchPhrase') : null;
            

            if( null != $wildcard )
            {
                $splashs = $this->Monitor->like_splash($wildcard, $start_from, $limit)->result_array();
                $total = $this->Monitor->likes_splash($wildcard)->num_rows();
            }
            else
            {
                $splashs = $this->Monitor->get_all_splash($start_from,  $limit)->result_array();
                $total = $this->Monitor->get_alls_splash()->num_rows();
            }

            foreach ($splashs as $key => $splash) {

                $bootgrid_arr[] = array(
                    'count_id'           => $key + 1 + $start_from,
                    'id'        => $splash['id'],
                    'video_title'      => $splash['video_title'],
                    'video_source'     => $splash['video_source']
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
            //echo "<script> console.log('".$data."');</script>";
            exit();
        }
    }

    public function splash()
    {
        $this->Data['Headers']->Page = 'monitor/splash_page';
        $this->load->view('layouts/main', $this->Data);
    }

    public function add_splash_source($generate = null)
    {
        
        $data = array();

        if(isset($_GET['files']))
        {   
            $error = false;
            $files = array();

            $uploaddir = 'uploads/';
            foreach($_FILES as $file)
            {
                if(move_uploaded_file($file['tmp_name'], $uploaddir .basename($file['name'])))
                {
                    $files[] = $uploaddir .$file['name'];
                }
                else
                {
                    $error = true;
                }
            }
            $data = ($error) ? array('error' => 'There was an error uploading your files') : array('success' => 'Form was submitted'.$uploaddir.'');
        }

        echo json_encode($data);   
    }

}   