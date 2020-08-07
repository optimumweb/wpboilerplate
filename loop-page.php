<?php /* Start loop */ ?>
<?php while ( have_posts() ) : the_post(); ?>
	<?php wpbp_post_before(); ?>
	<article id="page-<?php the_ID(); ?>" class="page page-<?php echo get_post_field('page_name'); ?>">
		<?php wpbp_post_inside_before(); ?>
        <?php if ( !get_post_meta(get_the_ID(), 'hide_the_title', true) ) : ?>
			<header class="page-header">
				<h1 class="page-title"><?php the_title(); ?></h1>
			</header>
        <?php endif; ?>
		<section class="page-content">
			<?php the_content(); ?>
		</section>
		<footer class="page-footer">
			<?php wp_link_pages(array( 'before' => '<nav class="page-nav"><p>' . __('Pages:', 'wpbp'), 'after' => '</p></nav>' )); ?>
		</footer>
		<div class="clear"></div>
		<?php wpbp_post_inside_after(); ?>
	</article>
	<?php wpbp_post_after(); ?>
<?php endwhile; // End the loop ?>