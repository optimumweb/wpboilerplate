<?php

function wpbp_custom_breadcrumb($sep = ' &rarr; ', $before = '', $after = '', $before_item = '', $after_item = '') {

	echo $before;

	echo $before_item . "<a href=\"" . get_option('home') . "\">" . get_bloginfo('name') . "</a>" . $after_item;
	
	if ( !is_home() && !is_front_page() ) {

		global $wp_query;

		$post_ID = $wp_query->post->ID;

		echo $sep;

		if ( is_category() || is_single() ) {

			if ( is_category() ) {
				echo $before_item;
				single_cat_title();
				echo $after_item;
			}

			elseif ( is_single() ) {
				$categories = get_the_category();
				$category = $categories[0];
				echo $before_item . "<a href=\"" . get_category_link( $category->cat_ID ) . "\">" . $category->cat_name . "</a>" . $after_item;
				echo $sep;
				echo $before_item . get_the_title() . $after_item;
			}
		}

		elseif ( is_tag() ) {
			echo $before_item;
			single_tag_title();
			echo $after_item;
		}

		elseif ( is_author() ) {
			echo $before_item;
			single_author_title();
			echo $after_item;
		}

		elseif ( is_search() ) {
			echo $before_item;
			the_search_query();
			echo $after_item;
		}

		elseif ( in_array( get_post_type(), get_post_types('public=1&_builtin=0','names','and') ) ) {
			$post_type = get_post_type_object( get_post_type() );
			echo $before_item . $post_type->labels->name . $after_item;
		}

		elseif ( is_page() ) {

			$page_parents = "";
			$page_parent_ID = $wp_query->post->post_parent;
			while ( $page_parent_ID ) {
				$page_parents = $before_item . "<a href=\"" . get_permalink( $page_parent_ID ) . "\">" . get_the_title( $page_parent_ID ) . "</a>" . $after_item . $sep . $page_parents;
				$page_parent = get_page( $page_parent_ID );
				$page_parent_ID = $page_parent->post_parent;
			}
			echo $page_parents;

			echo $before_item;
			the_title();
			echo $after_item;
		}

	}

	echo $after . "\n";

	var_dump( get_post_type(), get_post_types( array( 'public' => true, '_builtin' => false ), 'names', 'and' ) );
}

?>
