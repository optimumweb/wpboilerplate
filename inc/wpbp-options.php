<?php

function wpbp_add_contactmethods($contactmethods)
{
	// Add Social Profile
	$contactmethods['google_profile'] = 'Google Profile URL';
	$contactmethods['facebook_profile'] = 'Facebook Profile URL';
	$contactmethods['twitter_profile'] = 'Twitter Profile URL';
	$contactmethods['linkedin_profile'] = 'LinkedIn Profile URL';
	// Add Photo
	$contactmethods['photo'] = 'Photo URL';
	return $contactmethods;
}
add_filter('user_contactmethods', 'wpbp_add_contactmethods', 10, 1);

function wpbp_admin_enqueue_scripts($hook_suffix)
{
	if ( $hook_suffix !== 'appearance_page_theme_options' )
		return;

	wp_enqueue_style('wpbp-theme-options', get_template_directory_uri() . '/inc/css/theme-options.css');
	wp_enqueue_script('wpbp-theme-options', get_template_directory_uri() . '/inc/js/theme-options.js');
}
add_action('admin_enqueue_scripts', 'wpbp_admin_enqueue_scripts');

function wpbp_theme_options_init()
{
	if ( wpbp_get_theme_options() === false ) {
		add_option('wpbp_theme_options', wpbp_get_default_theme_options());
	}
	register_setting('wpbp_options', 'wpbp_theme_options', 'wpbp_theme_options_validate');
}
add_action('admin_init', 'wpbp_theme_options_init');

function wpbp_option_page_capability($capability)
{
	return 'edit_theme_options';
}
add_filter('option_page_capability_wpbp_options', 'wpbp_option_page_capability');

function wpbp_theme_options_add_page()
{
	$theme_page = add_theme_page(
		__('Boilerplate Options', 'wpbp'),
		__('Boilerplate Options', 'wpbp'),
		'edit_wpbp_theme_options',
		'wpbp_theme_options',
		'wpbp_theme_options_render_page'
	);

	if (!$theme_page) return;
}
add_action('admin_menu', 'wpbp_theme_options_add_page');

function wpbp_admin_bar_render()
{
	global $wp_admin_bar;

	$wp_admin_bar->add_menu(array(
		'parent' => 'appearance',
		'id' => 'wpbp_theme_options',
		'title' => __('Boilerplate Options', 'wpbp'),
		'href' => admin_url('themes.php?page=wpbp_options')
	));
}
add_action('wp_before_admin_bar_render', 'wpbp_admin_bar_render');

global $wpbp_css_frameworks;
$wpbp_css_frameworks = array(
	'960gs_12' => array(
		'name'		=> '960gs_12',
		'label'		=> __('960gs (12 cols)', 'wpbp'),
		'classes'	=> array(
			'container'	=> 'container_12',
			'main'		=> 'grid_9',
			'sidebar'	=> 'grid_3'
		)
	),
	'960gs_16' => array(
		'name'		=> '960gs_16',
		'label'		=> __('960gs (16 cols)', 'wpbp'),
		'classes'	=> array(
			'container'	=> 'container_16',
			'main'		=> 'grid_11',
			'sidebar'	=> 'grid_5'
		)
	)
);

// Write the above array of CSS frameworks into a script tag
function wpbp_add_frameworks_object_script()
{
	global $wpbp_css_frameworks;
	$json = json_encode($wpbp_css_frameworks);
    echo "<script> var wpbp_css_frameworks = " . $json . "; </script>\n";
}
add_action('admin_head', 'wpbp_add_frameworks_object_script');

function wpbp_get_default_theme_options()
{
	global $wpbp_css_frameworks;
	$default_framework = '960gs_12';
	$default_framework_settings = $wpbp_css_frameworks[$default_framework];
	$default_theme_options = array(
		'css_framework'         => $default_framework,
		'container_class'       => $default_framework_settings['classes']['container'],
		'main_class'            => $default_framework_settings['classes']['main'],
		'sidebar_class'         => $default_framework_settings['classes']['sidebar'],
		'google_analytics_id'   => '',
        'css_files'             => '',
		'custom_css'            => '',
        'js_files'              => '',
        'favicon'               => ''
	);

	return apply_filters('wpbp_default_theme_options', $default_theme_options);
}

function wpbp_get_theme_options()
{
	return get_option('wpbp_theme_options', wpbp_get_default_theme_options());
}

function wpbp_theme_options_render_page()
{
	global $wpbp_css_frameworks;
	?>
	<div class="wrap">
		<?php screen_icon(); ?>
		<h2><?php _e('Boilerplate Options', 'wpbp'); ?></h2>
		<?php settings_errors(); ?>

		<form method="post" action="options.php">
			<?php
				settings_fields('wpbp_options');
				$wpbp_options = wpbp_get_theme_options();
				$wpbp_default_options = wpbp_get_default_theme_options();
			?>

			<table class="form-table">

				<tr valign="top"><th scope="row"><?php _e('Google Analytics ID', 'wpbp'); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e('Google Analytics ID', 'wpbp'); ?></span></legend>
							<input type="text" name="wpbp_theme_options[google_analytics_id]" id="google_analytics_id" value="<?php echo esc_attr($wpbp_options['google_analytics_id']); ?>" />
							<br />
							<small class="description"><?php printf(__('Enter your UA-XXXXX-X ID', 'wpbp')); ?></small>
						</fieldset>
					</td>
				</tr>

				<tr valign="top" class="radio-option"><th scope="row"><?php _e('CSS Grid Framework', 'wpbp'); ?></th>
					<td>
						<fieldset class="wpbp_css_frameworks"><legend class="screen-reader-text"><span><?php _e('CSS Grid Framework', 'wpbp'); ?></span></legend>
							<select name="wpbp_theme_options[css_framework]" id="wpbp_theme_options[css_framework]">
							<?php foreach ($wpbp_css_frameworks as $css_framework) { ?>
								<option value="<?php echo esc_attr($css_framework['name']); ?>" <?php selected($wpbp_options['css_framework'], $css_framework['name']); ?>><?php echo $css_framework['label']; ?></option>
							<?php } ?>
							</select>
						</fieldset>
					</td>
				</tr>

				<tr valign="top"><th scope="row"><?php _e('#main CSS Classes', 'wpbp'); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e('#main CSS Classes', 'wpbp'); ?></span></legend>
							<input type="text" name="wpbp_theme_options[main_class]" id="main_class" value="<?php echo esc_attr($wpbp_options['main_class']); ?>" class="regular-text" />
							<br />
              				<small class="description"><?php _e('Default:', 'wpbp'); ?> <span><?php echo $wpbp_default_options['main_class']; ?></span></small>
						</fieldset>
					</td>
				</tr>

				<tr valign="top"><th scope="row"><?php _e('#sidebar CSS Classes', 'wpbp'); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e('#sidebar CSS Classes', 'wpbp'); ?></span></legend>
							<input type="text" name="wpbp_theme_options[sidebar_class]" id="sidebar_class" value="<?php echo esc_attr($wpbp_options['sidebar_class']); ?>" class="regular-text" />
							<br />
              				<small class="description"><?php _e('Default:', 'wpbp'); ?> <span><?php echo $wpbp_default_options['sidebar_class']; ?></span></small>
						</fieldset>
					</td>
				</tr>
                
                <tr valign="top"><th scope="row"><?php _e('CSS files', 'wpbp'); ?></th>
        			<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e('CSS files', 'wpbp'); ?></span></legend>
							<textarea name="wpbp_theme_options[css_files]" id="css_files" cols="94" rows="10"><?php echo esc_attr($wpbp_options['css_files']); ?></textarea>
							<br />
							<small class="description"><?php printf(__('Enter CSS files URL you want to include. One file per line.', 'wpbp')); ?></small>
						</fieldset>
					</td>
				</tr>

				<tr valign="top"><th scope="row"><?php _e('Custom CSS', 'wpbp'); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e('Custom CSS', 'wpbp'); ?></span></legend>
							<textarea name="wpbp_theme_options[custom_css]" id="custom_css" cols="94" rows="10"><?php echo esc_attr($wpbp_options['custom_css']); ?></textarea>
							<br />
							<small class="description"><?php printf(__('Enter custom CSS for this site', 'wpbp')); ?></small>
						</fieldset>
					</td>
				</tr>
                
                <tr valign="top"><th scope="row"><?php _e('Javascript files', 'wpbp'); ?></th>
    				<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e('Javascript files', 'wpbp'); ?></span></legend>
							<textarea name="wpbp_theme_options[js_files]" id="js_files" cols="94" rows="10"><?php echo esc_attr($wpbp_options['js_files']); ?></textarea>
							<br />
							<small class="description"><?php printf(__('Enter javascript files URL you want to include. One file per line.', 'wpbp')); ?></small>
						</fieldset>
					</td>
				</tr>
				
				<tr valign="top"><th scope="row"><?php _e('Favicon', 'wpbp'); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e('Favicon', 'wpbp'); ?></span></legend>
							<input type="text" name="wpbp_theme_options[favicon]" id="favicon" value="<?php echo esc_attr($wpbp_options['favicon']); ?>" />
							<br />
							<small class="description"><?php printf(__('Enter your favicon URL', 'wpbp')); ?></small>
						</fieldset>
					</td>
				</tr>

			</table>

			<?php submit_button(); ?>
		</form>
	</div>

	<?php
}

function wpbp_theme_options_validate($input)
{
	global $wpbp_css_frameworks;
	$output = $defaults = wpbp_get_default_theme_options();

	if (isset($input['css_framework']) && array_key_exists($input['css_framework'], $wpbp_css_frameworks))
		$output['css_framework'] = $input['css_framework'];

	// set the value of the main container class depending on the selected grid framework
	$output['container_class'] = $wpbp_css_frameworks[$output['css_framework']]['classes']['container'];

	$output['main_class'] = ( isset( $input['main_class'] ) ) ? $input['main_class'] : null;
	$output['sidebar_class'] = ( isset( $input['sidebar_class'] ) ) ? $input['sidebar_class'] : null;
	$output['google_analytics_id'] = ( isset( $input['google_analytics_id'] ) ) ? $input['google_analytics_id'] : null;
    $output['css_files'] = ( isset( $input['css_files'] ) ) ? $input['css_files'] : null;
	$output['custom_css'] = ( isset( $input['custom_css'] ) ) ? $input['custom_css'] : null;
    $output['js_files'] = ( isset( $input['js_files'] ) ) ? $input['js_files'] : null;
    $output['favicon'] = ( isset( $input['favicon'] ) ) ? $input['favicon'] : null;

	return apply_filters('wpbp_theme_options_validate', $output, $input, $defaults);
}
