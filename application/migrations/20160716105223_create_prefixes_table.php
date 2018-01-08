<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_prefixes_table extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'access' => array(
                'type' => 'TEXT',
                'constraint' => '255',
            ),
            'network' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
        ));

        $this->dbforge->add_field('created_on TIMESTAMP DEFAULT CURRENT_TIMESTAMP');

        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('prefixes');
    }

    public function down()
    {
        $this->dbforge->drop_table('prefixes', TRUE);
    }
}