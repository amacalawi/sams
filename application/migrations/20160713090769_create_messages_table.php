<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_messages_table extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'BIGINT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'message' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
            'msisdn' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => TRUE,
            ),
            'by' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => TRUE,
            ),
        ));

        $this->dbforge->add_field('created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP');
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('messages');
    }

    public function down()
    {
        $this->dbforge->drop_table('messages', TRUE);
    }
}