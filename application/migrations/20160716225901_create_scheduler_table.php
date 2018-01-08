<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_scheduler_table extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'message' => array(
                'type' => 'TEXT',
                'constraint' => '255',
                'null' => true,
            ),
            'member_ids' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ),
            'group_ids' => array(
                'type' => 'TEXT',
                'constraint' => '255',
                'null' => true,
            ),
            'smsc' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ),
            'msisdn' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ),
            'status' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ),
            'interval' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ),
            'send_at' => array(
                'type' => 'DATETIME',
                'null' => true,
            ),
        ));

        $this->dbforge->add_field('created_by INT');
        $this->dbforge->add_field('updated_by INT');
        $this->dbforge->add_field('removed_by INT');

        $this->dbforge->add_field('created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP');
        $this->dbforge->add_field('updated_at TIMESTAMP DEFAULT "0000-00-00 00:00:00" ON UPDATE CURRENT_TIMESTAMP');
        $this->dbforge->add_field('removed_at TIMESTAMP NULL');

        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('scheduler');
    }

    public function down()
    {
        $this->dbforge->drop_table('scheduler', TRUE);
    }
}