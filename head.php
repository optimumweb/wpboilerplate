<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->

<head>
    
<meta charset="utf-8">

<title><?php wp_title(''); ?></title>

<?php wpbp_stylesheets(); ?>

<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> Feed" href="<?php echo home_url(); ?>/feed/">

<?php wpbp_scripts(); ?>

<?php wp_head(); ?>

<?php wpbp_head(); ?>

</head>

<body <?php $page_slug = $post->post_name; body_class($page_slug); ?>>