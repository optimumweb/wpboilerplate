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

?>
