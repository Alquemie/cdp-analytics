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
			'segment_for_wp',
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
			__( 'Write Key', 'segment_for_wp' ),
			array( $this, 'render_segment_write_key_field' ),
			'segment_keys',
			'segment_keys_section'
		);
/*
		add_settings_field(
			'segment_custom_domain',
			__( 'Custom Subdomain', 'segment_for_wp' ),
			array( $this, 'render_segment_custom_domain_field' ),
			'segment_keys',
			'segment_keys_section'
		);
*/
	}

	public function page_layout() {

		// Check required user capability
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'segment_for_wp' ) );
		}

		// Admin Page Layout
		echo '<div class="wrap">';
		echo '	<h1>' . get_admin_page_title() . '</h1>';
		echo '	<form action="options.php" method="post">';

		settings_fields( 'segment_for_wp' );
		do_settings_sections( 'segment_keys' );
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
		echo '<input type="text" name="segment_keys[segment_write_key]" class="regular-text segment_write_key_field" placeholder="' . esc_attr__( '', 'segment_for_wp' ) . '" value="' . esc_attr( $value ) . '">';
		echo '<p class="description">' . __( 'Analytics.js write key for the source assigned to this site', 'segment_for_wp' ) . '</p>';

	}

	function render_segment_custom_domain_field() {

		// Retrieve data from the database.
		$options = get_option( 'segment_keys' );

		// Set default value.
		$value = isset( $options['segment_custom_domain'] ) ? $options['segment_custom_domain'] : '';

		// Field output.
		echo '<input type="text" name="segment_keys[segment_custom_domain]" class="regular-text segment_custom_domain_field" placeholder="' . esc_attr__( '', 'segment_for_wp' ) . '" value="' . esc_attr( $value ) . '">';
		echo '<p class="description">' . __( 'Contact friends@segment to configure your custom Segment subdomain', 'segment_for_wp' ) . '</p>';

	}

	function writekey_missing_notice() { ?>
	
		<div class="notice notice-error">
			<p><?php _e('You must update the WriteKey in order for Segment to work!', 'segment_for_wp'); ?></p>
		</div>
		
	<?php }
}

new settings;
