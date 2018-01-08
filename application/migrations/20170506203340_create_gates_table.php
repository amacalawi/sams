<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_gates_table extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'name' => array(
                'type' => 'TEXT',
                'constraint' => '255',
            ),
            'description' => array(
                'type' => 'TEXT',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'code' => array(
                'type' => 'TEXT',
                'constraint' => '255',
                'null' => TRUE,
            )
        ));

        $this->dbforge->add_field('created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP');
        $this->dbforge->add_field('updated_at TIMESTAMP DEFAULT "0000-00-00 00:00:00" ON UPDATE CURRENT_TIMESTAMP');
        $this->dbforge->add_field('removed_at TIMESTAMP NULL');

        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('gates');
    }

    public function down()
    {
        $this->dbforge->drop_table('gates', TRUE);
    }

}