<?php

function wpbp_add_contactmethods( $contactmethods )
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
add_filter( 'user_contactmethods', 'wpbp_add_contactmethods', 10, 1);

function wpbp_admin_enqueue_scripts($hook_suffix)
{
	if ($hook_suffix !== 'appearance_page_theme_options')
		return;

	wp_enqueue_style('wpbp-theme-options', get_template_directory_uri() . '/inc/css/theme-options.css');
	wp_enqueue_script('wpbp-theme-options', get_template_directory_uri() . '/inc/js/theme-options.js');
}
add_action('admin_enqueue_scripts', 'wpbp_admin_enqueue_scripts');

function wpbp_theme_options_init()
{
	if (false === wpbp_get_theme_options())
		add_option('wpbp_theme_options', wpbp_get_default_theme_options());

	register_setting(
		'wpbp_options',
		'wpbp_theme_options',
		'wpbp_theme_options_validate'
	);
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
		__('Theme Options', 'wpbp'),
		__('Theme Options', 'wpbp'),
		'edit_theme_options',
		'theme_options',
		'wpbp_theme_options_render_page'
	);

	if (!$theme_page)
		return;
}
add_action('admin_menu', 'wpbp_theme_options_add_page');

function wpbp_admin_bar_render()
{
	global $wp_admin_bar;

	$wp_admin_bar->add_menu(array(
		'parent' => 'appearance',
		'id' => 'theme_options',
		'title' => __('Theme Options', 'wpbp'),
		'href' => admin_url( 'themes.php?page=theme_options')
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
?>
	<script>
		var wpbp_css_frameworks = <?php echo $json; ?>;
	</script>
	<?php
}
add_action('admin_head', 'wpbp_add_frameworks_object_script');

function wpbp_get_default_theme_options()
{
	global $wpbp_css_frameworks;
	$default_framework = '960gs_16';
	$default_framework_settings = $wpbp_css_frameworks[$default_framework];
	$default_theme_options = array(
		'css_framework'			=> $default_framework,
		'container_class'		=> $default_framework_settings['classes']['container'],
		'main_class'			=> $default_framework_settings['classes']['main'],
		'sidebar_class'			=> $default_framework_settings['classes']['sidebar'],
		'google_analytics_id'	=> '',
		'custom_css'			=> '',
        'js_files'              => '',
		'js_plugins'			=>	array(
										'lesscss'   => 1,
										'modernizr' => 1,
										'formalize' => 1,
										'jquery'    => 1,
										'ajax-mail' => 1,
										'jSlider'   => 1,
                                        'cycle'     => 1
									)
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
		<h2><?php printf(__('%s Theme Options', 'wpbp'), get_current_theme()); ?></h2>
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

				<tr valign="top"><th scope="row"><?php _e('JavaScript Plugins', 'wpbp'); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e('JavaScript Plugins', 'wpbp'); ?></span></legend>
							<input type="checkbox" name="wpbp_theme_options[js_plugins][lesscss]" value="1" id="sidebar_class" <?php echo ( $wpbp_options['js_plugins']['lesscss'] == 1 ) ? "checked=\"checked\"" : ""; ?> /> LessCSS<br />
              				<small class="description"><?php _e('LESS extends CSS with dynamic behavior such as variables, mixins, operations and functions.', 'wpbp'); ?></small>
							<br />
							<input type="checkbox" name="wpbp_theme_options[js_plugins][modernizr]" value="1" id="sidebar_class" <?php echo ( $wpbp_options['js_plugins']['modernizr'] == 1 ) ? "checked=\"checked\"" : ""; ?> /> Modernizr<br />
              				<small class="description"><?php _e('Modernizr is an open-source JavaScript library that helps you build the next generation of HTML5 and CSS3-powered websites.', 'wpbp'); ?></small>
							<br />
							<input type="checkbox" name="wpbp_theme_options[js_plugins][jquery]" value="1" id="sidebar_class" <?php echo ( $wpbp_options['js_plugins']['jquery'] == 1 ) ? "checked=\"checked\"" : ""; ?> /> jQuery<br />
              				<small class="description"><?php _e('jQuery is a fast and concise JavaScript Library that simplifies HTML document traversing, event handling, animating, and Ajax interactions for rapid web development.', 'wpbp'); ?></small>
							<br />
							<input type="checkbox" name="wpbp_theme_options[js_plugins][formalize]" value="1" id="sidebar_class" <?php echo ( $wpbp_options['js_plugins']['formalize'] == 1 ) ? "checked=\"checked\"" : ""; ?> /> Formalize<br />
              				<small class="description"><?php _e('Break the cycle of inconsistent form defaults, style forms with impunity! <i>(requires jQuery)</i>', 'wpbp'); ?></small>
							<br />
							<input type="checkbox" name="wpbp_theme_options[js_plugins][ajax-mail]" value="1" id="sidebar_class" <?php echo ( $wpbp_options['js_plugins']['ajax-mail'] == 1 ) ? "checked=\"checked\"" : ""; ?> /> AJAX Mail<br />
              				<small class="description"><?php _e('Send mail easily with this jQuery plugin using AJAX and PHP. <i>(requires jQuery)</i>', 'wpbp'); ?></small>
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
	$output['custom_css'] = ( isset( $input['custom_css'] ) ) ? $input['custom_css'] : null;
	$output['js_plugins']['lesscss'] = ( isset( $input['js_plugins']['lesscss'] ) ) ? $input['js_plugins']['lesscss'] : 0;
	$output['js_plugins']['modernizr'] = ( isset( $input['js_plugins']['modernizr'] ) ) ? $input['js_plugins']['modernizr'] : 0;
	$output['js_plugins']['jquery'] = ( isset( $input['js_plugins']['jquery'] ) ) ? $input['js_plugins']['jquery'] : 0;
	$output['js_plugins']['formalize'] = ( isset( $input['js_plugins']['formalize'] ) && $output['js_plugins']['jquery'] != 0 ) ? $input['js_plugins']['formalize'] : 0;
	$output['js_plugins']['ajax-mail'] = ( isset( $input['js_plugins']['ajax-mail'] )  && $output['js_plugins']['jquery'] != 0 ) ? $input['js_plugins']['ajax-mail'] : 0;

	return apply_filters('wpbp_theme_options_validate', $output, $input, $defaults);
}

?>