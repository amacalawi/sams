<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Monitor_New extends CI_Model {

    private $members_table = 'members';
    private $groups_table = 'groups';
    private $levels_table = 'levels';
    private $dtr_table = 'dtr_log';
    private $announcement_table = 'announcement';
    private $splash_table = 'uploads';

    function __construct()
    {
        parent::__construct();
    }

    public function get_timed_in($stud_no, $dates)
    {
        $this->db->select('*');
        $this->db->from('dtr as dt');
        $this->db->join('members as mem', 'dt.member_id = mem.id');
        $this->db->where('mem.stud_no', $stud_no);
        $this->db->where('dt.datein LIKE', $dates . '%');
        $query = $this->db->get();

        $results = 0;

        if($query->num_rows() > 0)
        {
            foreach ($query->result() as $row) {
                $results = $row->timein;
            }
            return $results;
        }
        else
        {
            return $results;
        }
    }

    public function get_timed_out($stud_no, $dates)
    {
        $this->db->select('*');
        $this->db->from('dtr as dt');
        $this->db->join('members as mem', 'dt.member_id = mem.id');
        $this->db->where('mem.stud_no', $stud_no);
        $this->db->where('dt.dateout LIKE', $dates . '%');
        $query = $this->db->get();

        $results = 0;

        if($query->num_rows() > 0)
        {
            foreach ($query->result() as $row) {
                $results = $row->timeout;
            }
            return $results;
        }
        else
        {
            return $results;
        }
    }

    public function get_timed_late($stud_no, $dates)
    {
        $this->db->select('*');
        $this->db->from('dtr as dt');
        $this->db->join('members as mem', 'dt.member_id = mem.id');
        $this->db->where('mem.stud_no', $stud_no);
        $this->db->where('dt.dateout LIKE', $dates . '%');
        $query = $this->db->get();

        // $results = 0;

        if($query->num_rows() > 0)
        {
            foreach ($query->result() as $row) {
                $results = $row->total_late;
            }
            return $results;
        }
        // else
        // {
        //     return $results;
        // }
    }

    public function generate_dtr(&$a, &$b, &$c, &$d, &$e, &$f, &$g, &$h)
    {
        $date_from = date("Y-m-d",strtotime(str_replace('/', '-', $a)));
        $date_end = date("Y-m-d",strtotime(str_replace('/', '-', $b)));

        if($e=="Summary")
        {
            if($c=="Contact")
            {
                $this->db->select('mem.id, mem.stud_no');
                $this->db->from('members as mem');
                $this->db->where('mem.id', $d);
                $this->db->order_by('mem.firstname', $f);
                $query = $this->db->get();
                return $query->result();
            }
            else if($c=="Level")
            {
                $this->db->select('*');
                $this->db->from('members as mem');
                $this->db->join('levels as lv', 'mem.level = lv.levels_id');
                $this->db->where('lv.levels_id', $d);
                $this->db->order_by('mem.firstname', $f);
                $query = $this->db->get();
                return $query->result();
            }
            else if($c=="Group")
            {
                $this->db->select('*');
                $this->db->from('members as mem');
                $this->db->join('group_members as gpm', 'mem.id = gpm.member_id');
                $this->db->join('groups as gp', 'gpm.group_id = gp.groups_id');
                $this->db->where('gp.groups_id', $d);
                $this->db->order_by('mem.firstname', $f);
                $query = $this->db->get();
                return $query->result();
            }
            else
            {   
                $this->db->select('mem.id, mem.stud_no');
                $this->db->from('members as mem');
                $this->db->join('levels as lvl','mem.level = lvl.levels_id');
                $this->db->order_by('mem.firstname', $f);
                $query = $this->db->get();
                return $query->result();
            }
        }
        else if ($e=="Detailed")
        {
            if($c=="Contact")
            {   
                $this->db->select('mem.id');
                $this->db->from('levels as lvl');
                $this->db->join('members as mem','lvl.levels_id = mem.level');
                $this->db->where('mem.id', $d);
                $this->db->order_by('mem.level', $f);
                $this->db->order_by('mem.firstname', $f);
                $query = $this->db->get();
                return $query->result();
            }
            else if($c=="Level")
            {                 
                $this->db->select('mem.id');
                $this->db->from('levels as lv');
                $this->db->join('members as mem', 'lv.levels_id = mem.level');
                $this->db->where('lv.levels_id', $d);
                $this->db->order_by('mem.level', $f);
                $this->db->order_by('mem.firstname', $f);
                $query = $this->db->get();
                return $query->result();
            }
            elseif($c=="Group")
            {   
                $this->db->select('mem.id');
                $this->db->from('levels as lvl');
                $this->db->join('members as mem','lvl.levels_id = mem.level');
                $this->db->join('group_members as gpm', 'mem.id = gpm.member_id');
                $this->db->join('groups as gp', 'gpm.group_id = gp.groups_id');
                $this->db->where('gp.groups_id', $d);
                $this->db->order_by('mem.level', $f);
                $this->db->order_by('mem.firstname', $f);
                $query = $this->db->get();
                return $query->result();
            }
            else
            {
                $this->db->select('mem.id');
                $this->db->from('levels as lvl');
                $this->db->join('members as mem','lvl.levels_id = mem.level');
                $this->db->order_by('mem.level', $f);
                $this->db->order_by('mem.firstname', $f);
                $query = $this->db->get();
                return $query->result();
            }
        }
        elseif ($e=="Late_Only")
        {   
            if($c=="Contact")
            {
                $this->db->select('mem.id');
                $this->db->from('members as mem');
                $this->db->where('mem.id', $d);
                $this->db->join('levels as lv', 'mem.level = lv.levels_id');
                $this->db->order_by('mem.firstname', $f);
                $query = $this->db->get();
                return $query->result();
            }
            elseif($c=="Level")
            {
                $this->db->select('mem.id');
                $this->db->from('members as mem');
                $this->db->join('levels as lv', 'mem.level = lv.levels_id');
                $this->db->where('lv.levels_id', $d);
                $this->db->order_by('mem.firstname', $f);
                $query = $this->db->get();
                return $query->result();
            }
            elseif($c=="Group")
            {
                $this->db->select('mem.id');
                $this->db->from('members as mem');
                $this->db->join('group_members as gpm', 'mem.id = gpm.member_id');
                $this->db->join('groups as gp', 'gpm.group_id = gp.groups_id');
                $this->db->where('gp.groups_id', $d);
                $this->db->order_by('mem.firstname', $f);
                $query = $this->db->get();
                return $query->result();
            }
            else
            {
                $this->db->select('mem.id');
                $this->db->from('levels as lvl');
                $this->db->join('members as mem','lvl.levels_id = mem.level');
                $this->db->order_by('mem.level', $f);
                $query = $this->db->get();
                return $query->result();
            }
        }
        else 
        {
            if($c=="Contact")
            {
                $this->db->select('mem.id');
                $this->db->from('members as mem');
                $this->db->where('mem.id', $d);
                $this->db->join('levels as lv', 'mem.level = lv.levels_id');
                $this->db->order_by('mem.firstname', $f);
                $query = $this->db->get();
                return $query->result();
            }
            elseif($c=="Level")
            {
                $this->db->select('mem.id');
                $this->db->from('members as mem');
                $this->db->join('levels as lv', 'mem.level = lv.levels_id');
                $this->db->where('lv.levels_id', $d);
                $this->db->order_by('mem.firstname', $f);
                $query = $this->db->get();
                return $query->result();
            }
            elseif($c=="Group")
            {
                $this->db->select('mem.id');
                $this->db->from('members as mem');
                $this->db->join('group_members as gpm', 'mem.id = gpm.member_id');
                $this->db->join('groups as gp', 'gpm.group_id = gp.groups_id');
                $this->db->where('gp.groups_id', $d);
                $this->db->order_by('mem.firstname', $f);
                $query = $this->db->get();
                return $query->result();
            }
            else
            {
                $this->db->select('mem.id');
                $this->db->from('levels as lvl');
                $this->db->join('members as mem','lvl.levels_id = mem.level');
                $this->db->order_by('mem.level', $f);
                $query = $this->db->get();
                return $query->result();
            }
        }
    }

    public function get_fullname($id)
    {   
        $fullname = '';
        $this->db->where('id', $id);
        $query1 = $this->db->get('members');

        foreach($query1->result() as $row1 ){
           $fullname = $row1->firstname.' '.$row1->middlename.' '.$row1->lastname;
        }

        return $fullname;
    }

    public function get_levels($levels)
    {
        $this->db->select('*');
        $this->db->from('members as mem');        
        $this->db->join('levels as lv', 'mem.level = lv.levels_id');
        $this->db->where('mem.id',$levels);
        $query = $this->db->get();

        if($query->num_rows() > 0)
        {
            return $query->row()->levels_name;
        }
        else
        {
            return 'No Level Defined';
        }
    }

    public function get_filter($a, $b)
    {
        if( $a == 'Contact' )
        {
            $this->db->where('members.id', $b);
            $query = $this->db->get($this->members_table);

            foreach ($query->result() as $row)
            {
                $fullname = $row->firstname;
                $fullname.= " ".$row->middlename;
                $fullname.= " ".$row->lastname;
            }

            return $fullname;
        }
        elseif( $a == 'Level' )
        {
            $this->db->where('levels.levels_id', $b);
            $query = $this->db->get($this->levels_table);

            foreach ($query->result() as $row)
            {
                $levelname = $row->levels_name;
            }

            return $levelname;
        }
        elseif( $a == 'Group')
        {
            $this->db->where('groups.groups_id', $b);
            $query = $this->db->get($this->groups_table);

            foreach ($query->result() as $row)
            {
                $groupname = $row->groups_name;
            }

            return $groupname;
        }
    }

    public function get_dtrlog_in($stud_no,$timelog,$timefrom,$timeto){

        $query2 = $this->db->query("SELECT * FROM dtr_log as dtr INNER JOIN members as mem ON dtr.member_id = mem.id where dtr.timelog like '%".$timelog."%' and dtr.timelog >= '".$timelog." ".$timefrom."' and mem.stud_no = '".$stud_no."' ORDER BY dtr.id ASC LIMIT 0,1");
        
        foreach($query2->result() as $row2 ){
            $timelog = date("H:i:s", strtotime($row2->timelog));
        }

        return $query2->num_rows()==1 ? $timelog : '';

    }

    public function get_dtrlog_in_2($stud_no,$timelog){

        $query2 = $this->db->query("SELECT * FROM dtr_log as dtr INNER JOIN members as mem ON dtr.member_id = mem.id where dtr.timelog like '%".$timelog."%' and dtr.mode = 1 and mem.stud_no = '".$stud_no."' ORDER BY dtr.id ASC LIMIT 0,1");
        
        foreach($query2->result() as $row2 ){
            $timelog = date("H:i:s", strtotime($row2->timelog));
        }

        return ($query2->num_rows() > 0) ? $timelog : 0;
    }

    public function get_dtrlog_out($stud_no,$timelog,$timefrom,$timeto){
        
        $query2 = $this->db->query("SELECT * FROM dtr_log as dtr INNER JOIN members as mem ON dtr.member_id = mem.id WHERE dtr.timelog like '%$timelog%' AND dtr.timelog > '".$timelog." ".$timefrom."' and mem.stud_no = '".$stud_no."' ORDER BY dtr.id DESC LIMIT 0,1"); 

        foreach($query2->result() as $row2 ){
            $timelog = date("H:i:s", strtotime($row2->timelog));
        }

        return $query2->num_rows()>=1 ? $timelog : '';
    }

    public function get_late_in_by_member_id($id)
    {
        $this->db->select('dtr.time_from');
        $this->db->from('members as mem');
        $this->db->join('schedules as sched','mem.schedule_id = sched.id');
        $this->db->join('dtr_time_settings as dtr','sched.id = dtr.schedule_id');
        $this->db->where('dtr.name','LATE_IN');
        $this->db->where('mem.id',$id);
        $query = $this->db->get();

        foreach ($query->result() as $row) {
            $id = $row->time_from;
        }

        return $id;
    }

    public function select_detailed_logs($a, $b, $c, $d, $e, $f, $g, $h, $i)
    {
        $date_from = date("Y-m-d",strtotime(str_replace('/', '-', $a)));
        $date_end = date("Y-m-d",strtotime(str_replace('/', '-', $b)));

        if($c=="Contact") {
            $this->db->select('*');
            $this->db->from('dtr_log as dtr');
            $this->db->join('members as mem', 'dtr.member_id = mem.id');
            $this->db->where('dtr.timelog >=',$date_from.' '.$g);
            $this->db->where('dtr.timelog <=',$date_end.' '.$h);
            $this->db->where('mem.id',$d);
            $this->db->order_by('mem.firstname', $f);
            $this->db->order_by('dtr.id', $f);
            $this->db->where('mem.id', $i);
            $query = $this->db->get();
            return $query->result();
        }
        elseif($c=="Level") {
            $this->db->select('*');
            $this->db->from('dtr_log as dtr');
            $this->db->join('members as mem', 'dtr.member_id = mem.id');
            $this->db->join('levels as lv', 'mem.level = lv.levels_id');
            $this->db->where('lv.levels_id', $d);
            $this->db->where('dtr.timelog >=',$date_from.' '.$g);
            $this->db->where('dtr.timelog <=',$date_end.' '.$h);
            $this->db->order_by('mem.firstname', $f);
            $this->db->where('mem.id', $i);
            $query = $this->db->get();
            return $query->result();
        }
        elseif($c=="Group") {
            $this->db->select('*');
            $this->db->from('dtr_log as dtr');
            $this->db->join('members as mem', 'dtr.member_id = mem.id');
            $this->db->join('group_members as gpm', 'mem.id = gpm.member_id');
            $this->db->join('groups as gp', 'gpm.group_id = gp.groups_id');
            $this->db->where('gp.groups_id', $d);
            $this->db->where('dtr.timelog >=',$date_from.' '.$g);
            $this->db->where('dtr.timelog <=',$date_end.' '.$h);
            $this->db->order_by('mem.firstname', $f);
            $this->db->where('mem.id', $i);
            $query = $this->db->get();
            return $query->result();
        }
        else {
            $this->db->select('*');
            $this->db->from('dtr_log as dtr');
            $this->db->join('members as mem', 'dtr.member_id = mem.id');
            $this->db->where('dtr.timelog >=',$date_from.' '.$g);
            $this->db->where('dtr.timelog <=',$date_end.' '.$h);
            $this->db->order_by('mem.firstname', $f);
            $this->db->where('mem.id', $i);
            $query = $this->db->get();
            return $query->result();
        }
    }
}
