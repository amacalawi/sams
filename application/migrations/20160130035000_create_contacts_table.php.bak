<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_contacts_table extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'contacts_id' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'contacts_firstname' => array(
                'type' => 'TEXT',
                'constraint' => '255',
            ),
            'contacts_middlename' => array(
                'type' => 'TEXT',
                'constraint' => '255',
            ),
            'contacts_lastname' => array(
                'type' => 'TEXT',
                'constraint' => '255',
            ),
            'contacts_level' => array(
                'type' => 'TEXT',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'contacts_type' => array(
                'type' => 'TEXT',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'contacts_blockno' => array(
                'type' => 'TEXT',
            ),
            'contacts_street' => array(
                'type' => 'TEXT',
            ),
            'contacts_brgy' => array(
                'type' => 'TEXT',
            ),
            'contacts_city' => array(
                'type' => 'TEXT',
            ),
            'contacts_zip' => array(
                'type' => 'TEXT',
            ),
            'contacts_telephone' => array(
                'type' => 'TEXT',
            ),
            'contacts_mobile' => array(
                'type' => 'TEXT',
            ),
            'contacts_email' => array(
                'type' => 'TEXT',
            ),
            'contacts_group' => array(
                'type' => 'TEXT',
                'constraint' => '255',
                'null' => TRUE,
            )
        ));

        $this->dbforge->add_field('created_by INT');
        $this->dbforge->add_field('updated_by INT');
        $this->dbforge->add_field('removed_by INT');

        $this->dbforge->add_field('created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP');
        $this->dbforge->add_field('updated_at TIMESTAMP DEFAULT "0000-00-00 00:00:00" ON UPDATE CURRENT_TIMESTAMP');
        $this->dbforge->add_field('removed_at TIMESTAMP NULL');

        $this->dbforge->add_key('contacts_id', TRUE);
        $this->dbforge->create_table('contacts', false);
    }

    public function down()
    {
        $this->dbforge->drop_table('contacts', TRUE);
    }

}