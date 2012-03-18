<?php /* Start loop */ ?>
<?php while (have_posts()) : the_post(); ?>
	<?php wpbp_post_before(); ?>
	<?php wpbp_post_inside_before(); ?>
		<h1 class="page-title"><?php the_title(); ?></h1>
		<?php the_content(); ?>
		<?php wp_link_pages(array('before' => '<nav id="page-nav"><p>' . __('Pages:', 'wpbp'), 'after' => '</p></nav>' )); ?>
	<?php wpbp_post_inside_after(); ?>
	<?php wpbp_post_after(); ?>
<?php endwhile; // End the loop ?>