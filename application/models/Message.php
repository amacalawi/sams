<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Message extends CI_Model {
    private $table = 'messages';
    private $contacts_table = 'members';
    private $outbox_table = 'outbox';
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

    private static function SEND_URL() {
        return "http://localhost:13013/cgi-bin/sendsms?username=foo&password=bar&dlr-mask=24";
    }

    private static function DLR_URL()
    {
        return "http://projects/sams/cgi/dlr.php?type=%d&answer=%A";
    }

    public function detokenize($template, $data)
    {
    	$pattern = array('/<date>/i', '/<stud_name>/i', '/<stud_no>/i', '/<time>/i');
    	$replace = array($data['date'], $data['stud_name'], $data['stud_no'], date('h:ia', strtotime($data['time'])));
    	return preg_replace($pattern, $replace, $template);
    }

    public function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
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

    public function scheduler()
    {
        $message = $request->getParameter('message');
        $cat = $request->getParameter('list_cat');
        $smsc = $request->getParameter('smsc');
        $listbox = $request->getParameter('ListBox2');
        $smsc = ($smsc=='auto') ? false : $smsc;
        $ids = implode(array_unique($listbox),',');

        $dt['message']     = $message;
        $dt['by']          = $auth['username'];
        $dt['smsc']        = $smsc;
        $dt['status']        = 'pending';
        $dt['member_ids']  = ($cat == 'list_contacts') ? $ids : "";
        $dt['group_ids']   = ($cat == 'list_groups') ? $ids : "";
        $dt['send_on']     = date("Y-m-d H:i:s",strtotime($request->getParameter('send_date')));

        $mid = $mm->insert('scheduler', $dt);
        $controller->redirect('?module='.DEFAULT_MODULE.'&action=Messaging');
    }

    public function send($id, $msisdn, $smsc, $body, $groups=null)
    {
        #
        $dlr = self::DLR_URL() . '&outbox_id=' . $id;
        $smsc = $this->get_network($msisdn);
        $url = self::SEND_URL() . '&to=' . $msisdn . '&text=' . urlencode($body) . '&smsc=' . $smsc . '&dlr-url=' . urlencode($dlr);

        $ch = curl_init ($url);
        ob_start();
        curl_exec($ch);
        $str = ob_get_contents();
        ob_end_clean();
        curl_close ($ch);
        $this->db->query("UPDATE ".$this->outbox_table." SET extra = '$str' where id='$id'");
        # if (empty($str)) $this->db->query("UPDATE ".$this->outbox_table." SET status = 'success' where id='$id'");
    }

    public function num_format($msisdn)
    {
        $pattern = array('/i/i','/l/i','/o/i','/[^\d]/','/^(\+63|63)/');
        $replace = array(1,1,0,'','0');
        $msisdn = preg_replace($pattern, $replace, trim($msisdn));
        #if (preg_match("/^(\+63|63|0)([0-9]{1,12})/" , trim($msisdn), $matches))
        #    return "0".$matches[2];
        #else
        return $msisdn;
    }

    public function get_network($number)
    {
        $msisdn = $this->num_format($number);
        $prefix = str_split($msisdn,4);
        $sql = "SELECT network FROM prefixes where access LIKE '%{$prefix[0]}%'";
        $q = $this->db->query($sql)->row();
        return count($q) !== 0 ? $q->network : 'auto';
    }
}
 ?>
