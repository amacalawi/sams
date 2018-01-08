<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_groups_table extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'groups_id' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'groups_name' => array(
                'type' => 'TEXT',
                'constraint' => '255',
            ),
            'groups_description' => array(
                'type' => 'TEXT',
                'constraint' => '255',
            ),
            'groups_code' => array(
                'type' => 'TEXT',
                'constraint' => '255',
            )
        ));

        $this->dbforge->add_field('created_by INT');
        $this->dbforge->add_field('updated_by INT');
        $this->dbforge->add_field('removed_by INT');

        $this->dbforge->add_field('created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP');
        $this->dbforge->add_field('updated_at TIMESTAMP DEFAULT "0000-00-00 00:00:00" ON UPDATE CURRENT_TIMESTAMP');
        $this->dbforge->add_field('removed_at TIMESTAMP NULL');

        $this->dbforge->add_key('groups_id', TRUE);
        $this->dbforge->create_table('groups');
    }

    public function down()
    {
        $this->dbforge->drop_table('groups', TRUE);
    }
}