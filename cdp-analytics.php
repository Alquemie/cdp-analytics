<?php
/**
 * Segment Connection
 *
 * @package     Alquemie\CDP
 * @author      Chris Carrel
 * @license     GPL-3.0+
 *
 * @wordpress-plugin
 * Plugin Name: CDP Analytics (Segment) for WP
 * Plugin URI:  https://github.com/alquemie/cdp-analytics/
 * Description: WordPress implementation of Segment analytics.js source with support for external link tracking.
 * Version:     1.3.0
 * Author:      Chris Carrel
 * Author URI:  https://www.linkedin.com/in/chriscarrel/
 * Text Domain: alquemie
 * License:     GPL-3.0+
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 */

namespace Alquemie\CDP;

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
	$isDebug = (defined( 'WP_DEBUG' ) )  ? WP_DEBUG : false;
	return $isDebug;
}

/**
 * Autoload the plugin's files.
 *
 * @since 1.0.0
 *
 * @return void
 */
function autoload_files() {
	$files = array(
		// add the list of files to load here.
		'lib/autoload.php',
		'src/public/class-cdp-ajs.php',
		'src/public/class-cdp-analytics.php',
		'src/admin/class-cdp-settings.php'
	);

	foreach ( $files as $file ) {
		require __DIR__ . '/' . $file;
	}
}


/**
 * Launch the plugin.
 *
 * @since 1.0.0
 *
 * @return void
 */
function launch() {
	autoload_files();
	do_action( 'cdp_analytics_loaded' );
}

launch();
