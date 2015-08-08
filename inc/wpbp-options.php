<?php

function wpbp_add_contactmethods($contactmethods)
{
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

function wpbp_get_option($option)
{
	global $wpbp_options;
	return is_array( $wpbp_options ) && isset($wpbp_options[$option]) ? $wpbp_options[$option] : "";
}

function wpbp_option($option)
{
	echo wpbp_get_option($option);
}

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
		'manage_options',
		'wpbp_theme_options',
		'wpbp_theme_options_render_page'
	);

	if ( !$theme_page ) return;
}
add_action('admin_menu', 'wpbp_theme_options_add_page');

function wpbp_admin_bar_render()
{
	global $wp_admin_bar;

	$wp_admin_bar->add_menu(array(
		'parent' => 'appearance',
		'id'     => 'wpbp_theme_options',
		'title'  => __('Boilerplate Options', 'wpbp'),
		'href'   => admin_url('themes.php?page=wpbp_options')
	));
}
add_action('wp_before_admin_bar_render', 'wpbp_admin_bar_render');

function wpbp_get_default_theme_options()
{
	$default_theme_options = array(
        'google_analytics_id'   => '',
        'google_conversion_id'  => '',
        'google_tag_manager_id' => '',
        'optimizely_project_id' => '',
		'main_class'            => 'grid_9',
		'sidebar_class'         => 'grid_3',
        'fluid'                 => 'yes',
		'responsive'            => 'responsive',
        'css_files'             => '',
		'custom_css'            => '',
        'js_files'              => '',
        'custom_js'             => '',
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

                <tr valign="top"><th scope="row"><?php _e('Google Remarketing Tag ID', 'wpbp'); ?></th>
                    <td>
                        <fieldset><legend class="screen-reader-text"><span><?php _e('Google Remarketing Tag ID', 'wpbp'); ?></span></legend>
                            <input type="text" name="wpbp_theme_options[google_remarketing_id]" id="google_remarketing_id" value="<?php echo esc_attr($wpbp_options['google_remarketing_id']); ?>" />
                            <br />
                            <small class="description"><?php printf(__('Enter your Google Remarketing Tag ID', 'wpbp')); ?></small>
                        </fieldset>
                    </td>
                </tr>

                <tr valign="top"><th scope="row"><?php _e('Google Tag Manager ID', 'wpbp'); ?></th>
                    <td>
                        <fieldset><legend class="screen-reader-text"><span><?php _e('Google Tag Manager ID', 'wpbp'); ?></span></legend>
                            <input type="text" name="wpbp_theme_options[google_tag_manager_id]" id="google_tag_manager_id" value="<?php echo esc_attr($wpbp_options['google_tag_manager_id']); ?>" />
                            <br />
                            <small class="description"><?php printf(__('Enter your GTM-XXXX ID', 'wpbp')); ?></small>
                        </fieldset>
                    </td>
                </tr>

                <tr valign="top"><th scope="row"><?php _e('Optimizely Project ID', 'wpbp'); ?></th>
                    <td>
                        <fieldset><legend class="screen-reader-text"><span><?php _e('Optimizely Project ID', 'wpbp'); ?></span></legend>
                            <input type="text" name="wpbp_theme_options[optimizely_project_id]" id="optimizely_project_id" value="<?php echo esc_attr($wpbp_options['optimizely_project_id']); ?>" />
                            <br />
                            <small class="description"><?php printf(__('Enter your Optimizely Project ID', 'wpbp')); ?></small>
                        </fieldset>
                    </td>
                </tr>

                <tr valign="top" class="radio-option"><th scope="row"><?php _e('Fluid Layout?', 'wpbp'); ?></th>
                    <td>
                        <fieldset><legend class="screen-reader-text"><span><?php _e('Fluid Layout?', 'wpbp'); ?></span></legend>
                            <select name="wpbp_theme_options[fluid]" id="wpbp_theme_options[fluid]">
                                <option value="yes" <?php selected($wpbp_options['fluid'], 'yes'); ?>><?php _e("Yes", 'wpbp'); ?></option>
                                <option value="no" <?php selected($wpbp_options['fluid'], 'no'); ?>><?php _e("No", 'wpbp'); ?></option>
                            </select>
                        </fieldset>
                    </td>
                </tr>

				<tr valign="top" class="radio-option"><th scope="row"><?php _e('Responsive Layout?', 'wpbp'); ?></th>
                    <td>
                        <fieldset><legend class="screen-reader-text"><span><?php _e('Responsive Layout?', 'wpbp'); ?></span></legend>
                            <select name="wpbp_theme_options[responsive]" id="wpbp_theme_options[responsive]">
                                <option value="responsive" <?php selected($wpbp_options['responsive'], 'responsive'); ?>><?php _e("Full-responsive", 'wpbp'); ?></option>
                                <option value="mobile-responsive" <?php selected($wpbp_options['responsive'], 'mobile-responsive'); ?>><?php _e("Mobile-responsive", 'wpbp'); ?></option>
                                <option value="non-responsive" <?php selected($wpbp_options['responsive'], 'non-responsive'); ?>><?php _e("Non-responsive", 'wpbp'); ?></option>
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

                <tr valign="top"><th scope="row"><?php _e('Custom Javascript', 'wpbp'); ?></th>
                    <td>
                        <fieldset><legend class="screen-reader-text"><span><?php _e('Custom Javascript', 'wpbp'); ?></span></legend>
                            <textarea name="wpbp_theme_options[custom_js]" id="custom_js" cols="94" rows="10"><?php echo esc_attr($wpbp_options['custom_js']); ?></textarea>
                            <br />
                            <small class="description"><?php printf(__('Enter custom Javascript for this site', 'wpbp')); ?></small>
                        </fieldset>
                    </td>
                </tr>

				<tr valign="top"><th scope="row"><?php _e('Favicon', 'wpbp'); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e('Favicon', 'wpbp'); ?></span></legend>
							<input type="text" name="wpbp_theme_options[favicon]" id="favicon" value="<?php echo esc_attr($wpbp_options['favicon']); ?>" class="regular-text" />
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

    $output['google_analytics_id']   = isset($input['google_analytics_id'])   ? $input['google_analytics_id']   : null;
    $output['google_remarketing_id'] = isset($input['google_remarketing_id']) ? $input['google_remarketing_id'] : null;
    $output['google_tag_manager_id'] = isset($input['google_tag_manager_id']) ? $input['google_tag_manager_id'] : null;
    $output['optimizely_project_id'] = isset($input['optimizely_project_id']) ? $input['optimizely_project_id'] : null;
    $output['fluid']                 = isset($input['fluid'])                 ? $input['fluid']                 : null;
	$output['responsive']            = isset($input['responsive'])            ? $input['responsive']            : null;
	$output['main_class']            = isset($input['main_class'])            ? $input['main_class']            : null;
	$output['sidebar_class']         = isset($input['sidebar_class'])         ? $input['sidebar_class']         : null;
    $output['css_files']             = isset($input['css_files'])             ? $input['css_files']             : null;
	$output['custom_css']            = isset($input['custom_css'])            ? $input['custom_css']            : null;
    $output['js_files']              = isset($input['js_files'])              ? $input['js_files']              : null;
    $output['custom_js']             = isset($input['custom_js'])             ? $input['custom_js']             : null;
    $output['favicon']               = isset($input['favicon'])               ? $input['favicon']               : null;

	return apply_filters('wpbp_theme_options_validate', $output, $input, $defaults);
}
