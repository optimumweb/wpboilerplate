<?php

class wpbp_vcard extends WP_Widget {

	function wpbp_vcard() {
		$widget_ops = array('description' => 'Display a vCard');
		parent::WP_Widget(false, __('WPBP: vCard', 'wpbp'), $widget_ops);
	}
   
	function widget($args, $instance) {  
		extract($args);
		extract($instance);

		echo $before_widget;
		if ( isset($title) && strlen($title) > 0 ) {
			echo $before_title . $title . $after_title;
		}
		?>
		<p class="vcard">
			<?php if ( isset( $fn ) && strlen( $fn ) > 0 ) : ?>
				<span class="fn"><?php echo $fn; ?></span><br>
			<?php endif; ?>
			<?php if ( isset( $org ) && strlen( $org ) > 0 ) : ?>
				<?php if ( isset( $org_url ) && strlen( $org_url ) > 0 ) : ?>
					<a class="org url" href="<?php echo $org_url; ?>"><?php echo $org; ?></a><br />
				<?php else : ?>
					<span class="org"><?php echo $org; ?></span><br />
				<?php endif; ?>
			<?php endif; ?>
			<span class="adr">
				<?php if ( isset( $street_address ) && strlen( $street_address ) > 0 ) : ?>
					<span class="street-address"><?php echo $street_address; ?></span><br>
				<?php endif; ?>
				<?php if ( isset( $locality ) && strlen( $locality ) > 0 ) : ?>
					<span class="locality"><?php echo $locality; ?></span>,
				<?php endif; ?>
				<?php if ( isset( $region ) && strlen( $region ) > 0 ) : ?>
					<span class="region"><?php echo $region; ?></span>
				<?php endif; ?>
				<?php if ( isset( $post_code ) && strlen( $postal_code ) > 0 ) : ?>
					<span class="postal-code"><?php echo $postal_code; ?></span><br>
				<?php endif; ?>
			</span>
			<?php if ( isset( $tel ) && strlen( $tel ) > 0 ) : ?>
				<span class="tel"><span class="value"><span class="hidden">+1-</span><?php echo $tel; ?></span></span><br>
			<?php endif; ?>
			<?php if ( isset( $email ) && strlen( $email ) > 0 ) : ?>
				<a class="email" href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a>
			<?php endif; ?>
		</p>        
    <?php echo $after_widget;
        
	}
	
	function update($new_instance, $old_instance) {                
		return $new_instance;
	}

	function form($instance) {
		global $current_user;
		get_currentuserinfo();
		$fields = array(
			'title' => array(
				'id' => $this->get_field_id('title'),
				'name' => $this->get_field_name('title'),
				'title' => 'Title (optional):',
				'type' => 'text',
				'required' => false,
				'defval' => ''
			),
			'fn' => array(
				'id' => $this->get_field_id('fn'),
				'name' => $this->get_field_name('fn'),
				'title' => 'Full Name:',
				'type' => 'text',
				'required' => false,
				'defval' => $current_user->first_name . ' ' . $current_user->last_name
			),
			'org' => array(
				'id' => $this->get_field_id('org'),
				'name' => $this->get_field_name('org'),
				'title' => 'Company:',
				'type' => 'text',
				'required' => false,
				'defval' => get_bloginfo('name')
			),
			'org_url' => array(
				'id' => $this->get_field_id('org_url'),
				'name' => $this->get_field_name('org_url'),
				'title' => 'Company URL:',
				'type' => 'text',
				'required' => false,
				'defval' => get_bloginfo('url')
			),
			'street_address' => array(
				'id' => $this->get_field_id('street_address'),
				'name' => $this->get_field_name('street_address'),
				'title' => 'Street Address:',
				'type' => 'text',
				'required' => false,
				'defval' => ''
			),
			'locality' => array(
				'id' => $this->get_field_id('locality'),
				'name' => $this->get_field_name('locality'),
				'title' => 'City/Locality:',
				'type' => 'text',
				'required' => false,
				'defval' => ''
			),
			'region' => array(
				'id' => $this->get_field_id('region'),
				'name' => $this->get_field_name('region'),
				'title' => 'State/Region:',
				'type' => 'text',
				'required' => false,
				'defval' => ''
			),
			'postal_code' => array(
				'id' => $this->get_field_id('postal_code'),
				'name' => $this->get_field_name('postal_code'),
				'title' => 'Zipcode/Postal Code:',
				'type' => 'text',
				'required' => false,
				'defval' => ''
			),
			'tel' => array(
				'id' => $this->get_field_id('tel'),
				'name' => $this->get_field_name('tel'),
				'title' => 'Telephone:',
				'type' => 'text',
				'required' => false,
				'defval' => ''
			),
			'email' => array(
				'id' => $this->get_field_id('email'),
				'name' => $this->get_field_name('email'),
				'title' => 'Email:',
				'type' => 'text',
				'required' => false,
				'defval' => $current_user->user_email
			)
		);
		wpbp_build_form($fields, $instance);
	}
} 

register_widget('wpbp_vcard');


class wpbp_cat_nav extends WP_Widget {

	function wpbp_cat_nav() {
		$widget_ops = array('description' => 'Display a navigation menu based on categories and posts');
		parent::WP_Widget(false, __('WPBP: Category Navigation', 'wpbp'), $widget_ops);
	}

	function widget($args, $instance) {
		extract($args);
		extract($instance);

		echo $before_widget;
		if ( isset($title) && strlen($title) > 0 ) {
			echo $before_title . $title . $after_title;
		}

		echo "<ul class=\"cat-list\">";

		$cats = get_categories( array(
			'number' => $number_cats,
			'orderby' => $order_cats_by,
			'order' => $cats_order
		) );
		foreach($cats as $cat) {

			$current_menu_item = ( is_category() && ( $cat->cat_ID == get_query_var('cat') ) ) ? " current-menu-item" : "";
			echo "<li class=\"cat-name" . $current_menu_item . "\"><a href=\"" . get_category_link( $cat->cat_ID ) . "\">" . $cat->name . "</a>";
			echo "<ul class=\"cat-posts\">";

			$cat_posts = get_posts( array(
				'numberposts' => $number_posts,
				'category' => $cat->cat_ID,
				'orderby' => $order_posts_by,
				'order' => $posts_order
			) );
			foreach( $cat_posts as $post ) {
				$current_menu_item = ( is_single() && ( $post->ID == get_query_var('page_id') ) ) ? " current-menu-item" : "";
				echo "<li class=\"post-link" . $current_menu_item . "\"><a href=\"" . get_permalink( $post->ID ) . "\">" . $post->post_title . "</a></li>";
			}
			echo "</ul></li>";
		}
		echo "</ul>";

		echo $after_widget;

	}

	function update($new_instance, $old_instance) {
		return $new_instance;
	}

	function form($instance) {
		$fields = array(
			'title' => array(
				'id' => $this->get_field_id('title'),
				'name' => $this->get_field_name('title'),
				'title' => 'Title (optional):',
				'type' => 'text',
				'required' => false,
				'defval' => ''
			),
			'number_cats' => array(
				'id' => $this->get_field_id('number_cats'),
				'name' => $this->get_field_name('number_cats'),
				'title' => 'Maximum number of categories to display:',
				'type' => 'text',
				'defval' => '-1',
				'required' => true
			),
			'order_cats_by' => array(
				'id' => $this->get_field_id('order_cats_by'),
				'name' => $this->get_field_name('order_cats_by'),
				'title' => 'Order categories by:',
				'type' => 'dropdown',
				'required' => true,
				'options' => array(
					'id' => 'ID',
					'name' => 'Name',
					'slug' => 'Slug',
					'count' => 'Count',
					'term_group' => 'Term Group'
				),
				'defval' => 'name'
			),
			'cats_order' => array(
				'id' => $this->get_field_id('cats_order'),
				'name' => $this->get_field_name('cats_order'),
				'title' => 'Order:',
				'type' => 'dropdown',
				'required' => true,
				'options' => array(
					'asc' => 'asc',
					'desc' => 'desc'
				),
				'defval' => 'asc'
			),
			'number_posts' => array(
				'id' => $this->get_field_id('number_posts'),
				'name' => $this->get_field_name('number_posts'),
				'title' => 'Maximum number of posts to display per category:',
				'type' => 'text',
				'defval' => '-1',
				'required' => true
			),
			'order_posts_by' => array(
				'id' => $this->get_field_id('order_posts_by'),
				'name' => $this->get_field_name('order_posts_by'),
				'title' => 'Order posts by:',
				'type' => 'dropdown',
				'required' => true,
				'options' => array(
					'none' => 'No order',
					'ID' => 'ID',
					'author' => 'Author',
					'title' => 'Title',
					'date' => 'Date',
					'modified' => 'Last modified',
					'rand' => 'Random',
					'comment_count' => 'Comment count'
				),
				'defval' => 'date'
			),
			'posts_order' => array(
				'id' => $this->get_field_id('posts_order'),
				'name' => $this->get_field_name('posts_order'),
				'title' => 'Order:',
				'type' => 'dropdown',
				'required' => true,
				'options' => array(
					'asc' => 'asc',
					'desc' => 'desc'
				),
				'defval' => 'asc'
			)
		);
		wpbp_build_form($fields, $instance);
	}
}

register_widget('wpbp_cat_nav');


class wpbp_most_popular extends WP_Widget {

	function wpbp_most_popular() {
		$widget_ops = array('description' => 'Displays the most popular posts based on number of views in the last \'x\' days.');
		parent::WP_Widget(false, __('WPBP: Most Popular', 'wpbp'), $widget_ops);
	}

	function widget($args, $instance) {
		extract($args);
		extract($instance);

		echo $before_widget;
		if ( isset($title) && strlen($title) > 0 ) {
			echo $before_title . $title . $after_title;
		}

		$category = get_query_var('cat');

		echo $after_widget;

	}

	function update($new_instance, $old_instance) {
		return $new_instance;
	}

	function form($instance) {
		$fields = array(
			'title' => array(
				'id' => $this->get_field_id('title'),
				'name' => $this->get_field_name('title'),
				'title' => 'Title:',
				'type' => 'text',
				'defval' => '',
				'required' => false
			),
			'time_range' => array(
				'id' => $this->get_field_id('time_range'),
				'name' => $this->get_field_name('time_range'),
				'title' => 'Time range:',
				'type' => 'dropdown',
				'required' => true,
				'options' => array(
					'today' => 'Today',
					'3-days' => 'Last 3 days',
					'7-days' => 'Last week',
					'all-time' => 'All-time'
				),
				'defval' => 'today'
			),
			'display' => array(
				'id' => $this->get_field_id('display'),
				'name' => $this->get_field_name('display'),
				'title' => 'Display:',
				'type' => 'multi-checkbox',
				'required' => false,
				'options' => array(
					'post_title' => 'Post title',
					'featured_image' => 'Featured image',
					'post_time' => 'Post time',
					'post_author' => 'Post author',
					'post_excerpt' => 'Post excerpt',
					'comment_count' => 'Comment count',
					'view_count' => 'View count'
				),
				'defval' => array('post_title', 'post_time')
			)
		);
		wpbp_build_form($fields, $instance);
	}
}

register_widget('wpbp_most_popular');

?>