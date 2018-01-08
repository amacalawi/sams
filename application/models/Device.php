<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Device extends CI_Model {

    private $table = 'devices';
    private $column_id = 'id';
    public $validate = array(
        array( 'field' => 'name', 'label' => 'Device Name', 'rules' => 'required|trim' ),
        array( 'field' => 'code', 'label' => 'Code', 'rules' => 'trim' ),
        array( 'field' => 'gate_id', 'label' => 'Gate', 'rules' => 'required|trim' ),
    );

    function __construct()
    {
        parent::__construct();
    }

    public function validate($is_first_time=false, $id=null, $value=null)
    {
        $this->load->library('form_validation');

        foreach ($this->validate as $key => $validate)
        {
            $this->form_validation->set_rules( $validate['field'], $validate['label'], $validate['rules'] );
        }

        if($is_first_time)
        {
            $this->form_validation->set_message('is_unique', 'The %s is already in use');
            $this->form_validation->set_rules( 'code', 'Code', 'is_unique[devices.code]' );
        }
        else
        {
            $original_value = $this->db->where('id', $id)->get($this->table)->row()->code;
            if( $value != $original_value ) {
                $this->form_validation->set_message('is_unique', 'The %s is already in use');
                $this->form_validation->set_rules( 'code', 'Code', 'is_unique[devices.code]' );
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

    public function debug()
    {
        $sort = ['name'=>'desc', 'description'=>'asc'];
        if( is_array($sort) )
        {
            foreach ($sort as $key => $value) {
                $this->db->order_by($key, $value);
            }
        }
        return $this->db;
    }

    public function all()
    {
        $query = $this->db->get($this->table);
        return $query->result();
    }

    public function get_all($start_from=0, $limit=0, $sort=null)
    {
        if( null != $sort )
        {
            foreach ($sort as $field_name => $order) {
                $this->db->order_by($field_name, $order);
            }
        }

        $this->db->limit( $limit, $start_from );

        return $this->db->get($this->table);
    }

    public function find($id)
    {
        if( is_array($id) )
        {
          $query = $this->db->where_in($this->column_id, $id)->get($this->table);
          return $query->result();
        }

        $query = $this->db->where($this->column_id, $id)->get($this->table);
        return $query->row();
    }

    public function like($wildcard='', $start_from=0, $limit=0, $sort=null)
    {
        $first = ''; $last='';
        if(preg_match('/\s/', $wildcard))
        {
            $name = explode(" ", $wildcard);
            $first = $name[0];
            $last = $name[1];
        }
        $this->db->where('name LIKE', $wildcard . '%')
                ->or_where('id LIKE', $wildcard . '%')
                ->or_where('description LIKE', '%' . $wildcard . '%')
                ->or_where('code LIKE', $wildcard . '%')
                ->from($this->table)
                ->select('*');

        if( null != $sort )
        {
            foreach ($sort as $field_name => $order) {
                $this->db->order_by($field_name, $order);
            }
        }

        $this->db->limit( $limit, $start_from );

        return $this->db->get();
    }

    public function dropdown_list($select)
    {
        $query = $this->db->select($select)->get($this->table);
        return $query;
    }

    public function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        $this->db->where($this->column_id, $id);
        $this->db->update($this->table, $data);
        return true;
    }

    public function delete($id)
    {
        if( is_array($id) )
        {
          $this->db->where_in($this->column_id, $id)->delete($this->table);
          return $this->db->affected_rows() > 0;
        }

        $this->db->where($this->column_id, $id);
        $this->db->delete($this->table);
        return $this->db->affected_rows() > 0;
    }

}
