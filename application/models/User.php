<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User extends CI_Model {

    private $table = 'users';
    private $column_id = 'id';
    private $column_softDelete = 'removed_at';
    private $column_softDeletedBy = 'removed_by';
    public $validate = array(
        array( 'field' => 'username', 'label' => 'Username', 'rules' => 'required|trim' ),
        array( 'field' => 'email', 'label' => 'Email', 'rules' => 'required' ),
        array( 'field' => 'privilege', 'label' => 'Privilege', 'rules' => 'required' ),
        array( 'field' => 'privilege_level', 'label' => 'Privilege Level', 'rules' => 'required' ),
    );

    function __construct()
    {
        parent::__construct();
    }

    public function validate($is_first_time=false, $id=null, $value=null, $email=null)
    {
        $this->load->library('form_validation');

        foreach ($this->validate as $key => $validate)
        {
            $this->form_validation->set_rules( $validate['field'], $validate['label'], $validate['rules'] );
        }

        if($is_first_time)
        {
            $this->form_validation->set_message('is_unique', 'The %s is already in use');
            $this->form_validation->set_rules( 'username', 'Username', 'is_unique[users.username]' );
            $this->form_validation->set_rules( 'email', 'Email', 'is_unique[users.email]' );
        }
        else
        {
            $original_value = $this->db->where($this->column_id, $id)->get($this->table)->row()->username;
            $original_email = $this->db->where($this->column_id, $id)->get($this->table)->row()->email;
            if( $value != $original_value ) {
                $this->form_validation->set_message('is_unique', 'The %s is already in use');
                $this->form_validation->set_rules( 'username', 'Username', 'is_unique[users.username]' );
            }
            if( $email != $original_email ) {
                $this->form_validation->set_message('is_unique', 'The %s is already in use');
                $this->form_validation->set_rules( 'email', 'Email', 'is_unique[users.email]' );
            }
        }

        if ($this->form_validation->run() == FALSE)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    public function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function all($removed_only=false)
    {
        if( $removed_only ) return $this->db->where($this->column_softDelete ." != ", NULL)->get($this->table)->result();
        $query = $this->db->where($this->column_softDelete, NULL)->get($this->table);
        return $query->result();
    }

    public function get_all($start_from=0, $limit=0, $sort=null, $removed_only=false)
    {
        if( null != $sort )
        {
            foreach ($sort as $field_name => $order) {
                $this->db->order_by($field_name, $order);
            }
        }

        $this->db->limit( $limit, $start_from );

        if( $removed_only ) return $this->db->where($this->column_softDelete . " != ", NULL)->get($this->table);
        return $this->db->where($this->column_softDelete, NULL)->get($this->table);
    }

    public function find($id)
    {
        $this->db->select('id, username, firstname, middlename, lastname, email, privilege, privilege_level');
        if( is_array($id) ) return $this->db->where_in($this->column_id, $id)->get($this->table);

        $query = $this->db->where($this->column_id, $id)->get($this->table);
        return $query->row();
    }

    public function like($wildcard='', $start_from=0, $limit=0, $sort=null, $removed_only=false)
    {
        $first = ''; $last='';
        if(preg_match('/\s/', $wildcard))
        {
            $name = explode(" ", $wildcard);
            $first = $name[0];
            $last = $name[1];
        }
        $this->db->where('username LIKE', $wildcard . '%')
                ->or_where('id LIKE', $wildcard . '%')
                ->or_where('firstname LIKE', '%' . $wildcard . '%')
                ->or_where('middlename LIKE', $wildcard . '%')
                ->or_where('lastname LIKE', $wildcard . '%')
                ->or_where('email LIKE', $wildcard . '%')
                ->or_where('privilege LIKE', $wildcard . '%')
                ->or_where('privilege_level LIKE', $wildcard . '%')
                ->from( $this->table )
                ->select('*');

        if( null != $sort )
        {
            foreach ($sort as $field_name => $order) {
                $this->db->order_by($field_name, $order);
            }
        }

        $this->db->limit( $limit, $start_from );

        if( $removed_only ) return $this->db->where($this->column_softDelete . " !=", NULL)->get();
        return $this->db->where($this->column_softDelete, NULL)->get();
    }

    public function update($id, $data)
    {
        $this->db->where($this->column_id, $id);
        $this->db->update($this->table, $data);
        return true;
    }

    public function fullname($id, $format=1)
    {
        $query = $this->db->where($this->column_id, $id)->get($this->table);
        switch ($format) {
            case 1:
                return $query->firstname . " " . $query->middlename . " " . $query->lastname;
                break;

            default:
                return $query->firstname . " " . $query->middlename . " " . $query->lastname;
                break;
        }
        return $query->firstname . " " . $query->middlename . " " . $query->lastname;
    }

}
 ?>