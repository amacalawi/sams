<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_device_gate_table extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'device_id' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
            ),
            'gate_id' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
            ),
            'code' => array(
                'type' => 'TEXT',
                'constraint' => '255',
            )
        ));

        $this->dbforge->add_field('created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP');
        $this->dbforge->add_field('updated_at TIMESTAMP DEFAULT "0000-00-00 00:00:00" ON UPDATE CURRENT_TIMESTAMP');
        $this->dbforge->add_field('removed_at TIMESTAMP NULL');

        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('device_gate');
    }

    public function down()
    {
        $this->dbforge->drop_table('device_gate', TRUE);
    }

}