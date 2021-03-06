<?php $this->load->model('Inbox', '', TRUE); ?>

<ul class="main-menu">
    
    <?php $modules = $this->PrivilegesLevel->get_modules_by_priviledge($this->session->userdata('id')); ?>

    <?php 
        $module_access = array();
        $members = array(1,8,9); 
        $enrollments = array(6,7,8,9); 
        $messagings = array(37, 38, 39, 40, 41, 42, 43, 44, 45); 
        $groups = array(10,17,18); 
        $levels = array(28, 35, 36); 
        $types = array(19, 26, 27); 
        $schedules = array(65,66); 
        $schoolyears = array(67); 
        $monitors = array(68,69,70,71,72); 
        $users = array(73,74,75); 
        $managements = array(46,55,76); 
    ?>

    <?php foreach ($modules as $module ) { ?>
            
            <?php  foreach (explode(",",$module->modules) as $module) { ?> 
                <?php $module_access[] = $module ?>
            <?php } ?> 

    <?php } ?>

    <li class="<?php echo check_link(''); ?>">
        <?php echo anchor('', '<i class="zmdi zmdi-home"></i>Dashboard'); ?>
    </li>

    <?php if((count(array_intersect($module_access, $members)) > 0)) { ?>
        <li class="sub-menu <?php echo check_link(['members', 'members/trash', 'members/import', 'members/export']) ?>">
            <a role="button"><i class="zmdi zmdi-account-box"></i> Admission</a>
            <ul>
                <?php foreach ($members as $member) { ?>    
                    <?php if(in_array($member, $module_access)) { ?>
                        <li class="<?php echo check_link($this->Module->get_module_slug($member)) ?>"><?php echo anchor( base_url($this->Module->get_module_slug($member)), $this->Module->get_module_name($member) ); ?></li>
                    <?php } ?>
                <?php } ?>
                <li class="<?php echo check_link('members/trash') ?>"><?php echo anchor( base_url('members/trash'), 'Trashed members' ); ?></li>                
            </ul>
        </li>
    <?php } ?>

    <?php if((count(array_intersect($module_access, $messagings)) > 0)) { ?>
        <li class="sub-menu <?php echo check_link(['messaging/new', 'messaging/inbox', 'messaging/outbox', 'messaging/tracking', 'messaging/configuration', 'messaging/templates']) ?>">
        <a role="button"><i class="zmdi zmdi-email">&nbsp;</i>Messaging<?php echo Inbox::getUnreadCount() ? '<div style="left:29px;top:0;" class="notification-circle unread">'.Inbox::getUnreadCount().'</div>' : ''; ?></a>
            <ul>
            <li class="<?php echo check_link('messaging/new') ?>" ><?php echo anchor( base_url('messaging/new'), 'New' ); ?></li>
                <li class="<?php echo check_link('messaging/inbox') ?>" ><?php echo anchor( base_url('messaging/inbox'), 'Inbox'.(Inbox::getUnreadCount() ? '<div style="left:83px;top:10px;" class="notification-circle indicator"></div>' : '') ); ?></li>
                <li class="<?php echo check_link('messaging/outbox') ?>" ><?php echo anchor( base_url('messaging/outbox'), 'Outbox' ); ?></li>
                <li class="<?php echo check_link('messaging/tracking') ?>" ><?php echo anchor( base_url('messaging/tracking'), 'Tracking' ); ?></li>
                <li class="<?php echo check_link('messaging/templates') ?>" ><?php echo anchor( base_url('messaging/templates'), 'Templates' ); ?></li>
                <li class="<?php echo check_link('messaging/configuration') ?>" ><?php echo anchor( base_url('messaging/configuration'), 'Configuration' ); ?></li>
            </ul>
        </li>
    <?php } ?>
    
    <?php if((count(array_intersect($module_access, $groups)) > 0)) { ?>
        <li class="sub-menu <?php echo check_link(['groups', 'groups/import', 'groups/export', 'groups/trash']) ?>">
            <a role="button"><i class="zmdi zmdi-accounts"></i> Groups</a>
            <ul>
                <?php foreach ($groups as $group) { ?>    
                    <?php if(in_array($group, $module_access)) { ?>
                        <li class="<?php echo check_link($this->Module->get_module_slug($group)) ?>"><?php echo anchor( base_url($this->Module->get_module_slug($group)), $this->Module->get_module_name($group) ); ?></li>
                    <?php } ?>
                <?php } ?>
                
            </ul>
        </li>
    <?php } ?>

    <?php if((count(array_intersect($module_access, $levels)) > 0)) { ?>
        <li class="sub-menu <?php echo check_link(['levels', 'levels/import', 'levels/export']) ?>">
            <a role="button"><i class="fa fa-signal"></i> Levels</a>
            <ul>
                <?php foreach ($levels as $level) { ?>    
                    <?php if(in_array($level, $module_access)) { ?>
                        <li class="<?php echo check_link($this->Module->get_module_slug($level)) ?>"><?php echo anchor( base_url($this->Module->get_module_slug($level)), $this->Module->get_module_name($level) ); ?></li>
                    <?php } ?>
                <?php } ?>
                
            </ul>
        </li>
    <?php } ?>

    <?php if((count(array_intersect($module_access, $types)) > 0)) { ?>
        <li class="sub-menu <?php echo check_link(['types', 'types/import', 'types/export']) ?>">
            <a role="button"><i class="zmdi zmdi-view-list"></i> Types</a>
            <ul>
                <?php foreach ($types as $type) { ?>    
                    <?php if(in_array($type, $module_access)) { ?>
                        <li class="<?php echo check_link($this->Module->get_module_slug($type)) ?>"><?php echo anchor( base_url($this->Module->get_module_slug($type)), $this->Module->get_module_name($type) ); ?></li>
                    <?php } ?>
                <?php } ?>
                
            </ul>
        </li>
    <?php } ?>

    <?php if((count(array_intersect($module_access, $schedules)) > 0)) { ?>
        <li class="sub-menu <?php echo check_link(['schedules', 'schedules/import', 'schedules/export', 'schedules/trash']) ?>">
            <a role="button"><i class="zmdi zmdi-chart"></i> Schedules</a>
            <ul>
                <?php foreach ($schedules as $schedule) { ?>    
                    <?php if(in_array($schedule, $module_access)) { ?>
                        <li class="<?php echo check_link($this->Module->get_module_slug($schedule)) ?>"><?php echo anchor( base_url($this->Module->get_module_slug($schedule)), $this->Module->get_module_name($schedule) ); ?></li>
                    <?php } ?>
                <?php } ?>
                
            </ul>
        </li>
    <?php } ?>

    <?php if((count(array_intersect($module_access, $schoolyears)) > 0)) { ?>
        <li class="sub-menu <?php echo check_link(['schoolyears', 'schoolyears/import', 'schoolyears/export']) ?>">
            <a role="button"><i class="fa fa-calendar-o"></i> School Years</a>
            <ul>
                <?php foreach ($schoolyears as $schoolyear) { ?>    
                    <?php if(in_array($schoolyear, $module_access)) { ?>
                        <li class="<?php echo check_link($this->Module->get_module_slug($schoolyear)) ?>"><?php echo anchor( base_url($this->Module->get_module_slug($schoolyear)), $this->Module->get_module_name($schoolyear) ); ?></li>
                    <?php } ?>
                <?php } ?>                
            </ul>
        </li>
    <?php } ?>

    <?php if((count(array_intersect($module_access, $monitors)) > 0)) { ?>
        <li class="sub-menu <?php echo check_link(['monitor', 'monitor/dtr', 'monitor/splash', 'monitor/announcement', 'monitor/gates', 'monitor/devices']) ?>">
            <a role="button"><i class="zmdi zmdi-account"></i> Monitor</a>
            <ul>
                <?php foreach ($monitors as $monitor) { ?>    
                    <?php if(in_array($monitor, $module_access)) { ?>
                        <li class="<?php echo check_link($this->Module->get_module_slug($monitor)) ?>"><?php echo anchor( base_url($this->Module->get_module_slug($monitor)), $this->Module->get_module_name($monitor) ); ?></li>
                    <?php } ?>
                <?php } ?>
                
            </ul>
        </li>
    <?php } ?>

    <?php if((count(array_intersect($module_access, $users)) > 0)) { ?>
        <li class="sub-menu <?php echo check_link(['users', 'users/import', 'users/export']) ?>">
            <a role="button"><i class="zmdi zmdi-account"></i> Users</a>
            <ul>
                <?php foreach ($users as $user) { ?>    
                    <?php if(in_array($monitor, $module_access)) { ?>
                        <li class="<?php echo check_link($this->Module->get_module_slug($user)) ?>"><?php echo anchor( base_url($this->Module->get_module_slug($user)), $this->Module->get_module_name($user) ); ?></li>
                    <?php } ?>
                <?php } ?>
                
            </ul>
        </li>
    <?php } ?>

    <?php if((count(array_intersect($module_access, $managements)) > 0)) { ?>
        <li class="sub-menu <?php echo check_link(['privileges', 'privileges/trash', 'privileges-levels', 'modules']) ?>">
            <a role="button"><i class="fa fa-cogs"></i> Management</a>
            <ul>
                <?php foreach ($managements as $management) { ?>    
                    <?php if(in_array($management, $module_access)) { ?>
                        <li class="<?php echo check_link($this->Module->get_module_slug($management)) ?>"><?php echo anchor( base_url($this->Module->get_module_slug($management)), $this->Module->get_module_name($management) ); ?></li>
                    <?php } ?>
                <?php } ?>
                
            </ul>
        </li>
    <?php } ?>

</ul>
