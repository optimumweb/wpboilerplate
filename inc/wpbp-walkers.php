<?php

class Description_Walker extends Walker_Nav_Menu {

    function start_el( &$output, $item, $depth = 0, $args = array() ) {
        global $wp_query;
        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

        $class_names = $value = '';

        $classes = empty( $item->classes ) ? array() : (array) $item->classes;

        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
        $class_names = ' class="'. esc_attr( $class_names ) . '"';

        $output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';

        $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) . '"' : '';
        $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target )     . '"' : '';
        $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn )        . '"' : '';
        $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url )        . '"' : '';

        $separator   = !empty( $item->description ) ? '<span class="separator"> - </span>' : '';
        $description = !empty( $item->description ) ? '<span class="description">'.esc_attr( $item->description ).'</span>' : '';

        $item_output = $args->before;
        $item_output .= '<a' . $attributes . '>';
        $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID );
        $item_output .= $separator . $description . $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;

        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }

}

class Minimal_Walker extends Walker_Nav_Menu {

    function start_lvl( &$output, $depth = 0, $args = array() ) {
        return "";
    }

    function end_lvl( &$output, $depth = 0, $args = array() ) {
        return "";
    }

    function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        global $wp_query;
        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

        $class_names = $value = '';

        $classes = empty( $item->classes ) ? array() : (array) $item->classes;

        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
        $class_names = ' class="' . esc_attr( $class_names ) . '"';

        $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) . '"' : '';
        $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target )     . '"' : '';
        $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn )        . '"' : '';
        $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url )        . '"' : '';

        $item_output .= '<a' . $class_names . ' ' . $attributes . '>';
        $item_output .= $args->link_before;
        $item_output .= apply_filters( 'the_title', $item->title, $item->ID );
        $item_output .= $args->link_after;
        $item_output .= '</a>';

        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );

        return $output;
    }

    function end_el( &$output, $element, $depth = 0, $args = array() ) {
        return "";
    }
}

class Capability_Based_Walker extends Walker_Nav_Menu
{
	function start_el( &$output, $item, $depth = 0, $args = array() ) {
		$required_capability = $item->xfn;

		if ( !empty($required_capability) && !current_user_can($required_capability) ) return;

		global $wp_query;

		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$class_names = $value = '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
		$class_names = ' class="'. esc_attr( $class_names ) . '"';

		$output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';

		$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) . '"' : '';
		$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target )     . '"' : '';
		$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url )        . '"' : '';

		$item_output = $args->before;
		$item_output .= '<a'. $attributes .'>';
		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID );
		$item_output .= $args->link_after;
		$item_output .= '</a>';
		$item_output .= $args->after;

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	function end_el( &$output, $item, $depth = 0, $args = array() ) {
		$required_capability = $item->xfn;
		if ( strlen( $required_capability ) >= 1 && !current_user_can( $required_capability ) ) return;

		$output .= "</li>\n";
	}
}

class Sister_Nav_Walker extends Walker_Nav_Menu {
    var $found_parents = array();
    var $found_children = array();

    function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        global $wp_query;

        // this only works for second level sub navigations
        $parent_item_id = 0;

        $class_names = $value = '';

        $classes = empty($item->classes) ? array() : (array) $item->classes;

        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter( $classes ), $item));
        $class_names = ' class="' . esc_attr($class_names) . '"';

        if ( $item->menu_item_parent == 0 && strpos($class_names, 'current-menu-parent') ) {
            $output.= "<li>";
        }

        // checks if the current element is in the current selection
        if ( strpos($class_names, 'current-menu-item') || strpos($class_names, 'current-menu-parent') || strpos($class_names, 'current-menu-ancestor') || ( is_array($this->found_parents) && in_array($item->menu_item_parent, $this->found_parents) ) ) {

            // keep track of all selected parents
            $this->found_parents[] = $item->ID;

            $output .= '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';

            $attributes  = ! empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) .'"' : '';
            $attributes .= ! empty($item->target)     ? ' target="' . esc_attr($item->target    ) .'"' : '';
            $attributes .= ! empty($item->xfn)        ? ' rel="'    . esc_attr($item->xfn       ) .'"' : '';
            $attributes .= ! empty($item->url)        ? ' href="'   . esc_attr($item->url       ) .'"' : '';

            $item_output = $args->before;
            $item_output .= '<a'. $attributes .'>';
            $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
            $item_output .= '</a>';
            $item_output .= $args->after;

            $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);

        }
    }

    function end_el( &$output, $item, $depth = 0, $args = array() ) {
        // closes only the opened li
        if ( is_array($this->found_parents) && in_array($item->ID, $this->found_parents) ) {
            $output .= "</li>";
        }
    }

    function end_lvl( &$output, $depth = 0, $args = array() ) {
        $output = trim($output);
        // if the sub-menu is empty, strip the opening tag, else closes it
        if ( substr($output, -21) == '<ul class="sub-menu">' ) {
            $output = substr($output, 0, strlen($output)-21);
        } else {
            $output .= "</ul>";
        }
    }
}
