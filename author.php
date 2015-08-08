<?php get_header(); ?>
	<?php wpbp_content_before(); ?>
	<section id="content">
		<div class="container">
			<div class="<?php wpbp_option('main_class'); ?>">
				<?php wpbp_main_before(); ?>
				<section id="main" role="main">
					<?php wpbp_main_inside_before(); ?>
                    <h1 class="page-title">
                        <?php printf( __('Author: %s', 'wpbp'), single_author_title('', false) ); ?>
                    </h1>
                    <div class="author-info">
                        <?php $author = get_author(); ?>
                        <?php if ( $author->google_profile ) : ?>
                        <a href="<?php echo $author->google_profile; ?>" rel="me" target="_blank">
                            <?php printf( __("%s's Google Profile", "wpbp"), $author->display_name ); ?>
                        </a>
                        <?php endif; ?>
                    </div>
                    <?php wpbp_loop_before(); ?>
                    <?php get_template_part('loop', 'author'); ?>
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