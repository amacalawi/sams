<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $Headers->Title ?></title>

    <!-- <link rel="apple-touch-icon" href="<?php # echo base_url('apple-touch-icon.png') ?>"> -->
    <link rel="shortcut icon" href="<?php echo base_url('favicon.ico') ?>">

    <!-- Global CSS Files -->
    <link rel="stylesheet" href="<?php echo base_url('assets/vendors/animate.css/animate.min.css') ?>">
    <!--link rel="stylesheet" href="<?php //echo base_url('assets/vendors/bootstrap-sweetalert/lib/sweet-alert.css') ?>"-->

    <link href="<?php echo base_url('assets/vendors/sweetalert/dist/sweetalert.css'); ?>" rel="stylesheet">


    <link rel="stylesheet" href="<?php echo base_url('assets/vendors/material-design-iconic-font/dist/css/material-design-iconic-font.min.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/vendors/font-awesome/css/font-awesome.min.css') ?>">
    <link rel='stylesheet' href="<?php echo base_url('assets/vendors/Waves/dist/waves.min.css') ?>">
    <?php
    /*
    | -------------------------
    | # Page Specific CSS Files
    | -------------------------
    */
    if( isset($Headers->CSS) ) echo $Headers->CSS;


    // FOR CSS FILES
    $css = '';
    $handle = '';
    $file = '';
    $url = 'assets/css/';
    // open the "css" directory
    if ($handle = opendir( $url ) ) {
        // list directory contents
        while (false !== ($file = readdir($handle))) {
            // only grab file names
            if (is_file($url . $file)) {
                // insert HTML code for loading Javascript files
                $css .= '<link rel="stylesheet" href="' . base_url($url . $file) . '"/>' . "\n";
            }
        }
        closedir($handle);
        echo $css;
    } ?>
    <script>
        //////////////////////
        // Global Variables //
        //////////////////////
        var homebased = '<?= base_url() ?>';

        var base_url = function (segments) {
            return "<?php echo base_url(); ?>" + segments;
        }
        var get_hash = function (url) {
            return window.location.hash;
        }
    </script>
</head>
<body class="<?php echo isset($Headers->bodyClass) ? $Headers->bodyClass : '' ?>">
    <noscript>
        Javascript Must be Enabled to use this Application.
    </noscript>