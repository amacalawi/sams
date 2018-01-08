<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Search extends CI_Model {

    private $time_limit = 600;

    public function contacts($wildcard="")
    {
        $first = ''; $last='';
        if(preg_match('/\s/', $wildcard))
        {
            $name = explode(" ", $wildcard);
            $first = $name[0];
            $last = $name[1];
        }
        $this->db->where('contacts_firstname LIKE', '%' . $wildcard . '%')
                ->or_where('contacts_middlename LIKE', '%' . $wildcard . '%')
                ->or_where('contacts_lastname LIKE', '%' . $wildcard . '%')
                ->or_where('contacts_firstname', $first)
                ->or_where('contacts_lastname', $last)
                ->or_where('contacts_firstname', $last)
                ->or_where('contacts_lastname', $first)
                ->from('contacts')
                ->select('*');

        return $this->db->get();
    }

}