<?php

$wpbp_meta_box_prefix = 'wpbp-meta-box-';

$wpbp_meta_boxes = array(
	'seo' => array(
		'title'		=> 'SEO',
		'page'		=> 'post',
		'context'	=> 'normal',
		'priority'	=> 'high',
		'fields'	=> array(
			'title' => array(
				'title'			=> 'Title',
				'description'	=> ''
			),
			'description' => array(
				'title'			=> 'Meta Description',
				'description'	=> ''
			)
		)
	)
);

function wpbp_create_meta_boxes()
{
	global $wpbp_meta_boxes;

	if ( function_exists( 'add_meta_box' ) ) {

		$wpbp_meta_box_display_fct = 'wpbp_display_meta_box';

		if ( function_exists( $wpbp_meta_box_display_fct ) ) {

			foreach ( $wpbp_meta_boxes as $meta_box_key => $meta_box ) {

				add_meta_box( $wpbp_meta_box_prefix . $meta_box_key, $meta_box['title'], $wpbp_meta_box_display_fct, $meta_box['page'], $meta_box['context'], $meta_box['priority'], $meta_box );
			}
		}
	}
}

function wpbp_display_meta_box( $meta_box )
{
	// this callback function has access to $post and $meta_box variables

	$meta_box_key = key($meta_box);
	$meta_box_nonce_name = $wpbp_meta_box_prefix . $meta_box_key . '-nonce';
	$meta_box_nonce_value = wp_create_nonce( basename( __FILE__ ) );
	$meta_box_fields = $meta_box['fields'];
	$meta_box_data = get_post_meta( $post->ID, $meta_box_key, true );
?>
<div class="form-wrap">
	<input type="hidden" name="<?php echo $meta_box_nonce_name; ?>" value="<?php echo $meta_box_nonce_value; ?>" />
	<?php
		foreach ( $meta_box_fields as $id => $args ) :
			$meta_box_field_id = $wpbp_meta_box_prefix . $meta_box_key . '-' . $id;
			$meta_box_field_name = "[" . $meta_box_key . "][" . $id . "]";
			$meta_box_field_value = isset( $meta_box_data[$id] ) ? $meta_box_data[$id] : "";
	?>
	<div class="form-field form-required">
		<label for="<?php echo $meta_box_field_id; ?>"><?php echo $args['title']; ?></label>
		<input id="<?php echo $meta_box_field_id; ?>" type="text" name="<?php echo $meta_box_field_name; ?>" value="<?php echo $meta_box_field_value; ?>" />
		<p><?php echo $args['description']; ?></p>
	</div>
	<?php endforeach; ?>
</div>
<?php
}

function wpbp_save_meta( $post_id )
{
	global $wpbp_meta_boxes;

	foreach ( $wpbp_meta_boxes as $meta_box_key => $meta_box ) {

		$meta_box_nonce_name = $wpbp_meta_box_prefix . $meta_box_key . '-nonce';

		// verify nonce -- checks that the user has access
		if ( !wp_verify_nonce( $_POST[$meta_box_nonce_name], basename( __FILE__ ) ) ) {
			return $post_id;
		}

		// check autosave
		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
			return $post_id;
		}

		// check permissions
		if ( $_POST['post_type'] == 'page' ) {
			if ( !current_user_can( 'edit_page', $post_id ) ) {
				return $post_id;
			}
		} elseif ( !current_user_can( 'edit_post', $post_id) ) {
			return $post_id;
		}

		$new_meta_box_data = $_POST[$meta_box_key];

		if ( isset( $new_meta_box_data ) ) {
			update_post_meta( $post_id, $meta_box_key, $new_meta_box_data );
		}
	}
}

add_action( 'admin_menu', 'wpbp_create_meta_boxes' );
add_action( 'save_post', 'wpbp_save_meta' );

?>
