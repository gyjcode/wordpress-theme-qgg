<?php

/*
 * Loads the Options Panel
 * If you're loading from a child theme use stylesheet_directory instead of template_directory
 */

define( 'OPTIONS_FRAMEWORK_DIRECTORY', get_template_directory_uri() . '/settings/' );
require_once dirname( __FILE__ ) . '/../settings/options-framework.php';

// Loads options.php from child or parent theme
$optionsfile = locate_template( 'options.php' );
load_template( $optionsfile );

/*
 * This is an example of how to add custom scripts to the options panel.
 * This one shows/hides the an option when a checkbox is clicked.
 * You can delete it if you not using that option
 */
add_action( 'optionsframework_custom_scripts', 'optionsframework_custom_scripts' );

function optionsframework_custom_scripts() { ?>

<script type="text/javascript">
jQuery(document).ready(function() {

	jQuery('#example_showhidden').click(function() {
  		jQuery('#section-example_text_hidden').fadeToggle(400);
	});

	if (jQuery('#example_showhidden:checked').val() !== undefined) {
		jQuery('#section-example_text_hidden').show();
	}

});
</script>

<?php
}

/**
 * This is an example of filtering menu parameters
 */
function prefix_options_menu_filter( $menu ) {
	$menu['mode'] = 'menu';
	$menu['page_title'] = __( '主题选项', 'QGG');
	$menu['menu_title'] = __( '主题选项', 'QGG');
	$menu['menu_slug'] = 'qgg-options';
	return $menu;
}

add_filter( 'optionsframework_menu', 'prefix_options_menu_filter' );

/**
 * Helper function to return the theme option value.
 * If no value has been saved, it returns $default.
 * Needed because options are saved as serialized strings.
 * Not in a class to support backwards compatibility in themes.
 */
if ( !function_exists( 'QGG_Options' ) ) :
function QGG_Options( $name, $default = false ) {

	$option_name = 'QGG';

	// Gets option name as defined in the theme
	if ( function_exists( 'optionsframework_option_name' ) ) {
		$option_name = optionsframework_option_name();
	}

	// Fallback option name
	if ( '' == $option_name ) {
		$option_name = get_option( 'stylesheet' );
		$option_name = preg_replace( "/\W/", "_", strtolower( $option_name ) );
	}

	// Get option settings from database
	$options = get_option( $option_name );

	// Return specific option
	if ( isset( $options[$name] ) ) {
		return $options[$name];
	}

	return $default;
}
endif;

/*
 * This is an example of how to override a default filter
 * for 'textarea' sanitization and $allowedposttags + embed and script.
 */
add_action('admin_init','optionscheck_change_santiziation', 100);
function optionscheck_change_santiziation() {
    remove_filter( 'of_sanitize_textarea', 'of_sanitize_textarea' );
    add_filter( 'of_sanitize_textarea', 'custom_sanitize_textarea' );
}
function custom_sanitize_textarea($input) {
	global $allowedposttags;
	$custom_allowedtags["embed"] = array(
		"src" => array(),
		"type" => array(),
		"allowfullscreen" => array(),
		"allowscriptaccess" => array(),
		"height" => array(),
		"width" => array()
	);
	$custom_allowedtags["script"] = array( 
		"type" => array(),
		"src" => array()
	);
	$custom_allowedtags = array_merge($custom_allowedtags, $allowedposttags);
	$output = wp_kses( $input, $custom_allowedtags);
	return $output;
}
