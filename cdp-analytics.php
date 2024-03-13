<?php
/*
CDP Analytics (Segment) Connection for WordPress

@package     Alquemie\CDP
@author      Chris Carrel
@license     GPL-3.0+

@wordpress-plugin
Plugin Name: CDP Analytics (Segment) for WP
Plugin URI:  https://github.com/alquemie/cdp-analytics/
Description: WordPress implementation of Segment analytics.js source with support for external link tracking.
Version:     2.3.2
Author:      Chris Carrel
Author URI:  https://www.linkedin.com/in/chriscarrel/
Text Domain: cdp-analytics
License:     GPL-3.0+
License URI: http://www.gnu.org/licenses/gpl-3.0.txt

------------------------------------------------------------------------
Copyright 2022-2024 Carmack Holdings, LLC.

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
*/

namespace Alquemie\CDP;

define( 'ALQUEMIE_CDP_VERSION', '2.3.2' );

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Gets this plugin's absolute directory path.
 * @return string
 */
function _get_plugin_directory() {
	return __DIR__;
}

/**
 * Gets this plugin's URL.
 * @return string
 */
function _get_plugin_url() {
	static $plugin_url;

	if ( empty( $plugin_url ) ) {
		$plugin_url = plugins_url( basename( __DIR__ ) . '' );

	}

	return $plugin_url;
}

/**
 * Checks if this plugin is in development mode.
 * @return bool
 */
function _is_in_development_mode() {
	$isDebug = (defined( 'WP_DEBUG' ) ) ? WP_DEBUG : false;
	return $isDebug;
}

function _log($message) {
	if (WP_DEBUG === true) {
		if (is_array($message) || is_object($message)) {
			error_log(print_r($message, true));
		} else {
			error_log($message);
		}
	} 
}

function activate_analytics() {
	require_once _get_plugin_directory() . '/includes/class-analytics-activator.php';
	Analytics_Activator::run();
}
register_activation_hook( __FILE__,  __NAMESPACE__ . '\activate_analytics' );

function deactivate_analytics() {
	require_once _get_plugin_directory() . '/includes/class-analytics-deactivator.php';
	Analytics_Deactivator::run();
}
register_deactivation_hook( __FILE__,  __NAMESPACE__ . '\deactivate_analytics' );

require_once _get_plugin_directory() . '/includes/class-cdp-analytics.php';

/**
 * Begins execution of the plugin.
 */
function launch() {
	$plugin_data['Name'] = 'Alquemie CDP Analytics';
	$plugin_data['Version'] = ALQUEMIE_CDP_VERSION;
	
	$plugin = new Analytics($plugin_data);
	$plugin->run();

	do_action( 'cdp_analytics_loaded' );
}

launch();