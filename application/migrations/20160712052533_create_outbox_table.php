<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_outbox_table extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'BIGINT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'message_id' => array(
                'type' => 'BIGINT',
                'null' => TRUE,
            ),
            'folder_id' => array(
                'type' => 'BIGINT',
                'null' => TRUE,
            ),
            'member_id' => array(
                'type' => 'BIGINT',
                'constraint' => '20',
                'null' => TRUE,
            ),
            'group_id' => array(
                'type' => 'BIGINT',
                'constraint' => '20',
                'null' => TRUE,
            ),
            'msisdn' => array(
                'type' => 'VARCHAR',
                'constraint' => '30',
                'null' => TRUE,
            ),
            'smsc' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => TRUE,
            ),
            'status' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => TRUE,
            ),
            'extra' => array(
                'type' => 'VARCHAR',
                'constraint' => '200',
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
        $this->dbforge->create_table('outbox');
    }

    public function down()
    {
        $this->dbforge->drop_table('outbox', TRUE);
    }
}