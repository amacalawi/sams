<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Contact extends CI_Model {

    private $table = 'contacts';
    private $column_id = 'contacts_id';
    private $time_limit = 600;
    public $validations = array(
        array( 'field' => 'contacts_firstname', 'label' => 'First Name', 'rules' => 'required|trim' ),
        array( 'field' => 'contacts_lastname', 'label' => 'Last Name', 'rules' => 'required|trim' ),

        // array( 'field' => 'contacts_level', 'label' => 'Level', 'rules' => 'required' ),
        // array( 'field' => 'contacts_type', 'label' => 'Type', 'rules' => 'required' ),
        // array( 'field' => 'contacts_group', 'label' => 'Group', 'rules' => 'required' ),

        array( 'field' => 'contacts_street', 'label' => 'Street', 'rules' => 'required|trim' ),
        array( 'field' => 'contacts_brgy', 'label' => 'Subdivision / Brgy', 'rules' => 'required|trim' ),
        array( 'field' => 'contacts_city', 'label' => 'Town / City', 'rules' => 'required|trim' ),

        array( 'field' => 'contacts_mobile', 'label' => 'Mobile', 'rules' => 'required' ),
        array( 'field' => 'contacts_email', 'label' => 'Email', 'rules' => 'trim|required|valid_email' )
    );

    function __construct()
    {
        parent::__construct();
    }

    public function validate($is_first_time=false, $id=null, $value=null)
    {
        $this->load->library('form_validation');

        foreach ($this->validations as $validation)
        {
            $this->form_validation->set_rules( $validation['field'], $validation['label'], $validation['rules'] );
        }

        /**
         * If the Validation is running
         * on the Add Function
         */
        if($is_first_time)
        {
            $this->form_validation->set_message('is_unique', 'The %s is already in use');
            $this->form_validation->set_rules( 'contacts_email', 'Email', 'trim|required|valid_email|is_unique[contacts.contacts_email]' );
        }
        else
        {
            $original = $this->db->where('contacts_id', $id)->get($this->table)->row()->contacts_email;

            /**
             * Only reset the rules if the
             * Original value is not equal to
             * the current value
             */
            if( $value != $original ) {
                $this->form_validation->set_message('is_unique', 'The %s is already in use');
                $this->form_validation->set_rules( 'contacts_email', 'Email', 'trim|required|valid_email|is_unique[contacts.contacts_email]' );
            }
        }

        if ($this->form_validation->run() == FALSE) { return false; }
        else { return true; }
    }

    public function all()
    {
        $query = $this->db->where('removed_at', NULL)->get($this->table);
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

        return $this->db->where('removed_at', NULL)->get($this->table);
    }

    public function find($id)
    {
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
        $this->db->where('contacts_firstname LIKE', '%'. $wildcard . '%')
                ->or_where('contacts_middlename LIKE', '%'. $wildcard . '%')
                ->or_where('contacts_lastname LIKE', '%'. $wildcard . '%')

                ->or_where('contacts_middlename', $wildcard)

                ->or_where('contacts_id LIKE', $wildcard . '%')
                ->or_where('contacts_level LIKE', $wildcard . '%')
                ->or_where('contacts_type LIKE', $wildcard . '%')
                ->or_where('contacts_blockno LIKE', $wildcard . '%')
                ->or_where('contacts_street LIKE', $wildcard . '%')
                ->or_where('contacts_brgy LIKE', $wildcard . '%')
                ->or_where('contacts_city LIKE', $wildcard . '%')
                ->or_where('contacts_zip LIKE', $wildcard . '%')
                ->or_where('contacts_telephone LIKE', $wildcard . '%')
                ->or_where('contacts_mobile LIKE', $wildcard . '%')
                ->or_where('contacts_email LIKE', $wildcard . '%')
                ->or_where('contacts_group LIKE', $wildcard . '%')

                ->or_where('contacts_firstname', $first)
                ->or_where('contacts_lastname', $last)
                ->or_where('contacts_firstname', $last)
                ->or_where('contacts_lastname', $first)

                ->from($this->table)
                ->select('*');

        if( null != $sort )
        {
            foreach ($sort as $field_name => $order) {
                $this->db->order_by($field_name, $order);
            }
        }

        $this->db->limit( $limit, $start_from );

        return $this->db->where('removed_at', NULL)->get();
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
            $this->db->where_in($this->column_id, $id)->update($this->table, ['removed_at'=>date('Y-m-d H:i:s')]);
            return $this->db->affected_rows() > 0;
        }

        $this->db->where($this->column_id, $id);
        $this->db->update($this->table, ['removed_at'=>date('Y-m-d H:i:s')]);
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
        // $pdo = new PDO("mysql:host=localhost;dbname=sams_db", 'root', '', array(PDO::MYSQL_ATTR_LOCAL_INFILE => true));

        if(!file_exists($file) || !is_readable($file)) return false;

        # Load the data to database
        set_time_limit($this->time_limit); // for longer execution time if needed

        if( $truncate ) $this->db->truncate($this->table); // truncate the table if all is good

        // $columns = 'contacts_id, contacts_firstname, contacts_middlename, contacts_lastname, contacts_level, contacts_type, contacts_blockno, contacts_street, contacts_brgy, contacts_city, contacts_zip, contacts_telephone, contacts_mobile, contacts_email, contacts_group';

        $query = "LOAD DATA local INFILE '".addslashes($file)."' INTO TABLE ".$this->pdo->dbprefix.$this->table." CHARACTER SET ".$this->pdo->char_set." FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\n' IGNORE 1 LINES";

        // $query = "LOAD DATA local INFILE '".addslashes($file)."' INTO TABLE ".$this->pdo->dbprefix.$this->table." CHARACTER SET ".$this->pdo->char_set." FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"' ESCAPED BY '\"' LINES TERMINATED BY '\n' IGNORE 0 LINES (contacts_id, contacts_firstname, contacts_middlename, contacts_lastname, contacts_level, contacts_type, contacts_blockno, contacts_street, contacts_brgy, contacts_city, contacts_zip, contacts_telephone, contacts_mobile, contacts_email, contacts_group, @created_at, @updated_at) SET created_at = STR_TO_DATE(@created_at, '%Y-%m-%d %H:%i:%s'), updated_at = STR_TO_DATE(@updated_at, '%Y-%m-%d %H:%i:%s')";

        return $this->pdo->query($query);
    }

    public function export($all=false, $start_date=null, $end_date=null, $level=null)
    {
        if($all) return $this->db->select('*')->where("created_at BETWEEN '$start_date' AND '$end_date'")->get($this->table);
        $this->db->select('*')->where("created_at BETWEEN '$start_date' AND '$end_date'");
        $this->db->where('removed_at', NULL);
        return $this->db->where('contacts_level', $level)->get($this->table);
    }

}
 ?>