<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_devices_table extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'gate_id' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
            ),
            'name' => array(
                'type' => 'TEXT',
                'constraint' => '255',
            ),
            'description' => array(
                'type' => 'TEXT',
                'constraint' => '255',
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
        $this->dbforge->create_table('devices');
    }

    public function down()
    {
        $this->dbforge->drop_table('devices', TRUE);
    }

}