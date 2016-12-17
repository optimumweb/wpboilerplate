<?php get_header(); ?>
	<?php wpbp_content_before(); ?>
	<section id="content">
		<div class="<?php wpbp_container_class(); ?>">
			<div class="<?php wpbp_option('main_class'); ?>">
				<?php wpbp_main_before(); ?>
				<section id="main" role="main">
					<?php wpbp_main_inside_before(); ?>
                    <h1 class="page-title">
                        <?php if ( is_day() ) : printf(__('Daily Archives: %s', 'wpbp'), get_the_date()); ?>
                        <?php elseif ( is_month() ) : printf(__('Monthly Archives: %s', 'wpbp'), get_the_date('F Y')); ?>
                        <?php elseif ( is_year() ) : printf(__('Yearly Archives: %s', 'wpbp'), get_the_date('Y')); ?>
                        <?php else : $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy')); echo $term->name; ?>
                        <?php endif; ?>
                    </h1>
                    <?php if ( term_description() ) : ?>
                    	<p class="term-description"><?php echo term_description(); ?></p>
                    <?php endif; ?>
                    <?php wpbp_loop_before(); ?>
                    <?php get_template_part('loop', 'archive'); ?>
                    <?php wpbp_loop_after(); ?>
					<?php wpbp_main_inside_after(); ?>
				</section>
				<?php wpbp_main_after(); ?>
			</div>
			<div class="<?php wpbp_option('sidebar_class'); ?>">
				<?php wpbp_sidebar_before(); ?>
				<aside id="sidebar" role="complementary">
					<?php wpbp_sidebar_inside_before(); ?>
                    <?php get_sidebar(); ?>
					<?php wpbp_sidebar_inside_after(); ?>
				</aside>
				<?php wpbp_sidebar_after(); ?>
			</div>
		</div>
	</section>
	<?php wpbp_content_after(); ?>
<?php get_footer(); ?>