<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SearchController extends CI_Controller {

    private $Data = array();

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Search', '', TRUE);
    }

    public function contacts($wildcard=null)
    {
        $result = $this->Search->contacts($wildcard);
        if( $this->input->is_ajax_request() ) {
            $data = [
                "results" => $result->result_array(),
                "debug" => $result,
            ];
            echo json_encode($data); exit();
        } else {
            echo "<pre>";
                var_dump( $result->result_array() );
            echo "</pre>";
        }
    }
}