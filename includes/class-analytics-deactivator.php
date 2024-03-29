<?php

namespace Alquemie\CDP;

/**
 * Fired during plugin deactivation
 *
 * @link       https://alquemie.net
 * @since      1.0.0
 *
 * @package    Alquemie\CDP
 * @subpackage Analytics\includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Alquemie\CDP
 * @subpackage Analytics\includes
 * @author     Alquemie <support@alquemie.net>
 */
class Analytics_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function run() {
		_log("DEACTIVATE CDP Analytics");
		self::cancelCronJob();
	}

	public static function cancelCronJob() {
		$timestamp = wp_next_scheduled( 'cdp_refesh_source_map' );
		wp_unschedule_event( $timestamp, 'cdp_refesh_source_map' );
		wp_clear_scheduled_hook( 'cdp_refesh_source_map' );
	}
}
