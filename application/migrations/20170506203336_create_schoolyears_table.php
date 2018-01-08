<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_schoolyears_table extends CI_Migration
{

    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'BIGINT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'name' => array(
                'type' => 'VARCHAR',
                'null' => TRUE,
            ),
            'code' => array(
                'type' => 'VARCHAR',
                'null' => TRUE,
            ),
            'description' => array(
                'type' => 'TEXT',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'year_start' => array(
                'type' => 'BIGINT',
                'unsigned' => TRUE,
            ),
            'year_end' => array(
                'type' => 'BIGINT',
                'unsigned' => TRUE,
            ),
            'years' => array(
                'type' => 'VARCHAR',
                'unsigned' => TRUE,
            ),
            'status' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
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
        $this->dbforge->create_table('schoolyears');
    }

    public function down()
    {
        $this->dbforge->drop_table('schoolyears', TRUE);
    }
}