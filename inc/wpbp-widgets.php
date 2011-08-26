<?php

function wpbp_prepare_fields(&$fields, $prefix = 'wpbp-')
{
	foreach ( $fields as $key => $field ) {
		$fields[$key]['id'] = isset($fields[$key]['id']) ? $fields[$key]['id'] : $prefix . $key;
		$fields[$key]['name'] = isset($fields[$key]['name']) ? $fields[$key]['name'] : $prefix . $key;
	}
}

function wpbp_build_form($fields, $current = null)
{
	wpbp_prepare_fields($fields);

	foreach ( $fields as $key => $field ) {

		extract($field);

		echo "<p><label for=\"" . $id . "\">" . __($title, 'wpbp') . "</label> ";

		if ( $type == 'text' ) {
			$value = ( isset($current[$key]) ? $current[$key] : ( isset($defval) ? $defval : "" ) );
			echo "<input type=\"text\" name=\"" . $name . "\" id=\"" . $id . "\" value=\"" . $value . "\" class=\"widefat\" />";
		}

		elseif ( $type == 'dropdown' ) {
			echo "<select name=\"" . $name . "\" id=\"" . $id . "\" class=\"widefat\">";
			foreach ( $options as $optkey => $optval ) {
				$selected = ( isset($current[$key]) && $current[$key] == $optkey ) ? " selected=\"selected\"" : "";
				echo "<option value=\"" . $optkey . "\"" . $selected . ">" . $optval . "</option>";
			}
			echo "</select>";
		}

		echo "</p>" . PHP_EOL;
	}
}

class wpbp_vcard extends WP_Widget {

	function wpbp_vcard() {
		$widget_ops = array('description' => 'Display a vCard');
		parent::WP_Widget(false, __('WPBP: vCard', 'wpbp'), $widget_ops);
	}
   
	function widget($args, $instance) {  
		extract($args);
		extract($instance);

		echo $before_widget;
		if ($title) {
			echo $before_title . $title . $after_title;
		}
  ?>

		<p class="vcard">
			<a class="fn org url" href="<?php echo home_url('/'); ?>"><?php bloginfo('name'); ?></a><br>
			<span class="adr">
			<span class="street-address"><?php echo $street_address; ?></span><br>
			<span class="locality"><?php echo $locality; ?></span>,
			<span class="region"><?php echo $region; ?></span>
			<span class="postal-code"><?php echo $postal_code; ?></span><br>
			</span>
			<span class="tel"><span class="value"><span class="hidden">+1-</span><?php echo $tel; ?></span></span><br>
			<a class="email" href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a>
		</p>        
        
    <?php echo $after_widget;
        
	}
	
	function update($new_instance, $old_instance) {                
		return $new_instance;
	}

	function form($instance) {
	?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title (optional):', 'wpbp'); ?></label>
			<input type="text" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr($instance['title']); ?>" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" />
		</p>       
		<p>
			<label for="<?php echo $this->get_field_id('street_address'); ?>"><?php _e('Street Address:', 'wpbp'); ?></label>
			<input type="text" name="<?php echo $this->get_field_name('street_address'); ?>" value="<?php echo esc_attr($instance['street_address']); ?>" class="widefat" id="<?php echo $this->get_field_id('street_address'); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('locality'); ?>"><?php _e('City/Locality:', 'wpbp'); ?></label>
			<input type="text" name="<?php echo $this->get_field_name('locality'); ?>" value="<?php echo esc_attr($instance['locality']); ?>" class="widefat" id="<?php echo $this->get_field_id('locality'); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('region'); ?>"><?php _e('State/Region:', 'wpbp'); ?></label>
			<input type="text" name="<?php echo $this->get_field_name('region'); ?>" value="<?php echo esc_attr($instance['region']); ?>" class="widefat" id="<?php echo $this->get_field_id('region'); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('postal_code'); ?>"><?php _e('Zipcode/Postal Code:', 'wpbp'); ?></label>
			<input type="text" name="<?php echo $this->get_field_name('postal_code'); ?>" value="<?php echo esc_attr($instance['postal_code']); ?>" class="widefat" id="<?php echo $this->get_field_id('postal_code'); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('tel'); ?>"><?php _e('Telephone:', 'wpbp'); ?></label>
			<input type="text" name="<?php echo $this->get_field_name('tel'); ?>" value="<?php echo esc_attr($instance['tel']); ?>" class="widefat" id="<?php echo $this->get_field_id('tel'); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('email'); ?>"><?php _e('Email:', 'wpbp'); ?></label>
			<input type="text" name="<?php echo $this->get_field_name('email'); ?>" value="<?php echo esc_attr($instance['email']); ?>" class="widefat" id="<?php echo $this->get_field_id('email'); ?>" />
		</p>
	<?php
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
		if ($title) {
			echo $before_title . $title . $after_title;
		}

		global $wp_query;

		$cats = get_categories();
		echo "<ul class=\"cat-list\">";
		foreach($cats as $cat) {
			$cat_name = $cat->slug;
			echo "<li class=\"cat-name" . ( ( is_category() && ( $cat->ID == $wp_query->queried_object->cat_ID ) ) ? " current-menu-item" : "" ) . "\"><a href=\"" . get_category_link( $cat->cat_ID ) . "\">" . $cat->name . "</a>";
			echo "<ul class=\"cat-posts\">";
			$cat_posts = get_posts('numberposts=-1&category_name='.$cat_name);
			foreach($cat_posts as $post) {
				echo "<li class=\"post-link" . ( ( is_single() && ( $post->ID == $wp_query->post->ID ) ) ? " current-menu-item" : "" ) . "\"><a href=\"" . get_permalink($post->ID) . "\">" . get_the_title($post->ID) . "</a></li>";
			}
			echo "</ul></li>";
		}
		echo "</ul>";

		echo $after_widget;

	}

	function update($new_instance, $old_instance) {
		return $new_instance;
	}

	function form($instance) {}
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
		if ($title) {
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
				'defaval' => 'today',
				'required' => true,
				'options' => array(
					'today' => 'Today',
					'3-days' => 'Last 3 days',
					'7-days' => 'Last week',
					'all-time' => 'All-time'
				)
			)
		);
		wpbp_build_form($fields, $instance);
	}
}

register_widget('wpbp_most_popular');

?>