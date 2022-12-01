<?php
/**
 * REQUIRED: Segment analytics.js
 *
 * @package     Alquemie\CDP
 * @since       1.0.0
 * @author      Chris Carrel
 * @link        https://segment.com
 * @license     GNU-2.0+
 */

namespace Alquemie\CDP;

if ( ! class_exists( 'ajs' ) ) :

class ajs {
    private $_settings;

    public function __construct() {
        $this->_settings = get_option( 'segment_keys' );
        add_action( 'wp_enqueue_scripts', array($this,  'add_scripts') );
    }

    public function add_scripts() {
        $cdnDomain = (isset($this->_settings['segment_custom_domain']) && ($this->_settings['segment_custom_domain'] !== "")) ? $this->_settings['segment_custom_domain'] : "cdn.segment.com";
        wp_enqueue_script('cdp-ajs', _get_plugin_url() . '/src/public/js/segment-ajs.js');
        wp_localize_script('cdp-ajs', 'cdp_analytics', array(
                'writeKey' => $this->_settings['segment_write_key'],
                'cdn_host' => $cdnDomain
            )
        );

        $trackEnabled = ( isset( $this->_settings['segment_tracklinks_enabled'] ) && $this->_settings['segment_tracklinks_enabled'] == "Y" ) ? "1" : "0";
		$socialSelctor = isset( $options['segment_share_selector'] ) ? $options['segment_share_selector'] : '[data-share]';
        $newWindow = ( isset( $this->_settings['segment_ext_target_enabled'] ) && $this->_settings['segment_ext_target_enabled'] == "Y" ) ? "1" : "0";
		
        $isDevMode = _is_in_development_mode();
        if ($isDevMode) {
            $jsFileURI = _get_plugin_url() . '/src/public/js/cdp-analytics.js';
        } else {
            $jsFilePath = glob( _get_plugin_directory() . '/dist/js/public.*.js' );
            $jsFileURI = _get_plugin_url() . '/dist/js/' . basename($jsFilePath[0]);
        }
        wp_enqueue_script( 'cdp-ajs-links', $jsFileURI , array('jquery','cdp-ajs') , null , true );
        wp_localize_script('cdp-ajs-links', 'cdp_analytics', array(
            'track_links' => $trackEnabled,
            'social_selector' => $socialSelctor,
            'force_new_window' => $newWindow
            )
        );
    }


    public function addSegmentYouTube() {
      $segment = $this->_settings;
      // Insert YouTube Library
    }

}

new ajs;
endif;