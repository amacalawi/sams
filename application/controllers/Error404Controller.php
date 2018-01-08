<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Error404Controller extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->output->set_status_header('404');
        $Headers = get_page_headers();
        $Headers->Page = 'errors/html/error_404';
        $this->Data['Headers'] = $Headers;
        $this->Data['Content']['heading'] = 'Error 404';
        $this->Data['Content']['subheading'] = 'The page does not exist.';

        $this->load->view('layouts/main', $this->Data);
    }

}

?>