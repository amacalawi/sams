<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Scheduler extends CI_Model {
    private $table = 'scheduler';
    private $column_id = 'id';
    private $column_softDelete = 'removed_at';
    private $column_softDeletedBy = 'removed_by';
    public $validations = array(
        array( 'field' => 'msisdn[]', 'label' => 'Mobile Number', 'rules' => 'required' ),
        array( 'field' => 'body', 'label' => 'Mobile Number', 'rules' => 'required' ),
        array( 'field' => 'send_at_date', 'label' => 'Date', 'rules' => 'required' ),
        array( 'field' => 'send_at_time', 'label' => 'Time', 'rules' => 'required' ),
    );

    function __construct()
    {
        parent::__construct();
    }

    public function validate($is_first_time=false, $id=null, $value=null)
    {
        $this->load->library('form_validation');

        foreach ($this->validations as $validation) {
            $this->form_validation->set_rules( $validation['field'], $validation['label'], $validation['rules'] );
        }

        if ($this->form_validation->run() == FALSE) {
            return false;
        } else {
            return true;
        }
    }

    public function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function all($removed_only=false, $select="*")
    {
        if( $removed_only ) return $this->db->select($select)->where($this->column_softDelete ." != ", NULL)->get($this->table)->result();
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

    public function get_all_grouped($group_by='message', $start_from=0, $limit=0, $sort=null, $removed_only=false)
    {
        if( null != $sort )
        {
            foreach ($sort as $field_name => $order) {
                $this->db->order_by($field_name, $order);
            }
        }

        $this->db->limit( $limit, $start_from );

        if( $removed_only ) return $this->db->where($this->column_softDelete . " != ", NULL)->group_by($group_by)->get($this->table);
        return $this->db->where($this->column_softDelete, NULL)->group_by($group_by)->get($this->table);
    }

    public function find($id, $column=null)
    {
        if (null != $column) {
            $query = $this->db->where($column, $id)->get($this->table);
            return $query->row();
        }

        if ( is_array($id) ) return $this->db->where_in($this->column_id, $id)->get($this->table);

        $query = $this->db->where($this->column_id, $id)->get($this->table);
        return $query->row();
    }

    public function update($id, $data)
    {
        $this->db->where($this->column_id, $id);
        $this->db->update($this->table, $data);
        return true;
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
        $this->db->where('message LIKE', '%' . $wildcard . '%')
                ->or_where('id LIKE', $wildcard . '%')
                ->or_where('send_at LIKE', "%".date("Y-m-d", strtotime($wildcard)) . '%')
                ->or_where('status LIKE',$wildcard . '%')
                // ->or_where('(SELECT firstname FROM members WHERE id = member_ids) AS member_ids LIKE', '%' . $wildcard . '%')
                ->or_where('msisdn LIKE', '%' . $wildcard . '%')
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

    public function like_grouped($group_by, $wildcard='', $start_from=0, $limit=0, $sort=null, $removed_only=false)
    {
        $first = ''; $last='';
        if(preg_match('/\s/', $wildcard))
        {
            $name = explode(" ", $wildcard);
            $first = $name[0];
            $last = $name[1];
        }
        $this->db->where('message LIKE', '%' . $wildcard . '%')
                ->or_where('id LIKE', $wildcard . '%')
                ->or_where('send_at LIKE', "%".date("Y-m-d", strtotime($wildcard)) . '%')
                ->or_where('status LIKE',$wildcard . '%')
                // ->or_where('(SELECT firstname FROM members WHERE id = member_ids) AS member_ids LIKE', '%' . $wildcard . '%')
                ->or_where('msisdn LIKE', '%' . $wildcard . '%')
                ->from($this->table)
                ->select('*');

        if( null != $sort )
        {
            foreach ($sort as $field_name => $order) {
                $this->db->order_by($field_name, $order);
            }
        }

        $this->db->limit( $limit, $start_from );

        if( $removed_only ) return $this->db->where($this->column_softDelete . " !=", NULL)->group_by($group_by)->get();
        return $this->db->where($this->column_softDelete, NULL)->group_by($group_by)->get();
    }

    public function get($column, $where)
    {
        return $this->db->where($column, $where)->get($this->table);
    }

    public function get_scheduled()
    {
        $sql = "SELECT * FROM $this->table WHERE status IN ('pending', 'failure', 'failed', 'rejected', 'buffered') AND send_at < NOW();";
        return $this->db->query($sql)->result();
    }
}
 ?>
