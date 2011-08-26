<?php

function wpbp_prepare_fields(&$fields, $prefix = 'wpbp-')
{
	foreach ( $fields as $key => $field ) {
		// make sure we have an id and name for each field
		$fields[$key]['id'] = ( isset($fields[$key]['id']) && strlen($fields[$key]['id']) > 0 ) ? $fields[$key]['id'] : $prefix . $key;
		$fields[$key]['name'] = ( isset($fields[$key]['name']) && strlen($fields[$key]['id']) > 0 ) ? $fields[$key]['name'] : $prefix . $key;
	}
}

function wpbp_build_form($fields, $current = null)
{
	wpbp_prepare_fields($fields);

	foreach ( $fields as $key => $field ) {

		extract($field);

		echo "<p>";

		// input: text
		if ( $type == 'text' ) {
			echo "<label for=\"" . $id . "\">" . __($title, 'wpbp') . "</label><br />";
			$value = ( isset($current[$key]) ? $current[$key] : ( isset($defval) ? $defval : "" ) );
			echo "<input type=\"text\" name=\"" . $name . "\" id=\"" . $id . "\" value=\"" . $value . "\" class=\"widefat\" />";
		}

		// select
		elseif ( $type == 'dropdown' ) {
			echo "<label for=\"" . $id . "\">" . __($title, 'wpbp') . "</label><br />";
			echo "<select name=\"" . $name . "\" id=\"" . $id . "\" class=\"widefat\">";
			foreach ( $options as $optkey => $optval ) {
				$selected = ( isset($current[$key]) && $current[$key] == $optkey ) ? " selected=\"selected\"" : "";
				echo "<option value=\"" . $optkey . "\"" . $selected . ">" . __($optval, 'wpbp') . "</option>";
			}
			echo "</select>";
		}

		// checkbox
		elseif ( $type == 'checkbox' ) {
			$checked = ( isset($current[$key]) && $current[$key] == 'on' ) ? " checked=\"checked\"" : "";
			echo "<input type=\"checkbox\" name=\"" . $name . "\" id=\"" . $id . "\" value=\"on\"" . $checked . " /> ";
			echo "<label for=\"" . $id . "\">" . __($title, 'wpbp') . "</label>";
		}

		// multi-checkbox
		elseif ( $type == 'multi-checkbox' ) {
			echo "<label>" . __($title, 'wpbp') . "</label><br />";
			foreach ( $options as $optkey => $optval ) {
				$checked = ( isset($current[$key][$optkey]) && $current[$key][$optkey] == 'on' ) ? " checked=\"checked\"" : "";
				echo "<input type=\"checkbox\" name=\"" . $name . "[" . $optkey . "]\" id=\"" . $id . "-" . $optkey . "\" value=\"on\"" . $checked . " /> ";
				echo "<label for=\"" . $id . "-" . $optkey . "\">" . __($optval, 'wpbp') . "</label><br />";
			}
		}

		// radio
		elseif ( $type == 'radio' ) {
			echo "<label>" . __($title, 'wpbp') . "</label><br />";
			foreach ( $options as $optkey => $optval ) {
				$checked = ( isset($current[$key]) && $current[$key] == $optkey ) ? " checked=\"checked\"" : "";
				echo "<input type=\"radio\" name=\"" . $name . "\" id=\"" . $id . "-" . $optkey . "\" value=\"" . $optkey . "\"" . $checked . " /> ";
				echo "<label for=\"" . $id . "-" . $optkey . "\">" . __($optval, 'wpbp') . "</label><br />";
			}
		}

		echo "</p>" . PHP_EOL;
	}
}

?>
