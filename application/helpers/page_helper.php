<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
| -----------------------------------
| # Page Data
| -----------------------------------
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
        $Headers->SectionClass = '';

        return $Headers;
    }
}

/*
| -----------------------------------
| # Link Checker
| -----------------------------------
*/
if ( ! function_exists('check_link'))
{
    function check_link($link, $class='active', $not_class='', $class_suffix=' ', $not_class_suffix=' ', $fragment="")
    {
        $CI = get_instance();

        if( is_array($link) )
        {
            $is_active = $class . $class_suffix;
            foreach ($link as $key => $value) {
                $link = ($CI->uri->uri_string() == $value) ? $class . $class_suffix : $not_class . $not_class_suffix;
                if( $is_active == $link . $fragment  )
                {
                    return $class . $class_suffix;
                }
            }
        }

        return ($CI->uri->uri_string() == $link) ? $class . $class_suffix : $not_class . $not_class_suffix;
    }
}

/*
| -------------------------------
| # Footer
| -------------------------------
*/
if ( ! function_exists('get_copyright'))
{
    function get_copyright($year, $copy=null)
    {
        $CI = get_instance();
        if (null==$copy) $copy = 'Copyright &copy; ' . $year . ($year < date('Y') ? ' - ' . date('Y') : '') . ' ';

        $Headers = get_page_headers();
        $copy .= $Headers->Page_Title;

        return $copy;
    }
}

/*
| -------------------------------
| # Alert
| -------------------------------
*/
if ( ! function_exists('show_alert'))
{
    function show_alert($message, $type='info', $close_button=true, $message_only=false)
    {
        $CI = get_instance();
        ob_start(); ?>
        <div class="alert alert-<?php echo $type  ?>"><?php
            if($close_button): ?>
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><?php
            endif;
                echo $message ?>
        </div><?php
        echo ob_get_clean();
    }
}

if ( ! function_exists('jsontostring'))
{
    function jsontostring($json, $separator=', ')
    {
        $CI = get_instance(); $string = array();
        $decoded = json_decode($json);
        if (json_last_error() !== JSON_ERROR_NONE) return $json;

        foreach ($decoded as $key => $value) {
            if(!empty($value)) $string[] = $value;
        }

        return implode($separator, $string);
    }
}

if ( ! function_exists('arraytostring'))
{
    function arraytostring($array, $separator=', ')
    {
        $CI = get_instance();
        if ( !is_array($array) ) return $array;

        $string = array();
        foreach ($array as $key => $value) {
            if(!empty($value)) $string[] = $value;
        }

        return implode($separator, $string);
    }
}


if ( ! function_exists('dropdown_list'))
{
    function dropdown_list($array, $key_array, $first_value_text='Please select', $first_value_is_blank=true)
    {
        $CI = get_instance();
        $a = [];

        if($first_value_is_blank) $a[''] = $first_value_text;
        else $a[$first_value_is_blank] = $first_value_text;
        foreach ($array as $v) {
            $a[ $v[ $key_array[0] ] ] = $v[ $key_array[1] ];
        }

        return  $a;
    }
}


if ( ! function_exists('arraytoimplode'))
{
    function arraytoimplode($array, $glue=",")
    {
        return  (is_array($array)) ? implode($glue, $array) : $array;
    }
}

if ( ! function_exists('explodetoarray'))
{
    function explodetoarray($string, $delimeter=",")
    {
        return (is_string($string)) ? explode($delimeter, trim($string)) : $string;
    }
}

if( !function_exists('get_fullname') ) {
    function get_fullname($formal=false)
    {
        $CI = get_instance();
        $firstname = $CI->session->firstname;
        $lastname = $CI->session->lastname;
        if( $formal ) return $lastname . ", " . $firstname;
        return $firstname . " " . $lastname;
    }
}

/*
| -----------------------------------
| # Acronymify
| -----------------------------------
*/
if ( ! function_exists('acronymify'))
{
    function acronymify($array, $glue="")
    {
        $CI = get_instance();

        $pieces = array();
        foreach ($array as $string) {
            $pieces[] = !empty($string[0]) ? strtoupper($string[0]) : ' ';
        }

        return implode($glue, $pieces);
    }
}

function get_url_fragment($fragment, $link=null)
{
    $CI = get_instance();
    $frg = parse_url( null==$link ? $CI->uri->uri_string() : $link . "#" . $fragment );
    return $frg['fragment'];
}

function slugify($string, $case='lower', $replacements=[], $delimiter="-", $space=" ")
{
    $slug = "";
    # Add more here
    $defaults = array(
        '.' => '',
        ',' => '',
        '!' => '',
        '#' => '',
        '?' => '',
        '+' => '',
        '_' => '',
        ')' => '',
        '(' => '',
        '*' => '',
        '&' => '',
        '@' => '-at-',
        '/' => '',
        '\\' => '',
        ':' => '',
        '*' => '',
        '?' => '',
        '"' => '',
        '<' => '',
        '>' => '',
        '\'' => '',
        '|' => '',
        '&nbsp;-&nbsp;' => '-',
    );
    # Combine $replacements array with $defaults array
    # Substitute the speicifed chars in $string
    $string = strtr( $string, array_merge($defaults, $replacements) );
    switch ($case) {
        case 'lower':
            $slug = strtolower( implode($delimiter, explode($space, $string ) ) );
            break;
        case 'upper':
            $slug = strtoupper( implode($delimiter, explode($space, $string ) ) );
            break;
        case 'title':
            $slug = ucwords( implode($delimiter, explode($space, $string ) ) );
            break;
        case 'none':
        case '':
        case 'default':
        default:
            $slug = implode($delimiter, explode($space, $string ) );
            break;
    }
    return trim( $slug );
}
