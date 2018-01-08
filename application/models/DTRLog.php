<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class DTRLog extends CI_Model {
    private $table = 'dtr_log';
    private $column_id = 'id';
    private $default_timezone = 'Asia/Singapore';

    function __construct()
    {
        parent::__construct();
    }

    public function get_config()
    {
        date_default_timezone_set($this->default_timezone);
        // $mm = new MessagingModel('Messaging');
        // $json   = new Services_JSON();
        $d = date("Y-m-d");
        $q = $this->db->query("SELECT MAX(timelog) AS timelog FROM $this->table WHERE timelog LIKE '{$d}%';")->row();
        // var_dump($q); exit();
        $config['date'] = (null != $q && null != $q->timelog ) ? date("Ymd", strtotime($q->timelog)) : date("Ymd");
        $config['time'] = (null != $q && null != $q->timelog ) ? date("His", strtotime($q->timelog)) : "030000";
        // var_dump($config);exit();
        return json_encode($config);
    }

    public function execute()
    {
        echo "Hello";

    }

    public function insert($data) {
    	//date_default_timezone_set($this->default_timezone);
    	$this->db->insert($this->table, $data);
    }

    public function check_schedule($student, $timed_in, $mode)
    {   
	$this->db->select('dts.name');
	$this->db->from('members as mem');
	$this->db->join('schedules as sched','mem.schedule_id = sched.id');
	$this->db->join('dtr_time_settings as dts','sched.id = dts.schedule_id');
	$this->db->where('dts.time_from <=', $timed_in);
	$this->db->where('dts.time_to >=', $timed_in);
	$this->db->where('dts.mode', $mode);
	$this->db->where('mem.stud_no', $student);
	$query = $this->db->get();
	
	if($query->num_rows() > 0) {
		foreach($query->result() as $row){
			return $row->name;
		}
	} else {
		return "GENERIC";
	}

    }
}
 ?>
