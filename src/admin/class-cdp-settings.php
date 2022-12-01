<?php

namespace Alquemie\CDP;


class settings {

	public function __construct() {

		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'init_settings'  ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_script') );

		$options = get_option( 'segment_keys' );
		$keyset = ( isset( $options['segment_write_key'] ) && ($options['segment_write_key'] !== "") ) ? true : false;
		if (!$keyset) { 
			add_action('admin_notices', array( $this, 'writekey_missing_notice'  ) );
		}
	}

	public function enqueue_admin_script( $hook ) {
    /*if ( 'general-options.php' != $hook ) {
        return;
    }*/
		$isDevMode = _is_in_development_mode();
		if ($isDevMode) {
				$jsFileURI = _get_plugin_url() . '/src/admin/js/admin-cdp.js';
		} else {
				$jsFilePath = glob( _get_plugin_directory() . '/dist/js/admin.*.js' );
				$jsFileURI = _get_plugin_url() . '/dist/js/' . basename($jsFilePath[0]);
		}
		
		$this->_settings['jsfileuri'] = $jsFileURI;
		$this->_settings['devMode'] = $isDevMode;

		wp_enqueue_script( 'cdp_ajs_admin', $jsFileURI , array('jquery') , null , true );
	}


	public function add_admin_menu() {

		add_options_page(
			esc_html__( 'CDP Analytics (Segment) for WordPress', 'cdp-analytics' ),
			esc_html__( 'CDP Analytics', 'cdp-analytics' ),
			'manage_options',
			'cdp-analytics',
			array( $this, 'page_layout' )
		);

	}

	public function init_settings() {

		register_setting(
			'cdp-analytics',
			'segment_keys'
		);

		add_settings_section(
			'segment_keys_section',
			'',
			false,
			'segment_keys'
		);

		add_settings_field(
			'segment_write_key',
			__( 'Write Key', 'cdp-analytics' ),
			array( $this, 'render_segment_write_key_field' ),
			'segment_keys',
			'segment_keys_section'
		);

		add_settings_field(
			'segment_custom_domain',
			__( 'Custom Subdomain', 'cdp-analytics' ),
			array( $this, 'render_segment_custom_domain_field' ),
			'segment_keys',
			'segment_keys_section'
		);

		add_settings_field(
			'segment_tracklinks_enabled',
			__( 'Enable Link Tracking', 'cdp-analytics' ),
			array( $this, 'render_segment_tracklinks_enable_field' ),
			'segment_keys',
			'segment_keys_section'
		);

		add_settings_field(
			'segment_ext_target_enabled',
			__( 'Open External Links in new Winddow', 'cdp-analytics' ),
			array( $this, 'render_segment_target_enable_field' ),
			'segment_keys',
			'segment_keys_section',
			array( "class" => "ext_target_row")
		);

		add_settings_field(
			'segment_share_selector',
			__( 'Share Button Selector', 'cdp-analytics' ),
			array( $this, 'render_segment_share_selector_field' ),
			'segment_keys',
			'segment_keys_section',
			array("label_for" => "segment_share_selector", "class" => "share_selector_row")
		);

		add_settings_field(
			'segment_region_setting',
			__( 'Segment Region', 'cdp-analytics' ),
			array( $this, 'render_segment_region_setting_field' ),
			'segment_keys',
			'segment_keys_section'
		);
		/*
		add_settings_field(
			'segment_google_enabled',
			__( 'Enable Local gTag', 'cdp-analytics' ),
			array( $this, 'render_segment_ga4_enable_field' ),
			'segment_keys',
			'segment_keys_section'
		);

		add_settings_field(
			'segment_google_measurement_id',
			__( 'GA4 Measurement ID', 'cdp-analytics' ),
			array( $this, 'render_segment_ga4_measurment_field' ),
			'segment_keys',
			'segment_keys_section'
		);

		add_settings_field(
			'segment_google_identify',
			__( 'Identify GA Session', 'cdp-analytics' ),
			array( $this, 'render_segment_ga4_identify_field' ),
			'segment_keys',
			'segment_keys_section'
		);
		*/
	}

	public function page_layout() {

		// Check required user capability
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'cdp-analytics' ) );
		}

		// Admin Page Layout
		echo '<div class="wrap">';
		echo '	<h1>' . get_admin_page_title() . '</h1>';
		echo '	<form action="options.php" method="post">';

		settings_fields( 'cdp-analytics' );
		do_settings_sections( 'segment_keys' );
		$this->render_segment_enabled_modules();
		submit_button();

		echo '	</form>';
		echo '</div>';

	}

	function render_segment_write_key_field() {

		// Retrieve data from the database.
		$options = get_option( 'segment_keys' );

		// Set default value.
		$value = isset( $options['segment_write_key'] ) ? $options['segment_write_key'] : '';

		// Field output.
		echo "<fieldset>";
		echo "<label for=\"segment_write_key\">";
		echo '<input type="text" name="segment_keys[segment_write_key]" id="segment_write_key" class="regular-text segment_write_key_field" placeholder="' . esc_attr__( '', 'cdp-analytics' ) . '" value="' . esc_attr( $value ) . '">';
		echo '<p class="description">' . __( 'Analytics.js write key for the source assigned to this site', 'cdp-analytics' ) . '</p>';

	}

	function render_segment_region_setting_field() {

		// Retrieve data from the database.
		$options = get_option( 'segment_keys' );
		$curVal = isset( $options['segment_region_setting'] ) ? $options['segment_region_setting'] : 'us';
		$oregon = ($curVal == 'us') ? 'checked="true"' : '' ;
		$dublin = ($curVal == 'eu2')? 'checked="true"' : '' ;
		$singapore = ($curVal == 'ap1')? 'checked="true"' : '' ;
		$sydney = ($curVal == 'au1')? 'checked="true"' : '' ;

		// Field output.
		// echo "Testing: " . print_r($options, true) . " - Enabled: " . $enabled . " Checked: " . $checked;
		echo "<fieldset>";
		echo "<label for=\"segment_region_setting-0\"><input type=\"radio\" name=\"segment_keys[segment_region_setting]\" id=\"segment_region_setting-0\" value=\"us\" " . $oregon . " > Oregon (Default)</label><br>";
		echo "<label for=\"segment_region_setting-1\"><input type=\"radio\" name=\"segment_keys[segment_region_setting]\" id=\"segment_region_setting-1\" value=\"eu2\" " . $dublin . " > Dublin</label>" . PHP_EOL;
		//echo "<label for=\"segment_region_setting-1\"><input type=\"radio\" name=\"segment_keys[segment_region_setting]\" id=\"segment_region_setting-2\" value=\"ap1\" " . $singapore . " > Singapore</label>" . PHP_EOL;
		//echo "<label for=\"segment_region_setting-1\"><input type=\"radio\" name=\"segment_keys[segment_region_setting]\" id=\"segment_region_setting-3\" value=\"au1\" " . $sydney . " > Sydney</label>" . PHP_EOL;
		echo "</fieldset>" . PHP_EOL;
	}

	function render_segment_custom_domain_field() {

		// Retrieve data from the database.
		$options = get_option( 'segment_keys' );

		// Set default value.
		$value = isset( $options['segment_custom_domain'] ) ? $options['segment_custom_domain'] : '';

		// Field output.
		echo '<input type="text" name="segment_keys[segment_custom_domain]" class="regular-text segment_custom_domain_field" placeholder="' . esc_attr__( 'cdn.segment.com', 'cdp-analytics' ) . '" value="' . esc_attr( $value ) . '">';
		echo '<p class="description">' . __( 'Contact friends@segment to configure your custom Segment subdomain', 'cdp-analytics' ) . '</p>';

	}

	function render_segment_share_selector_field() {

		// Retrieve data from the database.
		$options = get_option( 'segment_keys' );

		// Set default value.
		$value = isset( $options['segment_share_selector'] ) ? $options['segment_share_selector'] : '';

		// Field output.
		echo '<input type="text" name="segment_keys[segment_share_selector]" id="segment_share_selector" class="regular-text segment_share_selector_field" placeholder="' . esc_attr__( '[data-share]', 'cdp-analytics' ) . '" value="' . esc_attr( $value ) . '">';
		echo '<p class="description">' . __( 'This is a jQuery selector to find the "share" buttons', 'cdp-analytics' ) . '</p>';
	}

	function render_segment_tracklinks_enable_field() {

		// Retrieve data from the database.
		$options = get_option( 'segment_keys' );
		$enabled = ( isset( $options['segment_tracklinks_enabled'] ) && $options['segment_tracklinks_enabled'] == "Y" ) ? true : false;
		$checked = $enabled ? 'checked="true"' : '' ;
		$notChecked = !$enabled ? 'checked="true"' : '' ;

		// Field output.
		// echo "Testing: " . print_r($options, true) . " - Enabled: " . $enabled . " Checked: " . $checked;
		echo "<fieldset>";
		// $checked = !$value ? 'checked="true"' : '' ;
		echo "<label for=\"segment_tracklinks_enabled-0\"><input type=\"radio\" name=\"segment_keys[segment_tracklinks_enabled]\" id=\"segment_tracklinks_enabled-0\" value=\"N\" " . $notChecked . " > No</label><br>";
		// $checked = $value ? 'checked' : '' ;
		echo "<label for=\"segment_tracklinks_enabled-1\"><input type=\"radio\" name=\"segment_keys[segment_tracklinks_enabled]\" id=\"segment_tracklinks_enabled-1\" value=\"Y\" " . $checked . " > Yes</label></fieldset>" . PHP_EOL;
	
	}

	function render_segment_target_enable_field() {

		// Retrieve data from the database.
		$options = get_option( 'segment_keys' );
		$enabled = ( isset( $options['segment_ext_target_enabled'] ) && $options['segment_ext_target_enabled'] == "Y" ) ? true : false;
		$checked = $enabled ? 'checked="true"' : '' ;
		$notChecked = !$enabled ? 'checked="true"' : '' ;

		// Field output.
		// echo "Testing: " . print_r($options, true) . " - Enabled: " . $enabled . " Checked: " . $checked;
		echo "<fieldset>";
		// $checked = !$value ? 'checked="true"' : '' ;
		echo "<label for=\"segment_ext_target_enabled-0\"><input type=\"radio\" name=\"segment_keys[segment_ext_target_enabled]\" id=\"segment_ext_target_enabled-0\" value=\"N\" " . $notChecked . " > No</label><br>";
		// $checked = $value ? 'checked' : '' ;
		echo "<label for=\"segment_ext_target_enabled-1\"><input type=\"radio\" name=\"segment_keys[segment_ext_target_enabled]\" id=\"segment_ext_target_enabled-1\" value=\"Y\" " . $checked . " > Yes</label></fieldset>" . PHP_EOL;
	
	}

	function render_segment_ga4_enable_field() {

		// Retrieve data from the database.
		$options = get_option( 'segment_keys' );
		$enabled = ( isset( $options['segment_google_enabled'] ) && $options['segment_google_enabled'] == "Y" ) ? true : false;
		$checked = $enabled ? 'checked="true"' : '' ;
		$notChecked = !$enabled ? 'checked="true"' : '' ;

		// Field output.
		// echo "Testing: " . print_r($options, true) . " - Enabled: " . $enabled . " Checked: " . $checked;
		echo "<fieldset>";
		// $checked = !$value ? 'checked="true"' : '' ;
		echo "<label for=\"segment_google_enabled-0\"><input type=\"radio\" name=\"segment_keys[segment_google_enabled]\" id=\"segment_google_enabled-0\" value=\"N\" " . $notChecked . " onClick=\"toggleGAfield();\"> No</label><br>";
		// $checked = $value ? 'checked' : '' ;
		echo "<label for=\"segment_google_enabled-1\"><input type=\"radio\" name=\"segment_keys[segment_google_enabled]\" id=\"segment_google_enabled-1\" value=\"Y\" " . $checked . " onClick=\"toggleGAfield();\"> Yes</label></fieldset>" . PHP_EOL;
	
	}

	function render_segment_ga4_measurment_field() {

		// Retrieve data from the database.
		$options = get_option( 'segment_keys' );

		// Set default value.
		$value = isset( $options['segment_google_measurement_id'] ) ? strtoupper($options['segment_google_measurement_id']) : '';

		// Field output.
		echo '<input type="text" name="segment_keys[segment_google_measurement_id]" class="regular-text segment_google_measurement_id_field" placeholder="' . esc_attr__( '', 'cdp-analytics' ) . '" value="' . esc_attr( $value ) . '">';
		echo '<p class="description">' . __( 'Measurement ID to add GA4 to site natively', 'cdp-analytics' ) . '</p>';
		echo "<script>" . PHP_EOL;
		echo "jQuery(document).ready(function() {";
			echo "   toggleGAfield(); ";
		echo "});" . PHP_EOL;
		echo "function toggleGAfield() {";
		echo "    var enabled = (jQuery('input[name=\"segment_keys[segment_google_enabled]\"]:checked').val() == \"Y\");";
		echo "    /* console.log('enable GA: ' + jQuery('input[name=\"segment_keys[segment_google_enabled]\"]:checked').val() ); */ ";
		echo "    jQuery('input[name=\"segment_keys[segment_google_measurement_id]\"]').prop('disabled', !enabled);";	
		echo "}" . PHP_EOL;
		echo "</script>" . PHP_EOL;
	}

	function render_segment_ga4_identify_field() {

		// Retrieve data from the database.
		$options = get_option( 'segment_keys' );
		$enabled = ( isset( $options['segment_google_identify_enabled'] ) && $options['segment_google_identify_enabled'] == "Y" ) ? true : false;
		$checked = $enabled ? 'checked="true"' : '' ;
		$notChecked = !$enabled ? 'checked="true"' : '' ;

		// Field output.
		// echo "Testing: " . print_r($options, true) . " - Enabled: " . $enabled . " Checked: " . $checked;
		echo "<fieldset>";
		// $checked = !$value ? 'checked="true"' : '' ;
		echo "<label for=\"segment_google_identify_enabled-0\"><input type=\"radio\" name=\"segment_keys[segment_google_identify_enabled]\" id=\"segment_google_identify_enabled-0\" value=\"N\" " . $notChecked . " onClick=\"toggleGAfield();\"> No</label><br>";
		// $checked = $value ? 'checked' : '' ;
		echo "<label for=\"segment_google_identify_enabled-1\"><input type=\"radio\" name=\"segment_keys[segment_google_identify_enabled]\" id=\"segment_google_identify_enabled-1\" value=\"Y\" " . $checked . " onClick=\"toggleGAfield();\"> Yes</label></fieldset>" . PHP_EOL;
	
	}

	function render_segment_enabled_modules() {

		echo '<h2>' . __( 'Active Modules', 'cdp-analytics') . '</h2>';
		echo '<ul>' . PHP_EOL;
		if ( class_exists( 'Alquemie\CDP\GravityAddon' ) ) {
			echo '<li >' . __( 'GravityForms Track/Identify', 'cdp-analytics' ) . '</li>' . PHP_EOL;
		}
		echo '</ul>' . PHP_EOL;

	}
	function writekey_missing_notice() { ?>
	
		<div class="notice notice-error">
			<p><?php _e('You must update the WriteKey in order for Segment to work!', 'cdp-analytics'); ?></p>
		</div>
		
	<?php }
}

new settings;
