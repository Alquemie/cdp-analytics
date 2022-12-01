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
        add_action('wp_head', array($this, 'addSegment'));
        add_action( 'wp_enqueue_scripts', array($this,  'add_scripts') );
    }

    public function add_scripts() {
        /*
        $cdnDomain = (isset($this->_settings['segment_custom_domain']) && ($this->_settings['segment_custom_domain'] !== "")) ? $this->_settings['segment_custom_domain'] : "cdn.segment.com";
        wp_enqueue_script('cdp-ajs', _get_plugin_url() . '/src/public/js/segment-ajs.js');
        wp_localize_script('cdp-ajs', 'cdp_analytics', array(
                'writeKey' => $this->_settings['segment_write_key'],
                'cdn_host' => $cdnDomain
            )
        );
        */

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


    public function addSegment() {
        // $segment = get_option( 'segment_keys' );
        $segment = $this->_settings;
  
        if ( isset( $segment['segment_write_key'] ) ) {
            $writeKey = $segment['segment_write_key'];
            $subdomain = (isset($segment['segment_custom_domain']) && ($segment['segment_custom_domain'] !== "")) ? $segment['segment_custom_domain'] : "cdn.segment.com";
            echo "<script>" . PHP_EOL;
            echo '!function(){var analytics=window.analytics=window.analytics||[];if(!analytics.initialize)if(analytics.invoked)window.console&&console.error&&console.error("Segment snippet included twice.");else{analytics.invoked=!0;analytics.methods=["trackSubmit","trackClick","trackLink","trackForm","pageview","identify","reset","group","track","ready","alias","debug","page","once","off","on","addSourceMiddleware","addIntegrationMiddleware","setAnonymousId","addDestinationMiddleware"];analytics.factory=function(e){return function(){var t=Array.prototype.slice.call(arguments);t.unshift(e);analytics.push(t);return analytics}};for(var e=0;e<analytics.methods.length;e++){var key=analytics.methods[e];analytics[key]=analytics.factory(key)}analytics.load=function(key,e){var t=document.createElement("script");t.type="text/javascript";t.async=!0;t.src="https://' . $subdomain . '/analytics.js/v1/" + key + "/analytics.min.js";var n=document.getElementsByTagName("script")[0];n.parentNode.insertBefore(t,n);analytics._loadOptions=e};analytics._writeKey="' . $writeKey . '";;analytics.SNIPPET_VERSION="4.15.3";';
            echo 'analytics.load("' . $writeKey . '");';
            echo "analytics.page();";
            echo "}}();" . PHP_EOL;
            echo "</script>"  . PHP_EOL;
        } else {
            echo PHP_EOL ."<!-- CDP Analytics: Missing Segment WriteKey --->". PHP_EOL;
        }
      }

    public function addSegmentYouTube() {
      $segment = $this->_settings;
      // Insert YouTube Library
    }

}

new ajs;
endif;