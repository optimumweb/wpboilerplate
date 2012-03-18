<?php /* Start loop */ ?>
<?php while (have_posts()) : the_post(); ?>
	<?php wpbp_post_before(); ?>
	<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
		<?php wpbp_post_inside_before(); ?>
		<header>
			<h1 class="entry-title">
				<?php the_title(); ?>
			</h1>
			<div class="entry-meta">
				<time class="updated" datetime="<?php the_time('c'); ?>" pubdate><?php printf(__('Posted on %s at %s', 'wpbp'), get_the_time(__('l, F jS, Y', 'wpbp')), get_the_time()); ?></time>
				<span class="byline author vcard"><?php _e('by', 'wpbp'); ?> <?php the_author_posts_link(); ?></span>
			</div>
		</header>
		<div class="entry-content">
			<?php the_content(); ?>
		</div>
		<footer>
			<?php wp_link_pages(array('before' => '<nav id="page-nav"><p>' . __('Pages:', 'wpbp'), 'after' => '</p></nav>' )); ?>
			<p class="entry-tags"><?php the_tags(); ?></p>
		</footer>
		<section class="entry-comments">
			<?php comments_template(); ?>
		</section>
		<?php wpbp_post_inside_after(); ?>
	</article>
	<?php wpbp_post_after(); ?>
<?php endwhile; // End the loop ?>