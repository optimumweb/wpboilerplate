<?php

function wpbp_custom_breadcrumb($before = '', $after = '') {

	echo $before;

	echo "<a href=\"" . get_option('home') . "\">" . get_bloginfo('name') . "</a>";
	
	if ( !is_home() && !is_front_page() ) {

		global $wp_query;

		$post_ID = $wp_query->post->ID;

		echo " &rarr; ";

		if ( is_category() || is_single() ) {

			if ( is_category() ) {
				single_cat_title();
			}

			if ( is_single() ) {
				$categories = get_the_category();
				$category = $categories[0];
				echo "<a href=\"" . get_category_link( $category->cat_ID ) . "\">" . $category->cat_name . "</a>";
				echo " &rarr; " . get_the_title();
			}
		}

		elseif ( is_tag() ) {
			single_tag_title();
		}

		elseif ( is_author() ) {
			single_author_title();
		}

		elseif ( is_search() ) {
			the_search_query();
		}

		elseif ( is_page() ) {

			$page_parents = "";
			$page_parent_ID = $wp_query->post->post_parent;
			while ( $page_parent_ID ) {
				$page_parents = "<a href=\"" . get_permalink( $page_parent_ID ) . "\">" . get_the_title( $page_parent_ID ) . "</a> &rarr; " . $page_parents;
				$page_parent = get_page( $page_parent_ID );
				$page_parent_ID = $page_parent->post_parent;
			}
			echo $page_parents;
			
			the_title();
		}

	}

	echo $after . "\n";
}

?>
