<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Monitor extends CI_Model {

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

    public function all_members()
    {
        $query = $this->db->get($this->members_table);
        return $query->result();
    }

    public function all_levels()
    {
        $query = $this->db->get($this->levels_table);
        return $query->result();
    }

    public function all_groups()
    {
        $query = $this->db->get($this->groups_table);
        return $query->result();
    }
    public function get_filter(&$a,&$b)
    {
        if($a=="Contact"):
            $this->db->where('members.id', $b);
            $query = $this->db->get($this->members_table);
            foreach ($query->result() as $row)
            {
                $fullname = $row->firstname;
                $fullname.= " ".$row->middlename;
                $fullname.= " ".$row->lastname;
            }
            echo $fullname;
        elseif($a=="Level"):
            $this->db->where('levels.levels_id', $b);
            $query = $this->db->get($this->levels_table);
            foreach ($query->result() as $row)
            {
                $levelname = $row->levels_name;
            }
            echo $levelname;
        elseif($a=="Group"):
            $this->db->where('groups.groups_id', $b);
            $query = $this->db->get($this->groups_table);
            foreach ($query->result() as $row)
            {
                $groupname = $row->groups_name;
            }
            echo $groupname;
        endif;
    }

    public function generate_csv($date_from, $date_to, $category, $category_lvl, $type, $type_order, $time_from, $time_to)
    {
        $date_from = date("Y-m-d",strtotime(str_replace('/', '-', $date_from)));
        $date_end = date("Y-m-d",strtotime(str_replace('/', '-', $date_to)));

        $this->load->dbutil();

        if($category == "Level"):

            $this->db->select('mem.stud_no, mem.firstname,mem.lastname, lv.levels_name, dtr.timelog');
            $this->db->from('dtr_log as dtr');
            $this->db->join('members as mem', 'dtr.member_id = mem.id');
            $this->db->join('levels as lv', 'mem.level = lv.levels_id');
            $this->db->where('lv.levels_id', $category_lvl);
            $this->db->where('dtr.timelog >=', $date_from.' '.$time_from);
            $this->db->where('dtr.timelog <=', $date_end.' '.$time_to);
            $this->db->order_by('lv.levels_id', $type_order);
            $this->db->order_by('mem.firstname', $type_order);
            $this->db->order_by('dtr.id', $type_order);
    
        elseif($category == "Group"):

            $this->db->select('mem.stud_no, mem.firstname,mem.lastname, lv.levels_name, dtr.timelog');
            $this->db->from('dtr_log as dtr');
            $this->db->join('members as mem', 'dtr.member_id = mem.id');
            $this->db->join('levels as lv', 'mem.level = lv.levels_id');
            $this->db->join('group_members as gpm', 'mem.id = gpm.member_id');
            $this->db->join('groups as gp', 'gpm.group_id = gp.groups_id');
            $this->db->where('gp.groups_id', $category_lvl);
            $this->db->where('dtr.timelog >=',$date_from.' '.$time_from);
            $this->db->where('dtr.timelog <=',$date_end.' '.$time_to);
            $this->db->order_by('lv.levels_id', $type_order);
            $this->db->order_by('mem.firstname', $type_order);
            $this->db->order_by('dtr.id', $type_order);

        elseif($category == "Contact"):

            $this->db->select('mem.stud_no, mem.firstname,mem.lastname, lv.levels_name, dtr.timelog');
            $this->db->from('dtr_log as dtr');
            $this->db->join('members as mem', 'dtr.member_id = mem.id');
            $this->db->join('levels as lv', 'mem.level = lv.levels_id');
            $this->db->where('dtr.timelog >=',$date_from.' '.$time_from);
            $this->db->where('dtr.timelog <=',$date_end.' '.$time_to);
            $this->db->where('mem.id',$category_lvl);
            $this->db->order_by('lv.levels_id', $type_order);
            $this->db->order_by('mem.firstname', $type_order);
            $this->db->order_by('dtr.id', $type_order);
        
        else:

            $this->db->select('mem.stud_no, mem.firstname,mem.lastname, lv.levels_name, dtr.timelog');
            $this->db->from('dtr_log as dtr');
            $this->db->join('members as mem', 'dtr.member_id = mem.stud_no');
            $this->db->join('levels as lv', 'mem.level = lv.levels_id');
            $this->db->where('dtr.timelog >=',$date_from.' '.$time_from);
            $this->db->where('dtr.timelog <=',$date_end.' '.$time_to);
            $this->db->order_by('lv.levels_id', $type_order);
            $this->db->order_by('mem.firstname', $type_order);
            $this->db->order_by('dtr.id', $type_order);
     
        endif;

            $query = $this->db->get();
            $delimiter = ",";
            $newline = "\r\n";

        return $this->dbutil->csv_from_result($query,$delimiter,$newline);
    }



    public function get_fullname($fname)
    {
        $this->db->select('*');
        $this->db->from('members');
        $this->db->where('members.id', $fname);
        $query1 = $this->db->get();
        foreach($query1->result() as $row1 ){
           $fname = $row1->firstname.' '.$row1->middlename.' '.$row1->lastname;
        }
        return $fname;
        $this->db->close();
    }

    public function get_levels($id)
    {
        $this->db->select('*');
        $this->db->from('members as mem');
        $this->db->join('levels as lv', 'mem.level = lv.levels_id');
        $this->db->where('mem.id', $id);
        $query2 = $this->db->get();

        if($query2->num_rows() > 0)
        {
            foreach($query2->result() as $row2 ){
                $levels = $row2->levels_name;
            }
        }
        else
        {
            $levels = 'NO LEVEL';
        }
        return $levels;
        $this->db->close();
    }

    public function get_dtrlog_in($stud_no,$timelog,$timefrom,$timeto){
        $query2 = $this->db->query("SELECT * FROM dtr_log as dtr INNER JOIN members as mem ON dtr.member_id = mem.id where dtr.timelog like '%".$timelog."%' and dtr.timelog >= '".$timelog." ".$timefrom."' and mem.stud_no = '".$stud_no."' ORDER BY dtr.id ASC LIMIT 0,1");
        foreach($query2->result() as $row2 ){
            $timelog = date("H:i:s", strtotime($row2->timelog));
        }
        return $query2->num_rows()==1 ? $timelog : '';
    }
    public function get_dtrlog_out($stud_no,$timelog,$timefrom,$timeto){
        $query2 = $this->db->query("SELECT * FROM dtr_log as dtr INNER JOIN members as mem ON dtr.member_id = mem.id WHERE dtr.timelog like '%$timelog%' AND dtr.timelog > '".$timelog." ".$timefrom."' and mem.stud_no = '".$stud_no."' ORDER BY dtr.id DESC LIMIT 0,1"); 

        foreach($query2->result() as $row2 ){
            $timelog = date("H:i:s", strtotime($row2->timelog));
        }

        return $query2->num_rows()>=1 ? $timelog : '';
        $this->db->close();
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

    public function select_members_from_level($id)
    {
        $this->db->select('*');
        $this->db->from('members as mem');
        $this->db->where('mem.level', $id);
        $this->db->order_by('mem.firstname', 'ASC');
        return $this->db->get()->result();
    }

    public function select_detailed_logs(&$a,&$b,&$c,&$d,&$e,&$f,&$g,&$h,&$i)
    {
        $date_from = date("Y-m-d",strtotime(str_replace('/', '-', $a)));
        $date_end = date("Y-m-d",strtotime(str_replace('/', '-', $b)));

        if($c=="Contact") {
            $this->db->select('*');
            $this->db->from('dtr_log as dtr');
            $this->db->join('members as mem', 'dtr.member_id = mem.id');
            $this->db->join('levels as lv', 'mem.level = lv.levels_id');
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
            $this->db->join('levels as lv', 'mem.level = lv.levels_id');
            $this->db->join('group_members as gpm', 'mem.id = gpm.member_id');
            $this->db->join('groups as gp', 'gpm.group_id = gp.groups_id');
            $this->db->where('gp.groups_id', $d);
            $this->db->where('dtr.timelog >=',$date_from.' '.$g);
            $this->db->where('dtr.timelog <=',$date_end.' '.$h);
            $this->db->order_by('mem.gender', $f);
            $this->db->order_by('mem.firstname', $f); 
            $this->db->where('mem.id', $i);
            $query = $this->db->get();
            return $query->result();
        }
        else {
            $this->db->select('*');
            $this->db->from('dtr_log as dtr');
            $this->db->join('members as mem', 'dtr.member_id = mem.id');
            $this->db->join('levels as lv', 'mem.level = lv.levels_id');
            $this->db->where('dtr.timelog >=',$date_from.' '.$g);
            $this->db->where('dtr.timelog <=',$date_end.' '.$h);
            $this->db->order_by('mem.firstname', $f);
            $this->db->where('mem.id', $i);
            $query = $this->db->get();
            return $query->result();
        }
    }

    public function select_late_logs(&$a,&$b,&$c,&$d,&$e,&$f,&$g,&$h,&$i)
    {
        $date_from = date("Y-m-d",strtotime(str_replace('/', '-', $a)));
        $date_end = date("Y-m-d",strtotime(str_replace('/', '-', $b)));

        if($c=="Contact") {
            $this->db->select('*');
            $this->db->from('dtr_log as dtr');
            $this->db->join('members as mem', 'dtr.member_id = mem.id');
            $this->db->where('dtr.timelog >=',$date_from.' '.$g);
            $this->db->where('dtr.timelog <=',$date_end.' '.$h);
            $this->db->where('dtr.status', 'LATE_IN');
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
            $this->db->where('dtr.status', 'LATE_IN');
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
            $this->db->where('dtr.status', 'LATE_IN');
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
            $this->db->where('dtr.status', 'LATE_IN');
            $this->db->order_by('mem.firstname', $f);
            $this->db->where('mem.id', $i);
            $query = $this->db->get();
            return $query->result();
        }
    }

    

    public function select_absent_logs(&$a,&$b,&$c,&$d,&$e,&$f,&$g,&$h,&$i,$j)
    {
        $dates = date("Y-m-d",strtotime(str_replace('/', '-', $j)));

        if($c=="Contact") {
            $this->db->select('*');
            $this->db->from('dtr_log as dtr');
            $this->db->join('members as mem', 'dtr.member_id = mem.id');  
            $this->db->where('dtr.timelog LIKE', '%' . $dates . '%');
            $this->db->where('mem.id', $i);
            $this->db->order_by('mem.firstname', $f);
            $this->db->order_by('dtr.id', $f);
            $query = $this->db->get();
            return $query->num_rows();
        }
        elseif($c=="Level") {
            $this->db->select('*');
            $this->db->from('dtr_log as dtr');
            $this->db->join('members as mem', 'dtr.member_id = mem.id');
            $this->db->join('levels as lv', 'mem.level = lv.levels_id');            
            $this->db->where('dtr.timelog LIKE', '%' . $dates . '%');            
            $this->db->where('mem.id', $i);
            $this->db->where('lv.levels_id', $d);
            $this->db->order_by('mem.firstname', $f);
            $query = $this->db->get();
            return $query->num_rows();
        }
        elseif($c=="Group") {
            $this->db->select('*');
            $this->db->from('dtr_log as dtr');
            $this->db->join('members as mem', 'dtr.member_id = mem.id');
            $this->db->join('group_members as gpm', 'mem.id = gpm.member_id');
            $this->db->join('groups as gp', 'gpm.group_id = gp.groups_id');            
            $this->db->where('dtr.timelog LIKE', '%' . $dates . '%');            
            $this->db->where('gp.groups_id', $d);
            $this->db->where('mem.id', $i);
            $this->db->order_by('mem.firstname', $f);
            $query = $this->db->get();
            return $query->num_rows();
        }
        else {
            $this->db->select('*');
            $this->db->from('dtr_log as dtr');
            $this->db->join('members as mem', 'dtr.member_id = mem.id');            
            $this->db->order_by('mem.firstname', $f);
            $this->db->where('dtr.timelog LIKE', '%' . $dates . '%');
            $this->db->where('mem.id', $i);
            $query = $this->db->get();
            return $query->num_rows();
        }
    }

    public function generate_dtr(&$a,&$b,&$c,&$d,&$e,&$f,&$g,&$h)
    {
    $date_from = date("Y-m-d",strtotime(str_replace('/', '-', $a)));
    $date_end = date("Y-m-d",strtotime(str_replace('/', '-', $b)));
        if($e=="Summary"):
            if($c=="Contact"):
                $this->db->select('*');
                $this->db->from('members as mem');
                $this->db->where('mem.id', $d);
                $this->db->order_by('mem.firstname', $f);
                $query = $this->db->get();
                return $query->result();
            elseif($c=="Level"):
                $this->db->select('*');
                $this->db->from('members as mem');
                $this->db->join('levels as lv', 'mem.level = lv.levels_id');
                $this->db->where('lv.levels_id', $d);
                $this->db->order_by('mem.firstname', $f);
                $query = $this->db->get();
                return $query->result();
            elseif($c=="Group"):
                $this->db->select('*');
                $this->db->from('members as mem');
                $this->db->join('group_members as gpm', 'mem.id = gpm.member_id');
                $this->db->join('groups as gp', 'gpm.group_id = gp.groups_id');
                $this->db->where('gp.groups_id', $d);
                $this->db->order_by('mem.firstname', $f);
                $query = $this->db->get();
                return $query->result();
            else:   
                $this->db->select('*');
                // $this->db->select('mem.id, lvl.levels_id');
                $this->db->from('levels as lvl');
                $this->db->join('members as mem','lvl.levels_id = mem.level');
                $this->db->order_by('mem.level', $f);
                $this->db->group_by('mem.level');
                $query = $this->db->get();
                return $query->result();
            endif;
        elseif ($e=="Detailed"):
            if($c=="Contact"):
                $this->db->select('mem.id');
                $this->db->from('members as mem');
                $this->db->where('mem.id', $d);
                $this->db->join('levels as lv', 'mem.level = lv.levels_id');
                $this->db->order_by('mem.firstname', $f);
                $query = $this->db->get();
                return $query->result();
            elseif($c=="Level"):
                $this->db->select('mem.id');
                $this->db->from('members as mem');
                $this->db->join('levels as lv', 'mem.level = lv.levels_id');
                $this->db->where('lv.levels_id', $d);
                $this->db->order_by('mem.firstname', $f);
                $query = $this->db->get();
                return $query->result();
            elseif($c=="Group"):
                $this->db->select('mem.id');
                $this->db->from('members as mem');
                $this->db->join('group_members as gpm', 'mem.id = gpm.member_id');
                $this->db->join('groups as gp', 'gpm.group_id = gp.groups_id');
                $this->db->where('gp.groups_id', $d);
                $this->db->order_by('mem.gender', $f);
                $this->db->order_by('mem.firstname', $f);
                $query = $this->db->get();
                return $query->result();
            else:
                $this->db->select('mem.id');
                $this->db->from('levels as lvl');
                $this->db->join('members as mem','lvl.levels_id = mem.level');
                $this->db->order_by('mem.level', $f);
                $query = $this->db->get();
                return $query->result();
            endif;
        elseif ($e=="Late_Only"):
            if($c=="Contact"):
                $this->db->select('mem.id');
                $this->db->from('members as mem');
                $this->db->where('mem.id', $d);
                $this->db->join('levels as lv', 'mem.level = lv.levels_id');
                $this->db->order_by('mem.firstname', $f);
                $query = $this->db->get();
                return $query->result();
            elseif($c=="Level"):
                $this->db->select('mem.id');
                $this->db->from('members as mem');
                $this->db->join('levels as lv', 'mem.level = lv.levels_id');
                $this->db->where('lv.levels_id', $d);
                $this->db->order_by('mem.firstname', $f);
                $query = $this->db->get();
                return $query->result();
            elseif($c=="Group"):
                $this->db->select('mem.id');
                $this->db->from('members as mem');
                $this->db->join('group_members as gpm', 'mem.id = gpm.member_id');
                $this->db->join('groups as gp', 'gpm.group_id = gp.groups_id');
                $this->db->where('gp.groups_id', $d);
                $this->db->order_by('mem.firstname', $f);
                $query = $this->db->get();
                return $query->result();
            else:
                $this->db->select('mem.id');
                $this->db->from('levels as lvl');
                $this->db->join('members as mem','lvl.levels_id = mem.level');
                $this->db->order_by('mem.level', $f);
                $query = $this->db->get();
                return $query->result();
            endif;
        elseif ($e=="Absents_Only"):
            if($c=="Contact"):
                $this->db->select('*');
                $this->db->from('members as mem');
                $this->db->where('mem.id', $d);
                $this->db->join('levels as lv', 'mem.level = lv.levels_id');
                $this->db->order_by('mem.firstname', $f);
                $query = $this->db->get();
                return $query->result();
            elseif($c=="Level"):
                $this->db->select('*');
                $this->db->from('members as mem');
                $this->db->join('levels as lv', 'mem.level = lv.levels_id');
                $this->db->where('lv.levels_id', $d);
                $this->db->order_by('mem.firstname', $f);
                $query = $this->db->get();
                return $query->result();
            elseif($c=="Group"):
                $this->db->select('*');
                $this->db->from('members as mem');
                $this->db->join('group_members as gpm', 'mem.id = gpm.member_id');
                $this->db->join('groups as gp', 'gpm.group_id = gp.groups_id');
                $this->db->where('gp.groups_id', $d);
                $this->db->order_by('mem.firstname', $f);
                $query = $this->db->get();
                return $query->result();
            else:
                $this->db->select('*');
                $this->db->from('levels as lvl');
                $this->db->join('members as mem','lvl.levels_id = mem.level');
                $this->db->order_by('mem.level', $f);
                $query = $this->db->get();
                return $query->result();
            endif;
        endif;
    }

    public function all_announcements(){
        $query = $this->db->get($this->announcement_table);
        return $query->result();
    }

    public function get_all($start_from=0, $limit=0)
    {
        $query = $this->db->limit( $limit, $start_from )->get($this->announcement_table);
        return $query;
    }
    public function get_alls()
    {
        $query = $this->db->get($this->announcement_table);
        return $query;
    }


    public function like($wildcard='', $start_from=0, $limit=0)
    {
        $this->db->where('announcement_id LIKE', '%'. $wildcard . '%')
                ->or_where('announcement_name LIKE', '%'. $wildcard . '%')
                ->or_where('announcement_text LIKE', '%'. $wildcard . '%')
                ->from($this->announcement_table)
                ->select('*')
                ->limit( $limit, $start_from );
        return $this->db->get();
    }

    public function likes($wildcard='')
    {
        $this->db->where('announcement_id LIKE', '%'. $wildcard . '%')
                ->or_where('announcement_name LIKE', '%'. $wildcard . '%')
                ->or_where('announcement_text LIKE', '%'. $wildcard . '%')
                ->from($this->announcement_table)
                ->select('*');
        return $this->db->get();
    }

    public function insert_announcement($data)
    {
        $this->db->insert($this->announcement_table, $data);
        return $this->db->insert_id();
    }
    public function del_announcement($data)
    {
        $this->db->where('announcement_id',$data);
        return $this->db->delete($this->announcement_table);
    }

    public function find($id)
    {
        $this->db->select('*');
        if( is_array($id) ) return $this->db->where_in('announcement_id', $id)->get($this->announcement_table);

        $query = $this->db->where('announcement_id', $id)->get($this->announcement_table);
        return $query->row();
    }


    public function find_splash($id)
    {
        $this->db->select('*');
        if( is_array($id) ) return $this->db->where_in('id', $id)->get($this->splash_table);

        $query = $this->db->where('id', $id)->get($this->splash_table);
        return $query->row();
    }

    public function update_announcement($id, $data)
    {
        $this->db->where('announcement_id', $id);
        $this->db->update('announcement', $data);
        return true;
    }


    public function update_splash($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update($this->splash_table, $data);
        return true;
    }

    public function del_splash($data)
    {
        $this->db->where('id', $data);
        return $this->db->delete($this->splash_table);
    }

    public function all_splashs(){
        $query = $this->db->get($this->splash_table);
        return $query->result();
    }

    public function get_all_splash($start_from=0, $limit=0)
    {
        $this->db->order_by('id', 'ASC');
        $query = $this->db->limit( $limit, $start_from )->get($this->splash_table);
        return $query;
    }
    public function get_alls_splash()
    {   
        $this->db->order_by('id', 'ASC');
        $query = $this->db->get($this->splash_table);
        return $query;
    }


    public function like_splash($wildcard='', $start_from=0, $limit=0)
    {
        $this->db->where('id LIKE', '%'. $wildcard . '%')
                ->or_where('video_source LIKE', '%'. $wildcard . '%')
                ->or_where('video_title LIKE', '%'. $wildcard . '%')
                ->from($this->splash_table)
                ->select('*')
                ->limit( $limit, $start_from );
        return $this->db->get();
    }

    public function likes_splash($wildcard='')
    {
        $this->db->where('id LIKE', '%'. $wildcard . '%')
                ->or_where('video_source LIKE', '%'. $wildcard . '%')
                ->or_where('video_title LIKE', '%'. $wildcard . '%')
                ->from($this->splash_table)
                ->select('*');
        return $this->db->get();
    }

    public function insert_splash($data)
    {
        $this->db->insert($this->splash_table, $data);
        return $this->db->insert_id();
    }

    public function extractAbsentStudents($allStudents, $presentStudents)
    {
        // $presentStudents
        $r = array_diff($allStudents, $presentStudents);
        // echo "<pre>";
        //     var_dump( 'presentStudents ' . implode(',', $presentStudents) );
        //     var_dump( '----allStudents ' . implode(',', $allStudents) );
        //     var_dump( '---------result ' . implode(',', $r) );
        //     // die();
        // echo "</pre>";
        return $r;
    }

    public function getMembersViaLevel($level, $id_only = true)
    {
        $query = "SELECT id FROM members WHERE level = '$level'";
        $r = $this->db->query($query)->result();

        if ($id_only) {
            $ids = [];
            foreach ($r as $rr) {
                $ids[] = $rr->id;
            }
            return $ids;
        }

        return $r->result();
    }

    public function getMembersViaLevelString($level, $id_only = true)
    {
        $query = "SELECT id FROM members WHERE level = '$level'";
        $r = $this->db->query($query)->result();

        if ($id_only) {
            $ids = [];
            foreach ($r as $rr) {
                $ids[] = $rr->id;
            }
        }

        return implode(',', $ids);
    }

    public function getMembersIdFromDTROfLevel($level, $date)
    {
        $query = "SELECT mem.id FROM $this->members_table AS mem
            LEFT JOIN dtr ON dtr.member_id = mem.id WHERE DATE_FORMAT(dtr.datein, '%Y-%m-%d') = '$date'
            AND mem.level = '$level'
            ";
        $r = $this->db->query($query);
        $d = $r->result();

        $ids = [];
        foreach ($d as $rr) {
            $ids[] = $rr->id;
        }

        return $ids;
    }

    public function computeLate($student_id, $timein, $date)
    {
        $q = "SELECT name, time_from, time_to FROM members
            INNER JOIN dtr_time_settings ON members.schedule_id = dtr_time_settings.schedule_id
            WHERE members.id = '$student_id'
            AND name = 'NORMAL_IN'
             ";
        $normal_in_to = $this->db->query($q)->row()->time_to;

        $t1 = strtotime($normal_in_to);
        $t2 = strtotime($timein);
        $r = $t2 - $t1;

        return $this->convertMinsToHours($r/60);
    }

    public function getLateMembersIdFromDTROfLevel($student_id, $date)
    {
        $r = $this->db->select('mem.id, name, time_from, time_to, mem.schedule_id');
        $r->from('members as mem');
        $r->join('dtr_time_settings as dts', 'dts.schedule_id = mem.schedule_id');
        $r->where('mem.id', $student_id);
        $r->where('dts.name', 'NORMAL_IN');
        $r = $r->get()->row();

        $query = "SELECT mem.id, mem.stud_no, firstname, lastname, dtr.timein, mem.schedule_id FROM $this->members_table AS mem
            LEFT JOIN dtr ON dtr.member_id = mem.id
            WHERE DATE_FORMAT(`dtr`.`datein`, '%Y-%m-%d') = '$date'
            AND `dtr`.`timein` > '$r->time_to'
            AND mem.id = '$student_id'
            ";
        return $this->db->query($query)->result();
    }

    public function convertMinsToHours($time, $format = '%02d:%02d') {
        if ($time < 1) {
            return;
        }
        $hours = floor($time / 60);
        $minutes = ($time % 60);
        return sprintf($format, $hours, $minutes);
    }
}
