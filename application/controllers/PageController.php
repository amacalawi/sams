<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PageController extends CI_Controller {
    private $Data = array();

    public function __construct()
    {
        parent::__construct();
        $this->load->library('migration');
        $this->load->model('Module', '', TRUE);
        $this->load->model('Level', '', TRUE);
        $this->load->model('PrivilegesLevel', '', TRUE);
        $this->load->model('Privilege', '', TRUE);
        $this->load->model('User', '', TRUE);

        $this->Data['Headers'] = get_page_headers();
        $this->Data['Headers']->CSS .= '<link rel="stylesheet" href="'.base_url('assets/vendors/ducksboard-gridster/dist/jquery.gridster.min.css').'">';
        $this->Data['Headers']->JS  = '<script src="'.base_url('assets/vendors/ducksboard-gridster/dist/jquery.gridster.min.js').'"></script>';

        $this->Data['Headers']->JS .= '<script src="'.base_url('assets/js/specifics/dashboard.js').'"></script>';

    }

    /**
     * Index for this controller.
     *
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function index()
    {
        $this->load->model('Inbox', '', TRUE);
        $this->validated();
        $this->Data['Headers']->Page = "dashboard";
        $this->Data['inbox_limit'] = 5;
        $this->Data['inbox_list'] = $this->Inbox->get($this->Data['inbox_limit']);
        $this->load->view('layouts/main', $this->Data);
    }

    public function validated()
    {
        // $this->session->set_flashdata('error', "");
        if(!$this->session->userdata('validated')) redirect('login');
    }

    public function debug_view()
    {
        $this->Data['Headers']->Page = "debug/index";
        $this->load->view('layouts/main', $this->Data);
    }

    public function debug()
    {


        $this->load->library('upload', ['upload_path'=>'./uploads/', 'allowed_types'=>'gif|jpg|jpeg|png']);
        echo "<pre>";
            var_dump( $this->input->post() );
            var_dump($_FILES);
        echo "</pre>";

        if (!$this->upload->do_upload('avatar')) {
            echo json_encode(array('message' => $this->upload->display_errors(), 'type'=>'error'));
        } else {
            echo json_encode($this->upload->data()['full_path']);
        }

        die();
        // $this->validated();
        // $this->load->model('Auth', '', TRUE);
        // if( $this->Auth->can('members/update') ) {
        //     echo "can";
        // } else {
        //     $data = array(
        //         'message' => 'Restricted access.',
        //         'type' => 'warning',
        //     );
        //     $this->session->set_flashdata('message', $data);
        //     $this->Data['Headers'] = get_page_headers();
        //     $this->Data['Headers']->Page = 'errors/403';
        //     $this->load->view('layouts/errors', $this->Data);
        // }
        // if( $this->Auth->can() )
    }

    /**
     * View for this controller.
     *
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function view($page)
    {
        $this->validated();
        $Data['Headers'] = get_page_headers();
        $this->load->view('layouts/main', $Data);
    }

    public function install($version=NULL)
    {
        set_time_limit(600); // for longer execution time if needed
        # Check if migration already exists
        // if ($this->db->table_exists('migrations')) redirect('login');

        // Migrate
        if(isset($version) && ($this->migration->version($version) === FALSE))
        {
          show_error($this->migration->error_string());
        }

        elseif(is_null($version) && $this->migration->latest() === FALSE)
        {
          show_error($this->migration->error_string());
        }

        else
        {
          echo 'The migration has concluded successfully.';
        }

        // Seed
        // $this->seed();
    }

    public function undo($version=null)
    {
        $migrations = $this->migration->find_migrations();
        $migration_keys = array();
        foreach($migrations as $key => $migration)
        {
            $migration_keys[] = $key;
        }
            if  (isset($version) && array_key_exists($version,$migrations) && $this->migration->version($version))
        {
            echo 'The migration was reset to the version: '.$version;
            exit;
        }
        elseif(isset($version) && !array_key_exists($version,$migrations))
        {
            echo 'The migration with version number '.$version.' doesn\'t exist.';
        }
        else
        {
            $penultimate = (sizeof($migration_keys)==1) ? 0 : $migration_keys[sizeof($migration_keys) - 2];
            if($this->migration->version($penultimate))
            {
                echo 'The migration has been rolled back successfully.';
                exit;
            }
            else
            {
                echo 'Couldn\'t roll back the migration.';
                exit;
            }
        }
    }
    public function reset()
    {
        if($this->migration->current()!== FALSE)
        {
            echo 'The migration was reset to the version set in the config file.';
            return TRUE;
        }
        else
        {
            echo 'Couldn\'t reset migration.';
            show_error($this->migration->error_string());
            exit;
        }
    }

    public function seed()
    {
        $modules = null;
        $modules[] = array(
            'name' => 'Members Index',
            'description' => 'Members index',
            'slug' => 'members',
        );
        $modules[] = array(
            'name' => 'List Members',
            'description' => 'Members list function',
            'slug' => 'members/listing',
        );
        $modules[] = array(
            'name' => 'Add Members',
            'description' => 'Members add function',
            'slug' => 'members/add',
        );
        $modules[] = array(
            'name' => 'Edit Members',
            'description' => 'Members edit function',
            'slug' => 'members/edit',
        );
        $modules[] = array(
            'name' => 'Update Members',
            'description' => 'Members update function',
            'slug' => 'members/update',
        );
        $modules[] = array(
            'name' => 'Remove Members',
            'description' => 'Members remove function',
            'slug' => 'members/remove',
        );
        $modules[] = array(
            'name' => 'Restore Members',
            'description' => 'Members restore function',
            'slug' => 'members/restore',
        );
        $modules[] = array(
            'name' => 'Export Members',
            'description' => 'Members export function',
            'slug' => 'members/export',
        );
        $modules[] = array(
            'name' => 'Import Members',
            'description' => 'Members import function',
            'slug' => 'members/import',
        );

        /*
        | -----------------
        | # Groups
        | -----------------
        */
        $modules[] = array(
            'name' => 'Groups',
            'description' => 'Groups list function',
            'slug' => 'groups',
        );
        $modules[] = array(
            'name' => 'List Groups',
            'description' => 'Groups list function',
            'slug' => 'groups/listing',
        );
        $modules[] = array(
            'name' => 'Add Groups',
            'description' => 'Groups add function',
            'slug' => 'groups/add',
        );
        $modules[] = array(
            'name' => 'Edit Groups',
            'description' => 'Groups edit function',
            'slug' => 'groups/edit',
        );
        $modules[] = array(
            'name' => 'Update Groups',
            'description' => 'Groups update function',
            'slug' => 'groups/update',
        );
        $modules[] = array(
            'name' => 'Remove Groups',
            'description' => 'Groups remove function',
            'slug' => 'groups/remove',
        );
        $modules[] = array(
            'name' => 'Restore Groups',
            'description' => 'Groups restore function',
            'slug' => 'groups/restore',
        );
        $modules[] = array(
            'name' => 'Export Groups',
            'description' => 'Groups export function',
            'slug' => 'groups/export',
        );
        $modules[] = array(
            'name' => 'Import Groups',
            'description' => 'Groups import function',
            'slug' => 'groups/import',
        );
        $modules[] = array(
            'name' => 'Trash Groups',
            'description' => 'Groups trash function',
            'slug' => 'groups/trash',
        );
        $modules[] = array(
            'name' => 'Restore Groups',
            'description' => 'Groups restore function',
            'slug' => 'groups/restore',
        );

        /*
        | -----------------
        | # Types
        | -----------------
        */
        $modules[] = array(
            'name' => 'Types',
            'description' => 'Types list function',
            'slug' => 'types',
        );
        $modules[] = array(
            'name' => 'List Types',
            'description' => 'Types list function',
            'slug' => 'types/listing',
        );
        $modules[] = array(
            'name' => 'Add Types',
            'description' => 'Types add function',
            'slug' => 'types/add',
        );
        $modules[] = array(
            'name' => 'Edit Types',
            'description' => 'Types edit function',
            'slug' => 'types/edit',
        );
        $modules[] = array(
            'name' => 'Update Types',
            'description' => 'Types update function',
            'slug' => 'types/update',
        );
        $modules[] = array(
            'name' => 'Remove Types',
            'description' => 'Types remove function',
            'slug' => 'types/remove',
        );
        $modules[] = array(
            'name' => 'Restore Types',
            'description' => 'Types restore function',
            'slug' => 'types/restore',
        );
        $modules[] = array(
            'name' => 'Export Types',
            'description' => 'Types export function',
            'slug' => 'types/export',
        );
        $modules[] = array(
            'name' => 'Import Types',
            'description' => 'Types import function',
            'slug' => 'types/import',
        );

        /*
        | -----------------
        | # Levels
        | -----------------
        */
        $modules[] = array(
            'name' => 'Levels',
            'description' => 'Levels list function',
            'slug' => 'levels',
        );
        $modules[] = array(
            'name' => 'List Levels',
            'description' => 'Levels list function',
            'slug' => 'levels/listing',
        );
        $modules[] = array(
            'name' => 'Add Levels',
            'description' => 'Levels add function',
            'slug' => 'levels/add',
        );
        $modules[] = array(
            'name' => 'Edit Levels',
            'description' => 'Levels edit function',
            'slug' => 'levels/edit',
        );
        $modules[] = array(
            'name' => 'Update Levels',
            'description' => 'Levels update function',
            'slug' => 'levels/update',
        );
        $modules[] = array(
            'name' => 'Remove Levels',
            'description' => 'Levels remove function',
            'slug' => 'levels/remove',
        );
        $modules[] = array(
            'name' => 'Restore Levels',
            'description' => 'Levels restore function',
            'slug' => 'levels/restore',
        );
        $modules[] = array(
            'name' => 'Export Levels',
            'description' => 'Levels export function',
            'slug' => 'levels/export',
        );
        $modules[] = array(
            'name' => 'Import Levels',
            'description' => 'Levels import function',
            'slug' => 'levels/import',
        );

        /*
        | -----------------
        | # Messaging
        | -----------------
        */
        $modules[] = array(
            'name' => 'Messaging',
            'description' => 'Messaging list function',
            'slug' => 'messaging',
        );
        $modules[] = array(
            'name' => 'List Messaging',
            'description' => 'Messaging list function',
            'slug' => 'messaging/listing',
        );
        $modules[] = array(
            'name' => 'Add Messaging',
            'description' => 'Messaging add function',
            'slug' => 'messaging/add',
        );
        $modules[] = array(
            'name' => 'Edit Messaging',
            'description' => 'Messaging edit function',
            'slug' => 'messaging/edit',
        );
        $modules[] = array(
            'name' => 'Update Messaging',
            'description' => 'Messaging update function',
            'slug' => 'messaging/update',
        );
        $modules[] = array(
            'name' => 'Remove Messaging',
            'description' => 'Messaging remove function',
            'slug' => 'messaging/remove',
        );
        $modules[] = array(
            'name' => 'Restore Messaging',
            'description' => 'Messaging restore function',
            'slug' => 'messaging/restore',
        );
        $modules[] = array(
            'name' => 'Export Messaging',
            'description' => 'Messaging export function',
            'slug' => 'messaging/export',
        );
        $modules[] = array(
            'name' => 'Import Messaging',
            'description' => 'Messaging import function',
            'slug' => 'messaging/import',
        );

	/*
        | -----------------
        | # Monitor
        | -----------------
        */
        $modules[] = array(
            'name' => 'Monitor',
            'description' => 'Monitor function',
            'slug' => 'monitor',
        );

        $modules[] = array(
            'name' => 'Daily Time Report',
            'description' => 'Monitor DTR function',
            'slug' => 'monitor/dtr',
        );
        $modules[] = array(
            'name' => 'Splash Page',
            'description' => 'Monitor splash page function',
            'slug' => 'monitor/splash',
        );

        /*
        | -----------------
        | # Privileges
        | -----------------
        */
        $modules[] = array(
            'name' => 'Privilege',
            'description' => 'Privilege list function',
            'slug' => 'privileges',
        );
        $modules[] = array(
            'name' => 'List Privilege',
            'description' => 'Privilege list function',
            'slug' => 'privileges/listing',
        );
        $modules[] = array(
            'name' => 'Add Privilege',
            'description' => 'Privilege add function',
            'slug' => 'privileges/add',
        );
        $modules[] = array(
            'name' => 'Edit Privilege',
            'description' => 'Privilege edit function',
            'slug' => 'privileges/edit',
        );
        $modules[] = array(
            'name' => 'Update Privilege',
            'description' => 'Privilege update function',
            'slug' => 'privileges/update',
        );
        $modules[] = array(
            'name' => 'Remove Privilege',
            'description' => 'Privilege remove function',
            'slug' => 'privileges/remove',
        );
        $modules[] = array(
            'name' => 'Restore Privilege',
            'description' => 'Privilege restore function',
            'slug' => 'privileges/restore',
        );
        $modules[] = array(
            'name' => 'Export Privilege',
            'description' => 'Privilege export function',
            'slug' => 'privileges/export',
        );
        $modules[] = array(
            'name' => 'Import Privilege',
            'description' => 'Privilege import function',
            'slug' => 'privileges/import',
        );

        /*
        | -----------------
        | # Privileges Levels
        | -----------------
        */
        $modules[] = array(
            'name' => 'Privileges Level',
            'description' => 'Privileges Level list function',
            'slug' => 'privileges-levels',
        );
        $modules[] = array(
            'name' => 'List Privileges Level',
            'description' => 'Privileges Level list function',
            'slug' => 'privileges-levels/listing',
        );
        $modules[] = array(
            'name' => 'Add Privileges Level',
            'description' => 'Privileges Level add function',
            'slug' => 'privileges-levels/add',
        );
        $modules[] = array(
            'name' => 'Edit Privileges Level',
            'description' => 'Privileges Level edit function',
            'slug' => 'privileges-levels/edit',
        );
        $modules[] = array(
            'name' => 'Update Privileges Level',
            'description' => 'Privileges Level update function',
            'slug' => 'privileges-levels/update',
        );
        $modules[] = array(
            'name' => 'Remove Privileges Level',
            'description' => 'Privileges Level remove function',
            'slug' => 'privileges-levels/remove',
        );
        $modules[] = array(
            'name' => 'Restore Privileges Level',
            'description' => 'Privileges Level restore function',
            'slug' => 'privileges-levels/restore',
        );
        $modules[] = array(
            'name' => 'Export Privileges Level',
            'description' => 'Privileges Level export function',
            'slug' => 'privileges-levels/export',
        );
        $modules[] = array(
            'name' => 'Import Privileges Level',
            'description' => 'Privileges Level import function',
            'slug' => 'privileges-levels/import',
        );

        /**
         * Modules
         */
        $modules[] = array(
            'name' => 'Modules',
            'description' => 'Modules list function',
            'slug' => 'modules',
        );
        $modules[] = array(
            'name' => 'List Modules',
            'description' => 'Modules list function',
            'slug' => 'modules/listing',
        );
        $modules[] = array(
            'name' => 'Add Modules',
            'description' => 'Modules add function',
            'slug' => 'modules/add',
        );
        $modules[] = array(
            'name' => 'Edit Modules',
            'description' => 'Modules edit function',
            'slug' => 'modules/edit',
        );
        $modules[] = array(
            'name' => 'Update Modules',
            'description' => 'Modules update function',
            'slug' => 'modules/update',
        );
        $modules[] = array(
            'name' => 'Remove Modules',
            'description' => 'Modules remove function',
            'slug' => 'modules/remove',
        );
        $modules[] = array(
            'name' => 'Restore Modules',
            'description' => 'Modules restore function',
            'slug' => 'modules/restore',
        );
        $modules[] = array(
            'name' => 'Export Modules',
            'description' => 'Modules export function',
            'slug' => 'modules/export',
        );
        $modules[] = array(
            'name' => 'Import Modules',
            'description' => 'Privileges Level import function',
            'slug' => 'modules/import',
        );

        foreach ($modules as $module) {
            if (!$this->Module->exists($module['slug'], "slug")) {
                $this->Module->insert($module);
                echo "success" . "<br>";
            }
        }


        # Priveleges Levels
        $mod = $this->Module->all();
        $mm = [];
        foreach ($mod as $m) {
            $mm[] = $m->id;
        }
        $privileges_leves[] = array(
            'name' => 'Level -1',
            'code' => 'super-admin-level',
            'description' => 'Super Admin Privilege Level',
            'modules' => implode(",", $mm),
            'created_by' => 1,
        );
        $privilege_level_id=1;
        foreach ($privileges_leves as $pl) {
            $privilege_level_id = $this->PrivilegesLevel->insert($pl);
            echo "Privilege Level Added" . "<br>";
        }
        # Privileges
        $privileges[] = array(
            'name' => 'Super Admin',
            'code' => 'super-admin',
            'description' => 'Super Admin Privilege',
            'level' => $privilege_level_id,
            'created_by' => 1,
        );
        $privilege_id = 1;
        foreach ($privileges as $pl) {
            $privilege_id = $this->Privilege->insert($pl);
            echo "Privilege Added" . "<br>";
        }
        # Users
        $data = null;
        $data = array(
            'username'    => 'admin',
            'password'   => password_hash('admin', PASSWORD_BCRYPT),
            'email'     => 'john.dionisio1@gmail.com',
            'firstname'        => 'John Lioneil',
            'middlename'         => 'Palanas',
            'lastname'      => 'Dionisio',
            'remember_token'       => 1,
            'privilege' => $privilege_id,
            'privilege_level' => $privilege_level_id,
        );
        $this->User->insert($data);
        $data = null;
        $data = array(
            'username'    => 'foxtrot',
            'password'   => password_hash('foxtrot', PASSWORD_BCRYPT),
            'email'     => 'ferdiesan060116@gmail.com',
            'firstname'        => 'Ferdie',
            'middlename'         => '',
            'lastname'      => 'Santiago',
            'remember_token'       => 1,
            'privilege' => $privilege_id,
            'privilege_level' => $privilege_level_id,
        );
        $this->User->insert($data);
        $data = null;
        $data = array(
            'username'    => 'tango',
            'password'   => password_hash('tango', PASSWORD_BCRYPT),
            'email'     => 'amacalawi@gmail.com',
            'firstname'        => 'Aliudin',
            'middlename'         => '',
            'lastname'      => 'Macalawi',
            'remember_token'       => 1,
            'privilege' => $privilege_id,
            'privilege_level' => $privilege_level_id,
        );
        $this->User->insert($data);
        echo "Alright."; exit();
    }

}
