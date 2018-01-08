<?php
/**
 * The fullname
 */
if ( ! function_exists('get_page_headers'))
{
    function get_page_headers()
    {
        $CI = get_instance();
        $Headers = new stdClass();

        $Headers->Page_Title = "SAMS";
        $Headers->Title      = $Headers->Page_Title . ($CI->uri->segment(1) ? " | " : ' ') . ucfirst($CI->uri->segment(1)) . (null !== $CI->uri->segment(2) ? " - " . ucfirst($CI->uri->segment(2)) : '');
        $Headers->Page       = $CI->uri->segment(1) . "/" . (null !== $CI->uri->segment(2) ? $CI->uri->segment(2) : 'index');
        $Headers->CSS        = '';
        $Headers->JS         = '';

        return $Headers;
    }
}
 ?>