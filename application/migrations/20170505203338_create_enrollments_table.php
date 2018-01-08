<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_enrollments_table extends CI_Migration
{

    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'BIGINT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'member_id' => array(
                'type' => 'BIGINT',
                'unsigned' => TRUE,
                'null' => TRUE,
            ),
            'section_id' => array(
                'type' => 'BIGINT',
                'unsigned' => TRUE,
                'null' => TRUE,
            ),
            'schoolyear_id' => array(
                'type' => 'BIGINT',
                'unsigned' => TRUE,
                'null' => TRUE,
            ),
            'enrollment_status' => array(
                'type' => 'VARCHAR',
                'unsigned' => TRUE,
            ),
            'guardian' => array(
                'type' => 'VARCHAR',
                'null' => TRUE,
            ),
        ));

        $this->dbforge->add_field('created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP');
        $this->dbforge->add_field('updated_at TIMESTAMP DEFAULT "0000-00-00 00:00:00" ON UPDATE CURRENT_TIMESTAMP');
        $this->dbforge->add_field('removed_at TIMESTAMP NULL');

        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('enrollments');
    }

    public function down()
    {
        $this->dbforge->drop_table('enrollments', TRUE);
    }
}