<?php /* Start loop */ ?>
<?php while (have_posts()) : the_post(); ?>
	<?php wpbp_post_before(); ?>
		<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
		<?php wpbp_post_inside_before(); ?>
			<header>
				<h1 class="entry-title"><?php the_title(); ?></h1>
				<div class="entry-meta">
					<time class="updated" datetime="<?php the_time('c'); ?>" pubdate><?php printf(__('Posted on %s at %s', 'wpbp'), get_the_time(__('l, F jS, Y', 'wpbp')), get_the_time()); ?></time>
					<span class="byline author vcard"><?php _e('by', 'wpbp'); ?> <?php the_author_posts_link(); ?></span>
				</div>
			</header>
			<div class="entry-content">
				<?php the_content(); ?>
			</div>
			<div class="clear"></div>
			<footer>
				<?php wp_link_pages(array('before' => '<nav id="page-nav"><p>' . __('Pages:', 'wpbp'), 'after' => '</p></nav>' )); ?>
				<p class="entry-tags"><?php the_tags(); ?></p>
				<div class="entry-author vcard">
					<h3 class="fn"><?php the_author_meta('display_name'); ?></h3>
					<div class="description">
						<?php the_author_meta('description'); ?>
					</div>
					<ul class="inline-list">
						<?php if ( strlen( get_the_author_meta('user_url') ) != 0 ) : ?>
						<li><a class="url" href="<?php the_author_meta('user_url'); ?>" target="_blank"><?php the_author_meta('user_url'); ?></a></li><?php endif; ?>
						<?php if ( strlen( get_the_author_meta('user_email') ) != 0 ) : ?>
						<li><a class="email" href="mailto:<?php the_author_meta('user_email'); ?>"><?php the_author_meta('user_email'); ?></a></li><?php endif; ?>
					</ul>
					<div class="clear"></div>
				</div>
			</footer>
			<div class="clear"></div>
			<hr />
			<?php comments_template(); ?>
			<?php wpbp_post_inside_after(); ?>
		</article>
	<?php wpbp_post_after(); ?>
<?php endwhile; // End the loop ?>