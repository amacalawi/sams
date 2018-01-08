<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_inbox_table extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'folder_id' => array(
                'type' => 'INT',
                'null' => TRUE,
            ),
            'msisdn' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => TRUE,
            ),
            'smsc' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => TRUE,
            ),
            'body' => array(
                'type' => 'TEXT',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'charset' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => TRUE,
            ),
        ));

        $this->dbforge->add_field('created_by INT');
        $this->dbforge->add_field('updated_by INT');
        $this->dbforge->add_field('removed_by INT');

        $this->dbforge->add_field('created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP');
        $this->dbforge->add_field('updated_at TIMESTAMP DEFAULT "0000-00-00 00:00:00" ON UPDATE CURRENT_TIMESTAMP');
        $this->dbforge->add_field('removed_at TIMESTAMP NULL');

        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('inbox');
    }

    public function down()
    {
        $this->dbforge->drop_table('inbox', TRUE);
    }
}