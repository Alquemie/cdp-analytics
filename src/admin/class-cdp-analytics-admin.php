<?php

namespace Alquemie\CDP;

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://alquemie.net
 *
 * @package    Alquemie\CDP
 * @subpackage Analytics/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Alquemie\CDP
 * @subpackage Analytics/admin
 * @author     Alquemie <support@alquemie.net>
 */
class Analytics_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

  public function settings_page() {


    // Control core classes for avoid errors
    if( class_exists( 'CSF' ) ) {

      //
      // Set a unique slug-like ID
      $prefix = 'segment_keys';

      //
      // Create options
      \CSF::createOptions( $prefix, array(

        // framework title
        'framework_title'         => 'CDP Analytics (Segment)',

        // menu settings
        'menu_title'              => 'CDP Analytics',
        'menu_slug'               => 'cdp-analytics',
        'menu_type'               => 'menu',
        'menu_capability'         => 'manage_options',
        'menu_icon'               => 'dashicons-chart-bar',
        'menu_position'           => null,
        'menu_hidden'             => false,
        'menu_parent'             => '',

        // menu extras
        'show_bar_menu'           => false,
        'show_sub_menu'           => true,
        'show_in_network'         => true,
        'show_in_customizer'      => false,

        'show_search'             => false,
        'show_reset_all'          => false,
        'show_reset_section'      => false,
        'show_footer'             => true,
        'show_all_options'        => true,
        'show_form_warning'       => true,
        'sticky_header'           => false,
        'save_defaults'           => true,
        'ajax_save'               => true,

        // admin bar menu settings
        'admin_bar_menu_icon'     => '',
        'admin_bar_menu_priority' => 80,

        // footer
        'footer_text'             => '',
        'footer_after'            => '',
        'footer_credit'           => '',

        // database model
        'database'                => '', // options, transient, theme_mod, network
        'transient_time'          => 0,

        // contextual help
        'contextual_help'         => array(),
        'contextual_help_sidebar' => '',

        // typography options
        'enqueue_webfont'         => true,
        'async_webfont'           => false,

        // others
        'output_css'              => true,

        // theme and wrapper classname
        'nav'                     => 'inline',
        'theme'                   => 'light',
        'class'                   => '',

        // external default values
        'defaults'                => array(),

      ) );

      //
      // Create a section
      \CSF::createSection( $prefix, array(
        'title'  => 'API Config',
        'assign' => 'static_front_page',
        'fields' => array(

          array(
            'type'    => 'heading',
            'content' => 'Segment API Settings',
          ),
          array(
            'type'    => 'content',
            'content' => 'Add the configuration settings from your <a href="https://app.segment.com" target="_blank">Segment Account</a> to get started.',
          ),
          array(
            'id'    => 'segment_write_key',
            'type'  => 'text',
            'title' => 'Source WriteKey',
          ),
          array(
            'id'      => 'segment_region_setting',
            'type'    => 'radio',
            'title'   => 'Region',
            'options' => array(
              'us'   => 'Oregon (Default)',
              'eu2'    => 'Dublin',
            ),
            'default' => 'us',
            'subtitle' => __( 'For more information on regional settings, visit the <a href="https://segment.com/docs/guides/regional-segment/" target="_blank">Segment Documentaion</a>.', 'cdp-analytics' ),
          ),
          array(
            'id'    => 'segment_custom_domain',
            'type'  => 'text',
            'title' => 'Custom Subdomain',
            'default' => "cdn.segment.com",
            'subtitle' => __( 'Contact friends@segment to configure a custom subdomain for the Segment CDN.', 'cdp-analytics' )
          ),
          

        )
      ) );

      //
      // Create a section
      \CSF::createSection( $prefix, array(
        'title'  => 'Filters',
        'fields' => array(
          array(
            'type'    => 'heading',
            'content' => 'Fitlers',
          ),
          array(
            'type'    => 'content',
            'content' => 'Fitler calls based on user permission or post type',
          ),
          array(
            'type'    => 'submessage',
            'style'   => 'info',
            'content' => 'Under Development',
          ),
          array(
            'id'         => 'cdp-consent-manager',
            'type'       => 'switcher',
            'title'      => 'Enable Drop-in Consent Manager',
            'text_on'    => 'Yes',
            'text_off'   => 'No',
            'subtitle' => __( 'Visit <a href="https://github.com/segmentio/consent-manager" target="_blank">Segment\'s GitHub</a> for more information.', 'cdp-analytics' ),
            'default' => false
          ),
        )
      ) );

      \CSF::createSection( $prefix, array(
        'title'  => 'Ad Campaigns',
        'fields' => array(
          array(
            'type'    => 'heading',
            'content' => 'Ad Campaigns',
          ),
          array(
            'type'    => 'content',
            'content' => 'Enhance calls with campaign data stored in local storage.',
          ),
          array(
            'id'         => 'cdp-campaign-context',
            'type'       => 'switcher',
            'title'      => 'Include Campaign Context',
            'text_on'    => 'Yes',
            'text_off'   => 'No',
            'subtitle' => __( 'The campaign context is part of a standard Page() call and includes the UTM query string parameters. Enabling this option will add the last touch campagin info to additional calls.', 'cdp-analytics' ),
            'default' => true
          ),
          array(
            'id'        => 'cdp-campaign-calls',
            'type'      => 'fieldset',
            'title'     => 'Add Enhanced Campaign Context',
            'fields'    => array(
              array(
                'id'    => 'enhance-track',
                'type'  => 'switcher',
                'title' => 'Track',
                'text_on'    => 'Yes',
                'text_off'   => 'No',
                'default' => false
              ),
              array(
                'id'    => 'enhance-identify',
                'type'  => 'switcher',
                'title' => 'Identify',
                'text_on'    => 'Yes',
                'text_off'   => 'No',
                'default' => false
              ),
              array(
                'id'    => 'enhance-group',
                'type'  => 'switcher',
                'title' => 'Group',
                'text_on'    => 'Yes',
                'text_off'   => 'No',
                'default' => false
              ),
            ),
            'dependency' => array( 'cdp-campaign-context', '==', true ),
          ),
          array(
            'id'         => 'cdp-campaign-partners',
            'type'       => 'switcher',
            'title'      => 'Enable Click ID Tracking',
            'text_on'    => 'Yes',
            'text_off'   => 'No',
            'subtitle' => __( 'Enable this to configure Click ID tracking (i.e. gclid, fbcid)', 'cdp-analytics' ),
            'default' => true
          ),
          array(
            'id'     => 'cdp-campaign-clickids',
            'type'   => 'repeater',
            'title'  => 'Advertising Tracking Parameters',
            'subtitle' => __( 'Add the advertising partner and the relevant query string parameter that includes their tracking information.', 'cdp-analytics' ),
            
            'fields' => array(
          
              array(
                'id'    => 'opt-ad-platform',
                'type'  => 'text',
                'title' => 'Advertiser',
                'sanitize' => array($this, 'snakecase_value')
              ),
              array(
                'id'    => 'opt-qs-param',
                'type'  => 'text',
                'title' => 'Query String Name',
                'sanitize' => array($this, 'snakecase_value')
              ),
              array(
                'id'         => 'opt-location',
                'type'       => 'radio',
                'title'      => 'Place info in...',
                'options'    => array(
                  'referer' => 'Context Referrer',
                  'properties' => 'Properties',
                ),
                'default'    => 'referer'
              ),
            ),
            'sanitize' => array($this, 'snakecase_value'),
            'dependency' => array( 'cdp-campaign-partners', '==', true ),
            'default'   => array(
              array(
                'opt-ad-platform' => 'google',
                'opt-qs-param' => 'gclid',
              ),
              array(
                'opt-ad-platform' => 'facebook',
                'opt-qs-param' => 'fbclid',
              ),
              array(
                'opt-ad-platform' => 'twitter',
                'opt-qs-param' => 'twclid',
              ),
              array(
                'opt-ad-platform' => 'microsoft',
                'opt-qs-param' => 'msclkid',
              ),
              array(
                'opt-ad-platform' => 'snapchat',
                'opt-qs-param' => 'sscid',
              )
            )
          ),
          /*
          array(
            'id'       => 'cdp-campaign-clickids',
            'type'     => 'code_editor',
            'title'    => 'Click ID Values',
            'subtitle' => __( 'Add the advertising partner and the relevant query string parameter that includes their tracking information.', 'cdp-analytics' ),
            'settings' => array(
              'theme'  => 'monokai',
              'mode'   => 'javascript',
            ),
            'default'  => '
{
"google": "gclid",
"facebook": "fbclid",
"twitter": "twclid",
"microsoft": "msclkid",
"snapchat": "sscid"
}
              ',
            'dependency' => array( 'cdp-campaign-context', '==', true ),
          ),
          */
         
        )
      ) );

      \CSF::createSection( $prefix, array(
        'title'  => 'Group',
        'fields' => array(
          array(
            'type'    => 'heading',
            'content' => 'Group Call',
          ),
          array(
            'type'    => 'content',
            'content' => 'Configure what group/account information sent with the Segment <a href="https://segment.com/docs/connections/spec/group/" target="_blank">Group Call</a>.',
          ),
          array(
            'type'    => 'submessage',
            'style'   => 'info',
            'content' => 'Group settings are coming soon!',
          ),

        )
      ) );

      \CSF::createSection( $prefix, array(
        'title'  => 'Identify',
        'fields' => array(
          array(
            'type'    => 'heading',
            'content' => 'Identify Calls',
            'subtitle' => 'Configure how user traits are sent with the Segment <a href="https://segment.com/docs/connections/spec/identify/" target="_blank">Identify Call</a>.',
          ),
          array(
            'type'    => 'content',
            'content' => 'Configure how user traits are sent with the Segment <a href="https://segment.com/docs/connections/spec/identify/" target="_blank">Identify Call</a>.',
          ),
          array(
            'type'    => 'submessage',
            'style'   => 'warning',
            'content' => 'Plain text and MD5 Hashed emails may result in security issues and are not accepted by all <em>Destinations</em>.',
            'dependency' => array( 'cdp-user-id', 'any', 'email,md5' )
          ),
          array(
            'id'    => 'cdp-user-id',
            'type'  => 'radio',
            'title' => 'User Id',
            'inline'  => true,
            'options' => array(
              'none'   => 'None',
              'wp_user_id'    => 'WP User Id',
              'md5'    => 'Hashed Email (MD5)',
              'sha256'    => 'Hashed Email (SHA-256)',
              'email'    => 'Email (pain text)',
              'custom'  => 'Custom Meta'
            ),
            'default' => 'none',
            'desc' => 'Configure WordPress User meta data to act as the Segment UserId, primarily used on eCommerce sites.'
          ),
          array(
            'id'      => 'cdp-uid-meta',
            'type'    => 'text',
            'title'   => 'ID Meta Field',
            'default' => '',
            'dependency' => array( 'cdp-user-id', '==', 'custom' ),
          ),
        )
      ) );

      \CSF::createSection( $prefix, array(
        'title'  => 'Page',
        'fields' => array(
          array(
            'type'    => 'content',
            'content' => '<h2>Page Calls</h2>',
          ),
          array(
            'type'    => 'content',
            'content' => 'Modify properties sent with Segment <a href="https://segment.com/docs/connections/spec/page/" target="_blank">Page Call</a>.',
          ),
          array(
            'id'         => 'cdp-page-pretty-name',
            'type'       => 'switcher',
            'title'      => 'Use Page Name',
            'subtitle' => 'Include the WordPress Post Name in the analytics.page() <em>name</em> attribute.',
            'text_on'    => 'Yes',
            'text_off'   => 'No',
            'default' => true
          ),
          array(
            'id'    => 'cdp-page-home',
            'type'  => 'text',
            'title' => 'Home Page Name',
          ),
          array(
            'id'    => 'cdp-page-404',
            'type'  => 'text',
            'title' => '404 Page Name',
          ),
          array(
            'id'         => 'cdp-page-taxonomy',
            'type'       => 'switcher',
            'title'      => 'Include Taxonomy Context',
            'subtitle' => 'Enhance the page call to include WordPress categories and tags in the page context.',
            'text_on'    => 'Yes',
            'text_off'   => 'No',
            'default' => true
          ),
          array(
            'id'         => 'cdp-page-server',
            'type'       => 'switcher',
            'title'      => 'Track Server Side',
            'text_on'    => 'Yes',
            'text_off'   => 'No',
            'default' => false
          ),

        )
      ) );

      \CSF::createSection( $prefix, array(
        'title'  => 'Track',
        'fields' => array(
          array(
            'type'    => 'heading',
            'content' => 'Track Calls',
          ),
          array(
            'type'    => 'content',
            'content' => 'Configure which events are sent with Segment <a href="https://segment.com/docs/connections/spec/track/" target="_blank">Track Calls</a>.',
          ),
          array(
            'id'        => 'cdp-track-wp',
            'type'      => 'fieldset',
            'title'     => 'Standard WP Actions',
            'fields'    => array(
              array(
                'id'    => 'track-signup',
                'type'  => 'switcher',
                'title' => 'Signup',
              ),
              array(
                'id'    => 'track-login',
                'type'  => 'switcher',
                'title' => 'Login',
              ),
              array(
                'id'    => 'track-logout',
                'type'  => 'switcher',
                'title' => 'Logout',
              ),
              array(
                'id'    => 'track-comment',
                'type'  => 'switcher',
                'title' => 'Leave Comment',
              ),
            ),
          ),
          array(
            'id'        => 'cdp-track-links',
            'type'      => 'fieldset',
            'title'     => 'External Links',
            'fields'    => array(
                array(
                  'id'    => 'links-enabled',
                  'type'  => 'switcher',
                  'title' => 'Enable Link Tracking',
                  'default' => true,
                ),
                array(
                  'id'    => 'force-target',
                  'type'  => 'switcher',
                  'title' => 'Force External ',
                  'dependency' => array( 'links-enabled', '==', true ),
                  'default' => false,
                ),
                array(
                  'id'    => 'share-selector',
                  'type'  => 'text',
                  'title' => 'Share Button Selector',
                  'dependency' => array( 'links-enabled', '==', true ),
                  'default' => '[data-share]',
                  'placeholder' => '[data-share]'
                ),
              ),
          ),
          array(
            'id'        => 'cdp-track-accordian',
            'type'      => 'fieldset',
            'title'     => 'Accordian Links',
            'fields'    => array(
                array(
                  'id'    => 'accordian-enabled',
                  'type'  => 'switcher',
                  'title' => 'Track Accoridan Clicks',
                ),
                array(
                  'id'    => 'accordian-event',
                  'type'  => 'text',
                  'title' => 'Accoridan Event Name',
                  'dependency' => array( 'accordian-enabled', '==', true ),
                  'default' => 'Accordian Clicked',
                  'placeholder' => 'Accordian Clicked'
                ),
                array(
                  'id'    => 'accordian-selector',
                  'type'  => 'text',
                  'title' => 'Accoridan Selector',
                  'dependency' => array( 'accordian-enabled', '==', true ),
                ),
              ),
          ),
          array(
            'id'        => 'cdp-track-video',
            'type'      => 'fieldset',
            'title'     => 'Video Tracking',
            'subtitle' => 'Enable this to complete the implemenation of the Segment <a href="https://segment.com/docs/connections/spec/video/" target="_blank">Video Spec</a> for YouTube or Vimeo.',
            'fields'    => array(
                array(
                  'id'    => 'video-enabled',
                  'type'  => 'switcher',
                  'title' => 'Track Video Activity',
                ),
                array(
                  'id'    => 'video-snippet',
                  'type'  => 'textarea',
                  'title' => 'Onsite Video Code Snippet',
                  'dependency' => array( 'video-enabled', '==', true ),
                  'default' => ''
                ),
              ),
          ),
          array(
            'id'         => 'cdp-track-server',
            'type'       => 'switcher',
            'title'      => 'Track Server Side',
            'text_on'    => 'Yes',
            'text_off'   => 'No',
            'default' => false
          ),

        )
      ) );

      \CSF::createSection( $prefix, array(
        'title'  => 'eCommerce',
        'fields' => array(
          array(
            'type'    => 'heading',
            'content' => 'eCommerce',
          ),
          array(
            'type'    => 'content',
            'content' => 'Enable the <a href="https://segment.com/docs/connections/spec/ecommerce/v2/" target="_blank">Segment eCommerce Spec</a> for popular plugins.',
          ),
          array(
            'type'    => 'submessage',
            'style'   => 'info',
            'content' => 'Coming Soon!',
          ),

        )
      ) );

      \CSF::createSection( $prefix, array(
        'title'  => 'Forms',
        'fields' => array(
          array(
            'type'    => 'heading',
            'content' => 'Forms',
          ),
          array(
            'type'    => 'content',
            'content' => 'Enables enhanced "Form Submitted" track event and optional <em>Identify</em> call for popular form builder plugins.',
          ),
          array(
            'type'    => 'callback',
            'style'   => 'info',
            'content' => 'Group settings are coming soon!',
          ),

        )
      ) );

      \CSF::createSection( $prefix, array(
        'title'  => 'Help',
        'fields' => array(

          array(
            'type'    => 'callback',
            'function' => array($this, 'help_function'),
          ),

        )
      ) );

    }
    
  }

  public function help_function() {
    $all_options = get_option( 'segment_keys' );
    echo "<pre>" . print_r($all_options, true) . "</pre>";
  }

  public function snakecase_value( $value ) {
      if (is_array($value)) {
        error_log(print_r($value, true));
        /*
        for ($i = 0; $i < count($value); $i++)  {
          if (is_array($value[$i])) {
            $value[$i] = $this->snakecase_value($value[$i]);
          } else {
            $value[$i] = strtolower(str_replace( ' ', '_', $value[$i] ));
          }
        }
        */
        $value =  array_map( array( $this, 'snakecase_value'), $value );
        return ($value);
      } else {
        return strtolower(str_replace( ' ', '_', $value ));
      }
      
    }

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/plugin-name-admin.css', array(), $this->version, 'all' );

	}

  public function writekey_missing_notice() { 
    $options = get_option( 'segment_keys' );
		if ( ! isset( $options['segment_write_key'] ) || ($options['segment_write_key'] == "") ) {
      ?>
      <div class="notice notice-error">
        <p><?php _e('You must update the WriteKey in order for Segment to work!', 'cdp-analytics'); ?></p>
      </div>
      <?php 
    }
  }

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/plugin-name-admin.js', array( 'jquery' ), $this->version, false );

	}

}
