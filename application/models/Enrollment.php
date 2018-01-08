<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Enrollment extends CI_Model
{
    private $table = 'enrollments';
    private $column_id = 'id';
    private $column_softDelete = 'removed_at';
    private $column_softDeletedBy = 'removed_by';
    private $time_limit = 600;

    public $validations = array();

    function __construct()
    {
        parent::__construct();

        $this->load->model('Schoolyear', '', TRUE);
    }

    public function validate($is_first_time=false, $id=null, $value=null)
    {
        return true;

        $this->load->library('form_validation');

        foreach ($this->validations as $validation) {
            $this->form_validation->set_rules( $validation['field'], $validation['label'], $validation['rules'] );
        }

        # If the Validation is running on the Add Function
        if( $is_first_time ) {
            // $this->form_validation->set_message('is_unique', 'The %s is already in use');
            // $this->form_validation->set_rules( 'code', 'Code', 'trim|required|is_unique['.$this->table.'.code]' );
        } else {
            $original = $this->db->where($this->column_id, $id)->get($this->table)->row()->code;

            /**
             * Only reset the rules if the
             * Original value is not equal to
             * the current value
             */
            if ( $value != $original ) {
                $this->form_validation->set_message('is_unique', 'The %s is already in use');
                $this->form_validation->set_rules( 'code', 'Code', 'trim|required|is_unique['.$this->table.'.code]' );
            }
        }

        if ($this->form_validation->run() == FALSE) {
            return false;
        } else {
            return true;
        }
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

        $this->db->where('schoolyear_id', $this->Schoolyear->find('CURRENT', 'status')->id);

        $this->db->limit($limit, $start_from);

        if( $removed_only ) return $this->db->where($this->column_softDelete . " != ", NULL)->get($this->table);
        return $this->db->where($this->column_softDelete, NULL)->get($this->table);
    }

    public function dropdown_list($select)
    {
        $query = $this->db->select($select)->get($this->table);
        return $query;
    }

    public function find($id, $column=null, $select="*")
    {
        if (null != $column) {
            $query = $this->db->select($select)->where($column, $id)->get($this->table);
            return $query->row();
        }

        if ( is_array($id) ) return $this->db->where_in($this->column_id, $id)->get($this->table);

        $query = $this->db->select($select)->where($this->column_id, $id)->get($this->table);
        return $query->row();
    }

    public function find_member_via_msisdn($msisdn)
    {
        $query = $this->db->where('msisdn', $msisdn)->where($this->column_softDelete, NULL)->get($this->table);
        return $query->result();
    }

    public function like($wildcard='', $start_from=0, $limit=0, $sort=null, $removed_only=false)
    {
        // $first = ''; $last='';
        // if(preg_match('/\s/', $wildcard)) {
        //     $name = explode(" ", $wildcard);
        //     $first = $name[0];
        //     $last = $name[1];
        // }
        $this->db->where('member_id LIKE ', '%'. $wildcard . '%')
                ->or_where('schoolyear_id LIKE', '%'. $wildcard . '%')
                // ->or_where('middlename LIKE ', '%'. $wildcard . '%')
                // ->or_where('lastname LIKE ', '%'. $wildcard . '%')

                // ->or_where('middlename', '%'. $wildcard)

                // ->or_where('id LIKE ', '%'. $wildcard . '%')
                // ->or_where('level LIKE ', '%'. $wildcard . '%')
                // ->or_where('type LIKE ', '%'. $wildcard . '%')

                // ->or_where('address_blockno LIKE', $wildcard . '%')
                // ->or_where('address_street LIKE', $wildcard . '%')
                // ->or_where('address_brgy LIKE', $wildcard . '%')
                // ->or_where('address_city LIKE', $wildcard . '%')
                // ->or_where('address_zip LIKE', $wildcard . '%')
                // ->or_where('telephone LIKE', $wildcard . '%')
                // ->or_where('msisdn LIKE', $wildcard . '%')
                // ->or_where('email LIKE', $wildcard . '%')
                // ->or_where('groups LIKE', $wildcard . '%')

                // ->or_where('firstname', $first)
                // ->or_where('lastname', $last)
                // ->or_where('firstname', $last)
                // ->or_where('lastname', $first)

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

    public function insert($data)
    {
        $this->db->insert($this->table, $data);
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
        //  $this->column_softDeletedBy => $this->session->userdata('id')
        $this->db->update($this->table, [$this->column_softDelete => date('Y-m-d H:i:s')]);
        return true;
    }

    public function restore($id)
    {
        if( is_array($id) ) {
            $this->db->where_in($this->column_id, $id)->update($this->table, [$this->column_softDelete => NULL, $this->column_softDeletedBy => NULL]);
            return $this->db->affected_rows() > 0;
        }

        $this->db->where($this->column_id, $id);
        // $this->column_softDeletedBy => NULL
        $this->db->update($this->table, [$this->column_softDelete => NULL]);
        return true;
    }

    public function delete($id)
    {
        if( is_array($id) ) {
            $this->db->where_in($this->column_id, $id)->delete($this->table);
            return $this->db->affected_rows() > 0;
        }

        $this->db->where($this->column_id, $id);
        $this->db->delete($this->table);
        return $this->db->affected_rows() > 0;
    }

    public function where($where, $start_from=0, $limit=0)
    {
        $this->db->select('*')->from($this->table);
        foreach ($where as $key => $value) {
            $this->db->where($key, $value);
        }
        $this->db->limit($limit, $start_from);
        return $this->db;
    }

    public function import($file=null, $truncate=false)
    {
        $this->pdo = $this->load->database('pdo', true);
        $this->pdo->query( "SET NAMES 'utf8'" );

        if(!file_exists($file) || !is_readable($file)) return false;

        # Load the data to database
        set_time_limit($this->time_limit); // for longer execution time if needed

        if( $truncate ) $this->db->truncate($this->table); // truncate the table if all is good

        $query = "LOAD DATA local INFILE '".addslashes($file)."' INTO TABLE ".$this->pdo->dbprefix.$this->table." CHARACTER SET ".$this->pdo->char_set." FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\n' IGNORE 1 LINES (@ignore, stud_no, firstname, middlename, lastname, @nick, level, type, address_blockno, address_street, address_brgy, address_city, address_zip, telephone, @msisdn, email, groups, avatar, schedule_id, created_by, updated_by, removed_by, @created_at, @updated_at, @ignore) SET created_at = NOW(), updated_at = NOW(), nick = nullif(@nick,''), msisdn=LPAD(@msisdn, 11, '0')";

        // $query = "LOAD DATA local INFILE '".addslashes($file)."' INTO TABLE ".$this->pdo->dbprefix.$this->table." CHARACTER SET ".$this->pdo->char_set." FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"' ESCAPED BY '\"' LINES TERMINATED BY '\n' IGNORE 0 LINES (id, firstname, middlename, lastname, level, type, blockno, street, brgy, city, zip, telephone, msisdn, email, group, @created_at, @updated_at) SET created_at = STR_TO_DATE(@created_at, '%Y-%m-%d %H:%i:%s'), updated_at = STR_TO_DATE(@updated_at, '%Y-%m-%d %H:%i:%s')";

        return $this->pdo->query($query);
    }

    public function export($all=false, $start_date=null, $end_date=null, $level=null)
    {
        if ($all) return $this->db->select('*')->where("created_at BETWEEN '$start_date' AND '$end_date'")->get($this->table);
        $this->db->select('*')->where("created_at BETWEEN '$start_date' AND '$end_date'");
        $this->db->where($this->column_softDelete, NULL);
        if( null != $level && 0 != $level ) $this->db->where('level', $level);
        return $this->db->get($this->table);
    }
}
