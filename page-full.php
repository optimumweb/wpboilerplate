<?php
/*
Template Name: Full Width
*/
get_header(); ?>
<?php wpbp_content_before(); ?>
<section id="content">
    <?php wpbp_main_before(); ?>
    <section id="main" role="main">
        <?php wpbp_main_inside_before(); ?>
        <?php wpbp_loop_before(); ?>
        <?php get_template_part( 'loop', 'page' ); ?>
        <?php wpbp_loop_after(); ?>
        <?php wpbp_main_inside_after(); ?>
    </section>
    <?php wpbp_main_after(); ?>
</section>
<?php wpbp_content_after(); ?>
<?php get_footer(); ?>
