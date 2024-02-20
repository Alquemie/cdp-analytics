<?php

namespace Alquemie\CDP;

/**
 * Fired during plugin activation
 *
 * @link       https://alquemie.net
 * @since      1.0.0
 *
 * @package    Alquemie\CDP
 * @subpackage Analytics\includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Alquemie\CDP
 * @subpackage Analytics\includes
 * @author     Alquemie <support@alquemie.net>
 */
class Analytics_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function run() {
		_log("Activate CDP Analytics");
		self::checkUploadFolder();
	}

	public static function checkUploadFolder() {
		_log("check upload folder");

		$upload_dir = wp_upload_dir(); 
		$cdp_dirname = $upload_dir['basedir'] . '/' . 'cdp-analytics';
		if(!file_exists($cdp_dirname)) wp_mkdir_p($cdp_dirname);
	}

	public static function startCronJob() {
		_log("Initialize CDP Cron Job");
		wp_schedule_event( time(), 'hourly', ['hourly_event_hook'] );
		
	}

}
