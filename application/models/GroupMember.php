<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class GroupMember extends CI_Model {

    private $table = 'group_members';
    private $column_id = 'group_id';

    public function __construct()
    {
        parent::__construct();
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

    public function lookup($column_name, $column_value)
    {
        $query = $this->db->where($column_name, $column_value)->get($this->table);
        return $query;
    }

    public function like($wildcard='', $start_from=0, $limit=0, $sort=null)
    {
        $first = ''; $last='';
        if(preg_match('/\s/', $wildcard)) {
            $name = explode(" ", $wildcard);
            $first = $name[0];
            $last = $name[1];
        }

        $this->db->where('group_id LIKE', $wildcard . '%')
                ->or_where('member_id LIKE', $wildcard . '%')
                ->from($this->table)
                ->select('*');

        if( null != $sort ) {
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

    public function delete_members($id)
    {
        $this->db->where('member_id', $id);
        // $this->db->where('group_id', $group);
        $this->db->delete($this->table);
        return $this->db->affected_rows() > 0;
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

    public function check_if_exist($id, $group='')
    {
        $this->db->where('member_id', $id);        
        $this->db->where('group_id', $group);
        $query = $this->db->get($this->table);
        return $query->num_rows();
    }

    public function delete_member($id, $group='')
    {
        if( is_array($id) )
        {
          $this->db->where_in('member_id', $id)->delete($this->table);
          return $this->db->affected_rows() > 0;
        }

        $this->db->where('member_id', $id);        
        $this->db->where('group_id', $group);
        $this->db->delete($this->table);
        return $this->db->affected_rows() > 0;
    }

    public function remove_member($member_id, $group_id)
    {   
        $this->db->where('group_id', $group_id);
        $this->db->where('member_id', $member_id);
        $this->db->delete($this->table);
        return $this->db->affected_rows() > 0;
    }


    public function find_group_by_member_id($id)
    {  
        $this->db->select('*');
        $this->db->from('group_members as mem');
        $this->db->join('groups as grp', 'mem.group_id = grp.groups_id');
        $query = $this->db->where('mem.member_id', $id)->get();
        
        $arr = array();

        foreach ($query->result() as $row) {
            $arr[] = $row->groups_name;
        }

        return implode(', ', $arr);

    }

    public function find_group_by_mem_id($id)
    {  
        $this->db->select('*');
        $this->db->from('group_members as mem');
        $this->db->join('groups as grp', 'mem.group_id = grp.groups_id');
        $query = $this->db->where('mem.member_id', $id)->get();

        $arr = array();

        foreach ($query->result() as $row) {
            $arr[] = $row->groups_id;
        }

        return implode(',', $arr);
    }
}
 ?>