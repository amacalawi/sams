<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class MessageTemplate extends CI_Model {
    private $table = 'message_templates';
    private $column_id = 'id';
    private $column_softDelete = 'removed_at';
    private $column_softDeletedBy = 'removed_by';
    public $validations = array(
        array( 'field' => 'name', 'label' => 'Name', 'rules' => 'trim|require' ),
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

        # If the Validation is running on the Add Function
        if( $is_first_time ) {
            $this->form_validation->set_message('is_unique', 'The %s is already in use');
            $this->form_validation->set_rules( 'code', 'Code', 'trim|is_unique['.$this->table.'.code]' );
        } else {
            $original = $this->db->where($this->column_id, $id)->get($this->table)->row()->code;

            /**
             * Only reset the rules if the
             * Original value is not equal to
             * the current value
             */
            if( $value != $original ) {
                $this->form_validation->set_message('is_unique', 'The %s is already in use');
                $this->form_validation->set_rules( 'code', 'Email', 'trim|is_unique['.$this->table.'.code]' );
            }
        }

        return $this->form_validation->run() == FALSE;
    }

    public function dropdown_list($select, $where=null, $value=null)
    {
        $query = $this->db->select($select);
        if (null !== $where) $this->db->where($where, $value);
        return $query->get($this->table);
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

    public function like($wildcard='', $start_from=0, $limit=0, $sort=null, $removed_only=false)
    {
        $this->db->where('id LIKE ', '%'. $wildcard . '%')
                ->or_where('name LIKE ', '%'. $wildcard . '%')

                ->or_where('code', '%'. $wildcard)

                ->from($this->table)
                ->select('*');

        if( null != $sort ) {
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

    public function tracking($wildcard='', $start_from=0, $limit=0, $sort=null, $removed_only=false)
    {
        $q  = "SELECT ms.id, ms.message, ob.status, COUNT(*) AS contacts,";
        $q .= " SUM(IF(ob.status='pending', 1, 0)) as pending,";
        $q .= " SUM(IF(ob.status='success', 1, 0)) as successful,";
        $q .= " SUM(IF(ob.status='reject', 1, 0)) as rejected,";
        $q .= " SUM(IF(ob.status='failure', 1, 0)) as failure,";
        $q .= " SUM(IF(ob.status='buffered', 1, 0)) as buffered";
        $q .= " FROM outbox ob JOIN messages ms ON ms.id = ob.message_id";
        $q .= " WHERE ob.status LIKE '%$wildcard'";
        $q .= " OR ms.message LIKE '%$wildcard%'";
        $q .= " AND ob.removed_by IS NULL";
        if( null != $sort ) {
            $q .= " ORDER BY";
            foreach ($sort as $field_name => $order) {
                $q .= " $field_name, $order, ";
            }
        }
        $q .= " GROUP BY ms.message";
        if (0 != $limit || null != $limit) $q .= " LIMIT $limit";
        if (0 != $start_from || null != $start_from) $q .= " OFFSET $start_from";
        return $this->db->query($q);
    }

    public function tracking_status_count($value, $column='status')
    {
        $q  = "SELECT ms.id, ms.message,";
        $q .= " SUM(IF(ob.$column='$value', 1, 0)) as $value";
        $q .= " FROM outbox ob JOIN messages ms ON ms.id = ob.message_id";
        $q .= " WHERE ob.removed_by IS NULL";
        $q .= " AND ob.$column='$value'";
        // $q .= " GROUP BY ms.id";
        return $this->db->query($q);
    }

    public function tracking_all($start_from=0, $limit=0, $sort=null, $removed_only=false)
    {
        $q  = "SELECT ms.id, ms.message, COUNT(*) AS contacts,";
        $q .= " SUM(IF(ob.status='pending', 1, 0)) as pending,";
        $q .= " SUM(IF(ob.status='success', 1, 0)) as successful,";
        $q .= " SUM(IF(ob.status='reject', 1, 0)) as rejected,";
        $q .= " SUM(IF(ob.status='failure', 1, 0)) as failure,";
        $q .= " SUM(IF(ob.status='buffered', 1, 0)) as buffered";
        $q .= " FROM outbox ob JOIN messages ms ON ms.id = ob.message_id";
        $q .= " WHERE ob.removed_by IS NULL";
        // if( null != $sort ) {
        //     $q .= " ORDER BY";
        //     foreach ($sort as $field_name => $order) {
        //         $q .= " $field_name, $order ";
        //     }
        // }
        $q .= " GROUP BY ms.message";
        if (0 != $limit || null != $limit) $q .= " LIMIT $limit";
        if (0 != $start_from || null != $start_from) $q .= " OFFSET $start_from";
        // $q .= " OFFSET ". (!empty($start_from)?$start_from:-1);
        return $this->db->query($q);
    }

}
 ?>