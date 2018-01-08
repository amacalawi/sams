<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_dtr_time_settings_table extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'name' => array(
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => TRUE,
            ),
            'time_from' => array(
                'type' => 'TIME',
                'null' => TRUE,
            ),
            'time_to' => array(
                'type' => 'TIME',
                'null' => TRUE,
            ),
            'presetmsg_id' => array(
                'type' => 'INT',
                'null' => TRUE,
            ),
            'schedule_id' => array(
                'type' => 'INT',
                'null' => TRUE,
            )
        ));

        // $this->dbforge->add_field('created_on TIMESTAMP DEFAULT CURRENT_TIMESTAMP');
        // $this->dbforge->add_field('updated_on TIMESTAMP DEFAULT "0000-00-00 00:00:00" ON UPDATE CURRENT_TIMESTAMP');

        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('dtr_time_settings');
    }

    public function down()
    {
        $this->dbforge->drop_table('dtr_time_settings', TRUE);
    }
}