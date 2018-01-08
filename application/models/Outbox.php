<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Outbox extends CI_Model {
    private $table = 'outbox';
    private $column_id = 'id';
    private $column_softDelete = 'removed_at';
    private $column_softDeletedBy = 'removed_by';
    public $validate = array(
        array( 'field' => 'msisdn', 'label' => 'Name', 'rules' => 'trim' ),
    );

    function __construct()
    {
        parent::__construct();
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
        $this->db->where('message_id LIKE ', '%'. $wildcard . '%')
                ->or_where('member_id LIKE ', '%'. $wildcard . '%')

                ->or_where('group_id', '%'. $wildcard)

                ->or_where('msisdn LIKE ', '%'. $wildcard . '%')
                ->or_where('sms LIKE ', '%'. $wildcard . '%')
                ->or_where('status LIKE ', '%'. $wildcard . '%')

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

    /* ------- NEW ------- */

    public function get_all_outbox($start_from=0, $limit=0, $sort=null)
    {  
        $this->db->select('out.id, mem.firstname, mem.lastname, mem.middlename, msg.message, out.msisdn, out.smsc, out.status');
        $this->db->from('outbox as out');
        $this->db->join('messages as msg','out.message_id = msg.id');
        $this->db->join('members as mem','out.member_id = mem.id');
        if( null != $sort ) {
            foreach ($sort as $field_name => $order) {

                if($field_name == 'member')
                {
                    $this->db->order_by('mem.firstname', $order);
                } else {
                    $this->db->order_by($field_name, $order);
                }
            }
        }
        $this->db->group_by('out.id');
        $query = $this->db->limit( $limit, $start_from )->get();
        return $query;
    }

    public function get_alls_outbox()
    {   
        $this->db->select('out.id, mem.firstname, mem.lastname, mem.middlename, msg.message, out.msisdn, out.smsc, out.status');
        $this->db->from('outbox as out');
        $this->db->join('messages as msg','out.message_id = msg.id');
        $this->db->join('members as mem','out.member_id = mem.id');
        $this->db->group_by('out.id');
        $query = $this->db->get();
        return $query;
    }   

    public function like_outbox($wildcard='', $start_from=0, $limit=0, $sort=null)
    {   
        $this->db->select('out.id, mem.firstname, mem.lastname, mem.middlename, msg.message, out.msisdn, out.smsc, out.status');
        $this->db->from('outbox as out');
        $this->db->join('messages as msg','out.message_id = msg.id');
        $this->db->join('members as mem','out.member_id = mem.id');
        $this->db->group_start();
        $this->db->or_where('out.id LIKE', '%' . $wildcard . '%');
        $this->db->or_where('mem.firstname LIKE', '%' . $wildcard . '%');
        $this->db->or_where('mem.lastname LIKE', '%' . $wildcard . '%');
        $this->db->or_where('mem.middlename LIKE', '%' . $wildcard . '%');
        $this->db->or_where('msg.message LIKE', '%' . $wildcard . '%');
        $this->db->or_where('out.msisdn LIKE', '%' . $wildcard . '%');
        $this->db->or_where('out.smsc LIKE', '%' . $wildcard . '%');
        $this->db->or_where('out.status LIKE', '%' . $wildcard . '%');
        $this->db->group_end();
        if( null != $sort ) {
            foreach ($sort as $field_name => $order) {
                $this->db->order_by($field_name, $order);
            }
        }
        $this->db->group_by('out.id');
        $query = $this->db->limit( $limit, $start_from )->get();
        return $query;
    }

    public function likes_outbox($wildcard='')
    {
        $this->db->select('out.id, mem.firstname, mem.lastname, mem.middlename, msg.message, out.msisdn, out.smsc, out.status');
        $this->db->from('outbox as out');
        $this->db->join('messages as msg','out.message_id = msg.id');
        $this->db->join('members as mem','out.member_id = mem.id');
        $this->db->group_start();
        $this->db->or_where('out.id LIKE', '%' . $wildcard . '%');
        $this->db->or_where('mem.firstname LIKE', '%' . $wildcard . '%');
        $this->db->or_where('mem.lastname LIKE', '%' . $wildcard . '%');
        $this->db->or_where('mem.middlename LIKE', '%' . $wildcard . '%');
        $this->db->or_where('msg.message LIKE', '%' . $wildcard . '%');
        $this->db->or_where('out.msisdn LIKE', '%' . $wildcard . '%');
        $this->db->or_where('out.smsc LIKE', '%' . $wildcard . '%');
        $this->db->or_where('out.status LIKE', '%' . $wildcard . '%');
        $this->db->group_end();
        $this->db->group_by('out.id');
        $query = $this->db->get();
        return $query;
    }

    ////////////////////////

    public function tracking($current='',$wildcard='', $start_from=0, $limit=0, $sort=null, $removed_only=false)
    {
        // $q  = "SELECT ms.id, ms.message, ob.status, COUNT(*) AS contacts,";
        // $q .= " SUM(IF(ob.status='pending', 1, 0)) as pending,";
        // $q .= " SUM(IF(ob.status='success', 1, 0)) as successful,";
        // $q .= " SUM(IF(ob.status='reject', 1, 0)) as rejected,";
        // $q .= " SUM(IF(ob.status='failure', 1, 0)) as failure,";
        // $q .= " SUM(IF(ob.status='buffered', 1, 0)) as buffered";
        // $q .= " FROM outbox ob JOIN messages ms ON ms.id = ob.message_id";
        // $q .= " WHERE ob.status LIKE '%$wildcard'";
        // $q .= " OR ms.message LIKE '%$wildcard%'";
        // $q .= " AND ob.removed_by IS NULL";
        // if( null != $sort ) {
        //     $q .= " ORDER BY";
        //     foreach ($sort as $field_name => $order) {
        //         $q .= " $field_name, $order, ";
        //     }
        // }
        // $q .= " GROUP BY ms.id";
        // if (0 != $limit || null != $limit) $q .= " LIMIT $limit";
        // if (0 != $start_from || null != $start_from) $q .= " OFFSET $start_from";
        // return $this->db->query($q);

        $this->db->select("ms.id, ms.message, count(*) AS contacts, SUM(IF(ob.status='pending', 1, 0)) as pending, SUM(IF(ob.status='success', 1, 0)) as successful, SUM(IF(ob.status='reject', 1, 0)) as rejected, SUM(IF(ob.status='failure', 1, 0)) as failure, SUM(IF(ob.status='buffered', 1, 0)) as buffered");
        $this->db->from("outbox as ob");
        $this->db->join("messages as ms","ob.message_id = ms.id");
        $this->db->where("ob.removed_by", NULL);
       // $this->db->where('ob.created_by !=', 0);
        $this->db->group_start();
        $this->db->or_where('ms.message LIKE', '%' . $wildcard . '%');
        $this->db->or_where('ms.id LIKE', '%' . $wildcard . '%');
        $this->db->group_end();
        if( null != $sort ) {
            foreach ($sort as $field_name => $order) {
                $this->db->ORDER_by($field_name, $order);
            }
        } 

        $this->db->order_by('ms.id', 'DESC');
        $this->db->group_by('ms.id');
        $query = $this->db->limit( $limit, $start_from )->get();
        return $query;
    }

    public function tracking_status_count($value, $column='status')
    {
        $q  = "SELECT ms.id, ms.message,";
        $q .= " SUM(IF(ob.$column='$value', 1, 0)) as $value";
        $q .= " FROM outbox ob JOIN messages ms ON ms.id = ob.message_id";
//        $q .= " WHERE ob.removed_by IS NULL and ob.created_by != 0";
        $q .= " WHERE ob.removed_by IS NULL";
        $q .= " AND ob.$column='$value'";
        // $q .= " GROUP BY ms.id";
        return $this->db->query($q);
    }

    public function tracking_all($start_from=0, $limit=0, $sort=null, $removed_only=false)
    {
        // $q  = "SELECT ms.id, ms.message, COUNT(*) AS contacts,";
        // $q .= " SUM(IF(ob.status='pending', 1, 0)) as pending,";
        // $q .= " SUM(IF(ob.status='success', 1, 0)) as successful,";
        // $q .= " SUM(IF(ob.status='reject', 1, 0)) as rejected,";
        // $q .= " SUM(IF(ob.status='failure', 1, 0)) as failure,";
        // $q .= " SUM(IF(ob.status='buffered', 1, 0)) as buffered";
        // $q .= " FROM outbox ob JOIN messages ms ON ms.id = ob.message_id";
        // $q .= " WHERE ob.removed_by IS NULL";
        // // if( null != $sort ) {
        // //     $q .= " ORDER BY";
        // //     foreach ($sort as $field_name => $order) {
        // //         $q .= " $field_name, $order ";
        // //     }
        // // }
        // $q .= " GROUP BY ms.id";
        // if (0 != $limit || null != $limit) $q .= " LIMIT $limit";
        // if (0 != $start_from || null != $start_from) $q .= " OFFSET $start_from";
        // // $q .= " OFFSET ". (!empty($start_from)?$start_from:-1);
        // return $this->db->query($q);

        $this->db->select("ms.id, ms.message, count(*) AS contacts, SUM(IF(ob.status='pending', 1, 0)) as pending, SUM(IF(ob.status='success', 1, 0)) as successful, SUM(IF(ob.status='reject', 1, 0)) as rejected, SUM(IF(ob.status='failure', 1, 0)) as failure, SUM(IF(ob.status='buffered', 1, 0)) as buffered");
        $this->db->from("outbox as ob");
        $this->db->join("messages as ms","ob.message_id = ms.id");
        $this->db->where("ob.removed_by", NULL);
//        $this->db->where('ob.created_by !=', 0);
        if( null != $sort ) {
            foreach ($sort as $field_name => $order) {
                $this->db->order_by($field_name, $order);
            }
        } 
            $this->db->order_by('ms.id', 'DESC');
        $this->db->group_by('ms.id');
        $query = $this->db->limit( $limit, $start_from )->get();
        return $query;

    }


    public function find_message_by_outbox($id)
    {   
        $this->db->select('out.id, msg.id as msg_id, out.msisdn, msg.message as msg_body');
        $this->db->from('outbox as out');
        $this->db->join('messages as msg', 'out.message_id = msg.id');
        $this->db->where('out.message_id', $id);
        $this->db->where('out.status', 'pending');
        $query = $this->db->get();

        if($query->num_rows() > 0)
        {
            return $query->result();
        } else {
            return 0;
        }
    }

    public function find_all_pending_message()
    {   
        $this->db->select('out.id, msg.id as msg_id, out.msisdn, msg.message as msg_body');
        $this->db->from('outbox as out');
        $this->db->join('messages as msg', 'out.message_id = msg.id');
        $this->db->where('out.status', 'pending');
        $query = $this->db->get();

        if($query->num_rows() > 0)
        {
            return $query->result();
        } else {
            return 0;
        }
    }
}
 ?>
