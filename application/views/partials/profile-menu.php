<div class="profile-menu">
    <a href="">
        <div class="profile-pic">
            <img src="<?php echo base_url('assets/img/profile-pics/2.jpg') ?>">
        </div>
        <div class="profile-info">
            <?php echo get_fullname(); ?>
            <i class="zmdi zmdi-caret-down-circle"></i>
        </div>
    </a>
    <ul class="main-menu">
        <!-- <li>
            <a href="profile-about.html"><i class="zmdi zmdi-account"></i> View Profile</a>
        </li> -->
        <!-- <li>
            <a href=""><i class="zmdi zmdi-settings"></i> Settings</a>
        </li> -->
        <li>
            <a href="<?php echo base_url('logout'); ?>"><i class="zmdi zmdi-time-restore"></i> Logout</a>
        </li>
    </ul>
</div>