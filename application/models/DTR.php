<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class DTR extends CI_Model {
    private $table = "dtr";

    public function find($id, $column="id")
    {
    	if (is_array($id)) return $this->db->where_in($column, $id)->where('datein', date("Y-m-d"))->get($this->table);

	   // $q = $this->db->where($column, $id)->where('datein', date('Y-m-d'))->where('dateout', NULL)->get($this->table);
       $q = $this->db->where($column, $id)->where('datein', date('Y-m-d'))->get($this->table);
	   return $q->row();
    }

    public function find_mode($id, $mode)
    {   
        $this->db->where('member_id', $id);
        $this->db->where('datein', date('Y-m-d'));
        $query = $this->db->get($this->table);

        if($query->num_rows() > 0)
        {   
            if($query->num_rows() > 0 && $mode == 0)
            {
                return $query->row()->id;
            }
            else
            {
                return -1;
            } 
        }
        else
        {
            return 0;
        }
    }


    public function update($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update($this->table, $data);
        return true;
    }

    public function insert($data)
    {
    	$this->db->insert($this->table, $data);
    	return $this->db->insert_id();
    }

    public function get_times($select="time_from, time_to")
    {
    	return $this->db->query("SELECT $select FROM $this->table")->result();
    }
}
?>
