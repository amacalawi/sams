<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_schedules_table extends CI_Migration {

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
                'constraint' => '225'
            ),
            'code' => array(
                'type' => 'TEXT',
                'constraint' => '225'
            ),
            'message_template_id' => array(
                'type' => 'INT',
                'constraint' => '225',
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
        $this->dbforge->create_table('schedules', false);
    }

    public function down()
    {
        $this->dbforge->drop_table('schedules', TRUE);
    }
}
