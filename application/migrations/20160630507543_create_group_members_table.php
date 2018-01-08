<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_group_members_table extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'group_id' => array(
                'type' => 'INT',
            ),
            'member_id' => array(
                'type' => 'INT',
            ),
        ));

        $this->dbforge->add_key('group_id', TRUE);
        $this->dbforge->add_key('member_id', TRUE);
        $this->dbforge->create_table('group_members', false);
    }

    public function down()
    {
        $this->dbforge->drop_table('group_members', TRUE);
    }
}