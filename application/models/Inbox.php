<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Inbox extends CI_Model {
    private $table = 'inbox';
    private $outbox_table = 'outbox';
    private $contacts_table = 'members';
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

    public function validate($id=null, $value=null)
    {
        $this->load->library('form_validation');

        foreach ($this->validate as $key => $validate) {
            $this->form_validation->set_rules( $validate['field'], $validate['label'], $validate['rules'] );
        }

        return ($this->form_validation->run() == FALSE) ? false : true;
    }

    public function all($removed_only=false)
    {
        if( $removed_only ) return $this->db->where($this->column_softDelete ." != ", NULL)->get($this->table)->result();
        $query = $this->db->where($this->column_softDelete, NULL)->get($this->table);
        return $query->result();
    }

    public function get($limit="-1", $select="*")
    {
        $sql = "SELECT $select FROM $this->table ORDER BY `inbox`.`id` DESC LIMIT $limit";
        return$this->db->query($sql)->result();
    }

    public function messages($msisdn, $order_by="ASC")
    {
        $query = "";

        $query .= " SELECT 'inbox' AS table_name, id, body, msisdn, smsc, status, created_at, NULL AS member_id "; # last space important
        $query .= " FROM " . $this->table;
        $query .= " WHERE msisdn IN ('" . $msisdn . "') ";

        $query .= " UNION ";

        $query .= " SELECT 'outbox' AS table_name, id, (SELECT message FROM messages WHERE outbox.message_id = messages.id) AS body, msisdn, smsc, status, (SELECT outbox.created_at FROM messages WHERE outbox.message_id = messages.id) AS created_at, member_id ";
        $query .= " FROM " . $this->outbox_table;
        $query .= " WHERE msisdn IN ('".$msisdn."')";
        $query .= " GROUP BY message_id ";

        $query .= " ORDER BY created_at " . $order_by;

        return $this->db->query( $query )->result();
    }

    public function contacts($order_by="DESC", $table=null)
    {
        if( null == $table ) $table = $this->contacts_table;
        $query = "";

        $query .= " SELECT 'inbox' as table_name, inbox.id, body, inbox.msisdn, inbox.smsc, inbox.created_at, SUM(inbox.status) as status, concat(members.firstname, ' ', members.lastname) as fullname, members.firstname, members.lastname, members.id AS member_id ";
        // $query .= " SELECT * ";
        $query .= " FROM " . $this->table . " ";

        $query .= " LEFT JOIN members ON inbox.msisdn = members.msisdn ";

        $query .= " GROUP BY msisdn ";
        $query .= " ORDER BY created_at " . $order_by;

        $q = $this->db->query( $query )->result();
        $contacts = [];
        foreach ($q as $contact) {
            $contacts[$contact->msisdn][] = $contact;
        }

        return $contacts;
    }

    public static function getUnreadCount()
    {
        $CI =& get_instance();
        $CI->load->model('Inbox', '', TRUE);

        $query = "SELECT SUM(status) AS s FROM {$CI->Inbox->table}";
        return $CI->db->query($query)->row() ? $CI->db->query($query)->row()->s : 0;
    }

    public function updateColumn($data, $msisdn)
    {
        $this->db->where('msisdn', $msisdn);
        $this->db->update($this->table, $data);
        return true;
    }
}
