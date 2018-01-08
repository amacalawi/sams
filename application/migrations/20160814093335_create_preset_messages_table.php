<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_preset_messages_table extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'BIGINT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'name' => array(
                'type' => 'TEXT',
                'constraint' => 255,
                'null' => TRUE,
            ),
            'active' => array(
                'type' => 'INT',
                'null' => TRUE,
            ),
        ));

        $this->dbforge->add_field('created_by VARCHAR(50)');
        $this->dbforge->add_field('modified_by VARCHAR(50)');
        // $this->dbforge->add_field('removed_by INT');

        $this->dbforge->add_field('creation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP');
        $this->dbforge->add_field('modified_date TIMESTAMP DEFAULT "0000-00-00 00:00:00" ON UPDATE CURRENT_TIMESTAMP');
        // $this->dbforge->add_field('removed_at TIMESTAMP NULL');

        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('preset_messages');
    }

    public function down()
    {
        $this->dbforge->drop_table('preset_messages', TRUE);
    }
}