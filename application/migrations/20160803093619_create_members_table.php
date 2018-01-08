<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_members_table extends CI_Migration {

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
                'constraint' => '50',
                'null' => TRUE
            ),
            'firstname' => array(
                'type' => 'TEXT',
                'constraint' => '255',
            ),
            'middlename' => array(
                'type' => 'TEXT',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'lastname' => array(
                'type' => 'TEXT',
                'constraint' => '255',
            ),
            'nick' => array(
                'type' => 'TEXT',
                'constraint' => '255',
                'null' => true,
            ),
            'level' => array(
                'type' => 'TEXT',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'type' => array(
                'type' => 'TEXT',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'address_blockno' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
            'address_street' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
            'address_brgy' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
            'address_city' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
            'address_zip' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
            'telephone' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
            'msisdn' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
            'email' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
            'groups' => array(
                'type' => 'TEXT',
                'constraint' => '255',
                'null' => TRUE,
            ),
	        'avatar' => array(
                'type' => 'TEXT',
		        'constraint' => '255',
		        'null' => TRUE,
	        ),
            'schedule_id' => array(
                'type' => 'INT',
                'null' => TRUE,
            ),
        ));

        $this->dbforge->add_field('created_by INT');
        $this->dbforge->add_field('updated_by INT');
        $this->dbforge->add_field('removed_by INT');

        $this->dbforge->add_field('created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP');
        $this->dbforge->add_field('updated_at TIMESTAMP DEFAULT "0000-00-00 00:00:00" ON UPDATE CURRENT_TIMESTAMP');
        $this->dbforge->add_field('removed_at TIMESTAMP NULL');
        $this->dbforge->add_field('active INT DEFAULT 0');

        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('members', false);
    }

    public function down()
    {
        $this->dbforge->drop_table('members', TRUE);
    }
}
