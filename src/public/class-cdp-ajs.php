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

if ( ! class_exists( 'analyticsJS' ) ) :

class analyticsJS {
  private $_settings;

	public function __construct() {
    $this->_settings = get_option( 'segment_keys' );
    add_action('wp_head', array($this, 'addSegment'));

    if ( isset( $this->_settings['segment_tracklinks_enabled'] ) && $this->_settings['segment_tracklinks_enabled'] == "Y" ) { 
        add_action( 'wp_enqueue_scripts', array($this,  'enqueue_webpack_scripts') );
    }

    if ( isset( $this->_settings['segment_ext_target_enabled'] ) && $this->_settings['segment_ext_target_enabled'] == "Y" ) { 
        add_action('wp_footer', array($this, 'force_external_links_new_window') );
    }
	}

    public function enqueue_webpack_scripts() {
      $isDevMode = _is_in_development_mode();
      if ($isDevMode) {
          $jsFileURI = _get_plugin_url() . '/src/public/js/public-cdp.js';
      } else {
          $jsFilePath = glob( _get_plugin_directory() . '/dist/js/public.*.js' );
          $jsFileURI = _get_plugin_url() . '/dist/js/' . basename($jsFilePath[0]);
      }
      
      $this->_settings['jsfileuri'] = $jsFileURI;
      $this->_settings['devMode'] = $isDevMode;

      wp_enqueue_script( 'cdp_ajs_public', $jsFileURI , array('jquery') , null , true );
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
          
          echo "<script>" . PHP_EOL;
          $forceExt = ( isset( $this->_settings['segment_ext_target_enabled'] ) && $this->_settings['segment_ext_target_enabled'] == "Y" ) ? "true" : "false"; 
          echo "var cdpAnalyticsForceExtLinks = \"{$forceExt}\";";
          $socialSelect = ( isset( $this->_settings['segment_share_selector'] ) && $this->_settings['segment_share_selector'] != "" ) ? $this->_settings['segment_share_selector'] : "[data-share]"; 
          echo "var cdpAnalyticsSocialLinks = \"{$socialSelect}\";";
          echo PHP_EOL;
          echo "</script>"  . PHP_EOL;
          // echo "<!-- " . print_r($segment, true) . " --->";
      }
    }

    public function addSegmentYouTube() {
      $segment = $this->_settings;
      // Insert YouTube Library
    }

    public function force_external_links_new_window() { 
        ?>
        <script> 
        jQuery(document).ready( function() { 
            jQuery('a[href^="http"]').filter(function(){
                return this.hostname && this.hostname !== location.hostname;
            }).attr({target: "_blank", rel: "external", }); 
        });
        </script>
        <?php
    }

}

new analyticsJS;
endif;