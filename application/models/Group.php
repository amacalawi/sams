<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Group extends CI_Model {

    private $table = 'groups';
    private $column_id = 'groups_id';
    private $column_softDelete = 'removed_at';
    private $column_softDeletedBy = 'removed_by';
    private $time_limit = 600;

    public $validate = array(
        array( 'field' => 'groups_name', 'label' => 'Group Name', 'rules' => 'required|trim' ),
        array( 'field' => 'groups_code', 'label' => 'Code', 'rules' => 'trim' ),
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
            $this->form_validation->set_rules( 'groups_code', 'Code', 'is_unique[groups.groups_code]' );
        }
        else
        {
            $original_value = $this->db->where('groups_id', $id)->get($this->table)->row()->groups_code;
            if( $value != $original_value ) {
                $this->form_validation->set_message('is_unique', 'The %s is already in use');
                $this->form_validation->set_rules( 'groups_code', 'Code', 'is_unique[groups.groups_code]' );
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
        $sort = ['groups_name'=>'desc', 'groups_description'=>'asc'];
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
        if( is_array($id) )
        {
          $query = $this->db->where_in($this->column_id, $id)->get($this->table);
          return $query->result();
        }

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
        $this->db->where('groups_name LIKE', $wildcard . '%')
                ->or_where('groups_id LIKE', $wildcard . '%')
                ->or_where('groups_description LIKE', '%' . $wildcard . '%')
                ->or_where('groups_code LIKE', $wildcard . '%')
                ->from($this->table)
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

    public function remove($id)
    {
        if( is_array($id) ) {
            $this->db->where_in($this->column_id, $id)->update($this->table, [$this->column_softDelete => date('Y-m-d H:i:s'), $this->column_softDeletedBy => $this->session->userdata('id')]);
            return $this->db->affected_rows() > 0;
        }

        $this->db->where($this->column_id, $id);
        $this->db->update($this->table, [$this->column_softDelete => date('Y-m-d H:i:s'), $this->column_softDeletedBy => $this->session->userdata('id')]);
        return $this->db->affected_rows() > 0;
    }

    public function restore($id)
    {
        if( is_array($id) ) {
            $this->db->where_in($this->column_id, $id)->update($this->table, [$this->column_softDelete => NULL, $this->column_softDeletedBy => NULL]);
            return $this->db->affected_rows() > 0;
        }

        $this->db->where($this->column_id, $id);
        $this->db->update($this->table, [$this->column_softDelete => NULL, $this->column_softDeletedBy => NULL]);
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

    public function export($all=false, $start_date=null, $end_date=null, $level=null)
    {
        if ($all) return $this->db->select('*')->where("created_at BETWEEN '$start_date' AND '$end_date'")->get($this->table);
        $this->db->select('*')->where("created_at BETWEEN '$start_date' AND '$end_date'");
        $this->db->where($this->column_softDelete, NULL);
        return $this->db->get($this->table);
    }

    public function import($file=null, $truncate=false)
    {
        $this->pdo = $this->load->database('pdo', true);
        $this->pdo->query( "SET NAMES 'utf8'" );

        if(!file_exists($file) || !is_readable($file)) return false;
        # Load the data to database
        set_time_limit($this->time_limit); // for longer execution time if needed

        if( $truncate ) $this->db->truncate($this->table); // truncate the table if all is good

        $query = "LOAD DATA local INFILE '".addslashes($file)."' INTO TABLE ".$this->pdo->dbprefix.$this->table." CHARACTER SET ".$this->pdo->char_set." FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\n' IGNORE 1 LINES ( groups_id, groups_name, groups_description, groups_code, created_by, updated_by, removed_by, created_at, updated_at, @removed_at) SET removed_at = nullif(@removed_at,'')";

        return $this->pdo->query($query);
    }

    public function check_if_group_exist($id)
    {
        $this->db->where('groups_code', $id);
        $query = $this->db->get($this->table);

        if($query->num_rows() > 0)
        {
            foreach ($query->result() as $row) {
                return $row->groups_id;
            }
        }
        else {
            return 0;
        }
    }

}
 ?>