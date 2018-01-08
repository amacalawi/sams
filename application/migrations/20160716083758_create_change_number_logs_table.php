<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_change_number_logs_table extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'stud_no' => array(
                'type' => 'VARCHAR',
                'CONSTRAINT' => '225',
                'null' => TRUE,
            ),
            'msisdn' => array(
                'type' => 'VARCHAR',
                'CONSTRAINT' => '225',
                'null' => TRUE,
            ),
        ));

        $this->dbforge->add_field('created_by INT');
        $this->dbforge->add_field('updated_by INT');

        $this->dbforge->add_field('created_on TIMESTAMP DEFAULT CURRENT_TIMESTAMP');
        $this->dbforge->add_field('updated_on TIMESTAMP DEFAULT "0000-00-00 00:00:00" ON UPDATE CURRENT_TIMESTAMP');

        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('change_number_logs');
    }

    public function down()
    {
        $this->dbforge->drop_table('change_number_logs', TRUE);
    }
}