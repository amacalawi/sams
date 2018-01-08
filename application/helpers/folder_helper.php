<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
| -----------------------------------
| # Page Data
| -----------------------------------
*/
if ( ! function_exists('clean_upload_folder'))
{
    function clean_upload_folder($path='./uploads/')
    {
        // if(null == $path) return false;
        $files = glob($path.'*'); // get all file names
        foreach($files as $file) { // iterate files
            if(is_file($file)) unlink($file); // delete file
        }
        return true;
    }
}



 ?>