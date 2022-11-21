<?php
/**
 * Segment Connection
 *
 * @package     Alquemie\Segment
 * @author      Chris Carrel
 * @license     GPL-3.0+
 *
 * @wordpress-plugin
 * Plugin Name: WordPress Segment Connection
 * Plugin URI:  https://github.com/alquemie/segment-cdp/
 * Description: WordPress implementation of Segment analytics.js source with basic support for Gravity Forms.
 * Version:     1.1.12
 * Author:      Chris Carrel
 * Author URI:  https://www.linkedin.com/in/chriscarrel/
 * Text Domain: alquemie
 * License:     GPL-3.0+
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 */

namespace Alquemie\Segment;

/**
 * Gets this plugin's absolute directory path.
 *
 * @since  1.0.2
 * @ignore
 * @access private
 *
 * @return string
 */
function _get_plugin_version() {
	$plugin_data = get_plugin_data( __FILE__ );
	return $plugin_data['Version'];
}

/**
 * Get's the asset file's version number by using it's modification timestamp.
 *
 * @since 1.0.0
 *
 * @param string $relative_path Relative path to the asset file.
 *
 * @return bool|int
 */
function _get_asset_version( $relative_path ) {
	return filemtime( _get_plugin_directory() . $relative_path );
}

/**
 * Gets this plugin's absolute directory path.
 *
 * @since  1.0.0
 * @ignore
 * @access private
 *
 * @return string
 */
function _get_plugin_directory() {
	return __DIR__;
}

/**
 * Gets this plugin's URL.
 *
 * @since  1.0.0
 * @ignore
 * @access private
 *
 * @return string
 */
function _get_plugin_url() {
	static $plugin_url;

	if ( empty( $plugin_url ) ) {
		$plugin_url = plugins_url( null, __FILE__ );
	}

	return $plugin_url;
}

/**
 * Checks if this plugin is in development mode.
 *
 * @since  1.0.0
 * @ignore
 * @access private
 *
 * @return bool
 */
function _is_in_development_mode() {
	return defined( WP_DEBUG ) && WP_DEBUG === true;
}

/**
 * Autoload the plugin's files.
 *
 * @since 1.0.0
 *
 * @return void
 */
function autoload_files() {
	$files = array(// add the list of files to load here.
		'public/analytics.php',
		// 'public/forms.php',
		'admin/settings.php'
	);

	foreach ( $files as $file ) {
		require __DIR__ . '/src/' . $file;
	}
}

/**
 * Enqueue the plugin's scripts and styles.
 *
 * @since 1.0.0
 *
 * @return void
 */
function load_public_scripts() {
	wp_enqueue_script( 'alquemie_segment_js', plugins_url( 'assets/public/js/frontend.js', __FILE__ ), array('jQuery'), _get_asset_version('/assets/public/js/frontend.js'), true );
	
}

/**
 * Launch the plugin.
 *
 * @since 1.0.0
 *
 * @return void
 */
function launch() {
	add_action('wp_enqueue_scripts', 'Alquemie\Segment\load_public_scripts');

	autoload_files();
}

launch();
