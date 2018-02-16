<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Member extends CI_Model {

    private $table = 'members';
    private $column_id = 'id';
    private $column_softDelete = 'removed_at';
    private $column_softDeletedBy = 'removed_by';
    private $time_limit = 600;

    public $validations = array(
        array( 'field' => 'firstname', 'label' => 'First Name', 'rules' => 'required|trim' ),
        array( 'field' => 'lastname', 'label' => 'Last Name', 'rules' => 'required|trim' ),
        array( 'field' => 'email', 'label' => 'Email', 'rules' => 'trim|valid_email' ),
        array( 'field' => 'msisdn', 'label' => 'Mobile', 'rules' => 'required|trim' ),
        array( 'field' => 'schedule_id', 'label' => 'Schedule', 'rules' => 'required' ),
        // array( 'field' => 'enrollment_status', 'label' => 'Status', 'rules' => 'required' ),
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
            $this->form_validation->set_rules( 'email', 'Email', 'trim|valid_email|is_unique['.$this->table.'.email]' );

            // Enrollment Status
            $this->form_validation->set_message('required', 'The %s is required');
            $this->form_validation->set_rules( 'enrollment_status', 'Enrollment Status', 'trim|required' );
        } else {
            $original = $this->db->where($this->column_id, $id)->get($this->table)->row()->email;

            /**
             * Only reset the rules if the
             * Original value is not equal to
             * the current value
             */
            if( $value != $original ) {
                $this->form_validation->set_message('is_unique', 'The %s is already in use');
                $this->form_validation->set_rules( 'email', 'Email', 'trim|valid_email|is_unique['.$this->table.'.email]' );
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

        $this->db->limit( $limit, $start_from );

        if( $removed_only ) 
            return $this->db->where($this->column_softDelete . " != ", NULL)->get($this->table);
        else
            return $this->db->where($this->column_softDelete, NULL)->get($this->table);
    }

    public function dropdown_list($select)
    {
        $query = $this->db->select($select)->get($this->table);
        return $query;
    }

    public function find_member($id, $column=null, $select="*")
    {
        if ( is_array($id) ) return $this->db->where_in($this->column_id, $id)->get($this->table);

        $query = $this->db->select($select)->where($this->column_id, $id)->get($this->table);
        return $query->row();
    }

    public function find($id, $column=null, $select="*")
    {
        if (null != $column) {
            $query = $this->db->select($select)->where($column, $id)->where($this->column_softDelete, NULL)->get($this->table);
            return $query->row();
        }
         
        if ( is_array($id) ) return $this->db->where_in($this->column_id, $id)->where($this->column_softDelete, NULL)->get($this->table);

        $query = $this->db->select($select)->where($this->column_id, $id)->where($this->column_softDelete, NULL)->get($this->table);
        return $query->row();
    }

    public function find2($id, $column=null, $select="*")
    {   
        if (null != $column) {
            $query = $this->db->select($select)->where($column, $id)->where($this->column_softDelete, NULL)->get($this->table);
            return $query->row();
        }
         
        if ( is_array($id) ) return $this->db->where_in($this->column_id, $id)->where($this->column_softDelete, NULL)->get($this->table);

        $query = $this->db->select($select)->where($this->column_id, $id)->where($this->column_softDelete, NULL)->get($this->table);
        return $query->row();

        // $this->db->where($column, $id);
        // $this->db->where($this->column_softDelete, NULL);
        // $query = $this->db->get($this->table);

        // if($query->num_rows() > 0)
        // {
        //     return $query->row();
        // }
        // else
        // {
        //     return 0;
        // }
    }

    public function find_member_via_msisdn($msisdn)
    {
        $this->db->where('msisdn', $msisdn);
        $this->db->where($this->column_softDelete, NULL);
        $query = $this->db->get($this->table);
        
        if($query->num_rows() > 0)
        {
            return $query->result();   
        }
        else
        {
            return 0;
        }
        
    }

    public function find_member_via_msisdn2($msisdn, $stud_no)
    {
        $this->db->where('msisdn', $msisdn);
        $this->db->where('stud_no', $stud_no);
        $this->db->where($this->column_softDelete, NULL);
        $query = $this->db->get($this->table);
        return $query->result();
    }


    public function like($wildcard='', $start_from=0, $limit=0, $sort=null, $removed_only=false)
    {
        $first = ''; $last='';
        if(preg_match('/\s/', $wildcard)) {
            $name = explode(" ", $wildcard);
            $first = $name[0];
            $last = $name[1];
        }

        $this->db->select('*');
        $this->db->group_start();
        $this->db->or_where('firstname LIKE ', '%'. $wildcard . '%');
        $this->db->or_where('stud_no LIKE', '%'. $wildcard . '%');
        $this->db->or_where('middlename LIKE ', '%'. $wildcard . '%');
        $this->db->or_where('lastname LIKE ', '%'. $wildcard . '%');
        $this->db->or_where('middlename', '%'. $wildcard. '%');
        $this->db->or_where('id LIKE ', '%'. $wildcard . '%');
        $this->db->or_where('level LIKE ', '%'. $wildcard . '%');
        $this->db->or_where('type LIKE ', '%'. $wildcard . '%');
        $this->db->or_where('address_blockno LIKE', '%'. $wildcard . '%');
        $this->db->or_where('address_street LIKE', '%'. $wildcard . '%');
        $this->db->or_where('address_brgy LIKE', '%'. $wildcard . '%');
        $this->db->or_where('address_city LIKE', '%'. $wildcard . '%');
        $this->db->or_where('address_zip LIKE', '%'. $wildcard . '%');
        $this->db->or_where('telephone LIKE', '%'. $wildcard . '%');
        $this->db->or_where('msisdn LIKE', '%'. $wildcard . '%');
        $this->db->or_where('email LIKE', '%'. $wildcard . '%');
        $this->db->or_where('groups LIKE', '%'. $wildcard . '%');
        $this->db->group_end();

        if( null != $sort ) {
            foreach ($sort as $field_name => $order) {
                $this->db->order_by($field_name, $order);
            }
        }

        $this->db->limit( $limit, $start_from );

        if( $removed_only ) 
            return $this->db->where($this->column_softDelete . " !=", NULL)->get($this->table);
        else
            return $this->db->where($this->column_softDelete, NULL)->get($this->table);
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

    public function origWhere($key, $value)
    {
        $this->db->select('*')->from($this->table);
        $this->db->where($key, $value);
        return $this->db;
    }

    public function whereNot($key, $value)
    {
        $this->db->select('*')->from($this->table);
        $this->db->where("$key != '$value'");
        return $this->db;
    }

    public function check_if_exist($stud_no)
    {
        $this->db->where('stud_no', $stud_no);
        $query = $this->db->get('members');

        if($query->num_rows() > 0)
        {
            foreach ($query->result() as $row) {
                return $row->id;
            }
        }
        else 
        {
            return 0;
        }
    }

    public function import($file=null, $truncate=false)
    {
        // $this->pdo = $this->load->database('pdo', true);
        // $this->pdo->query( "SET NAMES 'utf8'" );

        // if(!file_exists($file) || !is_readable($file)) return false;

        // # Load the data to database
        // set_time_limit($this->time_limit); // for longer execution time if needed

        // if( $truncate ) $this->db->truncate($this->table); // truncate the table if all is good

        // // $query = "LOAD DATA local INFILE '".addslashes($file)."' INTO TABLE ".$this->pdo->dbprefix.$this->table." CHARACTER SET ".$this->pdo->char_set." FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\n' IGNORE 1 ROWS";

        // $query = "LOAD DATA local INFILE '".addslashes($file)."' INTO TABLE ".$this->pdo->dbprefix.$this->table." CHARACTER SET ".$this->pdo->char_set." FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\n' IGNORE 1 LINES (@ignore, `stud_no`, `firstname`, middlename, lastname, birthdate, @nick, level, type, address_blockno, address_street, address_brgy, address_city, address_zip, telephone, @msisdn, email, groups, avatar, schedule_id, created_by, updated_by, removed_by, @created_at, @updated_at, @removed_at, @ignore) SET created_at = NOW(), updated_at = NOW(), nick = nullif(@nick,''), msisdn=LPAD(@msisdn, 11, '0')";

        // // $query = "LOAD DATA local INFILE '".addslashes($file)."' INTO TABLE ".$this->pdo->dbprefix.$this->table." CHARACTER SET ".$this->pdo->char_set." FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"' ESCAPED BY '\"' LINES TERMINATED BY '\n' IGNORE 0 LINES (id, firstname, middlename, lastname, level, type, blockno, street, brgy, city, zip, telephone, msisdn, email, group, @created_at, @updated_at) SET created_at = STR_TO_DATE(@created_at, '%Y-%m-%d %H:%i:%s'), updated_at = STR_TO_DATE(@updated_at, '%Y-%m-%d %H:%i:%s')";

        // return $this->pdo->query($query);
    }

    public function get_groups($members_id)
    {   
        $this->db->select('*');
        $this->db->from('group_members as mem');
        $this->db->join('groups as grp', 'mem.group_id = grp.groups_id');
        $this->db->where('mem.member_id', $members_id);
        $query = $this->db->get();

        $arr = array();
        foreach ($query->result() as $row) {
            $arr[] = $row->groups_code;
        }

        return implode(", ", $arr); 
    }

    public function export($all=false, $start_date=null, $end_date=null, $level=null, $filetype)
    {   
        // if ($all) return $this->db->select('*')->where("members.created_at BETWEEN '".$start_date." 00:00:00' AND '".$end_date." 23:59:59'")
        //     ->join('levels', 'members.level', 'levels.id')
        //     ->join('schedules', 'schedules.id', 'members.schedule')
        //     ->get($this->table);

        if($filetype == 'CSV') { 
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=Members_' . date('Y-m-d-H-i') . '.export.csv');

            $output = fopen('php://output', 'w');

            fputcsv($output, array('Student No', 'Firstname', 'Middlename', 'Lastname', 'Birthdate', 'Nickname', 'Level', 'Type', 'Block No', 'Street', 'Barangay' , 'City', 'Zip Code', 'Telephone', 'MSISDN', 'Email Address', 'Groups', 'Schedules'));

            if($level == 0)
            {
                $this->db->select('mem.id, mem.stud_no, mem.firstname, mem.middlename, mem.lastname, mem.birthdate, mem.nick, mem.level, mem.type, mem.address_blockno, mem.address_street, mem.address_brgy, mem.address_city, mem.address_zip, mem.telephone, mem.msisdn, mem.email, sched.code as schedule');
                $this->db->from($this->table.' as mem');
                $this->db->join('levels as lvl', 'mem.level = lvl.levels_id');
                $this->db->join('schedules as sched', 'mem.schedule_id = sched.id');                
                $this->db->where("mem.created_at BETWEEN '".$start_date." 00:00:00' AND '".$end_date." 23:59:59'");
                $this->db->where("mem.".$this->column_softDelete, NULL);
                $this->db->group_by('mem.id');
                $query = $this->db->get();
            } 
            else 
            {   
                $this->db->select('mem.id, mem.stud_no, mem.firstname, mem.middlename, mem.lastname, mem.birthdate, mem.nick, mem.level, mem.type, mem.address_blockno, mem.address_street, mem.address_brgy, mem.address_city, mem.address_zip, mem.telephone, mem.msisdn, mem.email, sched.code as schedule');
                $this->db->from($this->table.' as mem');
                $this->db->join('levels as lvl', 'mem.level = lvl.levels_id');
                $this->db->join('schedules as sched', 'mem.schedule_id = sched.id');
                $this->db->where("mem.created_at BETWEEN '".$start_date." 00:00:00' AND '".$end_date." 23:59:59'");
                $this->db->where("mem.".$this->column_softDelete, NULL);
                if( null != $level && 0 != $level ) 
                $this->db->where('mem.level', $level);
                $this->db->group_by('mem.id');
                $query = $this->db->get();
            }

            foreach($query->result() as $row)
            {
                fputcsv($output, array($row->stud_no, $row->firstname, $row->middlename, $row->lastname, $row->birthdate, $row->nick, $row->level, $row->type, $row->address_blockno, $row->address_street, $row->address_brgy, $row->address_city, $row->address_city, $row->telephone, $row->msisdn, $row->email, $this->get_groups($row->id), $row->schedule));
            }
        }
        else {      
             $this->db->select('mem.id, mem.stud_no, mem.firstname, mem.middlename, mem.lastname, mem.birthdate, mem.nick, mem.level, mem.type, mem.address_blockno, mem.address_street, mem.address_brgy, mem.address_city, mem.address_zip, mem.telephone, mem.msisdn, mem.email, sched.code as schedule');
                $this->db->from($this->table.' as mem');
                $this->db->join('levels as lvl', 'mem.level = lvl.levels_id');
                $this->db->join('schedules as sched', 'mem.schedule_id = sched.id');
                $this->db->where("mem.created_at BETWEEN '".$start_date." 00:00:00' AND '".$end_date." 23:59:59'");
            $this->db->where("mem.".$this->column_softDelete, NULL);
            if( null != $level && 0 != $level ) $this->db->where('mem.level', $level);
            return $this->db->get();
        }
    }

    public function find_students_if_exist($stud_no)
    {
        $this->db->where('stud_no', $stud_no);
        $query = $this->db->get('members');
        return $query->num_rows();
    }

    public function get_all_member_by_msisdn($msisdn)
    {
        $this->db->where('msisdn', $msisdn);
        $this->db->where($this->column_softDelete, NULL);
        $query = $this->db->get('members');

        if($query->num_rows() > 0)
        {
            return $query->result();
        }
        else 
        {
            return 0;
        }
    }

    public function get_all_current_listing($start_from=0, $limit=0, $group_id)
    {  
        $this->db->select('*');
        $this->db->from('members as mem');
        $this->db->join('group_members as gmem', 'mem.id = gmem.member_id');
        $this->db->join('groups as grp', 'gmem.group_id = grp.groups_id');
        $this->db->where('grp.groups_id', $group_id);
        $this->db->where('mem.removed_at', NULL);
        $this->db->order_by('mem.id', 'DESC');
        $this->db->group_by('mem.id');
        $query = $this->db->limit( $limit, $start_from )->get();
        return $query;
    }

    public function get_all_current_listings($group_id)
    {  
        $this->db->select('*');
        $this->db->from('members as mem');
        $this->db->join('group_members as gmem', 'mem.id = gmem.member_id');
        $this->db->join('groups as grp', 'gmem.group_id = grp.groups_id');
        $this->db->where('grp.groups_id', $group_id);
        $this->db->where('mem.removed_at', NULL);
        $this->db->order_by('mem.id', 'DESC');
        $this->db->group_by('mem.id');
        $query = $this->db->get();
        return $query;
    }

    public function like_all_current_listing($wildcard='', $start_from=0, $limit=0, $group_id)
    {   
        $this->db->select('*');
        $this->db->from('members as mem');
        $this->db->join('group_members as gmem', 'mem.id = gmem.member_id');
        $this->db->join('groups as grp', 'gmem.group_id = grp.groups_id');
        $this->db->where('grp.groups_id', $group_id);
        $this->db->where('mem.removed_at', NULL);
        $this->db->group_start();
        $this->db->or_where('mem.id LIKE', '%' . $wildcard . '%');
        $this->db->or_where('mem.firstname LIKE', '%' . $wildcard . '%');
        $this->db->or_where('mem.middlename LIKE', '%' . $wildcard . '%');
        $this->db->or_where('mem.lastname LIKE', '%' . $wildcard . '%');
        $this->db->or_where('grp.groups_name LIKE', '%' . $wildcard . '%');
        $this->db->group_end();
        $this->db->order_by('mem.id', 'DESC');
        $this->db->group_by('mem.id');
        $query = $this->db->limit( $limit, $start_from )->get();
        return $query;
    }

    public function like_all_current_listings($wildcard='', $group_id)
    {
        $this->db->select('*');
        $this->db->from('members as mem');
        $this->db->join('group_members as gmem', 'mem.id = gmem.member_id');
        $this->db->join('groups as grp', 'gmem.group_id = grp.groups_id');
        $this->db->where('grp.groups_id', $group_id);
        $this->db->where('mem.removed_at', NULL);
        $this->db->group_start();
        $this->db->or_where('mem.id LIKE', '%' . $wildcard . '%');
        $this->db->or_where('mem.firstname LIKE', '%' . $wildcard . '%');
        $this->db->or_where('mem.middlename LIKE', '%' . $wildcard . '%');
        $this->db->or_where('mem.lastname LIKE', '%' . $wildcard . '%');
        $this->db->or_where('grp.groups_name LIKE', '%' . $wildcard . '%');
        $this->db->group_end();
        $this->db->order_by('mem.id', 'DESC');
        $this->db->group_by('mem.id');
        $query = $this->db->get();
        return $query;
    }

    public function get_all_available_listing($start_from=0, $limit=0, $group_id)
    { 
        $arr = array(); $arr[] = 0;

        $this->db->where('group_id', $group_id);
        $query = $this->db->get('group_members');

        foreach ($query->result() as $row) {
            $arr[] = $row->member_id;
        }

        $this->db->select('*');
        $this->db->from('members');
        $this->db->where('removed_at', NULL);
        $this->db->where_not_in('id', $arr);
        $this->db->order_by('id', 'DESC');
        $query = $this->db->limit( $limit, $start_from )->get();
        return $query;
    }

    public function get_all_available_listings($group_id)
    {  
        $arr = array(); $arr[] = 0;

        $this->db->where('group_id', $group_id);
        $query = $this->db->get('group_members');

        foreach ($query->result() as $row) {
            $arr[] = $row->member_id;
        }

        $this->db->select('*');
        $this->db->from('members');
        $this->db->where('removed_at', NULL);
        $this->db->where_not_in('id', $arr);
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get();
        return $query;
    }

    public function like_all_available_listing($wildcard='', $start_from=0, $limit=0, $group_id)
    { 
        $arr = array(); $arr[] = 0;

        $this->db->where('group_id', $group_id);
        $query = $this->db->get('group_members');

        foreach ($query->result() as $row) {
            $arr[] = $row->member_id;
        }

        $this->db->select('*');
        $this->db->from('members as mem');
        // $this->db->join('group_members as grm', 'mem.id = grm.member_id');
        // $this->db->join('groups as grp', 'grm.group_id = grp.groups_id');
        $this->db->where('mem.removed_at', NULL);
        $this->db->where_not_in('mem.id', $arr);
        $this->db->group_start();
        $this->db->or_where('mem.id LIKE', '%' . $wildcard . '%');
        $this->db->or_where('mem.firstname LIKE', '%' . $wildcard . '%');
        $this->db->or_where('mem.middlename LIKE', '%' . $wildcard . '%');
        $this->db->or_where('mem.lastname LIKE', '%' . $wildcard . '%');
        // $this->db->or_where('grp.groups_name LIKE', '%' . $wildcard . '%');
        $this->db->group_end();
        $this->db->order_by('id', 'DESC');
        $query = $this->db->limit( $limit, $start_from )->get();
        return $query;
    }

    public function like_all_available_listings($wildcard='', $group_id)
    {   
        $arr = array(); $arr[] = 0;

        $this->db->where('group_id', $group_id);
        $query = $this->db->get('group_members');

        foreach ($query->result() as $row) {
            $arr[] = $row->member_id;
        }

        $this->db->select('*');
        $this->db->from('members as mem');
        // $this->db->join('group_members as grm', 'mem.id = grm.member_id');
        // $this->db->join('groups as grp', 'grm.group_id = grp.groups_id');
        $this->db->where('mem.removed_at', NULL);
        $this->db->where_not_in('mem.id', $arr);
        $this->db->group_start();
        $this->db->or_where('mem.id LIKE', '%' . $wildcard . '%');
        $this->db->or_where('mem.firstname LIKE', '%' . $wildcard . '%');
        $this->db->or_where('mem.middlename LIKE', '%' . $wildcard . '%');
        $this->db->or_where('mem.lastname LIKE', '%' . $wildcard . '%');
        // $this->db->or_where('grp.groups_name LIKE', '%' . $wildcard . '%');
        $this->db->group_end();
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get();
        return $query;
    }

}
