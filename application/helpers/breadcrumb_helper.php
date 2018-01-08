<?php
/*
| ----------------------------------
| # Breadcrumbs
| ----------------------------------
*/
if ( ! function_exists('breadcrumbs'))
{
    function breadcrumbs($breadcrumbs='', $title=null, $simple = false)
    {
        $CI = get_instance();

        if (''==$breadcrumbs) $breadcrumbs = $CI->uri->segment_array();
        if (null==$title) $title = 'All ' . ucfirst($breadcrumbs[1]);
        if ($simple) return implode('/', $breadcrumbs);

        ob_start(); ?>
        <div class="card-header m-b-25"><h2><?php echo $title; ?><ul class="pull-right breadcrumb"><?php

            foreach ($breadcrumbs as $key => $breadcrumb) {

                $link = base_url($CI->uri->segment($key)); ?>

                <li><?php if(count($breadcrumbs) != $key): ?><a href="<?php echo $link ?>"><?php endif; echo ucfirst($breadcrumb); if(count($breadcrumbs) != $key): ?></a><?php endif; ?></li> <?php

            }

            if( count($breadcrumbs) <= 1 )
            {
                ?><li class="active"><?php echo $title ?></li><?php
            } ?>


        </ul></h2></div><?php
        return ob_get_clean();
    }
}
 ?>