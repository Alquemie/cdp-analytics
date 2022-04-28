<?php
/**
 * Segment Connection
 *
 * @package     Twilio\Segment
 * @author      Chris Carrel
 * @license     GPL-3.0+
 *
 * @wordpress-plugin
 * Plugin Name: Segment WP Connection
 * Plugin URI:  https://github.com/chriscarrel/twilio-segment-wp/
 * Description: WordPress implementation of Segment analytics.js source with basic support for Gravity Forms.
 * Version:     1.1.6
 * Author:      Chris Carrel
 * Author URI:  https://segment.com
 * Text Domain: segment
 * License:     GPL-3.0+
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 */

namespace Twilio\Segment;

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
		'public/forms.php',
		'admin/settings.php'
	);

	foreach ( $files as $file ) {
		require __DIR__ . '/src/' . $file;
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
}

launch();
