<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_levels_table extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'levels_id' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'levels_name' => array(
                'type' => 'TEXT',
                'constraint' => '255',
            ),
            'levels_description' => array(
                'type' => 'TEXT',
                'constraint' => '255',
            ),
            'levels_code' => array(
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

        $this->dbforge->add_key('levels_id', TRUE);
        $this->dbforge->create_table('levels');
    }

    public function down()
    {
        $this->dbforge->drop_table('levels', TRUE);
    }

}