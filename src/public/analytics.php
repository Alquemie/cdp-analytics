<?php
/**
 * REQUIRED: Segment analytics.js
 *
 * @package     Alquemie\Twilio\Segment
 * @since       1.0.0
 * @author      Chris Carrel
 * @link        https://segment.com
 * @license     GNU-2.0+
 */

namespace Alquemie\Segment;


class analytics {

	public function __construct() {
        add_action('wp_head', array($this, 'addSegment'));
	}

    function addSegment() {
        $segment = get_option( 'segment_keys' );
        if ( isset( $segment['segment_write_key'] ) ) {
            $writeKey = $segment['segment_write_key'];
            $subdomain = (isset($segment['segment_custom_domain']) && ($segment['segment_custom_domain'] !== "")) ? $segment['segment_custom_domain'] : "cdn.segment.com";
            echo "<script>";
            echo '!function(){var analytics=window.analytics=window.analytics||[];if(!analytics.initialize)if(analytics.invoked)window.console&&console.error&&console.error("Segment snippet included twice.");else{analytics.invoked=!0;analytics.methods=["trackSubmit","trackClick","trackLink","trackForm","pageview","identify","reset","group","track","ready","alias","debug","page","once","off","on","addSourceMiddleware","addIntegrationMiddleware","setAnonymousId","addDestinationMiddleware"];analytics.factory=function(e){return function(){var t=Array.prototype.slice.call(arguments);t.unshift(e);analytics.push(t);return analytics}};for(var e=0;e<analytics.methods.length;e++){var key=analytics.methods[e];analytics[key]=analytics.factory(key)}analytics.load=function(key,e){var t=document.createElement("script");t.type="text/javascript";t.async=!0;t.src="https://' . $subdomain . '/analytics.js/v1/" + key + "/analytics.min.js";var n=document.getElementsByTagName("script")[0];n.parentNode.insertBefore(t,n);analytics._loadOptions=e};analytics._writeKey="' . $writeKey . '";;analytics.SNIPPET_VERSION="4.15.3";';
            echo 'analytics.load("' . $writeKey . '");';
            echo "analytics.page();";
            echo "}}();";
            echo "</script>" ;
        }
        if ( isset( $segment['segment_tracklinks_enabled'] ) && $segment['segment_tracklinks_enabled'] == "Y" ) { 
            echo "<script>var alquemieTracklinksEnabled = true;</script>";
        } else {
            echo "<script>var alquemieTracklinksEnabled = false;</script>";
        }
        
        if ( isset( $segment['segment_google_measurement_id'] ) && isset( $segment['segment_google_enabled'] ) && $segment['segment_google_enabled'] == "Y" ) {
            $measurementId = strtoupper($segment['segment_google_measurement_id']);
            if ( substr($measurementId,0,2) == "G-" ) {
                echo "<!-- Global site tag (gtag.js) - Google Analytics -->";
                echo "<script async src=\"https://www.googletagmanager.com/gtag/js?id=" . $measurementId . "\"></script>". PHP_EOL;
                echo "<script>" . PHP_EOL;
                echo " window.dataLayer = window.dataLayer || [];";
                echo " function gtag(){dataLayer.push(arguments);}";
                echo " gtag('js', new Date());";
                echo " gtag('config', '" . $measurementId . "');";
                echo "</script>" . PHP_EOL;
                echo "<script>" . PHP_EOL;
                echo " gtag('get', '" . $measurementId . "', 'session_id', (field) => { sessionStorage.setItem('ga4_session_id', field); });";
                echo " gtag('get', '" . $measurementId . "', 'client_id', (field) => { sessionStorage.setItem('ga4_client_id', field);  });";
                echo " gtag('get', '" . $measurementId . "', 'gclid', (field) => { sessionStorage.setItem('ads_gclid', field);  });";
                echo " var ga4Context = function({ payload, next, integrations }) {    " ;
                echo " const sessionId = sessionStorage.getItem('ga4_session_id');";
                echo " if (sessionId) { ";
                echo " const clientId = sessionStorage.getItem('ga4_client_id');";
                echo " const gclid = sessionStorage.getItem('ads_gclid');";
                echo " payload.obj.context.ga4_session = { \"session_id\": sessionId, \"client_id\": clientId, \"gclid\": gclid };" ;                
                echo " next(payload);" ;
                echo " };" ;
                echo " };" ;
                echo " analytics.addSourceMiddleware(ga4Context);" ;
                echo "</script>" . PHP_EOL;
            } else {
                echo "<!-- Global site tag (gtag.js) - INVALID MEASUREMENT ID -->" . PHP_EOL;
            }

            if ( isset( $segment['segment_google_identify_enabled'] ) && $segment['segment_google_identify_enabled'] == "Y" ) {
                echo "<script>" . PHP_EOL;
                echo " var ga4_session_id = sessionStorage.getItem('ga4_session_id');";
                echo " if (ga4_session_id) { ";
                echo " var ajs_user_traits = localStorage.getItem('ajs_user_traits');";
                echo " ajs_user_traits = (ajs_user_traits != null) ? JSON.parse(ajs_user_traits) : {};";
                echo " var cSessionId = ( ajs_user_traits.hasOwnProperty('ga4_session_id') ) ? ajs_user_traits.ga4_session_id : 0;";
                echo " if ( cSessionId != ga4_session_id ) { " ;
                echo " var ga4_client_id = sessionStorage.getItem('ga4_client_id');";
                echo " analytics.identify( { \"ga4_session_id\": ga4_session_id, \"ga4_client_id\": ga4_client_id } );";
                echo " }";
                echo " }";
                echo "</script>" . PHP_EOL;
            }
        }
    }

}

new analytics;
