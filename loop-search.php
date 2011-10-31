<?php /* If there are no posts to display, such as an empty archive page */ ?>
<?php if (!have_posts()) : ?>
	<div class="notice">
		<p class="bottom"><?php _e('Sorry, no results were found.', 'wpbp'); ?></p>
	</div>
	<?php get_search_form(); ?>	
<?php endif; ?>

<?php /* Start loop */ ?>
<?php while (have_posts()) : the_post(); ?>
	<?php wpbp_post_before(); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		    <?php wpbp_post_inside_before(); ?>
			<header>
				<h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<div class="entry-meta">
					<time class="updated" datetime="<?php the_time('c'); ?>" pubdate><?php printf(__('Posted on %s at %s', 'wpbp'), get_the_time(__('l, F jS, Y', 'wpbp')), get_the_time()); ?></time>
					<span class="byline author vcard"><?php _e('by', 'wpbp'); ?> <?php the_author_posts_link(); ?></span>
				</div>
			</header>
			<div class="entry-content">
				<?php the_excerpt(); ?>
			</div>
			<div class="clear"></div>
			<footer>
				<?php $tag = get_the_tags(); if (!$tag) { } else { ?><p><?php the_tags(); ?></p><?php } ?>
			</footer>
			<div class="clear"></div>
		    <?php wpbp_post_inside_after(); ?>
		</article>
	<?php wpbp_post_after(); ?>
<?php endwhile; // End the loop ?>

<?php /* Display navigation to next/previous pages when applicable */ ?>
<?php if ($wp_query->max_num_pages > 1) : ?>
	<nav id="post-nav">
		<div class="post-previous"><?php next_posts_link( __( '&larr; Older posts', 'wpbp' ) ); ?></div>
		<div class="post-next"><?php previous_posts_link( __( 'Newer posts &rarr;', 'wpbp' ) ); ?></div>
	</nav>
<?php endif; ?>