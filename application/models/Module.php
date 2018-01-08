<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Module extends CI_Model {

    private $table = 'modules';
    private $column_id = 'id';
    private $column_softDelete = 'removed_at';
    private $column_softDeletedBy = 'removed_by';
    public $validate = array(
        array( 'field' => 'name', 'label' => 'Name', 'rules' => 'required|trim' ),
        array( 'field' => 'slug', 'label' => 'Slug', 'rules' => 'required|trim' ),
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
            $this->form_validation->set_rules( 'slug', 'Slug', 'is_unique[modules.slug]' );
        }
        else
        {
            $original_value = $this->db->where('id', $id)->get($this->table)->row()->slug;
            if( $value != $original_value ) {
                $this->form_validation->set_message('is_unique', 'The %s is already in use');
                $this->form_validation->set_rules( 'slug', 'Slug', 'is_unique[modules.slug]' );
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
        if( is_array($id) ) return $this->db->where_in($this->column_id, $id)->get($this->table);

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
                ->or_where('slug LIKE', $wildcard . '%')
                ->or_where('description LIKE', '%' . $wildcard . '%')
                ->from( $this->table )
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
        $query = $this->db->select($select)->where($this->column_softDelete, NULL)->get($this->table);
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

    public function remove($id)
    {
        if( is_array($id) ) {
            $this->db->where_in($this->column_id, $id)->update($this->table, [$this->column_softDelete => date('Y-m-d H:i:s'), $this->column_softDeletedBy => $this->session->userdata('id')]);
            return $this->db->affected_rows() > 0;
        }

        $this->db->where($this->column_id, $id);
        $this->db->update($this->table, [$this->column_softDelete => date('Y-m-d H:i:s'), $this->column_softDeletedBy => $this->session->userdata('id')]);
        return true;
    }

    public function restore($id)
    {
        if( is_array($id) ) {
            $this->db->where_in($this->column_id, $id)->update($this->table, [$this->column_softDelete => NULL, $this->column_softDeletedBy => NULL]);
            return $this->db->affected_rows() > 0;
        }

        $this->db->where($this->column_id, $id);
        $this->db->update($this->table, [$this->column_softDelete => NULL, $this->column_softDeletedBy => NULL]);
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

    public function exists($value, $column="slug")
    {
        return $this->db->where($column, $value)->get($this->table)->num_rows() > 0;
    }

}
 ?>