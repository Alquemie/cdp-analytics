<?php
/**
 * REQUIRED: Segment analytics.js
 *
 * @package     Alquemie\CDP
 * @author      Chris Carrel
 * @license     GNU-3.0+
 */

namespace Alquemie\CDP;

if ( ! class_exists( 'AJS' ) ) :

class AJS {
    private $_settings;
	private $plugin_name;
	private $version;

    public function __construct( $plugin_name, $version ) {
        $this->_settings = get_option( 'segment_keys' );
        $this->plugin_name = $plugin_name;
		$this->version = $version;
    }

    public function add_scripts() {
        $campaignContext = ( isset( $this->_settings['cdp-campaign-context'] ) ) ? $this->_settings['cdp-campaign-context'] : 0;
        $campaignTrack = ( isset( $this->_settings['cdp-campaign-calls']['enhance-track'] ) ) ? $this->_settings['cdp-campaign-calls']['enhance-track'] : 0;
        $campaignIdentify = ( isset( $this->_settings['cdp-campaign-calls']['enhance-identify'] ) ) ? $this->_settings['cdp-campaign-calls']['enhance-identify'] : 0;
        $campaignGroup = ( isset( $this->_settings['cdp-campaign-calls']['enhance-group'] ) ) ? $this->_settings['cdp-campaign-calls']['enhance-group'] : 0;
        $campaignClickTracking = ( isset( $this->_settings['cdp-campaign-partners'] ) ) ? $this->_settings['cdp-campaign-partners'] : 0;
        $campaignAdvertisers = ( isset( $this->_settings['cdp-campaign-clickids'] ) ) ? $this->_settings['cdp-campaign-clickids'] : array();
        $taxContext = ( isset( $this->_settings['cdp-page-taxonomy'] ) ) ? $this->_settings['cdp-page-taxonomy'] : 1;
        $trackEnabled = ( isset( $this->_settings['cdp-track-links']['links-enabled'] ) ) ? $this->_settings['cdp-track-links']['links-enabled'] : 0;
        $newWindow = isset( $this->_settings['cdp-track-links']['force-target'] ) ? $this->_settings['cdp-track-links']['force-target'] : 0;
        $socialSelctor = isset( $this->_settings['cdp-track-links']['share-selector'] ) ? $this->_settings['cdp-track-links']['share-selector'] : "data-share";
        $accordianEnabled = ( isset( $this->_settings['cdp-track-accordian']['accordian-enabled'])) ? $this->_settings['cdp-track-accordian']['accordian-enabled'] : "0";
        $accordingEvent = ( isset( $this->_settings['cdp-track-accordian']['accordian-event'])) ? $this->_settings['cdp-track-accordian']['accordian-event'] : "Accordian Clicked";
        $accordingSelector = ( isset( $this->_settings['cdp-track-accordian']['accordian-enabled'])) ? $this->_settings['cdp-track-accordian']['accordian-selector'] : ".accordian";
        $videoEnabled = ( isset( $this->_settings['cdp-track-video']['video-enabled'])) ? $this->_settings['cdp-track-video']['video-enabled'] : "0";
        // global $post;
        $terms_obj = get_the_category();
        // $term_obj_list = get_the_terms( $post->ID, 'taxonomy' );
        $categories = join(', ', wp_list_pluck($terms_obj, 'name'));
        $terms_obj = get_the_tags();
        $tags = join(', ', wp_list_pluck($terms_obj, 'name'));

        $isDevMode = _is_in_development_mode();
        if ($isDevMode) {
            $jsFileURI = _get_plugin_url() . '/src/public/js/cdp-analytics.js';
        } else {
            $jsFilePath = glob( _get_plugin_directory() . '/dist/js/public.*.js' );
            $jsFileURI = _get_plugin_url() . '/dist/js/' . basename($jsFilePath[0]);
        }
        
        if ($videoEnabled) {
            wp_enqueue_script('youtube-iframe-api',"https://www.youtube.com/iframe_api", array(), null, false);
        }

        wp_enqueue_script( 'cdp-ajs-links', $jsFileURI , array('jquery') , null , true );
        wp_localize_script('cdp-ajs-links', 'cdp_analytics', array(
            'campaign_context' => $campaignContext,
            'campaign_track' => $campaignTrack,
            'campaign_identify' => $campaignIdentify,
            'campaign_group' => $campaignGroup,
            'campaign_click_tracking' => $campaignClickTracking,
            'click_ids' => $campaignAdvertisers,
            'taxonomy_context' => $taxContext,
            'track_links' => $trackEnabled,
            'social_selector' => $socialSelctor,
            'force_new_window' => $newWindow,
            'accordian_enable' => $accordianEnabled,
            'accordian_event' => $accordingEvent,
            'accordian_selector' => $accordingSelector,
            'enable_video' => $videoEnabled,
            'categories' => $categories,
            'tags' => $tags
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
            if (is_front_page() && ($segment['cdp-page-home'] !== "")) {
                echo "analytics.page('" . $segment['cdp-page-home'] . "');";
            } else if (is_404() && ($segment['cdp-page-404'] !== "")) {
                echo "analytics.page('" . $segment['cdp-page-404'] . "');";
            } else {
                $usePretty = ( isset( $this->_settings['cdp-page-pretty-name'] ) ) ? $this->_settings['cdp-page-pretty-name'] : true;
                $prettyPage = ($usePretty) ? the_title("'", "'", false) : "";
                echo "analytics.page(" . $prettyPage . ");";
            }
            echo "}}();" . PHP_EOL;
            echo "console.log('Analytics Code Loaded');"  . PHP_EOL;
            echo "</script>"  . PHP_EOL;
        } else {
            echo PHP_EOL ."<!-- CDP Analytics: Missing Segment WriteKey --->". PHP_EOL;
        }
      }

    public function addSegmentYouTube($html) {
        $videoEnabled = ( isset( $this->_settings['cdp-track-video']['video-enabled'])) ? $this->_settings['cdp-track-video']['video-enabled'] : false;
        // error_log("VIDEO: " . $videoEnabled);
        if ( ($videoEnabled == true) && ( false !== strpos( $html, 'youtube' ) )) {
            $html = str_replace( '?feature=oembed', '?feature=oembed&enablejsapi=1', $html );
        }
        return $html;
    }
    
}

// new ajs;
endif;