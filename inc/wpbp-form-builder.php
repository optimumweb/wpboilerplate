<?php

function wpbp_prepare_fields( &$fields, $prefix = 'wpbp-' ) {
	foreach ( $fields as $key => $field ) {
		// make sure we have an id and name for each field
		$fields[$key]['id'] = isset( $fields[$key]['id'] ) && strlen( $fields[$key]['id'] ) > 0 ? $fields[$key]['id'] : $prefix . $key;
		$fields[$key]['name'] = isset( $fields[$key]['name'] ) && strlen( $fields[$key]['id'] ) > 0 ? $fields[$key]['name'] : $prefix . $key;

		// defining defval and class as empty strings will fix the empty value bug
		$fields[$key]['defval'] = isset( $fields[$key]['defval'] ) ? $fields[$key]['defval'] : '';
		$fields[$key]['class'] = isset( $fields[$key]['class'] ) ? $fields[$key]['class'] : '';

		// fields are not required by default
		$fields[$key]['required'] = isset( $fields[$key]['required'] ) ? $fields[$key]['defval'] : false;
	}
}

function wpbp_build_form( $fields, $current = null ) {
	wpbp_prepare_fields( $fields );

	foreach ( $fields as $key => $field ) {
		extract( $field );

		echo '<p>';

		switch ( $type ) {

            case 'text':

                echo '<label for="' . $id . '">' . __( $label, 'wpbp' ) . '</label><br />';

                if ( isset( $current[$key] ) ) {
                    $value = $current[$key];
                } elseif ( isset( $defval ) ) {
                    $value = $defval;
                } else {
                    $value = '';
                }

                echo '<input type="text" name="' . $name . '" id="' . $id . '" value="' . esc_attr( $value ) . '" class="' . $class . '" />';

		        break;

            case 'textarea':

                echo '<label for="' . $id . '">' . __( $label, 'wpbp' ) . '</label><br />';

                if ( isset( $current[$key] ) ) {
                    $value = $current[$key];
                } elseif ( isset( $defval ) ) {
                    $value = $defval;
                } else {
                    $value = '';
                }

                echo '<textarea name="' . $name . '" id="' . $id . '" class="' . $class . '">' . esc_attr( $value ) . '</textarea>';

                break;

            case 'dropdown':

                echo '<label for="' . $id . '">' . __( $label, 'wpbp' ) . '</label><br />';

                echo '<select name="' . $name . '" id="' . $id . '" class="' . $class . '">';

                if ( isset( $options ) && is_array( $options ) && count( $options ) > 0 ) {
                    foreach ( $options as $optkey => $optval ) {
                        if ( isset( $current[$key] ) && $current[$key] == $optkey ) {
                            $selected = true;
                        } elseif ( ! isset( $current[$key] ) && $defval == $optkey ) {
                            $selected = true;
                        } else {
                            $selected = false;
                        }

                        $selected_attr = $selected ? ' selected="selected"' : '';

                        echo '<option value="' . $optkey . '"' . $selected_attr . '>' . __( $optval, 'wpbp' ) . '</option>';
                    }
                }

                echo '</select>';

                break;

            case 'checkbox':

                if ( isset( $current[$key] ) && ( $current[$key] == $optkey || $current[$key] == 'on' ) ) {
                    $checked = true;
                } elseif ( ! isset( $current[$key] ) && $defval == 'on' ) {
                    $checked = true;
                } else {
                    $checked = false;
                }

                $checked_attr = $checked ? ' checked="checked"' : '';

                echo '<input type="checkbox" name="' . $name . '" id="' . $id . '" value="on"' . $checked_attr . ' class="' . $class . '" /> ';

                echo '<label for="' . $id . '">' . __( $label, 'wpbp' ) . '</label>';

                break;

            case 'multi-checkbox':

                echo '<label>' . __( $label, 'wpbp' ) . '</label><br />';

                if ( isset( $options ) && is_array( $options ) && count( $options ) > 0 ) {
                    foreach ( $options as $optkey => $optval ) {
                        if ( isset( $current[$key] ) && in_array( $optkey, $current[$key] ) ) {
                            $checked = true;
                        } elseif ( ! isset( $current[$key] ) && ( is_array( $defval ) && in_array( $optkey, $defval ) ) ) {
                            $checked = true;
                        } else {
                            $checked = false;
                        }

                        $checked_attr = $checked ? ' checked="checked"' : '';

                        echo '<input type="checkbox" name="' . $name . '[]" id="' . $id . '-' . $optkey . '" value="' . $optkey . '"' . $checked_attr . ' /> ';

                        echo '<label for="' . $id . '-' . $optkey . '">' . __( $optval, 'wpbp' ) . '</label><br />';
                    }
                }

                echo '<input type="hidden" name="' . $name . '[]" id="' . $id . '-isset" value="isset" class="' . $class . '" />'; // fixes the $defval bug when nothing is checked

                break;

            case 'radio':

                echo '<label>' . __( $label, 'wpbp' ) . '</label><br />';

                if ( isset( $options ) && is_array( $options ) && count( $options ) > 0 ) {
                    foreach ( $options as $optkey => $optval ) {
                        if ( isset( $current[$key] ) && $current[$key] == $optkey ) {
                            $checked = true;
                        } elseif ( ! isset( $current[$key] ) && $defval == $optkey ) {
                            $checked = true;
                        } else {
                            $checked = false;
                        }

                        $checked_attr = $checked ? ' checked="checked"' : '';

                        echo '<input type="radio" name="' . $name . '" id="' . $id . '-' . $optkey . '" value="' . $optkey . '"' . $checked_attr . ' class="' . $class . '" /> ';

                        echo '<label for="' . $id . '-' . $optkey . '">' . __( $optval, 'wpbp' ) . '</label><br />';
                    }
                }

                break;

        }

		echo '</p>' . PHP_EOL;
	}
}
