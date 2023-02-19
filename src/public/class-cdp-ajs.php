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
        $campaignPartnerTracking = ( isset( $this->_settings['cdp-campaign-partners'] ) ) ? $this->_settings['cdp-campaign-partners'] : 0;
        $campaignAdvertisers = ( isset( $this->_settings['cdp-campaign-clickids'] ) ) ? $this->_settings['cdp-campaign-clickids'] : array();
        $taxContext = ( isset( $this->_settings['cdp-page-taxonomy'] ) ) ? $this->_settings['cdp-page-taxonomy'] : 1;
        $trackEnabled = ( isset( $this->_settings['cdp-track-links']['links-enabled'] ) ) ? $this->_settings['cdp-track-links']['links-enabled'] : 0;
        $newWindow = isset( $this->_settings['cdp-track-links']['force-target'] ) ? $this->_settings['cdp-track-links']['force-target'] : 0;
        $socialSelctor = isset( $this->_settings['cdp-track-links']['share-selector'] ) ? $this->_settings['cdp-track-links']['share-selector'] : "data-share";
        $accordianEnabled = ( isset( $this->_settings['cdp-track-accordian']['accordian-enabled'])) ? $this->_settings['cdp-track-accordian']['accordian-enabled'] : "0";
        $accordingEvent = ( isset( $this->_settings['cdp-track-accordian']['accordian-event'])) ? $this->_settings['cdp-track-accordian']['accordian-event'] : "Accordian Clicked";
        $accordingSelector = ( isset( $this->_settings['cdp-track-accordian']['accordian-enabled'])) ? $this->_settings['cdp-track-accordian']['accordian-selector'] : ".accordian";
        $videoEnabled = ( isset( $this->_settings['cdp-track-video']['video-enabled'])) ? $this->_settings['cdp-track-video']['video-enabled'] : "0";
        $consentMgrEnabled = ( isset( $this->_settings['cdp-consent-manager'])) ? $this->_settings['cdp-consent-manager'] : "0";
        // {"opt-ad-platform":"google","opt-qs-param":"gclid","opt-location":"referer"}
        $clickIds = array();

        foreach ($campaignAdvertisers as $partner) {
            $clickIds[$partner['opt-qs-param']] = array( "partner" => $partner['opt-ad-platform'], "location" => $partner['opt-location']);
        }

        // global $post;
        $terms_obj = get_the_category();
        // $term_obj_list = get_the_terms( $post->ID, 'taxonomy' );
        $categories = join(', ', wp_list_pluck($terms_obj, 'name'));
        $terms_obj = get_the_tags();
        $tags = join(', ', wp_list_pluck($terms_obj, 'name'));

        wp_enqueue_script( 'js-cookie', _get_plugin_url() . '/dist/static/js.cookie.min.js', null , null , true );

        $isDevMode = _is_in_development_mode();
        if ($isDevMode) {
            $jsFileURI = _get_plugin_url() . '/src/public/js/cdp-taxonomy.js';
            wp_enqueue_script( 'cdp-ajs-campaigntracker', _get_plugin_url() . '/src/public/js/cdp-campaigntracker.js' , array('jquery', 'cdp-ajs-links') , null , true );
            wp_enqueue_script( 'cdp-ajs-linktracker', _get_plugin_url() . '/src/public/js/cdp-linktracker.js' , array('jquery', 'cdp-ajs-links') , null , true );
           //  wp_enqueue_script( 'cdp-ajs-links', $jsFileURI , array('jquery', 'cdp-ajs-links') , null , true );
        } else {
            $jsFilePath = glob( _get_plugin_directory() . '/dist/js/public.*.js' );
            $jsFileURI = _get_plugin_url() . '/dist/js/' . basename($jsFilePath[0]);
        }
        
        if ($videoEnabled) {
            wp_enqueue_script('youtube-iframe-api',"https://www.youtube.com/iframe_api", array(), null, false);
        }
        if ($consentMgrEnabled) {
            wp_enqueue_script('segment-consent-mgr',"https://unpkg.com/@segment/consent-manager@5.3.0/standalone/consent-manager.js", array(), null, false);
        }

        wp_enqueue_script( 'cdp-ajs-links', $jsFileURI , array('jquery') , null , true );
        wp_localize_script('cdp-ajs-links', 'cdp_analytics', array(
            'dev_mode' => $isDevMode,
            'campaign_context' => $campaignContext,
            'campaign_track' => $campaignTrack,
            'campaign_identify' => $campaignIdentify,
            'campaign_group' => $campaignGroup,
            'campaign_partner_tracking' => $campaignPartnerTracking,
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
        wp_localize_script('cdp-ajs-links', 'cdp_ad_keys', $clickIds );
    }


    public function addSegment() {
        // $segment = get_option( 'segment_keys' );
        $segment = $this->_settings;
  
        if ( isset( $segment['segment_write_key'] ) ) {
            $writeKey = $segment['segment_write_key'];
            $subdomain = (isset($segment['segment_custom_domain']) && ($segment['segment_custom_domain'] !== "")) ? $segment['segment_custom_domain'] : "cdn.segment.com";
            $enableConsentMgr = ( isset( $segment['cdp-consent-manager'] ) ) ? $segment['cdp-consent-manager'] : 0;
        
            echo "<script>" . PHP_EOL;
            echo '!function(){var analytics=window.analytics=window.analytics||[];if(!analytics.initialize)if(analytics.invoked)window.console&&console.error&&console.error("Segment snippet included twice.");else{analytics.invoked=!0;analytics.methods=["trackSubmit","trackClick","trackLink","trackForm","pageview","identify","reset","group","track","ready","alias","debug","page","once","off","on","addSourceMiddleware","addIntegrationMiddleware","setAnonymousId","addDestinationMiddleware"];analytics.factory=function(e){return function(){var t=Array.prototype.slice.call(arguments);t.unshift(e);analytics.push(t);return analytics}};for(var e=0;e<analytics.methods.length;e++){var key=analytics.methods[e];analytics[key]=analytics.factory(key)}analytics.load=function(key,e){var t=document.createElement("script");t.type="text/javascript";t.async=!0;t.src="https://' . $subdomain . '/analytics.js/v1/" + key + "/analytics.min.js";var n=document.getElementsByTagName("script")[0];n.parentNode.insertBefore(t,n);analytics._loadOptions=e};analytics._writeKey="' . $writeKey . '";;analytics.SNIPPET_VERSION="4.15.3";';
            if (!$enableConsentMgr) echo 'analytics.load("' . $writeKey . '");';
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
    
    public function add_enqueue_script_attributes( $tag, $handle ) {
        // Add defer
        if( 'segment-consent-mgr' === $handle ) {
             return str_replace( ' src="', ' defer src="', $tag );
        }
    
        // Add async
/*
        if( 'another-handle' === $handle ) {
             return str_replace( ' src="', ' async src="', $tag );
        }

        // Add multiple defers
        $deferrable_handles = [
            'first-handle',
            'second-handle',
            'third-handle',
        ];
    
        if( in_array( $handle, $deferrable_handles ) ) {
            return str_replace( ' src="', ' defer src="', $tag );
        }
*/
        return $tag;
    }
    
}

// new ajs;
endif;