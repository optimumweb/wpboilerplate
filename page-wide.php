<?php
/*
Template Name: Wide
*/
get_header(); ?>
<?php wpbp_content_before(); ?>
<section id="content">
    <div class="<?php wpbp_container_class(); ?>">
        <div class="<?php wpbp_container_class(); ?>">
            <div class="grid_12">
                <?php wpbp_main_before(); ?>
                <section id="main" role="main">
                    <?php wpbp_main_inside_before(); ?>
                    <?php wpbp_loop_before(); ?>
                    <?php get_template_part( 'loop', 'page' ); ?>
                    <?php wpbp_loop_after(); ?>
                    <?php wpbp_main_inside_after(); ?>
                </section>
                <?php wpbp_main_after(); ?>
            </div>
        </div>
    </div>
</section>
<?php wpbp_content_after(); ?>
<?php get_footer(); ?>
