<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_codes_table extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'pattern_id' => array(
                'type' => 'INT',
                'null' => TRUE,
            ),
            'code' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => TRUE,
            ),
        ));

        $this->dbforge->add_field('created_by INT');
        $this->dbforge->add_field('updated_by INT');

        $this->dbforge->add_field('created_on TIMESTAMP DEFAULT CURRENT_TIMESTAMP');
        $this->dbforge->add_field('updated_on TIMESTAMP DEFAULT "0000-00-00 00:00:00" ON UPDATE CURRENT_TIMESTAMP');

        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('codes');
    }

    public function down()
    {
        $this->dbforge->drop_table('codes', TRUE);
    }
}