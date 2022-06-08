<?php


namespace Twilio\Segment;

class settings {

	public function __construct() {

		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'init_settings'  ) );

		$options = get_option( 'segment_keys' );
		$keyset = ( isset( $options['segment_write_key'] ) && ($options['segment_write_key'] !== "") ) ? true : false;
		if (!$keyset) { 
			add_action('admin_notices', array( $this, 'writekey_missing_notice'  ) );
		}
	}

	public function add_admin_menu() {

		add_options_page(
			esc_html__( 'Segment for WordPress', 'segment' ),
			esc_html__( 'Segment', 'segment' ),
			'manage_options',
			'segment',
			array( $this, 'page_layout' )
		);

	}

	public function init_settings() {

		register_setting(
			'segment',
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
			__( 'Write Key', 'segment' ),
			array( $this, 'render_segment_write_key_field' ),
			'segment_keys',
			'segment_keys_section'
		);
/*
		add_settings_field(
			'segment_custom_domain',
			__( 'Custom Subdomain', 'segment' ),
			array( $this, 'render_segment_custom_domain_field' ),
			'segment_keys',
			'segment_keys_section'
		);
*/

		add_settings_field(
			'segment_google_enabled',
			__( 'Enable Local gTag', 'segment' ),
			array( $this, 'render_segment_ga4_enable_field' ),
			'segment_keys',
			'segment_keys_section'
		);

		add_settings_field(
			'segment_google_measurement_id',
			__( 'GA4 Measurement ID', 'segment' ),
			array( $this, 'render_segment_ga4_measurment_field' ),
			'segment_keys',
			'segment_keys_section'
		);
	}

	public function page_layout() {

		// Check required user capability
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'segment' ) );
		}

		// Admin Page Layout
		echo '<div class="wrap">';
		echo '	<h1>' . get_admin_page_title() . '</h1>';
		echo '	<form action="options.php" method="post">';

		settings_fields( 'segment' );
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
		echo '<input type="text" name="segment_keys[segment_write_key]" class="regular-text segment_write_key_field" placeholder="' . esc_attr__( '', 'segment' ) . '" value="' . esc_attr( $value ) . '">';
		echo '<p class="description">' . __( 'Analytics.js write key for the source assigned to this site', 'segment' ) . '</p>';

	}

	function render_segment_custom_domain_field() {

		// Retrieve data from the database.
		$options = get_option( 'segment_keys' );

		// Set default value.
		$value = isset( $options['segment_custom_domain'] ) ? $options['segment_custom_domain'] : '';

		// Field output.
		echo '<input type="text" name="segment_keys[segment_custom_domain]" class="regular-text segment_custom_domain_field" placeholder="' . esc_attr__( '', 'segment' ) . '" value="' . esc_attr( $value ) . '">';
		echo '<p class="description">' . __( 'Contact friends@segment to configure your custom Segment subdomain', 'segment' ) . '</p>';

	}

	function render_segment_ga4_enable_field() {

		// Retrieve data from the database.
		$options = get_option( 'segment_keys' );
		$yesVal = ( isset( $options['segment_google_enabled'] ) && $options['segment_google_enabled'] == "Y" ) ? true : false;
		$noVal = !$yesVal;
		
		// Field output.
		echo "<fieldset>";
		$checked = !$value ? 'checked="true"' : '' ;
		echo "<label for=\"segment_google_enabled-0\"><input type=\"radio\" name=\"segment_option_name[segment_google_enabled]\" id=\"segment_google_enabled-0\" value=\"N\" " . $checked . " onClick=\"toggleGAfield();\"> No</label><br>";
		$checked = $value ? 'checked' : '' ;
		echo "<label for=\"segment_google_enabled-1\"><input type=\"radio\" name=\"segment_option_name[segment_google_enabled]\" id=\"segment_google_enabled-1\" value=\"Y\" " . $checked . " onClick=\"toggleGAfield();\"> Yes</label></fieldset>" . PHP_EOL;
	
	}

	function render_segment_ga4_measurment_field() {

		// Retrieve data from the database.
		$options = get_option( 'segment_keys' );

		// Set default value.
		$value = isset( $options['segment_google_measurement_id'] ) ? strtoupper($options['segment_google_measurement_id']) : '';

		// Field output.
		echo '<input type="text" name="segment_keys[segment_google_measurement_id]" class="regular-text segment_google_measurement_id_field" placeholder="' . esc_attr__( '', 'segment' ) . '" value="' . esc_attr( $value ) . '">';
		echo '<p class="description">' . __( 'Measurement ID to add GA4 to site natively', 'segment' ) . '</p>';
		echo "<script>" . PHP_EOL;
		echo "jQuery(document).ready(function() {";
			echo "   toggleGAfield(); ";
		echo "});" . PHP_EOL;
		echo "function toggleGAfield() {";
		echo "    var enabled = (jQuery('input[name=\"segment_option_name[segment_google_enabled]\"]:checked').val() == \"true\");";
		echo "    jQuery('input[name=\"segment_keys[segment_google_measurement_id]\"]').prop('disabled', !enabled);";	
		echo "}" . PHP_EOL;
		echo "</script>" . PHP_EOL;
	}

	function render_segment_enabled_modules() {

		echo '<h2>' . __( 'Active Modules', 'segment') . '</h2>';
		echo '<ul>' . PHP_EOL;
		if ( class_exists( 'GFForms' ) ) {
			echo '<li >' . __( 'GravityForms Track/Identify', 'segment' ) . '</li>' . PHP_EOL;
		}
		echo '</ul>' . PHP_EOL;

	}
	function writekey_missing_notice() { ?>
	
		<div class="notice notice-error">
			<p><?php _e('You must update the WriteKey in order for Segment to work!', 'segment'); ?></p>
		</div>
		
	<?php }
}

new settings;
