<?php

define( 'WPBP_META_BOX_PREFIX', 'wpbp-meta-box-' );

$wpbp_meta_boxes = array(
	'seo' => array(
		'key'		=> 'seo',
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

			foreach ( $wpbp_meta_boxes as $wpbp_meta_box ) {

				add_meta_box( WPBP_META_BOX_PREFIX . $wpbp_meta_box['key'], $wpbp_meta_box['title'], $wpbp_meta_box_display_fct, $wpbp_meta_box['page'], $wpbp_meta_box['context'], $wpbp_meta_box['priority'], $wpbp_meta_box );
			}
		}
	}
}

function wpbp_display_meta_box( $post, $wpbp_add_meta_box_args )
{
	$wpbp_meta_box = $wpbp_add_meta_box_args['args'];

	$wpbp_meta_box_key = $wpbp_meta_box['key'];
	$wpbp_meta_box_nonce_name = WPBP_META_BOX_PREFIX . $wpbp_meta_box_key . '-nonce';
	$wpbp_meta_box_nonce_value = wp_create_nonce( basename( __FILE__ ) );
	$wpbp_meta_box_fields = $wpbp_meta_box['fields'];
	$wpbp_meta_box_data = get_post_meta( $post->ID, $wpbp_meta_box_key, false );
?>
<div class="form-wrap">
	<input type="hidden" name="<?php echo $wpbp_meta_box_nonce_name; ?>" value="<?php echo $wpbp_meta_box_nonce_value; ?>" />
	<?php
		foreach ( $wpbp_meta_box_fields as $wpbp_meta_box_field_key => $wpbp_meta_box_field_args ) :
			$wpbp_meta_box_field_id = WPBP_META_BOX_PREFIX . $wpbp_meta_box_key . '-' . $wpbp_meta_box_field_key;
			$wpbp_meta_box_field_name = "[" . $wpbp_meta_box_key . "][" . $wpbp_meta_box_field_key . "]";
			$wpbp_meta_box_field_value = isset( $wpbp_meta_box_data[$wpbp_meta_box_field_key] ) ? $wpbp_meta_box_data[$wpbp_meta_box_field_key] : "";
	?>
	<div class="form-field form-required">
		<label for="<?php echo $wpbp_meta_box_field_id; ?>"><?php echo $wpbp_meta_box_field_args['title']; ?></label>
		<input id="<?php echo $wpbp_meta_box_field_id; ?>" type="text" name="<?php echo $wpbp_meta_box_field_name; ?>" value="<?php echo $wpbp_meta_box_field_value; ?>" />
		<p><?php echo $wpbp_meta_box_field_args['description']; ?></p>
	</div>
	<?php endforeach; ?>
</div>
<?php
}

function wpbp_save_meta( $post_id )
{
	global $wpbp_meta_boxes;

	foreach ( $wpbp_meta_boxes as $wpbp_meta_box ) {

		$wpbp_meta_box_key = $wpbp_meta_box['key'];

		$wpbp_meta_box_nonce_name = WPBP_META_BOX_PREFIX . $wpbp_meta_box_key . '-nonce';

		// verify nonce -- checks that the user has access
		if ( !wp_verify_nonce( $_POST[$wpbp_meta_box_nonce_name], basename( __FILE__ ) ) ) {
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

		//$wpbp_meta_box_data_old = get_post_meta( $post_id, $wpbp_meta_box_key, true );

		$wpbp_meta_box_data_new = $_POST[$wpbp_meta_box_key];
		
		echo "<pre>"; var_dump($wpbp_meta_box_data_new); echo "</pre>\n";

		if ( isset( $wpbp_meta_box_data_new ) ) {
			update_post_meta( $post_id, $wpbp_meta_box_key, $wpbp_meta_box_data_new );
		}
	}
}

add_action( 'admin_menu', 'wpbp_create_meta_boxes' );
add_action( 'save_post', 'wpbp_save_meta' );

?>
