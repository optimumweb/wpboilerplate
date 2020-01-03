<?php

function wpbp_remove_widget_title($widget_title)
{
    return substr($widget_title, 0, 1) === '!' ? null : $widget_title;
}
add_filter('widget_title', 'wpbp_remove_widget_title');
