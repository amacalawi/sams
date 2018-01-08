<?php $this->load->model('Inbox', '', TRUE); ?>

<ul class="main-menu">
    <li class="<?php echo check_link(''); ?>">
        <?php echo anchor('', '<i class="zmdi zmdi-home"></i>Dashboard'); ?>
    </li>
    <li><hr></li>

    <li class="sub-menu <?php echo check_link(['members', 'members/trash', 'members/import', 'members/export']) ?>">
        <a role="button"><i class="zmdi zmdi-account-box"></i> Admission</a>
        <ul>
            <li class="<?php echo check_link('members') ?>"><?php echo anchor( base_url('members'), 'All Members' ); ?></li>
            <li class="<?php echo check_link('members/export') ?>"><?php echo anchor( base_url('members/export'), 'Export Members' ); ?></li>
            <li class="<?php echo check_link('members/import') ?>"><?php echo anchor( base_url('members/import'), 'Import Members' ); ?></li>
            <li class="<?php echo check_link('members/trash') ?>"><?php echo anchor( base_url('members/trash'), 'Trashed Members' ); ?></li>
        </ul>
    </li>

    <li class="sub-menu <?php echo check_link(['enrollments', 'enrollments/import', 'enrollments/export', 'enrollments/trash']) ?>">
        <a role="button"><i class="zmdi zmdi-accounts"></i> Enrollments</a>
        <ul>
            <li class="<?php echo check_link('enrollments') ?>" ><?php echo anchor( base_url('enrollments'), 'All Enrollments' ); ?></li>
            <li class="<?php echo check_link('enrollments/export') ?>" ><?php echo anchor( base_url('enrollments/export'), 'Export Enrollments' ); ?></li>
            <li class="<?php echo check_link('enrollments/import') ?>" ><?php echo anchor( base_url('enrollments/import'), 'Import Enrollments' ); ?></li>
            <li class="<?php echo check_link('enrollments/trash') ?>" ><?php echo anchor( base_url('enrollments/trash'), 'Trashed Enrollments' ); ?></li>
        </ul>
    </li>

    <li><hr></li>

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

    <li><hr></li>

    <!-- <li class="sub-menu <?php echo check_link(['contacts', 'contacts/trash', 'contacts/import', 'contacts/export']) ?>">
        <a role="button"><i class="zmdi zmdi-account-box"></i> Contacts</a>
        <ul>
            <li class="<?php echo check_link('contacts') ?>"><?php echo anchor( base_url('contacts'), 'All Contacts' ); ?></li>
            <li class="<?php echo check_link('contacts/export') ?>"><?php echo anchor( base_url('contacts/export'), 'Export Contacts' ); ?></li>
            <li class="<?php echo check_link('contacts/import') ?>"><?php echo anchor( base_url('contacts/import'), 'Import Contacts' ); ?></li>
            <li class="<?php echo check_link('contacts/trash') ?>"><?php echo anchor( base_url('contacts/trash'), 'Trashed Contacts' ); ?></li>
        </ul>
    </li> -->

    <li class="sub-menu <?php echo check_link(['groups', 'groups/import', 'groups/export', 'groups/trash']) ?>">
        <a role="button"><i class="zmdi zmdi-accounts"></i> Groups</a>
        <ul>
            <li class="<?php echo check_link('groups') ?>" ><?php echo anchor( base_url('groups'), 'All Groups' ); ?></li>
            <li class="<?php echo check_link('groups/export') ?>" ><?php echo anchor( base_url('groups/export'), 'Export Groups' ); ?></li>
            <li class="<?php echo check_link('groups/import') ?>" ><?php echo anchor( base_url('groups/import'), 'Import Groups' ); ?></li>
            <li class="<?php echo check_link('groups/trash') ?>" ><?php echo anchor( base_url('groups/trash'), 'Trashed Groups' ); ?></li>
        </ul>
    </li>

    <li class="sub-menu <?php echo check_link(['levels', 'levels/import', 'levels/export']) ?>">
        <a role="button"><i class="fa fa-signal"></i> Levels</a>
        <ul>
            <li class="<?php echo check_link('levels') ?>" ><?php echo anchor( base_url('levels'), 'All Levels' ); ?></li>
            <li class="<?php echo check_link('levels/export') ?>" ><?php echo anchor( base_url('levels/export'), 'Export Levels' ); ?></li>
            <li class="<?php echo check_link('levels/import') ?>" ><?php echo anchor( base_url('levels/import'), 'Import Levels' ); ?></li>
        </ul>
    </li>

    <li class="sub-menu <?php echo check_link(['types', 'types/import', 'types/export']) ?>">
        <a role="button"><i class="zmdi zmdi-view-list"></i> Types</a>
        <ul>
            <li class="<?php echo check_link('types') ?>" ><?php echo anchor( base_url('types'), 'All Types' ); ?></li>
            <li class="<?php echo check_link('types/export') ?>" ><?php echo anchor( base_url('types/export'), 'Export Types' ); ?></li>
            <li class="<?php echo check_link('types/import') ?>" ><?php echo anchor( base_url('types/import'), 'Import Types' ); ?></li>
        </ul>
    </li>

    <li class="sub-menu <?php echo check_link(['schedules', 'schedules/import', 'schedules/export', 'schedules/trash']) ?>">
        <a role="button"><i class="zmdi zmdi-chart"></i> Schedules</a>
        <ul>
            <li class="<?php echo check_link('schedules') ?>" ><?php echo anchor( base_url('schedules'), 'All Schedules' ); ?></li>
            <li class="<?php echo check_link('schedules/preset-messages') ?>" ><?php echo anchor( base_url('schedules/preset-messages'), 'Preset Messages' ); ?></li>
            <!-- <li class="<?php echo check_link('schedules/export') ?>" ><?php echo anchor( base_url('schedules/export'), 'Export Groups' ); ?></li>
            <li class="<?php echo check_link('schedules/import') ?>" ><?php echo anchor( base_url('schedules/import'), 'Import Groups' ); ?></li>
            <li class="<?php echo check_link('schedules/trash') ?>" ><?php echo anchor( base_url('schedules/trash'), 'Trashed Groups' ); ?></li> -->
        </ul>
    </li>

    <li class="sub-menu <?php echo check_link(['schoolyears', 'schoolyears/import', 'schoolyears/export']) ?>">
        <a role="button"><i class="fa fa-calendar-o"></i> School Years</a>
        <ul>
            <li class="<?php echo check_link('schoolyears') ?>" ><?php echo anchor( base_url('schoolyears'), 'All School Years' ); ?></li>
            <li class="<?php echo check_link('schoolyears/export') ?>" ><?php echo anchor( base_url('schoolyears/export'), 'Export School Years' ); ?></li>
            <li class="<?php echo check_link('schoolyears/import') ?>" ><?php echo anchor( base_url('schoolyears/import'), 'Import School Years' ); ?></li>
        </ul>
    </li>

    <li><hr></li>
    <li class="sub-menu <?php echo check_link(['monitor', 'monitor/dtr', 'monitor/splash', 'monitor/announcement', 'monitor/gates', 'monitor/devices']) ?>">
        <a role="button"><i class="zmdi zmdi-account"></i> Monitor</a>
        <ul>
            <li class="<?php echo check_link('monitor/dtr') ?>" ><?php echo anchor( base_url('monitor/dtr'), 'Daily Time Record' ); ?></li>
            <li class="<?php echo check_link('monitor/splash') ?>" ><?php echo anchor( base_url('monitor/splash'), 'Splash Page' ); ?></li>
            <li class="<?php echo check_link('monitor/announcement') ?>" ><?php echo anchor( base_url('monitor/announcement'), 'Announcement' ); ?></li>
            <li class="<?php echo check_link('monitor/gates') ?>" ><?php echo anchor( base_url('monitor/gates'), 'Gates' ); ?></li>
            <li class="<?php echo check_link('monitor/devices') ?>" ><?php echo anchor( base_url('monitor/devices'), 'Devices' ); ?></li>
        </ul>
    </li>

    <li><hr></li>
    <li class="sub-menu <?php echo check_link(['users', 'users/import', 'users/export']) ?>">
        <a role="button"><i class="zmdi zmdi-account"></i> Users</a>
        <ul>
            <li class="<?php echo check_link('users') ?>" ><?php echo anchor( base_url('users'), 'All Users' ); ?></li>
            <li class="<?php echo check_link('users/export') ?>" ><?php echo anchor( base_url('users/export'), 'Export Users' ); ?></li>
            <li class="<?php echo check_link('users/import') ?>" ><?php echo anchor( base_url('users/import'), 'Import Users' ); ?></li>
        </ul>
    </li>

    <li class="sub-menu <?php echo check_link(['privileges', 'privileges/trash', 'privileges-levels', 'modules']) ?>">
        <a role="button"><i class="fa fa-cogs"></i> Management</a>
        <ul>
            <li class="<?php echo check_link(['privileges', 'privileges/trash']) ?>" ><?php echo anchor( base_url('privileges'), 'Privileges' ); ?></li>
            <li class="<?php echo check_link('privileges-levels') ?>" ><?php echo anchor( base_url('privileges-levels'), 'Privileges Levels' ); ?></li>
            <li class="<?php echo check_link('modules') ?>" ><?php echo anchor( base_url('modules'), 'Modules' ); ?></li>
        </ul>
    </li>

</ul>
