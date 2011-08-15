<?php

$wpbp_meta_boxes = array(
	'seo' => array(
		'seo-title' => array(
			'title'			=> 'Title',
			'description'	=> ''
		),
		'seo-description' => array(
			'title'			=> 'Meta Description',
			'description'	=> ''
		)
	)
);

function wpbp_create_meta_boxes() {
	global $wpbp_meta_boxes;

	if ( function_exists( 'add_meta_box' ) ) {
		foreach ( $wpbp_meta_boxes as $key => $meta_box ) {
			add_meta_box( $key . '-meta-box', ucfirst( $key ), 'wpbp_display_meta_boxes', 'post', 'normal', 'default' );
		}
	}
}

function wpbp_display_meta_boxes() {
	global $post, $wpbp_meta_boxes;
?>
<div class="form-wrap">
	<?php
	foreach ( $wpbp_meta_boxes as $key => $meta_box ) :
		$data = get_post_meta($post->ID, $key, true);
		foreach ( $meta_box as $name => $info ) :
	?>
	<div class="form-field form-required">
		<label><?php echo $info['title']; ?></label>
		<input type="text" name="[<?php echo $key; ?>][<?php echo $name; ?>]" value="<?php echo isset($data[$name]) ? $data[$name] : ""; ?>" />
		<p><?php echo $info['description']; ?></p>
	</div>
	<?php
		endforeach;
	endforeach;
	?>
</div>
<?php
}

function wpbp_save_meta_boxes($post_id) {
	global $post, $wpbp_meta_boxes;

	foreach ( $wpbp_meta_boxes as $key => $meta_box ) {

		foreach( $meta_box as $name => $info ) {
			$data[$key] = $_POST[$key][$name];
		}

		if ( !current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}

		update_post_meta( $post_id, $key, $data );
	}
}

add_action( 'admin_menu', 'wpbp_create_meta_boxes' );
add_action( 'save_post', 'wpbp_save_meta_boxes' );

?>
