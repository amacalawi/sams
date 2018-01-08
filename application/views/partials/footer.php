    <footer id="footer">

        <span><?php echo get_copyright('2015'); ?></span>

        <ul class="f-menu">
            <li><a href="<?php echo base_url('dashboard') ?>">Dashboard</a></li>
            <li><a href="http://www.awwits.info" target="_blank">Support</a></li>
            <li><a href="mailto:info@awwits.info">Contact</a></li>
        </ul>

        <!-- Global JS Files -->
        <script src="<?php echo base_url('assets/vendors/jquery/dist/jquery.min.js') ?>"></script>
        <script src="<?php echo base_url('assets/vendors/jquery.nicescroll/dist/jquery.nicescroll.min.js') ?>"></script>
        <script src="<?php echo base_url('assets/vendors/bootstrap/dist/js/bootstrap.min.js') ?>"></script>
        <!--script src="<?php //echo base_url('assets/vendors/bootstrap-sweetalert/lib/sweet-alert.min.js') ?>"></script-->

        <script src="<?php echo base_url('assets/vendors/sweetalert/dist/sweetalert.min.js'); ?>"></script>


        <script src="<?php echo base_url('assets/vendors/Waves/dist/waves.min.js') ?>"></script>
        <script src="<?php echo base_url('assets/vendors/autosize/dist/autosize.min.js') ?>"></script>
        <script src="<?php echo base_url('assets/js/functions/form.js') ?>"></script>
        <?php
        /*
        | -------------
        | # Extra Files
        | -------------
        */
        if( isset($Headers->JS) ) echo $Headers->JS;

        /*
        | ------
        | # For
        | ------
        */
        $js = '';
        $handle = '';
        $file = '';
        $url = 'assets/js/';
        // open the "js" directory
        if ($handle = opendir($url)) {
            // list directory contents
            while (false !== ($file = readdir($handle))) {
                // only grab file names
                if (is_file($url . $file)) {
                    // insert HTML code for loading Javascript files
                    $js .= '<script src="'. base_url($url . $file) . '" type="text/javascript"></script>' . "\n";
                }
            }
            closedir($handle);
            echo $js;
        }


        ?>
    </footer>
</body>
</html>