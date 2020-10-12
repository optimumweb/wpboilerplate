<?php

/**
 * WPBP: vCard
 * Displays a vCard.
 */

class wpbp_vcard extends WP_Widget
{

	function __construct()
    {
		$widget_ops = array('description' => 'Displays a vCard');
		parent::WP_Widget(false, __('WPBP: vCard', 'wpbp'), $widget_ops);
	}
   
	function widget($args, $instance)
    {
		extract($args);
		extract($instance);

		echo $before_widget;
		if ( isset($title) && strlen($title) > 0 ) {
			echo $before_title . $title . $after_title;
		}
		?>
		<p class="vcard">
			<?php if ( isset( $photo ) && strlen( $photo ) > 0 ) : ?>
				<img src="<?php echo $photo; ?>" class="photo" alt="<?php echo ( isset( $fn ) && strlen( $fn ) > 0 ) ? $fn :  ""; ?>" /><br />
			<?php endif; ?>
			<?php if ( isset( $fn ) && strlen( $fn ) > 0 ) : ?>
				<span class="fn"><?php echo $fn; ?></span><br />
			<?php endif; ?>
			<?php if ( isset( $job_title ) && strlen( $job_title ) > 0 ) : ?>
				<span class="title"><?php echo $job_title; ?></span><br />
			<?php endif; ?>
			<?php if ( isset( $org ) && strlen( $org ) > 0 ) : ?>
				<?php if ( isset( $org_url ) && strlen( $org_url ) > 0 ) : ?>
					<a class="org url" href="<?php echo $org_url; ?>"><?php echo $org; ?></a><br />
				<?php else : ?>
					<span class="org"><?php echo $org; ?></span><br />
				<?php endif; ?>
				<?php if ( isset( $logo ) && strlen( $logo ) > 0 ) : ?>
					<img src="<?php echo $logo; ?>" class="logo" alt="<?php echo $org; ?>" /><br />
				<?php endif; ?>
			<?php endif; ?>
			<span class="adr">
				<?php if ( isset( $street_address ) && strlen( $street_address ) > 0 ) : ?>
					<span class="street-address"><?php echo $street_address; ?></span><br />
				<?php endif; ?>
				<?php if ( isset( $locality ) && strlen( $locality ) > 0 ) : ?>
					<span class="locality"><?php echo $locality; ?></span /><br />,
				<?php endif; ?>
				<?php if ( isset( $region ) && strlen( $region ) > 0 ) : ?>
					<span class="region"><?php echo $region; ?></span /><br />
				<?php endif; ?>
				<?php if ( isset( $post_code ) && strlen( $postal_code ) > 0 ) : ?>
					<span class="postal-code"><?php echo $postal_code; ?></span><br />
				<?php endif; ?>
			</span>
			<?php if ( isset( $tel ) && strlen( $tel ) > 0 ) : ?>
				<span class="tel"><span class="value"><span class="hidden">+1-</span><?php echo $tel; ?></span></span><br />
			<?php endif; ?>
			<?php if ( isset( $email ) && strlen( $email ) > 0 ) : ?>
				<a class="email" href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a><br />
			<?php endif; ?>
			<?php if ( isset( $note ) && strlen( $note ) > 0 ) : ?>
				<span class="note"><?php echo $note; ?></span>
			<?php endif; ?>
		</p>        
    <?php echo $after_widget;
	}
	
	function update($new_instance, $old_instance)
    {
		return $new_instance;
	}

	function form($instance)
    {
		global $current_user;
		get_currentuserinfo();
		$fields = array(
			'title' => array(
				'id' => $this->get_field_id('title'),
				'name' => $this->get_field_name('title'),
				'label' => 'Title (optional):',
				'type' => 'text',
				'class' => 'widefat'
			),
			'fn' => array(
				'id' => $this->get_field_id('fn'),
				'name' => $this->get_field_name('fn'),
				'label' => 'Full Name:',
				'type' => 'text',
				'defval' => $current_user->first_name . ' ' . $current_user->last_name,
				'class' => 'widefat'
			),
			'job_title' => array(
				'id' => $this->get_field_id('job_title'),
				'name' => $this->get_field_name('job_title'),
				'label' => 'Job Title:',
				'type' => 'text',
				'class' => 'widefat'
			),
			'photo' => array(
				'id' => $this->get_field_id('photo'),
				'name' => $this->get_field_name('photo'),
				'label' => 'Photo URL:',
				'type' => 'text',
				'defval' => $current_user->photo,
				'class' => 'widefat'
			),
			'org' => array(
				'id' => $this->get_field_id('org'),
				'name' => $this->get_field_name('org'),
				'label' => 'Company:',
				'type' => 'text',
				'defval' => get_bloginfo('name'),
				'class' => 'widefat'
			),
			'org_url' => array(
				'id' => $this->get_field_id('org_url'),
				'name' => $this->get_field_name('org_url'),
				'label' => 'Company URL:',
				'type' => 'text',
				'defval' => get_bloginfo('url'),
				'class' => 'widefat'
			),
			'logo' => array(
				'id' => $this->get_field_id('logo'),
				'name' => $this->get_field_name('logo'),
				'label' => 'Logo URL:',
				'type' => 'text',
				'class' => 'widefat'
			),
			'street_address' => array(
				'id' => $this->get_field_id('street_address'),
				'name' => $this->get_field_name('street_address'),
				'label' => 'Street Address:',
				'type' => 'text',
				'class' => 'widefat'
			),
			'locality' => array(
				'id' => $this->get_field_id('locality'),
				'name' => $this->get_field_name('locality'),
				'label' => 'City/Locality:',
				'type' => 'text',
				'class' => 'widefat'
			),
			'region' => array(
				'id' => $this->get_field_id('region'),
				'name' => $this->get_field_name('region'),
				'label' => 'State/Region:',
				'type' => 'text',
				'class' => 'widefat'
			),
			'postal_code' => array(
				'id' => $this->get_field_id('postal_code'),
				'name' => $this->get_field_name('postal_code'),
				'label' => 'Zipcode/Postal Code:',
				'type' => 'text',
				'class' => 'widefat'
			),
			'tel' => array(
				'id' => $this->get_field_id('tel'),
				'name' => $this->get_field_name('tel'),
				'label' => 'Telephone:',
				'type' => 'text',
				'class' => 'widefat'
			),
			'email' => array(
				'id' => $this->get_field_id('email'),
				'name' => $this->get_field_name('email'),
				'label' => 'Email:',
				'type' => 'text',
				'defval' => $current_user->user_email,
				'class' => 'widefat'
			),
			'note' => array(
				'id' => $this->get_field_id('note'),
				'name' => $this->get_field_name('note'),
				'label' => 'Note:',
				'type' => 'textarea',
				'defval' => $current_user->description,
				'class' => 'widefat'
			)
		);
		wpbp_build_form($fields, $instance);
	}
}
register_widget('wpbp_vcard');


/**
 * WPBP: Taxonomy Navigation
 * Displays a navigation menu based on taxonomies and their respective posts.
 */

class wpbp_tax_nav extends WP_Widget
{

	function __construct()
    {
		$widget_ops = array('description' => 'Displays a navigation menu based on taxonomies and their respective posts.');
		parent::WP_Widget(false, __('WPBP: Taxonomy Navigation', 'wpbp'), $widget_ops);
	}

	function widget($args, $instance)
    {
		extract($args);
		extract($instance);

		global $wp_query, $post;

		echo $before_widget;
		if ( isset($title) && strlen($title) > 0 ) {
			echo $before_title . $title . $after_title;
		}

		$current_post_id = $post->ID;

		echo '<ul class="tax-nav-menu menu">';

		$taxs = get_categories( array(
			'taxonomy' => $taxonomy,
			'number' => ( $number_taxs > 0 ) ? $number_taxs : null,
			'orderby' => $order_taxs_by,
			'order' => $taxs_order,
			'hide_empty' => 0
		) );
		
		foreach( $taxs as $tax ) {
			
			if ( is_tax() ) {
				$is_current_tax = ( get_query_var($taxonomy) == $tax->slug );
			}
			elseif ( is_single() ) {
				$post_taxs = wp_get_post_terms($current_post_id, $taxonomy);
				$post_taxs_slugs = array();
				foreach ( $post_taxs as $post_tax ) {
					$post_taxs_slugs[] = $post_tax->slug;
				}
				$is_current_tax = in_array($tax->slug, $post_taxs_slugs);
			}
			else {
				$is_current_tax = false;
			}
			
			echo '<li class="tax-name' . ( $is_current_tax ? ' current-taxonomy-item' : '' ) . '">';
			echo '<a href="' . get_term_link( $tax ) . '">' . $tax->name . '</a>';
			
			if ( $number_posts != 0 ) {
			
				echo '<ul class="tax-posts sub-menu">';
				
				$tmp_query = new WP_Query( array(
					'tax_query' => array(
						array(
							'taxonomy' => $taxonomy,
							'field' => 'id',
							'terms' => $tax->term_id
						)
					)
				) );
				
				while ( $tmp_query->have_posts() ) {
					$tmp_query->the_post();
					$is_current_post = ( is_single() && get_the_ID() == $current_post_id );
					echo '<li class="post-link' . ( $is_current_post ? ' current-menu-item' : '' ) . '"><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
				}
				wp_reset_postdata();
				
				echo '</ul>';
				
			}
			
			echo '</li>';
			
		}
		
		echo '</ul>';

		echo $after_widget;

	}

	function update($new_instance, $old_instance)
    {
		return $new_instance;
	}

	function form($instance)
    {
		$fields = array(
			'title' => array(
				'id' => $this->get_field_id('title'),
				'name' => $this->get_field_name('title'),
				'label' => 'Title (optional):',
				'type' => 'text',
				'class' => 'widefat'
			),
			'taxonomy' => array(
				'id' => $this->get_field_id('taxonomy'),
				'name' => $this->get_field_name('taxonomy'),
				'label' => 'Taxonomy:',
				'type' => 'dropdown',
				'required' => true,
				'options' => get_taxonomies( array( 'public' => true ) ),
				'class' => 'widefat'
			),
			'number_taxs' => array(
				'id' => $this->get_field_id('number_taxs'),
				'name' => $this->get_field_name('number_taxs'),
				'label' => 'Maximum number of taxonomies to display:',
				'type' => 'text',
				'defval' => '-1',
				'required' => true,
				'class' => 'widefat'
			),
			'order_taxs_by' => array(
				'id' => $this->get_field_id('order_taxs_by'),
				'name' => $this->get_field_name('order_taxs_by'),
				'label' => 'Order taxonomies by:',
				'type' => 'dropdown',
				'required' => true,
				'options' => array(
					'id' => 'ID',
					'name' => 'Name',
					'slug' => 'Slug',
					'count' => 'Count',
					'term_group' => 'Term Group'
				),
				'defval' => 'name',
				'class' => 'widefat'
			),
			'taxs_order' => array(
				'id' => $this->get_field_id('taxs_order'),
				'name' => $this->get_field_name('taxs_order'),
				'label' => 'Order:',
				'type' => 'dropdown',
				'required' => true,
				'options' => array(
					'asc' => 'asc',
					'desc' => 'desc'
				),
				'defval' => 'asc',
				'class' => 'widefat'
			),
			'number_posts' => array(
				'id' => $this->get_field_id('number_posts'),
				'name' => $this->get_field_name('number_posts'),
				'label' => 'Maximum number of posts to display per taxonomy:',
				'type' => 'text',
				'defval' => '-1',
				'required' => true,
				'class' => 'widefat'
			),
			'order_posts_by' => array(
				'id' => $this->get_field_id('order_posts_by'),
				'name' => $this->get_field_name('order_posts_by'),
				'label' => 'Order posts by:',
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
				'defval' => 'date',
				'class' => 'widefat'
			),
			'posts_order' => array(
				'id' => $this->get_field_id('posts_order'),
				'name' => $this->get_field_name('posts_order'),
				'label' => 'Order:',
				'type' => 'dropdown',
				'required' => true,
				'options' => array(
					'asc' => 'asc',
					'desc' => 'desc'
				),
				'defval' => 'asc',
				'class' => 'widefat'
			)
		);
		wpbp_build_form($fields, $instance);
	}
}
register_widget('wpbp_tax_nav');


/**
 * WPBP: Latest Posts
 * Displays the latest posts
 */

class wpbp_latest_posts extends WP_Widget
{

    function __construct()
    {
		$widget_ops = array('description' => 'Displays the latest posts');
		parent::WP_Widget(false, __('WPBP: Latest Posts', 'wpbp'), $widget_ops);
	}

	function widget($args, $instance)
    {
		extract($args);
		extract($instance);

		echo $before_widget;
		if ( isset($title) && strlen($title) > 0 ) {
			echo $before_title . $title . $after_title;
		}

		$query_args = array(
			'posts_per_page' => $posts_per_page,
			'order' => 'desc'
		);
        
        global $wp_query;
        $tmp = $wp_query;
        $wp_query = null;
        
		query_posts( $query_args );

		get_template_part('loop', 'latest-posts');
		
		wp_reset_query();
        
        $wp_query = $tmp;

		echo $after_widget;

	}

	function update($new_instance, $old_instance)
    {
		return $new_instance;
	}

	function form($instance)
    {
		$fields = array(
			'title' => array(
				'id' => $this->get_field_id('title'),
				'name' => $this->get_field_name('title'),
				'label' => 'Title:',
				'type' => 'text',
				'class' => 'widefat'
			),
			'posts_per_page' => array(
				'id' => $this->get_field_id('posts_per_page'),
				'name' => $this->get_field_name('posts_per_page'),
				'label' => 'Number of posts to show:',
				'type' => 'text',
				'defval' => 5,
				'class' => 'widefat',
				'required' => true
			)
		);
		wpbp_build_form($fields, $instance);
	}
}
register_widget('wpbp_latest_posts');


/**
 * WPBP: Most Popular
 * Displays the most popular posts based on number of views in the last 'x' days.
 */

class wpbp_most_popular extends WP_Widget
{

	function __construct()
    {
		$widget_ops = array('description' => 'Displays the most popular posts based on number of views in the last \'x\' days.');
		parent::WP_Widget(false, __('WPBP: Most Popular', 'wpbp'), $widget_ops);
	}

	function widget($args, $instance)
    {
		extract($args);
		extract($instance);

		echo $before_widget;
		if ( isset($title) && strlen($title) > 0 ) {
			echo $before_title . $title . $after_title;
		}

		$category = get_query_var('cat');

		$query_args = array(
			'numberposts' => $number_posts,
			'orderby' => 'meta_value_num',
			'meta_key' => 'wpbp_post_views',
			'order' => 'desc'
		);

		list($year, $month, $week, $day) = explode(',', date('Y,m,W,d'));

		if ( $time_range == 'today' ) {
				$query_args['year'] = $year;
				$query_args['monthnum'] = $month;
				$query_args['day'] = $day;
		} elseif ( $time_range == 'this_week' ) {
				$query_args['year'] = $year;
				$query_args['w'] = $week;
		} elseif ( $time_range == 'this_month' ) {
				$query_args['year'] = $year;
				$query_args['monthnum'] = $month;
		} elseif ( $time_range == 'this_year' ) {
				$query_args['year'] = $year;
		} elseif ( $time_range == 'all_time' ) {}

		$posts = new WP_Query( $query_args );

		get_template_part('loop', 'most-popular');
		
		wp_reset_query();

		echo $after_widget;

	}

	function update($new_instance, $old_instance)
    {
		return $new_instance;
	}

	function form($instance)
    {
		$fields = array(
			'title' => array(
				'id' => $this->get_field_id('title'),
				'name' => $this->get_field_name('title'),
				'label' => 'Title:',
				'type' => 'text',
				'class' => 'widefat'
			),
			'number_posts' => array(
				'id' => $this->get_field_id('number_posts'),
				'name' => $this->get_field_name('number_posts'),
				'label' => 'Number of posts to show:',
				'type' => 'text',
				'defval' => 10,
				'class' => 'widefat',
				'required' => true
			),
			'time_range' => array(
				'id' => $this->get_field_id('time_range'),
				'name' => $this->get_field_name('time_range'),
				'label' => 'Time range:',
				'type' => 'dropdown',
				'required' => true,
				'options' => array(
					'today' => 'Today',
					'this_week' => 'This Week',
					'this_month' => 'This Month',
					'this_year' => 'This Year',
					'all_time' => 'Since the beginning of time'
				),
				'defval' => 'this_week',
				'class' => 'widefat'
			),
			'display' => array(
				'id' => $this->get_field_id('display'),
				'name' => $this->get_field_name('display'),
				'label' => 'Display:',
				'type' => 'multi-checkbox',
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


/**
 * WPBP: AdSense Unit
 * Inserts a Google AdSense Ad Unit to your page.
 */
 
 class wpbp_google_adsense_unit extends WP_Widget
 {

	function __construct()
    {
		$widget_ops = array('description' => 'Inserts a Google AdSense Ad Unit to your page.');
		parent::WP_Widget(false, __('WPBP: Google AdSense Unit', 'wpbp'), $widget_ops);
	}

	function widget($args, $instance)
    {
		extract($args);
		extract($instance);

		echo $before_widget;
		if ( isset($title) && strlen($title) > 0 ) {
			echo $before_title . $title . $after_title;
		}
?>
<script><!--
google_ad_client = "<?php echo $google_ad_client; ?>";
google_ad_slot = "<?php echo $google_ad_slot; ?>";
google_ad_width = "<?php echo $google_ad_width; ?>";
google_ad_height = "<?php echo $google_ad_height; ?>";
//-->
</script>
<script src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
<?php

		echo $after_widget;
	}

	function update($new_instance, $old_instance)
    {
		return $new_instance;
	}

	function form($instance)
    {
		$fields = array(
			'title' => array(
				'id' => $this->get_field_id('title'),
				'name' => $this->get_field_name('title'),
				'label' => 'Title:',
				'type' => 'text',
				'class' => 'widefat'
			),
			'google_ad_client' => array(
				'id' => $this->get_field_id('google_ad_client'),
				'name' => $this->get_field_name('google_ad_client'),
				'label' => 'Ad Client:',
				'type' => 'text',
				'class' => 'widefat'
			),
			'google_ad_slot' => array(
				'id' => $this->get_field_id('google_ad_slot'),
				'name' => $this->get_field_name('google_ad_slot'),
				'label' => 'Ad Slot:',
				'type' => 'text',
				'class' => 'widefat'
			),
			'google_ad_width' => array(
				'id' => $this->get_field_id('google_ad_width'),
				'name' => $this->get_field_name('google_ad_width'),
				'label' => 'Ad Width:',
				'type' => 'text',
				'class' => 'widefat'
			),
			'google_ad_height' => array(
				'id' => $this->get_field_id('google_ad_height'),
				'name' => $this->get_field_name('google_ad_height'),
				'label' => 'Ad Height:',
				'type' => 'text',
				'class' => 'widefat'
			)
		);
		wpbp_build_form($fields, $instance);
	}
}
register_widget('wpbp_google_adsense_unit');
