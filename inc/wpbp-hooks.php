<?php 

// header.php
function wpbp_head() { do_action('wpbp_head'); }
function wpbp_stylesheets() { do_action('wpbp_stylesheets'); }
function wpbp_scripts() { do_action('wpbp_scripts'); }
function wpbp_wrap_before() { do_action('wpbp_wrap_before'); }
function wpbp_header_before() { do_action('wpbp_header_before'); }
function wpbp_header_inside() { do_action('wpbp_header_inside'); }
function wpbp_header_after() { do_action('wpbp_header_after'); }
function wpbp_breadcrumb() { do_action('wpbp_breadcrumb'); }

// 404.php, archive.php, front-page.php, index.php, loop-page.php, loop-search.php, loop-single.php, loop.php
// page-custom.php, page-full.php, page-sitemap.php, page-subpages.php, page.php, search.php, single.php
function wpbp_content_before() { do_action('wpbp_content_before'); }
function wpbp_content_after() { do_action('wpbp_content_after'); }
function wpbp_main_before() { do_action('wpbp_main_before'); }
function wpbp_main_after() { do_action('wpbp_main_after'); }
function wpbp_post_before() { do_action('wpbp_post_before'); }
function wpbp_post_after() { do_action('wpbp_post_after'); }
function wpbp_post_inside_before() { do_action('wpbp_post_inside_before'); }
function wpbp_post_inside_after() { do_action('wpbp_post_inside_after'); }
function wpbp_loop_before() { do_action('wpbp_loop_before'); }
function wpbp_loop_after() { do_action('wpbp_loop_after'); }
function wpbp_sidebar_before() { do_action('wpbp_sidebar_before'); }
function wpbp_sidebar_inside_before() { do_action('wpbp_sidebar_inside_before'); }
function wpbp_sidebar_inside_after() { do_action('wpbp_sidebar_inside_after'); }
function wpbp_sidebar_after() { do_action('wpbp_sidebar_after'); }

// footer.php
function wpbp_footer_before() { do_action('wpbp_footer_before'); }
function wpbp_footer_inside() { do_action('wpbp_footer_inside'); }
function wpbp_footer_after() { do_action('wpbp_footer_after'); }
function wpbp_footer() { do_action('wpbp_footer'); }
